@extends('layouts.app')

@section('content')

<div class='container'>

  <!-- 検索フォーム -->
  <!-- inputを作成し、inputの値をget方式で送信する -->
  <?php
  // ↓確認用
  // var_dump($lists);
  // var_dump(count($lists));
  if (count($lists) == 0) {
    echo "検索結果がありませんでした。";
  }
  ?>

  {!! Form::open
  (['url' => '/index',
  'method' => 'get']) !!}
  <div class="form-group" style="margin-bottom: .25rem;">
    {!! Form::input('search',
    'search',
    $Search,
    ['class' => 'form-search',
    'placeholder' => '検索ワードを入力してください']) !!}
  </div>

  <button type="submit" class="btn btn-primary pull-right" style="margin-bottom: 24px;">検索する</button>
  {!! Form::close() !!}

  <!-- 送信先は同じページ -->

  <!-- 新規投稿ボタン -->
  <p class="pull-right"><a class="btn btn-success" href="{{ route('posts.createForm') }}">新規投稿</a></p>
  <h2 class='page-header'>投稿一覧</h2>
  <!-- <table class='table table-hover'> -->
  <table class='container-fluid'>

    <!-- 配列$listに格納されてるアイテムをforeach文で繰り返し表示させる -->
    @foreach ($lists as $list)

    <!-- <tr>
      <th>No</th>
      <th>名前</th>
      <th>コメント</th>
      <th>投稿日時</th>
      <th></th>
      <th></th>
    </tr>
    <tr>
      <td>{{ $list->id }}</td>
      <td>{{ $list->user_name }}</td>
      <td>{{ $list->contents }}</td>
      <td>{{ $list->created_at }}</td> -->
    <!-- 更新ボタン -->
    <!-- <td><a class="btn btn-primary" href="/post/{{ $list->id }}/update-form">編集</a></td>
      <td><a class="btn btn-danger" href="/post/{{ $list->id }}/delete" onclick="return confirm('こちらの投稿を削除してもよろしいでしょうか？')">削除</a></td>
    </tr> -->

    <tr>
      <td class="col">投稿者名：{{ $list->user->name ?? $list->user_name ?? '名無し' }}</td>
    </tr>
    <tr>
      <td>コメント：{{ $list->contents }}</td>
    </tr>
    <tr>
      <td>投稿日時：{{ $list->created_at }}</td>
    </tr>
    <tr>
      <td style="border: none; padding-bottom: 1.2rem; text-align: end;">
        <!-- 更新ボタン -->
        @auth {{-- ユーザーがログインしている場合のみ、以下のコードブロックを実行 --}}
        {{-- ログインユーザーのID (Auth::id()) と、投稿の作成者ID ($list->user_id) が一致するか確認 --}}
        @if(Auth::id() == $list->user_id)
        <a class="btn btn-primary" href="{{ route('posts.edit', $list) }}">編集</a> <!-- 削除ボタン -->
        <form action="{{ route('posts.destroy', $list) }}" method="POST" style="display:inline;">
          @csrf {{-- CSRFトークンを含める --}}
          @method('DELETE') {{-- DELETEメソッドをシミュレートするLaravelディレクティブ --}}
          <button type="submit" class="btn btn-danger" onclick="return confirm('こちらの投稿を削除してもよろしいでしょうか？')">削除</button>
        </form> @endif
        @endauth
      </td>
    </tr>

    @endforeach

  </table><!-- table table-hover -->

</div><!-- container -->
<style>
  tr:nth-of-type(2n) {
    margin-bottom: 10px;
  }
</style>

@endsection
