<?php

namespace Database\Seeders;

// DB接続文
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //レコードを追加する一文。表示させたい投稿者名「user_name」と投稿内容「contents」を用意する
        DB::table('posts')->insert([

            [
                'user_name' => '山田太郎',
                'contents' => '投稿内容を表示しています'
            ],
            [
                'user_name' => '山田二郎',
                'contents' => '2つ目の投稿内容を表示しています'
            ],
            [
                'user_name' => '山田三郎',
                'contents' => '3つ目の投稿内容を表示しています'
            ]

        ]);
    }
}
