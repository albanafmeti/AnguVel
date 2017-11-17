@extends('app')

@section('head')

    <title>{{ $post->title }} | Te Rejat</title>
    <meta property="og:url"
          content="http://terejat.al/{{ request()->path() }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="{{ $post->title }} | Te Rejat"/>
    <meta property="og:description" content="{{ $post->small_content }}"/>
    <meta property="og:image"
          content="{{ $post->thumb_lg }}"/>
@endsection