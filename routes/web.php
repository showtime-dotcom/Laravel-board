<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\URL;

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
    return view('welcome');
});

//練習用
// Route::get('hello', function () {

//     echo 'キチンと表示されてればOKです';
// });

Route::get('hello', [
    PostsController::class,
    'hello'
]);

// index.blade.phpを踏むとindexメソッドが動くルーティング設定
Route::get('index', [PostsController::class, 'index']);

// create-formのURLを踏むとcreateFormメソッドが動くルーティング設定(createFrom.blade.phpを表示する)
Route::get('/create-form', [PostsController::class, 'createForm']);

// post形式で値が送られてきたらcreateメソッドが動くルーティング設定
Route::post('post/create', [PostsController::class, 'create']);

// 編集ボタンを押すとupdateFormメソッドが動くルーティング
Route::get('post/{id}/update-form', [PostsController::class, 'updateForm']);

// /post/updateを踏んだらpost形式で値を送り、updateメソッドが動くルーティング
Route::post('/post/update', [PostsController::class, 'update']);

// 削除ボタンを押すとdeleteメソッドが動くルーティング
Route::get('/post/{id}/delete', [PostsController::class, 'delete']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
