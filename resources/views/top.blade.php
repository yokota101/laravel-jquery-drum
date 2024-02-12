<!DOCTYPE html>
<html lang="ja">

<head>
    @include('layouts.head')
    <link rel="stylesheet" href="{{ asset('/css/top.css')  }}">
    <link rel="stylesheet" href="{{ asset('/css/user-display.css')  }}">
    <script type="text/javascript" src="{{ asset('js/top.js') }}"></script>
</head>

<body>
    @include('layouts.header')
    <div id="top">
        <div id="top_image">
            <div id="top_message">
                <h2>みんなでつくる！<br />
                    ドラム情報局<br />
                    「DrumBase」</h2>
                <a href="/philosophy">
                    <h2>※DrumBaseとは？</h2>
                </a>
            </div>

            <select id="select_box" style="display: block;" name="rank_list">
                <optgroup label="TOP">
                    <option value="0">総合ランキング</option>
                </optgroup>

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

                    <option value="{{$category->value}}">{{$category->category_name}}</option>

                    @if ($prevLabel !== $category->label)

                    @endif
                    @empty
                <optgroup label="TOP">
                    <option label="総合ランキング" value="0"></option>
                </optgroup>
                @endforelse
            </select>
            <div class="checkbox">
                <label>
                    <input type="checkbox" class="filled-in" checked="checked" />
                    <span>サブカテゴリも含む</span>
                </label>
            </div>
            <div class="search">
                <button class="btn waves-effect waves-light" id="exec_search" type="button">検索する
                    <i class="material-icons right">search</i>
                </button>
            </div>
        </div>

        <section id="main_section" class="first">
            <h1>人気動画ランキング</h1>
            <h2 id="rank_label">総合ランキング</h2>
            <h2>TOP5</h2>

            <div class="article_list">
                @forelse( $totalranks as $totalrank )
                <article>
                    <div class="rank_main">
                        <figure>
                            @php
                            $linkStr = 'watch?post_id='.$totalrank->id.'&movie_id='.$totalrank->movie_id;
                            $goodPoint = empty($totalrank->good_point) ? 0 : $totalrank->good_point;
                            $badPoint = empty($totalrank->bad_point) ? 0 : $totalrank->bad_point;
                            $totalPoint = empty($totalrank->total) ? 0 : $totalrank->total;
                            @endphp
                            <a href="{{ $linkStr }}">
                                <img src="{{ $totalrank->thumbnail_url }}" alt="{{ $totalrank->title }}" style="cursor: pointer;">
                            </a>
                            <h3>
                                <i class="small material-icons">thumb_up</i>{{ $goodPoint }}
                                <i class="small material-icons">thumb_down</i>{{ $badPoint }}
                                <br />
                                合計ポイント → {{ $totalPoint }}
                            </h3>
                        </figure>
                        <div class="rank_sub">
                            <span class="mute_txt">投稿日:{{ $totalrank->created_at}}</span>
                            <a href="{{ $linkStr }}">
                                @php
                                $titleStr = ($loop->index + 1).'位: '.$totalrank->title;
                                @endphp
                                <h1 style="cursor: pointer;">{{ $titleStr }}</h1>
                            </a>
                            <figure>
                                <a href="{{ $linkStr }}">
                                    <img src="{{ $totalrank->thumbnail_url }}" alt="{{ $totalrank->title }}" style="cursor: pointer;">
                                </a>
                                <h3>
                                    <i class="small material-icons">thumb_up</i>{{ $goodPoint }}
                                    <i class="small material-icons">thumb_down</i>{{ $badPoint }}
                                    <br />
                                    合計ポイント → {{ $totalPoint }}
                                </h3>
                            </figure>
                            <div id="userDisplay">
                                @php
                                $userpageStr = 'user?user_id='.$totalrank->user_id;
                                if(empty($totalrank->image)){
                                $imageUrlStr = '/images/default_drummer.png';
                                }else{
                                $imageUrlStr = '/prof_images/'.$totalrank->image;
                                }
                                @endphp
                                <a href="{{ $userpageStr }}">
                                    <div style="cursor: pointer;">
                                        <img class="profimg" src="{{ $imageUrlStr }}" alt="{{ $totalrank->name }}">
                                        <strong>
                                            {{ $totalrank->name }}さん
                                        </strong>
                                        がアップしました。
                                    </div>
                                </a>
                            </div>
                            <div class="tag_container">
                                @if( isset($totalrank->main_category_id) &&
                                array_key_exists($totalrank->main_category_id, $modifiedCategories) &&
                                isset($modifiedCategories[$totalrank->main_category_id]))
                                <div class="chip green accent-3"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $modifiedCategories[$totalrank->main_category_id] }}</div>
                                @else
                                <span>カテゴリ設定なし</span>
                                @endif
                                @if( isset($totalrank->sub_category_id_first) &&
                                array_key_exists($totalrank->sub_category_id_first, $modifiedCategories) &&
                                isset($modifiedCategories[$totalrank->sub_category_id_first]))
                                <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $modifiedCategories[$totalrank->sub_category_id_first] }}</div>
                                @endif
                                @if( isset($totalrank->sub_category_id_second) &&
                                array_key_exists($totalrank->sub_category_id_second, $modifiedCategories) &&
                                isset($modifiedCategories[$totalrank->sub_category_id_second]))
                                <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $modifiedCategories[$totalrank->sub_category_id_second] }}</div>
                                @endif
                                @if( isset($totalrank->sub_category_id_third) &&
                                array_key_exists($totalrank->sub_category_id_third, $modifiedCategories) &&
                                isset($modifiedCategories[$totalrank->sub_category_id_third]))
                                <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $modifiedCategories[$totalrank->sub_category_id_third] }}</div>
                                @endif
                            </div>
                            <h2>内容</h2>
                            <pre>{{ $totalrank->content}}</pre>
                        </div>

                    </div>
                </article>
                @empty
                <p>No articles</p>
                @endforelse
            </div>

            <div class="center_position">
                <a class="waves-effect waves-light btn-large indigo" href="/ranking">
                    <i class="small material-icons left">format_list_numbered</i>ランキングをもっと見る
                </a>
            </div>
        </section>

        <section id="main_section">
            <h1>新着動画一覧</h1>

            <div class="article_list">
                @forelse( $recentposts as $recentpost )
                <article>
                    <div class="rank_main">
                        <figure>
                            @php
                            $linkRecentStr = 'watch?post_id='.$recentpost->id.'&movie_id='.$recentpost->movie_id;
                            @endphp
                            <a href="{{ $linkRecentStr }}">
                                <img src="{{ $recentpost->thumbnail_url }}" alt="{{ $recentpost->title }}" style="cursor: pointer;">
                            </a>
                        </figure>
                        <div class="rank_sub">
                            <span class="mute_txt">投稿日:{{ $recentpost->created_at}}</span>
                            <a href="{{ $linkRecentStr }}">
                                @php
                                $titleStr = 'No.'.($loop->index + 1).': '.$recentpost->title;
                                @endphp
                                <h1 style="cursor: pointer;">{{ $titleStr }}</h1>
                            </a>
                            <figure>
                                <a href="{{ $linkRecentStr }}">
                                    <img src="{{ $recentpost->thumbnail_url }}" alt="{{ $recentpost->title }}" style="cursor: pointer;">
                                </a>
                            </figure>
                            <div id="userDisplay">
                                @php
                                $userpageStr = 'user?user_id='.$recentpost->user_id;

                                if(empty($recentpost->image)){
                                $imageUrlStr = '/images/default_drummer.png';
                                }else{
                                $imageUrlStr = '/prof_images/'.$recentpost->image;
                                }
                                @endphp
                                <a href="{{ $userpageStr }}">
                                    <div style="cursor: pointer;">
                                        <img class="profimg" src="{{ $imageUrlStr }}" alt="{{ $recentpost->name }}">
                                        <strong>
                                            {{ $recentpost->name }}さん
                                        </strong>
                                        がアップしました。
                                    </div>
                                </a>
                            </div>
                            <div class="tag_container">
                                @if( isset($recentpost->main_category_id) &&
                                array_key_exists($recentpost->main_category_id, $modifiedCategories) &&
                                isset($modifiedCategories[$recentpost->main_category_id]))
                                <div class="chip green accent-3"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $modifiedCategories[$recentpost->main_category_id] }}</div>
                                @endif
                                @if( isset($recentpost->sub_category_id_first) &&
                                array_key_exists($recentpost->sub_category_id_first, $modifiedCategories) &&
                                isset($modifiedCategories[$recentpost->sub_category_id_first]))
                                <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $modifiedCategories[$recentpost->sub_category_id_first] }}</div>
                                @endif
                                @if( isset($recentpost->sub_category_id_second) &&
                                array_key_exists($recentpost->sub_category_id_second, $modifiedCategories) &&
                                isset($modifiedCategories[$recentpost->sub_category_id_second]))
                                <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $modifiedCategories[$recentpost->sub_category_id_second] }}</div>
                                @endif
                                @if( isset($recentpost->sub_category_id_third) &&
                                array_key_exists($recentpost->sub_category_id_third, $modifiedCategories) &&
                                isset($modifiedCategories[$recentpost->sub_category_id_third]))
                                <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $modifiedCategories[$recentpost->sub_category_id_third] }}</div>
                                @endif
                            </div>
                            <h2>内容</h2>
                            <pre>{{ $recentpost->content}}</pre>
                        </div>

                    </div>
                </article>
                @empty
                <p>No articles</p>
                @endforelse
                <div class="center_position">
                    <a class="waves-effect waves-light btn-large" href="/archive">
                        <i class="material-icons left">format_list_bulleted</i>新着動画リストを見る
                    </a>
                </div>
            </div>
        </section>
    </div>

    <footer id="foot_container">
        @include('layouts.footer')
    </footer>
    <script>
        const categoriesArry = @json($modifiedCategories);
    </script>
</body>

</html>