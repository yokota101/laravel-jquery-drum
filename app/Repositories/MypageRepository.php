<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class MypageRepository
{
    /**
     * 指定ユーザの記事を取得するクエリを返却
     * @param int|string $id ユーザID
     * @param int|null   $openFlg 公開フラグ
     */
    public function makeUserPostsQuery($id, $openFlg)
    {
        $subQueryVote = Vote::Select(
            'post_id',
            DB::raw('ifnull(sum(`good_point`), 0) as good_point'),
            DB::raw('ifnull(sum(`bad_point`), 0) as bad_point'),
            DB::raw('ifnull(sum(`good_point`), 0) - ifnull(sum(`bad_point`), 0) as total')
        )->Groupby('post_id');

        $query = Post::Select(
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
            ->leftJoinSub($subQueryVote, 'tb2', 't1.id', 'tb2.post_id')
            ->where(function ($q) use ($id) {
                $q->where('t1.user_id', $id);
            })
            ->where('t1.deleted_at', null)
            ->where('t2.deleted_at', null)
            ->orderBy('tb2.total', 'desc')
            ->orderBy('t1.created_at', 'desc');

        if ($openFlg === 1 || $openFlg === 0){
            $query->where('t1.open_flg', $openFlg);
        }
        return $query;
    }
}
