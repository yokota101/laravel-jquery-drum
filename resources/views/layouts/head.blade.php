<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- seo -->
@if(empty($seoObj))
    <title>DrumBase【ドラムの総合情報ランキングサイト】</title>
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="description" content="現在、YouTube上でたくさんの素晴らしい情報が集まっています。その中でも「ドラマーにとって有益な情報をシェアすること」を目的として作られたシステムが「DrumBase」です。">
    <meta name="keywords" content="ドラム,上達法,練習法,Youtube,ドラマー専用SNS">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="DrumBase【ドラムの総合情報ランキングサイト】" />
    <meta property="og:description" content="現在、YouTube上でたくさんの素晴らしい情報が集まっています。その中でも「ドラマーにとって有益な情報をシェアすること」を目的として作られたシステムが「DrumBase」です。" />
    <meta property="og:site_name" content="DrumBase" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="/images/DrumBaseTop.png" />
    <meta name="twitter:card" content="/images/DrumBaseTop.png" />
@else
    <title>{{ $seoObj->title }} | DrumBase</title>
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="description" content="{{ $seoObj->desc }}" />
    <meta name="keywords" content="{{ $seoObj->keywords }}">
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ $seoObj->title }}" />
    <meta property="og:description" content="{{ $seoObj->desc }}" />
    <meta property="og:site_name" content="DrumBase" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ $seoObj->image }}" />
    <meta name="twitter:card" content="{{ $seoObj->image }}" />
@endif
<!-- seo -->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-GXPLF2VBC8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-GXPLF2VBC8');
</script>
<link rel="stylesheet" href="{{ asset('/css/materialize.css')  }}" >
<link rel="stylesheet" href="{{ asset('/css/reset.css')  }}" >
<link rel="stylesheet" href="{{ asset('/css/header.css')  }}" >
<link rel="stylesheet" href="{{ asset('/css/footer.css')  }}" >
<link rel="stylesheet" href="{{ asset('/css/all.css')  }}" >
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">
<link rel="shortcut icon" href="{{ asset('/images/drfavicon.ico') }}">
<script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/materialize.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/header.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2827951780306863" crossorigin="anonymous"></script>