<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * 記事閲覧クラス
 */
class WatchController extends Controller
{
    /**
     * 記事閲覧ページの表示
     * 
     * @param  Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getIndex(Request $request)
    {
        $attributes = $request->only(['post_id', 'movie_id']);

        // validation
        if (empty($attributes['post_id']) || empty($attributes['movie_id'])) {
            return view('errors.404');
        }


        $subQuery = Vote::Select(
            DB::raw($attributes['post_id'] . ' as id'),
            DB::raw('ifnull(sum(`good_point`), 0) as good_point'),
            DB::raw('ifnull(sum(`bad_point`), 0) as bad_point')
        )
            ->where('post_id', $attributes['post_id'])
            ->Groupby('post_id');

        $post = Post::Select(
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
            'vt.good_point',
            'vt.bad_point'
        )
            ->from('posts as t1')
            ->join('users as t2', 't1.user_id', 't2.id')
            ->where('t1.id', $attributes['post_id'])
            ->where('t1.deleted_at', null)
            ->where('t2.deleted_at', null)
            ->where('t1.open_flg', 1)
            ->leftJoinSub($subQuery, 'vt', 'vt.id', 't1.id')
            ->first();

        // 記事が見つからなかった場合
        if (empty($post)) {
            return view('errors.404');
        }

        $id = Auth::id();

        // id取得できなければログインしていない判定
        if (empty($id)) {
            $loginFlg = 0;
            $voteinfo = null;
        } else {
            // ログインしていれば、現在の評価情報を取得
            $loginFlg = 1;
            $voteinfo = Vote::where('user_id', $id)->where('post_id', $attributes['post_id'])->first();
        }


        $categories = Category::all()->pluck('category_name', 'id')->toArray();


        $keywords = $categories[$post->main_category_id];
        $keywords .= empty($post->sub_category_id_first) ? '' : ', ' . $categories[$post->sub_category_id_first];
        $keywords .= empty($post->sub_category_id_second) ? '' : ', ' . $categories[$post->sub_category_id_second];
        $keywords .= empty($post->sub_category_id_third) ? '' : ', ' . $categories[$post->sub_category_id_third];

        $seoObj = (object) [
            'title' => $post->title,
            'desc' => mb_substr($post->content, 0, 120),
            'keywords' => $keywords,
            'image' => $post->thumbnail_url
        ];

        return view('watch', ['post' => $post, 'categories' => $categories, 'loginFlg' => $loginFlg, 'voteinfo' => $voteinfo, 'seoObj' => $seoObj]);
    }


    /**
     * 評価テーブルのレコード作成 or 更新
     * 
     * @param  Request $request
     * @return int 1:登録成功, 2:失敗
     */
    public function voteUpdate(Request $request)
    {

        $id = Auth::id();
        // id取得できなければログインしていないためエラー
        if (empty($id)) return 0;

        $attributes = $request->only(['good_point', 'bad_point', 'post_id']);

        // validation
        if (array_key_exists('good_point', $attributes) && array_key_exists('bad_point', $attributes) && array_key_exists('post_id', $attributes)) {
            // ここでvotes を 作成 or 更新
            Vote::updateOrCreate(
                ['user_id' => $id, 'post_id' => $attributes['post_id']],
                ['user_id' => $id, 'good_point' => $attributes['good_point'], 'bad_point' => $attributes['bad_point'], 'post_id' => $attributes['post_id']]
            );
            return 1;
        } else {
            // パラメータが欠けている。
            return 0;
        }
    }
}
