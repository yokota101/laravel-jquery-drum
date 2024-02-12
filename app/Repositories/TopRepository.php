<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class TopRepository
{
    /**
     * 総合ランキング取得クエリを返却
     */
    public function makeTotalRankQuery()
    {
        $subQueryVote = Vote::Select(
            'post_id',
            DB::raw('ifnull(sum(`good_point`), 0) as good_point'),
            DB::raw('ifnull(sum(`bad_point`), 0) as bad_point'),
            DB::raw('ifnull(sum(`good_point`), 0) - ifnull(sum(`bad_point`), 0) as total')
        )->Groupby('post_id');

        return Post::Select(
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
            ->from('posts as t1')
            ->join('users as t2', 't1.user_id', 't2.id')
            ->where('t1.deleted_at', null)
            ->where('t2.deleted_at', null)
            ->where('t1.open_flg', 1)
            ->leftJoinSub($subQueryVote, 'tb2', 't1.id', 'tb2.post_id');
    }
    /**
     * 直近の記事を取得するクエリを返却
     */
    public function makeRecentPostQuery()
    {
        return Post::Select(
            'posts.id',
            'posts.request_flg',
            'posts.open_flg',
            'posts.alert_flg',
            'posts.user_id',
            'posts.title',
            'posts.url',
            'posts.movie_id',
            'posts.thumbnail_url',
            'posts.content',
            'posts.main_category_id',
            'posts.sub_category_id_first',
            'posts.sub_category_id_second',
            'posts.sub_category_id_third',
            'posts.created_at',
            'users.name',
            'users.image',
            'users.self_intro',
            'users.twitter_username',
            'users.instagram_id',
            'users.facebook_url'
        )
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('posts.deleted_at', null)
            ->where('users.deleted_at', null)
            ->where('posts.open_flg', 1);
    }
}
