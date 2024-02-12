<!DOCTYPE html>
<html lang="ja">

<head>
    @include('layouts.head')
    <link rel="stylesheet" href="{{ asset('/css/mypage.css')  }}">
    <script type="text/javascript" src="{{ asset('js/mypage.js') }}"></script>
</head>

<body>
    @include('layouts.header')
    <div id="mypage">
        <section id="mypage_container">
            <h1>マイページ</h1>
            <div class="mypage_box">
                <div>
                    @php
                    if(empty($userinfo->image)){
                    $imageUrlStr = '/images/default_drummer.png';
                    }else{
                    $imageUrlStr = '/prof_images/'.$userinfo->image;
                    }
                    @endphp
                    <img src="{{$imageUrlStr}}" style="width: 100%;" alt="プロフィール画像">
                    <a href="/profile-edit">
                        <button data-target="modal1" class="waves-effect waves-light btn">プロフィールを修正する</button>
                    </a>
                </div>
                <div class="self-intro">
                    <h2>{{ $userinfo->name }}さん</h2>
                    <div>
                        <h3>自己紹介：</h3>
                        <p>{{ $userinfo->self_intro }}</p>
                        <div>
                            <h3>X(twitter):</h3>
                            <p>{{ $userinfo->twitter_username }}</p>
                        </div>
                        <div>
                            <h3>facebook:</h3>
                            <p>{{ $userinfo->facebook_url }}</p>
                        </div>
                        <div>
                            <h3>Instagram:</h3>
                            <p>{{ $userinfo->instagram_id }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width" style="height: 80px">
                        <li class="tab" style="height: 80px;"><a class="active" href="#posted" style="line-height: 0;"><i class="material-icons small" style="line-height: 2;">create</i><br />投稿した記事</a></li>
                        <li class="tab" style="height: 80px"><a href="#like" style="line-height: 0;"><i class="material-icons small" style="line-height: 2;">thumb_up</i><br />いいねした記事</a></li>
                        <li class="tab" style="height: 80px"><a href="#mylist" style="line-height: 0;"><i class="material-icons small" style="line-height: 2;">stars</i><br />マイリスト</a></li>
                    </ul>
                </div>
                <div class="card-content grey lighten-4">
                    <div id="posted" style="min-height: 500px;" class="parentclass">
                        <div class="article_list">
                            @forelse( $postedrticles as $postedrticle )
                            <article>
                                <div class="rank_main">
                                    <figure>
                                        @php
                                        $linkStr = 'post-edit?post_id='.$postedrticle->id.'&movie_id='.$postedrticle->movie_id;
                                        @endphp
                                        <a href="{{ $linkStr }}">
                                            <img src="{{ $postedrticle->thumbnail_url }}" alt="{{ $postedrticle->title }}" style="cursor: pointer;">
                                        </a>
                                        <h3>
                                            <i class="small material-icons">thumb_up</i>{{ $postedrticle->good_point }}
                                            <i class="small material-icons">thumb_down</i>{{ $postedrticle->bad_point }}
                                            <br />
                                            合計ポイント → {{ $postedrticle->total }}
                                        </h3>
                                    </figure>
                                    <div class="rank_sub">
                                        @if($postedrticle->open_flg == '1')
                                        <div class="chip pink lighten-3">公開中</div>
                                        @else
                                        <div class="chip">下書き</div>
                                        @endif
                                        <span class="mute_txt">投稿日:{{ $postedrticle->created_at}}</span>
                                        <a href="{{ $linkStr }}">
                                            <h1 style="cursor: pointer;">{{ $postedrticle->title }}</h1>
                                        </a>
                                        <figure>
                                            <a href="{{ $linkStr }}">
                                                <img src="{{ $postedrticle->thumbnail_url }}" alt="{{ $postedrticle->title }}" style="cursor: pointer;">
                                            </a>
                                            <h3>
                                                <i class="small material-icons">thumb_up</i>{{ $postedrticle->good_point }}
                                                <i class="small material-icons">thumb_down</i>{{ $postedrticle->bad_point }}
                                                <br />
                                                合計ポイント → {{ $postedrticle->total }}
                                            </h3>
                                        </figure>
                                        <div class="tag_container">
                                            @if( isset($postedrticle->main_category_id) &&
                                            array_key_exists($postedrticle->main_category_id, $categories) &&
                                            isset($categories[$postedrticle->main_category_id]))
                                            <div class="chip green accent-3"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$postedrticle->main_category_id] }}</div>
                                            @else
                                            <span>カテゴリ設定なし</span>
                                            @endif
                                            @if( isset($postedrticle->sub_category_id_first) &&
                                            array_key_exists($postedrticle->sub_category_id_first, $categories) &&
                                            isset($categories[$postedrticle->sub_category_id_first]))
                                            <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$postedrticle->sub_category_id_first] }}</div>
                                            @endif
                                            @if( isset($postedrticle->sub_category_id_second) &&
                                            array_key_exists($postedrticle->sub_category_id_second, $categories) &&
                                            isset($categories[$postedrticle->sub_category_id_second]))
                                            <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$postedrticle->sub_category_id_second] }}</div>
                                            @endif
                                            @if( isset($postedrticle->sub_category_id_third) &&
                                            array_key_exists($postedrticle->sub_category_id_third, $categories) &&
                                            isset($categories[$postedrticle->sub_category_id_third]))
                                            <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$postedrticle->sub_category_id_third] }}</div>
                                            @endif
                                        </div>
                                        <h2>内容</h2>
                                        <pre>{{ $postedrticle->content}}</pre>
                                    </div>

                                </div>
                            </article>
                            @empty
                            <p>No articles</p>
                            @endforelse
                        </div>
                        @if($postedCount > 0)
                        <div id="pagination_content_1">
                            <ul class="pagination">
                                <li id="first_1"> <i class="material-icons">first_page</i></li>
                                <li id="prev_1"> <i class="material-icons">navigate_before</i></li>
                                <li class="count"></li>
                                <li id="next_1"> <i class="material-icons">navigate_next</i></li>
                                <li id="last_1"> <i class="material-icons">last_page</i></li>
                            </ul>
                        </div>
                        @endif
                    </div>

                    <div id="like" style="min-height: 500px;" class="parentclass">
                        <div class="article_list">
                            @forelse( $goodposts as $goodpost )
                            <article>
                                <div class="rank_main">
                                    <figure>
                                        @php
                                        $linkStr = 'watch?post_id='.$goodpost->id.'&movie_id='.$goodpost->movie_id;
                                        @endphp
                                        <a href="{{ $linkStr }}">
                                            <img src="{{ $goodpost->thumbnail_url }}" alt="{{ $goodpost->title }}" style="cursor: pointer;">
                                        </a>
                                        <h3>
                                            <i class="small material-icons">thumb_up</i>{{ $goodpost->good_point }}
                                            <i class="small material-icons">thumb_down</i>{{ $goodpost->bad_point }}
                                            <br />
                                            合計ポイント → {{ $goodpost->total }}
                                        </h3>
                                    </figure>
                                    <div class="rank_sub">
                                        <span class="mute_txt">投稿日:{{ $goodpost->created_at}}</span>
                                        <a href="{{ $linkStr }}">
                                            <h1 style="cursor: pointer;">{{ $goodpost->title }}</h1>
                                        </a>
                                        <figure>
                                            <a href="{{ $linkStr }}">
                                                <img src="{{ $goodpost->thumbnail_url }}" alt="{{ $goodpost->title }}" style="cursor: pointer;">
                                            </a>
                                            <h3>
                                                <i class="small material-icons">thumb_up</i>{{ $goodpost->good_point }}
                                                <i class="small material-icons">thumb_down</i>{{ $goodpost->bad_point }}
                                                <br />
                                                合計ポイント → {{ $goodpost->total }}
                                            </h3>
                                        </figure>
                                        <div class="tag_container">
                                            @if( isset($goodpost->main_category_id) &&
                                            array_key_exists($goodpost->main_category_id, $categories) &&
                                            isset($categories[$goodpost->main_category_id]))
                                            <div class="chip green accent-3"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$goodpost->main_category_id] }}</div>
                                            @else
                                            <span>カテゴリ設定なし</span>
                                            @endif
                                            @if( isset($goodpost->sub_category_id_first) &&
                                            array_key_exists($goodpost->sub_category_id_first, $categories) &&
                                            isset($categories[$goodpost->sub_category_id_first]))
                                            <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$goodpost->sub_category_id_first] }}</div>
                                            @endif
                                            @if( isset($goodpost->sub_category_id_second) &&
                                            array_key_exists($goodpost->sub_category_id_second, $categories) &&
                                            isset($categories[$goodpost->sub_category_id_second]))
                                            <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$goodpost->sub_category_id_second] }}</div>
                                            @endif
                                            @if( isset($goodpost->sub_category_id_third) &&
                                            array_key_exists($goodpost->sub_category_id_third, $categories) &&
                                            isset($categories[$goodpost->sub_category_id_third]))
                                            <div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>{{ $categories[$goodpost->sub_category_id_third] }}</div>
                                            @endif
                                        </div>
                                        <h2>内容</h2>
                                        <pre>{{ $goodpost->content}}</pre>
                                    </div>

                                </div>
                            </article>
                            @empty
                            <p>No articles</p>
                            @endforelse
                        </div>
                        @if($goodPostCount > 0)
                        <div id="pagination_content_2">
                            <ul class="pagination">
                                <li id="first_2"> <i class="material-icons">first_page</i></li>
                                <li id="prev_2"> <i class="material-icons">navigate_before</i></li>
                                <li class="count"></li>
                                <li id="next_2"> <i class="material-icons">navigate_next</i></li>
                                <li id="last_2"> <i class="material-icons">last_page</i></li>
                            </ul>
                        </div>
                        @endif
                    </div>

                    <div id="mylist" style="height: 500px;">
                        【開発中】
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        const categoriesArry = @json($categories);
        let postedCount = @json($postedCount);
        let goodPostCount = @json($goodPostCount);
    </script>
    <footer id="foot_container">
        @include('layouts.footer')
    </footer>
</body>

</html>