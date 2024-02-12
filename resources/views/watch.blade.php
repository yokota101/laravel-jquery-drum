<!DOCTYPE html>
<html lang="ja">

<head>
    @include('layouts.head')
    <link rel="stylesheet" href="{{ asset('/css/watch.css')  }}">
    <link rel="stylesheet" href="{{ asset('/css/user-display.css')  }}">
    <script type="text/javascript" src="{{ asset('js/watch.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    @include('layouts.header')
    <section id="watch_container">

        <h2>個別記事ページ</h2>

        <div class="movie_area">
            @php
            $youtubeUrl = 'https://www.youtube.com/embed/'. $post->movie_id;
            @endphp
            <iframe frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" width="640" height="360" src="{{ $youtubeUrl }}"></iframe>
        </div>
        <br />
        @php
        if(empty($voteinfo)){
        // 空の時はボタンはフラット
        $goodBtnClass = 'waves-effect waves-indigo btn-flat';
        $goodBtnCss = 'border: 1px solid #3f51b5; color: #3f51b5; ';
        $badBtnClass = 'waves-effect waves-pink btn-flat';
        $badBtnCss = 'border: 1px solid #ff4081; color: #ff4081; ';
        }else{
        // レコードがある時はボタンに色を付ける
        if($voteinfo->good_point == 1){
        // goodがある時
        $goodBtnClass = 'waves-effect waves-indigo indigo btn';
        $goodBtnCss = 'border: 1px solid #3f51b5; color: ; ';
        $badBtnClass = 'waves-effect waves-pink btn-flat';
        $badBtnCss = 'border: 1px solid #ff4081; color: #ff4081; ';
        }elseif($voteinfo->bad_point == 1){
        // badがある時
        $goodBtnClass = 'waves-effect waves-indigo btn-flat';
        $goodBtnCss = 'border: 1px solid #3f51b5; color: #3f51b5; ';
        $badBtnClass = 'waves-effect waves-pink pink accent-2 btn';
        $badBtnCss = 'border: 1px solid #ff4081; color: ; ';
        }else{
        // 空の時はボタンはフラット
        $goodBtnClass = 'waves-effect waves-indigo btn-flat';
        $goodBtnCss = 'border: 1px solid #3f51b5; color: #3f51b5; ';
        $badBtnClass = 'waves-effect waves-pink btn-flat';
        $badBtnCss = 'border: 1px solid #ff4081; color: #ff4081; ';
        }
        }
        @endphp
        @csrf
        <a id="good-button" class="{{ $goodBtnClass }}" style="{{ $goodBtnCss }}"><i class="material-icons left">thumb_up</i><span id="good-point">{{ $post->good_point ?? 0 }}</span></a>
        <a id="bad-button" class="{{ $badBtnClass }}" style="{{ $badBtnCss }}"><i class="material-icons left">thumb_down</i><span id="bad-point">{{ $post->bad_point ?? 0}}</span></a>
        <br />

        <div class="article">
            <h3>{{$post->title}}</h3>

            <div id="userDisplay">
                @php
                $linkStr = '/user?user_id='.$post->user_id;
                @endphp
                <a href="{{ $linkStr }}">
                    <div style="cursor:'pointer'">
                        @if(empty($post->image))
                        <img class="profimg" src='/images/default_drummer.png' alt="{{$post->name}}"></img>
                        @else
                        <img class="profimg" src="{{$post->image}}" alt="{{$post->name}}" onerror="this.src = '/images/default_drummer.png';"></img>
                        @endif
                        <strong>{{$post->name}}さん</strong>がアップしました。
                    </div>
                </a>
            </div>

            <br />
            <h6>メインカテゴリー</h6>
            <div>
                @if( isset($post->main_category_id) && isset($categories) &&
                array_key_exists($post->main_category_id, $categories) &&
                isset($categories[$post->main_category_id]))
                <div class="chip green accent-3"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$post->main_category_id] }}</div>
                @else
                <span>カテゴリ設定なし</span>
                @endif
            </div>
            <br />
            <h6>サブカテゴリー</h6>
            <div>
                @if( isset($post->sub_category_id_first) && isset($categories) &&
                array_key_exists($post->sub_category_id_first, $categories) &&
                isset($categories[$post->sub_category_id_first]))
                <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$post->sub_category_id_first] }}</div>
                @endif
                @if( isset($post->sub_category_id_second) && isset($categories) &&
                array_key_exists($post->sub_category_id_second, $categories) &&
                isset($categories[$post->sub_category_id_second]))
                <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$post->sub_category_id_second] }}</div>
                @endif
                @if( isset($post->sub_category_id_third) && isset($categories) &&
                array_key_exists($post->sub_category_id_third, $categories) &&
                isset($categories[$post->sub_category_id_third]))
                <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$post->sub_category_id_third] }}</div>
                @endif
            </div>

            <br />
            <p>▼紹介文▼</p>
            <pre class="content">{{$post->content}}</pre>

            <div>
                <button aria-label="facebook" class="react-share__ShareButton" style="background-color: transparent; border: none; padding: 0px; font: inherit; color: inherit; cursor: pointer;"><svg viewBox="0 0 64 64" width="60" height="60">
                        <circle cx="32" cy="32" r="31" fill="#3b5998"></circle>
                        <path d="M34.1,47V33.3h4.6l0.7-5.3h-5.3v-3.4c0-1.5,0.4-2.6,2.6-2.6l2.8,0v-4.8c-0.5-0.1-2.2-0.2-4.1-0.2 c-4.1,0-6.9,2.5-6.9,7V28H24v5.3h4.6V47H34.1z" fill="white"></path>
                    </svg></button>

                <button aria-label="line" class="react-share__ShareButton" style="background-color: transparent; border: none; padding: 0px; font: inherit; color: inherit; cursor: pointer;"><svg viewBox="0 0 64 64" width="60" height="60">
                        <circle cx="32" cy="32" r="31" fill="#00b800"></circle>
                        <path d="M52.62 30.138c0 3.693-1.432 7.019-4.42 10.296h.001c-4.326 4.979-14 11.044-16.201 11.972-2.2.927-1.876-.591-1.786-1.112l.294-1.765c.069-.527.142-1.343-.066-1.865-.232-.574-1.146-.872-1.817-1.016-9.909-1.31-17.245-8.238-17.245-16.51 0-9.226 9.251-16.733 20.62-16.733 11.37 0 20.62 7.507 20.62 16.733zM27.81 25.68h-1.446a.402.402 0 0 0-.402.401v8.985c0 .221.18.4.402.4h1.446a.401.401 0 0 0 .402-.4v-8.985a.402.402 0 0 0-.402-.401zm9.956 0H36.32a.402.402 0 0 0-.402.401v5.338L31.8 25.858a.39.39 0 0 0-.031-.04l-.002-.003-.024-.025-.008-.007a.313.313 0 0 0-.032-.026.255.255 0 0 1-.021-.014l-.012-.007-.021-.012-.013-.006-.023-.01-.013-.005-.024-.008-.014-.003-.023-.005-.017-.002-.021-.003-.021-.002h-1.46a.402.402 0 0 0-.402.401v8.985c0 .221.18.4.402.4h1.446a.401.401 0 0 0 .402-.4v-5.337l4.123 5.568c.028.04.063.072.101.099l.004.003a.236.236 0 0 0 .025.015l.012.006.019.01a.154.154 0 0 1 .019.008l.012.004.028.01.005.001a.442.442 0 0 0 .104.013h1.446a.4.4 0 0 0 .401-.4v-8.985a.402.402 0 0 0-.401-.401zm-13.442 7.537h-3.93v-7.136a.401.401 0 0 0-.401-.401h-1.447a.4.4 0 0 0-.401.401v8.984a.392.392 0 0 0 .123.29c.072.068.17.111.278.111h5.778a.4.4 0 0 0 .401-.401v-1.447a.401.401 0 0 0-.401-.401zm21.429-5.287c.222 0 .401-.18.401-.402v-1.446a.401.401 0 0 0-.401-.402h-5.778a.398.398 0 0 0-.279.113l-.005.004-.006.008a.397.397 0 0 0-.111.276v8.984c0 .108.043.206.112.278l.005.006a.401.401 0 0 0 .284.117h5.778a.4.4 0 0 0 .401-.401v-1.447a.401.401 0 0 0-.401-.401h-3.93v-1.519h3.93c.222 0 .401-.18.401-.402V29.85a.401.401 0 0 0-.401-.402h-3.93V27.93h3.93z" fill="white"></path>
                    </svg></button>

                <button aria-label="email" class="react-share__ShareButton" style="background-color: transparent; border: none; padding: 0px; font: inherit; color: inherit; cursor: pointer;"><svg viewBox="0 0 64 64" width="60" height="60">
                        <circle cx="32" cy="32" r="31" fill="#7f7f7f"></circle>
                        <path d="M17,22v20h30V22H17z M41.1,25L32,32.1L22.9,25H41.1z M20,39V26.6l12,9.3l12-9.3V39H20z" fill="white"></path>
                    </svg></button>
            </div>

        </div>
        <input id="loginflg" type="hidden" value="{{ $loginFlg }}" />
        <input id="post_id" type="hidden" value="{{ $post->id }}" />
    </section>

    <footer id="foot_container">
        @include('layouts.footer')
    </footer>
</body>

</html>