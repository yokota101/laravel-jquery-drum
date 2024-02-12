<!DOCTYPE html>
<html lang="ja">

<head>
  @include('layouts.head')
  <link rel="stylesheet" href="{{ asset('/css/philosophy.css')  }}">
</head>

<body>
  @include('layouts.header')
  <div id="philosophy">
    <div id="top_image">
      <div id="top_message">
        <h1>DrumBaseとは？</h1>
        <p>現在、YouTube上でたくさんの素晴らしい情報が集まっています。<br />
          その中でも「ドラマーにとって有益な情報をシェアすること」を<br />
          目的として作られたシステムが<span class="line_deco">「DrumBase」</span>です。</p>
      </div>
    </div>
    <section id="main_container">
      <h2 class="titles">DrumBaseが作られたきっかけ</h2>
      <p>
        ドラムは他の楽器よりも練習環境に困りやすい楽器です。<br />
        生のドラムだと音は大きいですし、電子ドラムを用意したとしても、<br />
        打音や振動が周りの人の迷惑となってしまいます。</p>
      <p>
        このような状況があるので、<br />
        実際にドラムを叩いて<span class="line_deco">練習する以外の時間をいかに充実させるか</span><br />
        ということを考えた時に、<br />
        <span class="line_deco">Youtube上でドラマーにとって良い情報を学べるサイトがあれば<br />
          多くの方の役に立つのではないか</span>と思ったことがきっかけになります。
      </p>
      <h2 class="titles">DrumBaseの使い方</h2>
      <p>
        画面上部の右側にあるボタンでログインを行っていただければ、<br />
        記事を投稿することが可能です。<br />
        投稿された記事はDrumBase上で公開されます。<br />
        公開された動画は新着動画一覧の一番上に表示され、<br />
        <span class="line_deco">良い評価と悪い評価をつける</span>ことができます。
      </p>
      <h2 class="titles">評価システム</h2>
      <p>
        <span class="line_deco">「良い」と「悪い」の評価を合算し、合計値が高い動画が上位にランキングされます。</span><br />
        開発当初は「良い」評価だけにしようかと思っていたのですが、<br />
        両方の評価があった方が質の良いランキングにできるのではないかと思ったので<br />
        現状の形となりました。<br />
        今後、良いだけの方が質が高いランキングになりそうだと管理人が判断した場合には<br />
        そういう方向性でメンテナンスをしようと考えています。
      </p>
      <h2 class="titles">管理人からのお願い</h2>
      <p>
        当サイトはドラマーにとって役立てることを願い作成させて頂きました。<br />
        今後、ひとつのWebサービスとしてDrumBaseを育てていくだめには、<br />
        ご利用者様からの記事投稿や評価が絶対に必要となってきますので、<br />
        <span class="line_deco">ぜひとも皆様のご協力をお願い致します。</span><br />
        DrumBase管理人は日々サービス向上に向け頑張っていこうと意気込んでおります…！
      </p>
    </section>
  </div>
  <footer id="foot_container">
    @include('layouts.footer')
  </footer>
</body>