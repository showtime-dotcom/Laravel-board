@extends('layouts.app')

@section('content')

<!-- ↓これより入力フォーム部分 -->
<div class='container'>

  <h2 class='page-header'>新しく投稿する</h2>
  {!! Form::open(['route' => 'posts.store']) !!}

  <div class="form-group">
    <!-- 名前を変数newName、投稿内容を変数newPostとしてPostController.phpのcreateメソッドへpost形式で送る -->
    <!-- {!! Form::input('name', 'newName', null, ['required', 'class' => 'form-control', 'placeholder' => '名前']) !!} -->
    {!! Form::input('text', 'contents', null, ['required', 'class' => 'form-control', 'placeholder' => '投稿内容']) !!}

    @error('contents')
    <div style="color: red;">{{ $message }}</div>
    @enderror
  </div>

  <!-- ↓送信ボタン -->
  <button type="submit" class="btn btn-success pull-right" style="margin-top: 8px;">投稿する</button>
  {!! Form::close() !!}

</div><!-- .container -->

@endsection
