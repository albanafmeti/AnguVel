@extends('app')

@section('head')

    <title>Te Rejat | Kliko dhe Informohu</title>
    <link rel="canonical" target="_blank" href="http://terejat.al"/>
    <!-- <meta property="og:url" content="http://terejat.al"/> -->
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="TeRejat.al - Kliko dhe Informohu | Te Rejat"/>
    <meta property="og:description" content="Njihuni me informacionet me te fundit nga vendi dhe bota duke naviguar ne artikujt e
          portalit tone. Fokusohemi kryesisht ne showbiz, lifestyle dhe sport. Navigim te kendshem!"/>
    <meta property="og:image" content="{{ thumbnail('cover.png', 1200, 630, 'background') }}"/>
    <link rel="canonical" href=""/>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
@endsection

@section('body')
    <div class="flex-center position-ref full-height">

        <div class="content">
            <div class="title m-b-md">
                TeRejat.al BackEnd Service
            </div>

            <div class="links">
                <a href="https://fb.com/terejat.al">Facebook</a>
                <a href="https://twitter.com/terejatal">Twitter</a>
            </div>
        </div>
    </div>
@endsection