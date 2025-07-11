<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // user_id カラムを追加
            // foreignId() は Laravel 7+ で推奨される外部キーの定義方法
            // constrained() は users テーブルの id カラムに紐付け、
            // onDelete('cascade') はユーザーが削除されたら関連する投稿も削除する設定
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            // user_name カラムを削除
            $table->dropColumn('user_name');
            // もし既存の投稿にuser_idがない場合、nullable() を付けて一時的にnullを許可します。
            // 既存のデータがある場合は、後で user_id を設定する処理が必要です。

            // user_name カラムは、user_id でユーザー情報を取得できるため、
            // 不要であれば削除することも検討できますが、
            // 課題8の要件を考えると、削除するのが適切です。
            // $table->dropColumn('user_name'); // 不要であればこの行を追加
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // down メソッドでは、up で追加したカラムを削除する
            $table->dropForeign(['user_id']); // 外部キー制約を削除
            $table->dropColumn('user_id');    // user_id カラムを削除
            // $table->string('user_name', 255)->after('id'); // user_name を戻す場合
        });
    }
};
