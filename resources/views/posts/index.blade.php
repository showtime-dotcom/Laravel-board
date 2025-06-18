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
  <p class="pull-right"><a class="btn btn-success" href="/create-form">新規投稿</a></p>

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

    <tr style="background-color: gray;">
      <td class="col" style="color: #f8f9fa;">No.{{ $list->id }}</td>
    </tr>
    <tr>
      <td class="col">投稿者名：{{ $list->user_name }}</td>
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
        <a class="btn btn-primary" href="/post/{{ $list->id }}/update-form">編集</a>
        <a class="btn btn-danger" href="/post/{{ $list->id }}/delete" onclick="return confirm('こちらの投稿を削除してもよろしいでしょうか？')">削除</a>
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
