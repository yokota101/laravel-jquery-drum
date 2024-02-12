<!DOCTYPE html>
<html lang="ja">

<head>
    @include('layouts.head')
    <link rel="stylesheet" href="{{ asset('/css/post-confirm.css')  }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    @include('layouts.header')
    <div id="post-confirm">
        <section id="container">
            <h1>投稿内容確認</h1>
            <form id="root_div" action="/post-complete" method="post">
                @csrf
                <div>
                    <h3>タイトル</h3>
                    <h4 class="input_content">{{ $request->title }}</h4>
                    <input value="{{ $request->title }}" name="title" type="hidden">
                </div>
                <div>
                    <h3>URL</h3>
                    <h4 class="input_content">{{ $request->movie_url }}</h4>
                    <input value="{{ $request->movie_url }}" name="movie_url" type="hidden">
                </div>
                <div>
                    <p>元動画のタイトル</p>
                    <p>{{ $request->origin_title }}</p>
                    <img src="{{ $request->thumbnail }}">
                    <input value="{{ $request->thumbnail }}" name="thumbnail" type="hidden">
                </div>
                <div>
                    <h3>メインカテゴリ</h3>
                    <div class="chip green accent-3">
                        <i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$request->main_category] }}
                    </div>
                    <input value="{{ $request->main_category }}" name="main_category" type="hidden">
                </div>
                <div>
                    <h3>サブカテゴリ</h3>
                    @if( isset($request->sub_category[0]) &&
                    array_key_exists($request->sub_category[0], $categories) &&
                    isset($categories[$request->sub_category[0]]))
                    <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$request->sub_category[0]] }}</div>
                    <input value="{{ $request->sub_category[0] }}" name="sub_category1" type="hidden">
                    @endif
                    @if( isset($request->sub_category[1]) &&
                    array_key_exists($request->sub_category[1], $categories) &&
                    isset($categories[$request->sub_category[1]]))
                    <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$request->sub_category[1]] }}</div>
                    <input value="{{ $request->sub_category[1] }}" name="sub_category2" type="hidden">
                    @endif
                    @if( isset($request->sub_category[2]) &&
                    array_key_exists($request->sub_category[2], $categories) &&
                    isset($categories[$request->sub_category[2]]))
                    <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$request->sub_category[2]] }}</div>
                    <input value="{{ $request->sub_category[2] }}" name="sub_category3" type="hidden">
                    @endif
                </div>

                <div>
                    <h3>内容本文</h3>
                    <pre class="input_content">{{ $request->intro }}</pre>
                    <input value="{{ $request->intro }}" name="intro" type="hidden">
                    <input value="{{ $request->videoid }}" name="videoid" type="hidden">
                </div>
                <div>
                    <h3>公開しますか？</h3>
                    @php
                    if($request->open_flg_group == '1'){
                    $dispOpenTxt = '公開する';
                    }else{
                    $dispOpenTxt = '下書き';
                    }
                    @endphp
                    <p>{{ $dispOpenTxt }}</p>
                    <input value="{{ $request->open_flg_group }}" name="open_flg_group" type="hidden">
                </div>
                <div>
                    <button class="btn waves-effect waves-light indigo" type="submit">投稿する
                        <i class="material-icons right">create</i>
                    </button>
                    <button class="btn waves-effect waves-light red darken-1" type="button" onClick="history.back()">もどる</button>
                </div>
                <input type="hidden" value="{{ $update }}" name="update" />
                <input type="hidden" value="{{ $post_id }}" name="post_id" />
            </form>

        </section>
    </div>
    <footer id="foot_container">
        @include('layouts.footer')
    </footer>
</body>

</html>