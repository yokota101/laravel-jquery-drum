<!DOCTYPE html>
<html lang="ja">

<head>
    @include('layouts.head')
    <link rel="stylesheet" href="{{ asset('/css/post.css')  }}">
    <script type="text/javascript" src="{{ asset('js/post.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    @include('layouts.header')
    <div id="post">
        <section id="container">
            <h1>記事投稿ページ</h1>
            @if ($errors->any())
            <div class="caution">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form id="root_form" action="/post-confirm" method="post">
                @csrf
                <div class="input-field">
                    <label for="title">タイトル</label>
                    <input placeholder="タイトルをここに入力" id="title" type="text" class="validate" name="title" value="{{ old('title') }}">
                </div>
                <div class="input-field">
                    <input placeholder="動画URLをここに入力" id="movie_url" type="text" class="validate" name="movie_url" value="{{ old('movie_url') }}">
                    <label for="title">動画URL </label>
                    <span class="helper-text" data-error="wrong" data-success="動画のYoutubeアドレスを入力して頂ければ下記に表示されます。">動画のYoutubeアドレスを入力して頂ければ下記に表示されます。</span>
                </div>
                <div>
                    <div class="movie_area">
                        <div class="skeleton"></div>

                        <h4 class="input_title">メインカテゴリ</h4>
                        <div>
                            <div class="input-field">
                                <select name="main_category">
                                    <option value="" disabled selected>選択してください</option>
                                    @forelse( $categories as $category )
                                    @php
                                    $cnt = $loop->index - 1;
                                    if($cnt >= 0 ){
                                    $prevLabel = $categories[$cnt]->label;
                                    }else{
                                    $prevLabel = '';
                                    }
                                    @endphp

                                    @if ($prevLabel !== $category->label)
                                    @if ($prevLabel !== '')
                                    </optgroup>
                                    @endif
                                    <optgroup label="{{$category->label}}">
                                        @endif
                                        <option value="{{$category->value}}" @if($category->value === (int)old('main_category')) selected @endif>{{$category->category_name}}</option>

                                        @if ($prevLabel !== $category->label)

                                        @endif
                                        @empty
                                        @endforelse
                                </select>
                            </div>
                            <h4 class="input_title">サブカテゴリ（3つまで）：</h4>
                            <div class="input-field">
                                <select name="sub_category[]" multiple>
                                    <option value="" disabled>選択してください</option>
                                    @forelse( $categories as $category )
                                    @php
                                    $cnt = $loop->index - 1;
                                    if($cnt >= 0 ){
                                    $prevLabel = $categories[$cnt]->label;
                                    }else{
                                    $prevLabel = '';
                                    }
                                    @endphp

                                    @if ($prevLabel !== $category->label)
                                    @if ($prevLabel !== '')
                                    </optgroup>
                                    @endif
                                    <optgroup label="{{$category->label}}">
                                        @endif
                                        <option value="{{$category->value}}" @if( in_array($category->value, old('sub_category', []) ) ) selected @endif>{{$category->category_name}}</option>

                                        @if ($prevLabel !== $category->label)

                                        @endif
                                        @empty
                                        @endforelse
                                </select>
                            </div>
                        </div>
                        <aside>※メインカテゴリと重複しないものを選んでください。</aside>
                    </div>
                </div>
                <div class="content_area">
                    <h3 class="input_title">紹介内容</h3>
                    <div class="input-field">
                        <textarea id="textarea" class="materialize-textarea" data-length="1000" name="intro">{{ old('intro')}}</textarea>
                    </div>
                    <div class="counter">
                        文字数：<span id="show-count">0</span>
                    </div>
                    <aside>※主にメインカテゴリに関係する内容を記載してください。(質の高いランキングを構築するため)</aside>
                    <aside>※文字数に制限はありませんが、出来るだけ短く要点をまとめているのが理想です。ご協力お願いします。</aside>
                </div>
                <div>
                    <h3 class="input_title">公開しますか？</h3>
                    <label>
                        <input class="with-gap" name="open_flg_group" type="radio" value="1" />
                        <span>公開する</span>
                    </label>
                    <label>
                        <input class="with-gap" name="open_flg_group" type="radio" value="0" />
                        <span>下書き</span>
                    </label>
                </div>
                <button class="btn waves-effect waves-light" type="submit" name="action">内容確認
                    <i class="material-icons right">send</i>
                </button>
                <input type="hidden" value="{{ $update }}" name="update" />
            </form>
        </section>
    </div>
    <footer id="foot_container">
        @include('layouts.footer')
    </footer>
</body>

</html>