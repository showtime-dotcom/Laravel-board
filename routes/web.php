<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Auth\LoginController; // logout ルートを使うために追加
use App\Http\Controllers\HomeController; // HomeController を使うために追加

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

//練習用
// Route::get('hello', function () {

//     echo 'キチンと表示されてればOKです';
// });

Route::get('hello', [
    PostsController::class,
    'hello'
]);

Route::get('/', function () {
    return redirect()->route('login'); // 'login' という名前のルートへリダイレクト
});

// index.blade.phpを踏むとindexメソッドが動くルーティング設定
Route::get('index', [PostsController::class, 'index'])->name('posts.index');

// create-formのURLを踏むとcreateFormメソッドが動くルーティング設定(createFrom.blade.phpを表示する)
Route::get('/create-form', [PostsController::class, 'createForm'])->name('posts.createForm');

// post形式で値が送られてきたらcreateメソッドが動くルーティング設定
Route::post('/posts', [PostsController::class, 'create'])->name('posts.store');

// 編集ボタンを押すとupdateFormメソッドが動くルーティング
Route::get('posts/{post}/edit', [PostsController::class, 'updateForm'])->name('posts.edit');

// /post/updateを踏んだらpost形式で値を送り、updateメソッドが動くルーティング
Route::put('posts/{post}', [PostsController::class, 'update'])->name('posts.update');

// 削除ボタンを押すとdeleteメソッドが動くルーティング
Route::delete('posts/{post}', [PostsController::class, 'delete'])->name('posts.destroy');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
