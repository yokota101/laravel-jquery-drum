<?php

namespace App\Http\Controllers;

use App\Consts\ProjectConst;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use App\Repositories\MypageRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * マイページに関するクラス
 */
class MypageController extends Controller
{
    protected $mypageRepository;

    public function __construct()
    {
        $this->mypageRepository = new MypageRepository;
    }
    /**
     * マイページ表示画面
     * @param  Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request)
    {
        $id = Auth::id();

        // id取得できなければログインしていないためエラー
        if (empty($id)) return view('errors.404');

        // 自分が投稿した記事を取得
        $postedrticles = $this->mypageRepository->makeUserPostsQuery($id, null);

        $postedCount = $postedrticles->get()->count();
        
        $postedrticles = $postedrticles->limit(ProjectConst::DISP_PER_PAGE)->get();

        $categories = Category::all()->pluck('category_name', 'id')->toArray();

        $goodPostSubQuery = Vote::where(function ($q) use ($id) {
            $q->where('votes.user_id', $id);
        })->where('votes.good_point', '>', 0);

        $subQueryVote = Vote::Select(
            'post_id',
            DB::raw('ifnull(sum(`good_point`), 0) as good_point'),
            DB::raw('ifnull(sum(`bad_point`), 0) as bad_point'),
            DB::raw('ifnull(sum(`good_point`), 0) - ifnull(sum(`bad_point`), 0) as total')
        )->Groupby('post_id');

        // いいねした記事を取得
        $goodPosts = Post::Select(
            't1.id',
            't1.request_flg',
            't1.open_flg',
            't1.alert_flg',
            't1.user_id',
            't1.title',
            't1.url',
            't1.movie_id',
            't1.thumbnail_url',
            't1.content',
            't1.main_category_id',
            't1.sub_category_id_first',
            't1.sub_category_id_second',
            't1.sub_category_id_third',
            't1.created_at',
            'tb2.good_point',
            'tb2.bad_point',
            'tb2.total'
        )
            ->from('posts as t1')
            ->joinSub($goodPostSubQuery, 'gd', 't1.id', 'gd.post_id')
            ->leftJoinSub($subQueryVote, 'tb2', 't1.id', 'tb2.post_id')
            ->where('t1.deleted_at', null)
            ->orderBy('gd.updated_at', 'desc');

        $goodPostCount = $goodPosts->get()->count();
        $goodPosts = $goodPosts->limit(ProjectConst::DISP_PER_PAGE)->get();

        $user = Auth::user();

        return view('mypage', ['postedrticles' => $postedrticles, 'postedCount' => $postedCount, 'categories' => $categories, 'goodposts' => $goodPosts, 'goodPostCount' => $goodPostCount, 'userinfo' => $user]);
    }

    /**
     * ユーザーページを表示（他人のプロフィール画面）
     * 
     * @param  Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function userPage(Request $request)
    {
        // ユーザID取得
        $attributes = $request->only('user_id');

        // idのパラメータなければエラー
        if (empty($attributes)) return view('errors.404');

        $user_id = $attributes['user_id'];

        $user = User::where('id', $user_id)->where('deleted_at', null)->first();
        // ユーザが存在しなければエラー
        if (empty($user)) return view('errors.404');

        // ユーザーが投稿した記事を取得
        $postedrticles = $this->mypageRepository->makeUserPostsQuery($user_id, ProjectConst::POST_STATE_OPEN);
        
        $postedCount = $postedrticles->get()->count();
        $postedrticles = $postedrticles->limit(ProjectConst::DISP_PER_PAGE)->get();

        $categories = Category::all()->pluck('category_name', 'id')->toArray();

        $goodPostSubQuery = Vote::where(function ($q) use ($user_id) {
            $q->where('votes.user_id', $user_id);
        })->where('votes.good_point', '>', 0);

        $subQueryVote = Vote::Select(
            'post_id',
            DB::raw('ifnull(sum(`good_point`), 0) as good_point'),
            DB::raw('ifnull(sum(`bad_point`), 0) as bad_point'),
            DB::raw('ifnull(sum(`good_point`), 0) - ifnull(sum(`bad_point`), 0) as total')
        )->Groupby('post_id');

        $goodPosts = Post::Select(
            't1.id',
            't1.request_flg',
            't1.open_flg',
            't1.alert_flg',
            't1.user_id',
            't1.title',
            't1.url',
            't1.movie_id',
            't1.thumbnail_url',
            't1.content',
            't1.main_category_id',
            't1.sub_category_id_first',
            't1.sub_category_id_second',
            't1.sub_category_id_third',
            't1.created_at',
            'tb2.good_point',
            'tb2.bad_point',
            'tb2.total'
        )
            ->from('posts as t1')
            ->joinSub($goodPostSubQuery, 'gd', 't1.id', 'gd.post_id')
            ->leftJoinSub($subQueryVote, 'tb2', 't1.id', 'tb2.post_id')
            ->where('t1.deleted_at', null)
            ->where('t1.open_flg', 1)
            ->orderBy('gd.updated_at', 'desc');

        $goodPostCount = $goodPosts->get()->count();
        $goodPosts = $goodPosts->limit(ProjectConst::DISP_PER_PAGE)->get();

        return view('user', ['postedrticles' => $postedrticles, 'postedCount' => $postedCount, 'categories' => $categories, 'goodposts' => $goodPosts, 'goodPostCount' => $goodPostCount, 'userinfo' => $user]);
    }

    /**
     * ユーザーページのリストを取得（ページング用）
     * @param  Request $request
     * @return  array 投稿した記事と投稿数
     */
    public function getList(Request $request)
    {
        // ユーザID取得
        $attributes = $request->only(['kind', 'start', 'user_id']);

        // validation
        if (empty($attributes['kind']) || is_null($attributes['start']) || empty($attributes['user_id'])) {
            return ['cnt' => 0];
        }

        $user_id = $attributes['user_id'];
        $start   = $attributes['start'];

        $subQueryVote = Vote::Select(
            'post_id',
            DB::raw('ifnull(sum(`good_point`), 0) as good_point'),
            DB::raw('ifnull(sum(`bad_point`), 0) as bad_point'),
            DB::raw('ifnull(sum(`good_point`), 0) - ifnull(sum(`bad_point`), 0) as total')
        )->Groupby('post_id');

        if ($attributes['kind'] === '#posted') {
            // 投稿記事一覧
            $postedrticles = Post::Select(
                't1.id',
                't1.request_flg',
                't1.open_flg',
                't1.alert_flg',
                't1.user_id',
                't1.title',
                't1.url',
                't1.movie_id',
                't1.thumbnail_url',
                't1.content',
                't1.main_category_id',
                't1.sub_category_id_first',
                't1.sub_category_id_second',
                't1.sub_category_id_third',
                't1.created_at',
                't2.name',
                't2.image',
                't2.self_intro',
                't2.twitter_username',
                't2.instagram_id',
                't2.facebook_url',
                'tb2.good_point',
                'tb2.bad_point',
                'tb2.total'
            )
                ->from('posts as t1')->join('users as t2', 't1.user_id', 't2.id')
                ->leftJoinSub($subQueryVote, 'tb2', 't1.id', 'tb2.post_id')
                ->where(function ($q) use ($user_id) {
                    $q->where('t1.user_id', $user_id);
                })
                ->where('t1.deleted_at', null)
                ->where('t1.open_flg', 1)
                ->orderBy('tb2.total', 'desc')
                ->orderBy('t1.created_at', 'desc');

            $postedrticles = $postedrticles->offset($start)->limit(ProjectConst::DISP_PER_PAGE)->get();


            return ['lists' => $postedrticles, 'cnt' => $postedrticles->count()];
        } elseif ($attributes['kind'] === '#like') {
            // いいねした記事一覧
            $goodPostSubQuery = Vote::where(function ($q) use ($user_id) {
                $q->where('votes.user_id', $user_id);
            })->where('votes.good_point', '>', 0);

            $goodPosts = Post::Select(
                't1.id',
                't1.request_flg',
                't1.open_flg',
                't1.alert_flg',
                't1.user_id',
                't1.title',
                't1.url',
                't1.movie_id',
                't1.thumbnail_url',
                't1.content',
                't1.main_category_id',
                't1.sub_category_id_first',
                't1.sub_category_id_second',
                't1.sub_category_id_third',
                't1.created_at',
                'tb2.good_point',
                'tb2.bad_point',
                'tb2.total'
            )
                ->from('posts as t1')
                ->joinSub($goodPostSubQuery, 'gd', 't1.id', 'gd.post_id')
                ->leftJoinSub($subQueryVote, 'tb2', 't1.id', 'tb2.post_id')
                ->where('t1.deleted_at', null)
                ->where('t1.open_flg', 1)
                ->orderBy('gd.updated_at', 'desc');

            $goodPosts = $goodPosts->offset($start)->limit(ProjectConst::DISP_PER_PAGE)->get();


            return ['lists' => $goodPosts, 'cnt' => $goodPosts->count()];
        } else {
            return ['cnt' => 0];
        }
    }
    /**
     * マイページのリストを取得（ページング用）
     * 
     * @param  Request $request
     * @return  array 投稿した記事と投稿数
     */
    public function getListForMypage(Request $request)
    {
        // ユーザID取得
        $attributes = $request->only(['kind', 'start']);

        // validation
        if (empty($attributes['kind']) || is_null($attributes['start'])) {
            return ['cnt' => 0];
        }

        $user_id = Auth::id();
        $start   = $attributes['start'];

        $subQueryVote = Vote::Select(
            'post_id',
            DB::raw('ifnull(sum(`good_point`), 0) as good_point'),
            DB::raw('ifnull(sum(`bad_point`), 0) as bad_point'),
            DB::raw('ifnull(sum(`good_point`), 0) - ifnull(sum(`bad_point`), 0) as total')
        )->Groupby('post_id');

        if ($attributes['kind'] === '#posted') {
            // 投稿記事一覧
            $postedrticles = Post::Select(
                't1.id',
                't1.request_flg',
                't1.open_flg',
                't1.alert_flg',
                't1.user_id',
                't1.title',
                't1.url',
                't1.movie_id',
                't1.thumbnail_url',
                't1.content',
                't1.main_category_id',
                't1.sub_category_id_first',
                't1.sub_category_id_second',
                't1.sub_category_id_third',
                't1.created_at',
                't2.name',
                't2.image',
                't2.self_intro',
                't2.twitter_username',
                't2.instagram_id',
                't2.facebook_url',
                'tb2.good_point',
                'tb2.bad_point',
                'tb2.total'
            )
                ->from('posts as t1')->join('users as t2', 't1.user_id', 't2.id')
                ->leftJoinSub($subQueryVote, 'tb2', 't1.id', 'tb2.post_id')
                ->where(function ($q) use ($user_id) {
                    $q->where('t1.user_id', $user_id);
                })
                ->where('t1.deleted_at', null)
                ->orderBy('tb2.total', 'desc')
                ->orderBy('t1.created_at', 'desc');

            $postedrticles = $postedrticles->offset($start)->limit(ProjectConst::DISP_PER_PAGE)->get();


            return ['lists' => $postedrticles, 'cnt' => $postedrticles->count()];
        } elseif ($attributes['kind'] === '#like') {
            // いいねした記事一覧
            $goodPostSubQuery = Vote::where(function ($q) use ($user_id) {
                $q->where('votes.user_id', $user_id);
            })->where('votes.good_point', '>', 0);

            $goodPosts = Post::Select(
                't1.id',
                't1.request_flg',
                't1.open_flg',
                't1.alert_flg',
                't1.user_id',
                't1.title',
                't1.url',
                't1.movie_id',
                't1.thumbnail_url',
                't1.content',
                't1.main_category_id',
                't1.sub_category_id_first',
                't1.sub_category_id_second',
                't1.sub_category_id_third',
                't1.created_at',
                'tb2.good_point',
                'tb2.bad_point',
                'tb2.total'
            )
                ->from('posts as t1')
                ->joinSub($goodPostSubQuery, 'gd', 't1.id', 'gd.post_id')
                ->leftJoinSub($subQueryVote, 'tb2', 't1.id', 'tb2.post_id')
                ->where('t1.deleted_at', null)
                ->where('t1.open_flg', 1)
                ->orderBy('gd.updated_at', 'desc');

            $goodPosts = $goodPosts->offset($start)->limit(ProjectConst::DISP_PER_PAGE)->get();


            return ['lists' => $goodPosts, 'cnt' => $goodPosts->count()];
        } else {
            return ['cnt' => 0];
        }
    }
    /**
     * プロフ画像の登録処理
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postImage(Request $request)
    {
        $id = Auth::id();
        // id取得できなければログインしていないためエラー
        if (empty($id)) return view('errors.404');

        //check file
        if ($request->hasFile('file')) {
            $filename = '';
            try {
                // ファイルの保存
                $file      = $request->file('file');
                $filename  = $file->getClientOriginalName();
                //move image to public/prof_images folder
                $file->move(public_path('prof_images'), $filename);
            } catch (Exception $e) {
                // エラー発生
                Log::error('プロフィール画像保存時にエラーが発生しました。',  $e->getMessage());
                throw $e;
                return;
            }

            USER::where('id', $id)->update(['image' => $filename]);

            return response()->json(["message" => "Image Uploaded Succesfully"]);
        } else {
            return response()->json(["message" => "Please select image!"]);
        }
    }
    /**
     * プロフィール編集後の確認画面表示
     * 
     * @param  Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function postProfileConfirm(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ], [
            'name.required' => '名前は必須項目です。'
        ]);

        if ($validator->fails()) {
            return redirect('profile-edit')
                ->withErrors($validator)
                ->withInput();
        }

        $id = Auth::id();
        // id取得できなければログインしていないためエラー
        if (empty($id)) return view('errors.404');

        return view('prof-confirm', ['request' => $request]);
    }

    /**
     * プロフィール編集完了し登録実行
     * 
     * @param  Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function postProfileComplete(Request $request)
    {

        $id = Auth::id();

        // id取得できなければログインしていないためエラー
        if (empty($id)) return view('errors.404');

        $attributes = $request->only(['name', 'twitter_username', 'instagram_id', 'facebook_url', 'self_intro']);

        User::where('id', $id)->update([
            'name'             => $attributes['name'],
            'twitter_username' => $attributes['twitter_username'],
            'instagram_id'     => $attributes['instagram_id'],
            'facebook_url'     => $attributes['facebook_url'],
            'self_intro'       => $attributes['self_intro']
        ]);

        $result = User::where('id', $id)->first();

        return view('profile-edit', ['userinfo' => $result]);
    }
}
