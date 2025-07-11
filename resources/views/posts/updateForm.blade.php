@extends('layouts.app')

@section('content')

<div class='container'>

  <h2 class='page-header'>投稿内容を変更する</h2>

  {!! Form::open(['route' => ['posts.update', $post], 'method' => 'put']) !!} <div class="form-group">
    {!! Form::hidden('id', $post->id) !!}
    {!! Form::input('text', 'contents', $post->contents, ['required', 'class' => 'form-control']) !!}

    <!-- エラーメッセージを表示する -->
    @error('contents')
    <div style="color: red;">{{ $message }}</div>
    @enderror
  </div>

  <button type="submit" class="btn btn-primary pull-right" style="margin-top: 8px;">更新</button>
  {!! Form::close() !!}

</div>

@endsection
