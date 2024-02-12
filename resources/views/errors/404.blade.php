<!DOCTYPE html>
<html lang="ja">
    <head>
    @include('layouts.head')
    <link rel="stylesheet" href="{{ asset('/css/notfound.css')  }}" >
    </head>
    <body>
        @include('layouts.header')
        <div id="notfound">
            <h1>404</h1>
            <h2>お探しのページが見つかりませんでした。</h2>
            <a href="/" class="button">
            <i class="material-icons small" style="line-height: 1.2;">home</i><p>トップページへ戻る</p>
            </a>
        </div>
        <footer id="foot_container">
            @include('layouts.footer')
        </footer>
    </body>

</html>
