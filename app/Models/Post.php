<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // ★追加または修正★
    // user_id も一括代入可能にする
    protected $fillable = [
        'user_id', // 新しく追加
        'contents', // 既存のcontents
        // 'user_name', // user_nameを削除するならここも削除
    ];

    // user_name を削除した場合、このリレーションでユーザー名を取得できるようにする
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
