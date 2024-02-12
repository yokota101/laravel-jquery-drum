<!DOCTYPE html>
<html lang="ja">

<head>
    @include('layouts.head')
    <link rel="stylesheet" href="{{ asset('/css/profile-edit.css')  }}">
    <script type="text/javascript" src="{{ asset('js/profile-edit.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    @include('layouts.header')
    <div id="profile-edit">
        <section id="container">
            <h1>プロフィール設定</h1>
            @if ($errors->any())
            <div class="caution">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form id="root_form" action="/profile-edit/confirm" method="post">
                @csrf
                <div class="input-field">
                    <label for="name">名前 （DrumBaseでの表示名になります。）</label>
                    <input value="{{ old('name', $userinfo->name) }}" placeholder="名前をここに入力" id="name" name="name" type="text" class="">
                </div>
                <div class="input-field">
                    <input value="{{ old('twitter_username', $userinfo->twitter_username) }}" placeholder="Xのユーザー名をここに入力" id="twitter_username" name="twitter_username" type="text" class="" />
                    <label for="twitter_username">X(twitter)ユーザ名 （@ユーザー名の形式で入力してください。）</label>
                </div>
                <div class="input-field">
                    <input value="{{ old('instagram_id', $userinfo->instagram_id) }}" placeholder="Instagramのユーザー名をここに入力" id="instagram_id" name="instagram_id" type="text" class="" />
                    <label for="instagram_id">Instagramユーザ名 （アカウントのIDを入力してください。）</label>
                </div>
                <div class="input-field">
                    <input value="{{ old('facebook_url', $userinfo->facebook_url) }}" placeholder="FACEBOOKのユーザー名をここに入力" id="facebook_url" name="facebook_url" type="text" class="" />
                    <label for="facebook_url">FACEBOOK （アカウントページのアドレスを入力してください。）</label>
                </div>
                <div class="content_area">
                    <h3>自己紹介</h3>
                    <textarea id="textarea" name="self_intro" style="border: 1px solid; border-radius: 5px;" rows="10" data-length="1000" placeholder="ドラム歴〇〇年です。よく演奏するジャンルはポップスです。好きなドラマーは〇〇さんです。">{{ old('self_intro', $userinfo->self_intro) }}</textarea>
                    <div class="counter">
                        文字数：<span id="show-count">0</span>
                    </div>
                    <aside>※1000文字まで</aside>
                </div>
                <div class="button_container">
                    <button class="btn waves-effect waves-light indigo" type="submit" name="action">内容確認
                        <i class="material-icons right">create</i>
                    </button>
                    <button data-target="deleteAccountModal" type="button" class="waves-effect waves-light btn modal-trigger red darken-1"><i class="material-icons right">delete</i>アカウントを削除する</button>
                    <!-- Modal Structure -->
                    <div id="deleteAccountModal" class="modal">
                        <div class="modal-content">
                            <h3>本当にアカウントを削除してもよろしいですか？
                                <br />※一度削除すると元に戻すことはできません。
                            </h3><br />
                            <button class="waves-effect waves-light btn modal-close indigo" type="button">もどる</button>
                            <br /><br />
                            <button id="delete_account" class="waves-effect waves-light btn red" type="button">削除を実行する</button>
                        </div>
                        <div class="modal-footer">
                            <a class="modal-close waves-effect waves-green btn-flat">閉じる</a>
                        </div>
                    </div>
                </div>
            </form>

            <h2>プロフィール画像の設定</h2>
            <div>
                @php
                if(empty($userinfo->image)){
                $imageUrlStr = '/images/default_drummer.png';
                }else{
                $imageUrlStr = '/prof_images/'.$userinfo->image;
                }
                @endphp
                <div style="height: 250px; width: 250px;">
                    <img src="{{$imageUrlStr}}" style="width: 100%;">
                </div>
            </div>
            <div>
                <input type="file" id="file" />
                <button class="btn waves-effect waves-light" type="button" id="uploadimage">アップロードする
                    <i class="material-icons right">send</i>
                </button>
            </div>
            <p>※ファイルサイズは500KB以下でお願いします。</p>
            <input type="hidden" value="{{ $userinfo->id }}" id="user_id" />
        </section>
    </div>
    <footer id="foot_container">
        @include('layouts.footer')
    </footer>
</body>

</html>