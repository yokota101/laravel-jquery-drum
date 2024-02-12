<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * カテゴリ一覧カテゴリごとに整理してを取得
     */
    public function getCategoryList()
    {
        $subQueryCat = Category::Select('sub.group_id', 'sub.category_name as label')
            ->from('categories as sub')
            ->where('header_flg', 1);

        return Category::Select('t1.id as value', 't1.group_id', 't1.category_name', 't2.label')
            ->from('categories as t1')
            ->leftJoinSub($subQueryCat, 't2', 't1.group_id', 't2.group_id')
            ->where('t1.header_flg', 0)
            ->orderBy('t1.group_id', 'asc')
            ->orderBy('t1.id', 'asc');
    }
}
