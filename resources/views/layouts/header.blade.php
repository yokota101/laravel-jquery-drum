<header>
    <div id="nav_area">
        <a class="logo" href="/">
            <span style="color: rgb(41, 182, 246);">Drum</span>
            <span style="color: rgb(139, 195, 74);">Base</span>
        </a>
        <div>
            @php
                $lineURL = env('APP_URL').'/oauth/line/redirect';
                $googleURL = env('APP_URL').'/oauth/google/redirect';
                $facebookURL = env('APP_URL').'/oauth/facebook/redirect';
            @endphp

            @guest
                <button data-target="modal1" class="waves-effect waves-light btn modal-trigger light-blue">ログイン</button>
                <!-- Modal Structure -->
                <div id="modal1" class="modal">
                    <div class="modal-content">
                        <h3>SNSのアカウントを使ってログインをします。希望のアカウントを選択してください。</h3><br/>
                        <a href="{{ $googleURL }}">
                            <button class="waves-effect waves-light btn red">Google でログイン</button>
                        </a><br/><br/>
                        <a href="{{ $lineURL }}">
                            <button class="waves-effect waves-light btn green darken-1">LINE でログイン</button>
                        </a>
                    </div>
                    <div class="modal-footer">
                        <a class="modal-close waves-effect waves-green btn-flat">閉じる</a>
                    </div>
                </div>
            @endguest
            
            @auth
                <div class="account_info">
                    <a href="/mypage">
                        <div class="icon_box">
                            @php
                                $userinfo = Auth::user();
                                if(empty($userinfo->image)){
                                    $imageUrlStr = '/images/default_drummer.png';
                                }else{
                                    $imageUrlStr = '/prof_images/'.$userinfo->image;
                                }
                            @endphp
                            <img class="avatar" src="{{ $imageUrlStr }}" />
                        </div>
                    </a>
                    <a class='dropdown-trigger btn' href='#' data-target='dropdown1' style='width:120px'>メニュー</a>
                </div>

                @php
                    $name = Auth::user()->name;
                @endphp
                <!-- Dropdown Structure -->
                <ul id='dropdown1' class='dropdown-content '>
                    <li><a style="color: black">{{ $name }}さん </a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li><a href="/mypage">マイページ</a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li><a href="/post">投稿する</a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li><a>
                        <form action="{{ route('logout') }}" method="get">
                        @csrf
                            <button type="submit" class="">ログアウト</button>
                        </form>
                    </a></li>
                </ul>
            @endauth          
        </div>
    </nav>
</header>