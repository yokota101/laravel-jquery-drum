<?php

namespace App\Http\Controllers;

use App\Consts\ProjectConst;
use App\Models\Category;
use App\Models\Post;
use App\Models\Vote;
use Exception;
use Google_Client;
use Google_Service_YouTube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * 記事投稿クラス
 */
class PostController extends Controller
{
    /**
     * 投稿ページtop
     */
    public function index(Request $request)
    {
        $subQueryCat = Category::Select('sub.group_id', 'sub.category_name as label')
            ->from('categories as sub')
            ->where('header_flg', 1);

        // カテゴリ取得
        $categories = Category::Select('t1.id as value', 't1.group_id', 't1.category_name', 't2.label')
            ->from('categories as t1')
            ->leftJoinSub($subQueryCat, 't2', 't1.group_id', 't2.group_id')
            ->where('t1.header_flg', 0)
            ->orderBy('t1.group_id', 'asc')
            ->orderBy('t1.id', 'asc')
            ->get();

        return view('post', ['categories' => $categories,  'update' => 0]);
    }

    /**
     * Youtube APIから動画の情報を取得する
     */
    public function getYoutube(Request $request)
    {
        $url = $request->url;

        // Googleへの接続情報のインスタンスを作成と設定
        $client = new Google_Client();
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));

        /* note 外部API使用時は失敗する可能性を考慮し例外処理を実装している */
        try {
            // Youtubeのデータへアクセス可能なインスタンスを生成
            $youtube = new Google_Service_YouTube($client);

            // 必要情報を引数に持たせ、listSearchで検索して動画一覧を取得
            $items = $youtube->search->listSearch('snippet', [
                'regionCode' => 'JP',
                'type'      => 'video',
                'q'         => $url,
                'maxResults' => 1,
            ]);


            // 各情報をセット
            $thumbnail = $items->getItems()[0]->snippet->thumbnails->medium->url;
            $title = $items->getItems()[0]->snippet->title;
            $videoId = $items->getItems()[0]->id->videoId;

            return ['title' => $title, 'thumbnail' => $thumbnail, 'videoid' => $videoId];

        }catch(Exception $e){
            Log::error('Youtube api接続時にエラーが発生しました。',  $e->getMessage());
            throw $e;
        }    
    }

    /**
     * 投稿内容の確認ページを表示する
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function postConfirm(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'movie_url' => 'required',
            'main_category' => 'required',
            'thumbnail' => 'required',
            'sub_category' => 'array|between:0,3',
            'intro' => 'required',
            'open_flg_group' => 'required'
        ], [
            'title.required' => 'タイトルは必須項目です。',
            'movie_url.required' => '動画URLは必須項目です。',
            'thumbnail' => 'Youtube上で動画が見つかっていません。',
            'main_category.required' => 'メインカテゴリは必須項目です。',
            'sub_category' => 'サブカテゴリは0-3つの間で指定してください。',
            'intro.required' => '紹介内容は必須項目です。',
            'open_flg_group' => '「公開する」か「下書き」を選んでください。'
        ]);

        if ($validator->fails()) {
            return redirect('post')
                ->withErrors($validator)
                ->withInput();
        }

        $id = Auth::id();
        /* note セッションが切れた場合を考慮している */
        // id取得できなければログインしていないためエラー
        if (empty($id)) return view('errors.404');

        $categories = Category::all()->pluck('category_name', 'id')->toArray();

        $postId = isset($request->post_id) ? $request->post_id : null;

        return view('post-confirm', ['request' => $request, 'categories' => $categories, 'update' => $request->update, 'post_id' => $postId]);
    }

    /**
     * 記事の登録実行
     * 
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function postComplete(Request $request)
    {
        $id = Auth::id();

        // id取得できなければログインしていないためエラー (正確にいうと 419 error)
        if (empty($id)) return view('errors.404');

        $attributes = $request->only(['title', 'movie_url', 'videoid', 'thumbnail', 'intro', 'main_category', 'sub_category1', 'sub_category2', 'sub_category3', 'open_flg_group']);

        if ($request->update) {
            // 更新
            $postId = $request->post_id;
            Post::where('id', $postId)
                ->where('deleted_at', null)
                ->update([
                    'title'                 => $attributes['title'],
                    'url'                   => $attributes['movie_url'],
                    'movie_id'              => $attributes['videoid'],
                    'thumbnail_url'         => $attributes['thumbnail'],
                    'content'               => $attributes['intro'],
                    'main_category_id'      => $attributes['main_category'],
                    'sub_category_id_first' => array_key_exists('sub_category1', $attributes) ? $attributes['sub_category1'] : null,
                    'sub_category_id_second' => array_key_exists('sub_category2', $attributes) ? $attributes['sub_category2'] : null,
                    'sub_category_id_third' => array_key_exists('sub_category3', $attributes) ? $attributes['sub_category3'] : null,
                    'open_flg'        => $attributes['open_flg_group'],
                ]);
        } else {
            // 新規作成
            Post::create([
                'user_id'               => $id,
                'title'                 => $attributes['title'],
                'url'                   => $attributes['movie_url'],
                'movie_id'              => $attributes['videoid'],
                'thumbnail_url'         => $attributes['thumbnail'],
                'content'               => $attributes['intro'],
                'main_category_id'      => $attributes['main_category'],
                'sub_category_id_first' => array_key_exists('sub_category1', $attributes) ? $attributes['sub_category1'] : null,
                'sub_category_id_second' => array_key_exists('sub_category2', $attributes) ? $attributes['sub_category2'] : null,
                'sub_category_id_third' => array_key_exists('sub_category3', $attributes) ? $attributes['sub_category3'] : null,
                'open_flg'        => $attributes['open_flg_group'],
            ]);
        }


        // 処理後はマイページtopへ リダイレクト
        return redirect('mypage');
    }

    /**
     * 既存の記事の修正ページ
     * 
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function postEdit(Request $request)
    {
        $attributes = $request->only(['post_id', 'movie_id']);

        // パラメータがなければエラー
        if (empty($attributes['post_id']) || empty($attributes['movie_id'])) {
            return view('errors.404');
        }

        $post = Post::where('id', $attributes['post_id'])->where('deleted_at', null)->first();

        $subQueryCat = Category::Select('sub.group_id', 'sub.category_name as label')
            ->from('categories as sub')
            ->where('header_flg', 1);

        // カテゴリ取得
        $categories = Category::Select('t1.id as value', 't1.group_id', 't1.category_name', 't2.label')
            ->from('categories as t1')
            ->leftJoinSub($subQueryCat, 't2', 't1.group_id', 't2.group_id')
            ->where('t1.header_flg', ProjectConst::HEADER_FLG_ZERO)
            ->orderBy('t1.group_id', 'asc')
            ->orderBy('t1.id', 'asc')
            ->get();

        return view('post-edit', ['post' => $post, 'categories' => $categories, 'update' => 1]);
    }


    /**
     * 記事を論理削除する
     * 
     * @param Request $request
     * @return 
     */
    public function deletePost(Request $request)
    {
        $attributes = $request->only(['post_id']);

        // パラメータがなければエラー
        if (empty($attributes['post_id'])) {
            return view('errors.404');
        }

        $id = Auth::id();
        // id取得できなければログインしていないためエラー
        if (empty($id)) return view('errors.404');

        try {
            /* note リカバリが必要になる可能性を考慮し記事は物理削除しない */
            // 記事削除
            Post::where('id', $attributes['post_id'])->update(['deleted_at' => now()]);
            // vote削除
            Vote::where('post_id', $attributes['post_id'])->update(['deleted_at' => now()]);
        } catch (Exception $e) {
            Log::error('記事削除時にエラーが発生しました。',  $e->getMessage());
        }

        return;
    }
}
