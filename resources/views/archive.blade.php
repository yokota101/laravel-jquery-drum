<!DOCTYPE html>
<html lang="ja">

<head>
    @include('layouts.head')
    <link rel="stylesheet" href="{{ asset('/css/user-display.css')  }}">
    <link rel="stylesheet" href="{{ asset('/css/archive.css')  }}">
    <script type="text/javascript" src="{{ asset('js/archive.js') }}"></script>
</head>

<body>
    @include('layouts.header')
    <div id="archive">
        <div id="top_image">
            <div id="top_message">
                <h1>過去の投稿一覧ページ</h1>
                <p>新しい順に記事を見ることができます。</p>
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
                            @endphp
                            <a href="{{ $linkStr }}">
                                <img src="{{ $totalrank->thumbnail_url }}" alt="{{ $totalrank->title }}" style="cursor: pointer;">
                            </a>
                            <h3>
                                <i class="small material-icons">thumb_up</i>{{ $totalrank->good_point }}
                                <i class="small material-icons">thumb_down</i>{{ $totalrank->bad_point }}
                                <br />
                                合計ポイント → {{ $totalrank->total }}
                            </h3>
                        </figure>
                        <div class="rank_sub">
                            <span class="mute_txt">投稿日:{{ $totalrank->created_at}}</span>
                            <a href="{{ $linkStr }}">
                                @php
                                $titleStr = ($loop->index + 1).': '.$totalrank->title;
                                @endphp
                                <h1 style="cursor: pointer;">No.{{ $titleStr }}</h1>
                            </a>
                            <figure>
                                <a href="{{ $linkStr }}">
                                    <img src="{{ $totalrank->thumbnail_url }}" alt="{{ $totalrank->title }}" style="cursor: pointer;">
                                </a>
                                <h3>
                                    <i class="small material-icons">thumb_up</i>{{ $totalrank->good_point }}
                                    <i class="small material-icons">thumb_down</i>{{ $totalrank->bad_point }}
                                    <br />
                                    合計ポイント → {{ $totalrank->total }}
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
                                        <img class="profimg" src="{{ $imageUrlStr }}" alt="{{ $totalrank->name }}"> <strong>
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
        </section>
    </div>
    <div id="pagination_content">
        <ul class="pagination">
            <li id="first"> <i class="material-icons">first_page</i></li>
            <li id="prev"> <i class="material-icons">navigate_before</i></li>
            <li class="count"></li>
            <li id="next"> <i class="material-icons">navigate_next</i></li>
            <li id="last"> <i class="material-icons">last_page</i></li>
        </ul>
    </div>

    <footer id="foot_container">
        @include('layouts.footer')
    </footer>
    <script>
        const categoriesArry = @json($modifiedCategories);
        let archiveCount = @json($archiveCount);
    </script>
</body>

</html>