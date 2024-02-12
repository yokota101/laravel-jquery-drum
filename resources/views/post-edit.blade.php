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
            <h1>記事編集ページ</h1>
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
                    <input placeholder="タイトルをここに入力" id="title" type="text" class="validate" name="title" value="{{ old('title', $post->title) }}">
                </div>
                <div class="input-field">
                    <input placeholder="動画URLをここに入力" id="movie_url" type="text" class="validate" name="movie_url" value="{{ old('movie_url', $post->url) }}">
                    <label for="title">動画URL </label>
                    <span class="helper-text" data-error="wrong" data-success="動画のYoutubeアドレスを入力して頂ければ下記に表示されます。">動画のYoutubeアドレスを入力して頂ければ下記に表示されます。</span>
                </div>
                <div>
                    <div class="movie_area">
                        @php
                        $urlStr = 'https://www.youtube.com/embed/'.$post->movie_id;
                        @endphp
                        <iframe class="added" frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" width="640" height="360" src="{{ $urlStr }}"></iframe>
                        <input class="added" type="hidden" name="thumbnail" value="{{$post->thumbnail_url}}">
                        <input class="added" type="hidden" name="videoid" value="{{$post->movie_id}}">

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
                                        <option value="{{$category->value}}" @if($category->value === (int)old('main_category', $post->main_category_id)) selected @endif>{{$category->category_name}}</option>

                                        @if ($prevLabel !== $category->label)

                                        @endif
                                        @empty
                                        @endforelse
                                </select>
                            </div>
                            <h4 class="input_title">サブカテゴリ（3つまで）：</h4>
                            @php
                            $subCatArray = array($post->sub_category_id_first, $post->sub_category_id_second, $post->sub_category_id_third)
                            @endphp
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
                                        <option value="{{$category->value}}" @if( in_array($category->value, old('sub_category', $subCatArray) ) ) selected @endif>{{$category->category_name}}</option>

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
                        <textarea id="textarea" class="materialize-textarea" data-length="1000" name="intro">{{ old('intro', $post->content)}}</textarea>
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
                        <input class="with-gap" name="open_flg_group" type="radio" value="1" @if( old('open_flg_group', $post->open_flg) == '1') checked @endif />
                        <span style="color: black;">公開する</span>
                    </label>
                    <label>
                        <input class="with-gap" name="open_flg_group" type="radio" value="0" @if( old('open_flg_group', $post->open_flg) == '0') checked @endif />
                        <span style="color: black;">下書き</span>
                    </label>
                </div>
                <button class="btn waves-effect waves-light" type="submit" name="action">内容確認
                    <i class="material-icons right">create</i>
                </button>
                <button data-target="deleteAccountModal" type="button" class="waves-effect waves-light btn modal-trigger red darken-1"><i class="material-icons right">delete</i>記事を削除する</button>
                <!-- Modal Structure -->
                <div id="deleteAccountModal" class="modal">
                    <div class="modal-content">
                        <h3>記事を削除してもよろしいですか？
                            <br />※一度削除すると元に戻すことはできません。
                        </h3><br />
                        <button class="waves-effect waves-light btn modal-close indigo" type="button">もどる</button>
                        <br /><br />
                        <button id="delete_post" class="waves-effect waves-light btn red" type="button">記事を削除する</button>
                    </div>
                    <div class="modal-footer">
                        <a class="modal-close waves-effect waves-green btn-flat">閉じる</a>
                    </div>
                </div>
                <input type="hidden" value="{{ $update }}" name="update" />
                <input type="hidden" value="{{ $post->id }}" name="post_id" />
            </form>
        </section>
    </div>
    <footer id="foot_container">
        @include('layouts.footer')
    </footer>
</body>

</html>