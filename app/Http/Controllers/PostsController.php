<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// DB接続文(DB::～を使う場合は必ず記載すること)
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class PostsController extends Controller

{
    // Auth機能をインスタンス化した処理
    public function __construct()

    {
        $this->middleware('auth');
    }


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
        $query = Post::with('user')->orderBy('id', 'asc');
        // 検索フォーム欄が空か半角スパースもしくは全角スペースであれば
        if (!empty($Search) && ($Search !== ' ' && $Search !== '　')) {
            // 検索ワードが空でなく、スペースのみでもない場合
            // user_name カラムを今後削除するなら、user リレーションを介して検索する
            $query->where(function ($q) use ($Search) {
                // contents での検索
                $q->where('contents', 'LIKE', "%" . $Search . "%");

                // user_id で紐付けたユーザー名での検索 (推奨)
                // これを使うには Post モデルに user() リレーションが必須です
                $q->orWhereHas('user', function ($subQuery) use ($Search) {
                    $subQuery->where('name', 'LIKE', "%" . $Search . "%");
                });

                // もし一時的に user_name カラムも残して検索対象とするなら
                // $q->orWhere('user_name', 'LIKE', "%" . $Search . "%");
            });
        }
        // 検索ワードが空、またはスペースのみの場合は、追加でwhere句が適用されないため、
        // 上記の $query = Post::with('user')->orderBy('id', 'asc'); がそのまま適用され、全件取得となる

        // 検索結果を変数listsに入れ、検索キーワードを変数Searchを入れてviewに渡す
        // ★修正: 最後に一度だけ get() を呼び出す
        $lists = $query->get();

        return view('posts.index', ['lists' => $lists, 'Search' => $Search]);

        // 以下の$list変数の定義で、データベースの処理を変更する
        // whereを入れて、inputの値を利用する
        // 曖昧検索なので、likeオペレーターを利用する（％を使う）
        // $list = DB::table('posts')->get();
        // 'posts.index'でpostsディレクトリ下にあるindex.blade.phpを呼び出す。
        // 「$list」を変数「lists」として渡す
        // return view('posts.index', ['lists' => $list]);
    }


    public function createForm()
    {
        // posts直下のcreateFrom.blade.phpを返す
        return view('posts.createForm');
    }

    // ブラウザ表示をしない、登録処理だけを行うメソッド(処理終了後、index.blade.phpへ戻す)
    public function create(Request $request)
    {
        // ★★★ バリデーションの追加 ★★★
        $request->validate([
            // 'contents' は createForm.blade.php で変更した入力フィールド名
            'contents' => 'required|string|max:100',
        ], [
            'contents.required' => '投稿内容は必須です。',
            'contents.string' => '投稿内容は文字列で入力してください。',
            'contents.max' => '投稿内容は100文字以内で入力してください。',
        ]);

        // ★★★ 名前入力欄なし、ログインユーザー名自動登録 ★★★
        // DB::table('posts')->insert() の代わりに Eloquent ORM を使用
        Post::create([
            'user_id' => Auth::id(), // ★ログインユーザーのIDを自動でセット★
            // user_name カラムは、user_id を使うなら不要になるので、DBから削除を推奨
            // もし user_name を残すなら、以下のように記述 (ただし非推奨)
            // 'user_name' => Auth::user()->name, // ログインユーザーの名前を自動でセット
            'contents' => $request->contents, // createForm.blade.php で 'contents' に変更した前提
        ]);

        return redirect()->route('posts.index')->with('success', '投稿が作成されました！'); // ルート名でリダイレクト推奨
    }


    // 編集ボタンを押すと編集フォームを表示するメソッド
    public function updateForm($id)
    {
        // Eloquent ORM を使用して投稿を取得
        $post = Post::where('id', $id)->first();

        // ★認可のチェック（課題7関連）★
        // ログインユーザーが投稿の作成者でない場合、アクセスを拒否
        if (Auth::id() != $post->user_id) {
            abort(403, '不正なアクセスです。この投稿を編集する権限がありません。');
        }

        return view('posts.updateForm', ['post' => $post]);
    }


    // 更新ボタンを押すと更新されるメソッド
    public function update(Request $request, Post $post) // ★引数にPostモデルを追加（ルートモデルバインディング）★
    {
        // ★ バリデーションの追加 ★
        $request->validate([
            'contents' => 'required|string|max:100',
        ], [
            'contents.required' => '投稿内容は必須です。',
            'contents.string' => '投稿内容は文字列で入力してください。',
            'contents.max' => '投稿内容は100文字以内で入力してください。',
        ]);

        // ★認可のチェック（課題7関連）★
        // updateForm と同様に、ログインユーザーが投稿の作成者でない場合、アクセスを拒否
        if (Auth::id() != $post->user_id) {
            abort(403, '不正なアクセスです。この投稿を更新する権限がありません。');
        }

        // Eloquent ORM を使用して更新
        $post->contents = $request->contents; // フォームの入力フィールド名に合わせる
        $post->save();

        return redirect()->route('posts.index')->with('success', '投稿が更新されました！');
    }


    // 削除実行メソッド
    public function delete(Post $post) // ★引数を変更★
    {
        // 認可チェック: 自分の投稿でなければ削除できないようにする
        if (Auth::id() != $post->user_id) {
            return redirect()->route('posts.index')->with('error', '他のユーザーの投稿は削除できません。');
        }

        // Eloquent ORM を使用して削除
        $post->delete();

        return redirect()->route('posts.index')->with('success', '投稿が削除されました！');
    }
}
