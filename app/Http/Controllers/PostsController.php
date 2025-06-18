<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

// DB接続文(DB::～を使う場合は必ず記載すること)
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class PostsController extends Controller

{
    //helloメソッドを追加(復習)
    public function hello()

    {
        echo 'Hello World!!<br>';
        echo 'コントローラーから';
    }

    //indexメソッド追加(トップページを表示させる)
    public function index(Request $request)
    {
        // Request $requestを定義して、$requestからinputの値を取得する
        $Search = $request->input('search');
        // 投稿番号(id)で降順に表示する
        $query = Post::orderBy('id', 'asc');

        // 検索フォーム欄が空か半角スパースもしくは全角スペースであれば
        if (empty($Search) or ($Search === ' ' or $Search === '　')) {
            // print_r("1");
            // 投稿を全て表示させる
            $list = DB::table('posts')->get();
            // 検索フォームに値が入力されている場合、
        } else if (!empty($Search)) {
            // where句を用いて%を用いて「名前」と「コメント」からあいまい検索して一致したものを表示する
            // var_dump("2");
            $query = $query->where('user_name', 'LIKE', "%" . $Search . "%")
                ->orWhere('contents', 'LIKE', "%" . $Search . "%");
        }

        // 検索結果を変数listsに入れ、検索キーワードを変数Searchに入れてviewに渡す
        return view('posts.index', ['lists' => $query->get(), 'Search' => $Search]);

        // 以下の$list変数の定義で、データベースの処理を変更する
        // whereを入れて、inputの値を利用する
        // 曖昧検索なので、likeオペレーターを利用する（％を使う）
        $list = DB::table('posts')->get();
        // 'posts.index'でpostsディレクトリ下にあるindex.blade.phpを呼び出す。
        // 「$list」を変数「lists」として渡す
        return view('posts.index', ['lists' => $list]);
    }

    public function createForm()
    {
        // posts直下のcreateFrom.blade.phpを返す
        return view('posts.createForm');
    }

    // ブラウザ表示をしない、登録処理だけを行うメソッド(処理終了後、index.blade.phpへ戻す)
    public function create(Request $request)
    {
        // createForm.blade.phpのinputから受け取った値をそれぞれ変数$name,変数$postに格納し、DBにレコードとして挿入(insert)する
        $name = $request->input('newName');
        $post = $request->input('newPost');
        DB::table('posts')->insert([
            'user_name' => $name,
            'contents' => $post
        ]);
        // ↓変数検証用
        // var_dump($post);
        // var_dump($name);
        return redirect('/index');
    }

    // 変種ボタンを押すと編集フォームを表示するメソッド
    public function updateForm($id)
    {
        // postsテーブルからidが一致するレコードを代入
        $post = DB::table('posts', 'contents')
            ->where('id', $id)
            ->first();
        // 選んだIDの箇所と同じコメントが入力された編集フォームを返す
        // var_dump($post);
        return view('posts.updateForm', ['post' => $post]);
    }

    // 更新ボタンを押すと更新されるメソッド
    public function update(Request $request)
    {
        $id = $request->input('id');
        $up_post = $request->input('upPost');
        DB::table('posts')
            ->where('id', $id)
            ->update(
                ['contents' => $up_post]
            );
        return redirect('/index');
    }

    // 削除実行メソッド
    public function delete($id)
    {
        DB::table('posts')
            ->where('id', $id)
            ->delete();
        return redirect('/index');
    }

    // Auth機能をインスタンス化した処理
    public function __construct()

    {
        $this->middleware('auth');
    }
}
