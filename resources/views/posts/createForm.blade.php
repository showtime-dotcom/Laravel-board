@extends('layouts.app')

@section('content')

<!-- ↓これより入力フォーム部分 -->
<div class='container'>

  <h2 class='page-header'>新しく投稿する</h2>
  {!! Form::open(['url' => 'post/create']) !!}

  <div class="form-group">
    <!-- 名前を変数newName、投稿内容を変数newPostとしてPostController.phpのcreateメソッドへpost形式で送る -->
    {!! Form::input('name', 'newName', null, ['required', 'class' => 'form-control', 'placeholder' => '名前']) !!}
    {!! Form::input('text', 'newPost', null, ['required', 'class' => 'form-control', 'placeholder' => '投稿内容']) !!}
  </div>

  <!-- ↓送信ボタン -->
  <button type="submit" class="btn btn-success pull-right">追加</button>
  {!! Form::close() !!}

</div><!-- .container -->

@endsection
