<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_flg', 'open_flg', 'alert_flg', 'user_id', 'title',
        'url', 'movie_id', 'thumbnail_url', 'content', 'main_category_id',
        'sub_category_id_first', 'sub_category_id_second', 'sub_category_id_third', 'deleted_at', 'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
