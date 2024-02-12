<!DOCTYPE html>
<html lang="ja">

<head>
    @include('layouts.head')
    <link rel="stylesheet" href="{{ asset('/css/profile-confirm.css')  }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    @include('layouts.header')
    <div id="profile-confirm">
        <section id="container">
            <h1>プロフィール変更内容の確認</h1>
            <form id="root_div" action="/profile-edit/complete" method="post">
                @csrf
                <div>
                    <h3>名前</h3>
                    <p class="input_content" id="name" name="name">{{ $request->name }}</p>
                </div>
                <div>
                    <h3>X(Twitter)</h3>
                    <p class="input_content" id="twitter_username" name="twitter_username">{{ $request->twitter_username }}</p>
                </div>
                <div>
                    <h3>Instagram</h3>
                    <p class="input_content" name="instagram_id">{{ $request->instagram_id }}</p>
                </div>
                <div>
                    <h3>Facebook</h3>
                    <p class="input_content" name="facebook_url">{{ $request->facebook_url }}</p>
                </div>
                <div>
                    <h3>自己紹介</h3>
                    <p class="input_content" name="self_intro">{{ $request->self_intro }}</p>
                </div>

                <button class="btn waves-effect waves-light indigo" type="submit" name="submit" value="complete">更新する
                    <i class="material-icons right">send</i>
                </button>
                <button class="btn waves-effect waves-light red darken-1" type="button" onClick="history.back()">もどる
                </button>

                <input value="{{ $request->name }}" placeholder="名前をここに入力" id="name" name="name" type="hidden">
                <input value="{{ $request->twitter_username }}" placeholder="Xのユーザー名をここに入力" id="twitter_username" name="twitter_username" type="hidden" />
                <input value="{{ $request->instagram_id }}" placeholder="Instagramのユーザー名をここに入力" id="instagram_id" name="instagram_id" type="hidden" />
                <input value="{{ $request->facebook_url }}" placeholder="FACEBOOKのユーザー名をここに入力" id="facebook_url" name="facebook_url" type="hidden" />
                <textarea id="textarea" name="self_intro" style="display:none" rows="10" data-length="1000" placeholder="ドラム歴〇〇年です。よく演奏するジャンルはポップスです。好きなドラマーは〇〇さんです。">{{ $request->self_intro }}</textarea>
            </form>
        </section>
    </div>

    <footer id="foot_container">
        @include('layouts.footer')
    </footer>
</body>

</html>