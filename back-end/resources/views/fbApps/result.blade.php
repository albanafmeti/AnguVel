@extends('app')

@section('head')

    <title>{{ $result->title }} | Te Rejat</title>
    <meta property="og:url"
          content="http://terejat.al/fb/login?appId={{ $result->app_id }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="{{ $result->title }} | Zbuloje edhe ti!"/>
    <meta property="og:description" content="{{ $result->description }}"/>
    <meta property="og:image"
          content="{{ $result->image_url }}"/>
@endsection