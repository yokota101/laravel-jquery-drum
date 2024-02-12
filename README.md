## 制作物に関して

・デプロイはしておりますので、確認いただく場合は下記URLにアクセスお願いします。
https://drumbase.org/

・「* note」で全体を検索して頂くと工夫した点を見て頂けます。ご確認よろしくお願いいたします。


# 作ってみてふりかえり
・はじめはreact,laravelで作成したが、SPAで実装を行うと、googleのアクセス解析のタグ設置が上手くいかず
フロント側はサーバー側でレンダリングされてから表示する必要性に気づいた。


最終的に実装しようとしている機能がすべてreactを使わずとも実現できると思い、
オーバーエンジニアリングになっていると感じ、結果フロントはJQueryで実装してしまいました。


・ただし、集団で開発するとなると、Reactは宣言的UIという言葉がある通りコードが読みやすくなり、
またtypescriptを使用している方が型もしっかり決まりバグが起きにくくなるため、
Nextjs,typescriptにしても良かったと後から思いました。


・普通のjsでループを回してランキングを生成する場合、
コードが非常に不格好になる点はよくないと感じています。
今回は文字列でhtmlの要素をひたすら書きましたが、
別の方法としては、テンプレートとなるhtml要素をhidden状態置いておき、それをコピーして作っていく方式もあるようです。


こういったループにより要素を生成していくのは、
最近のフロントのフレームワークを使うときれいに書けて気持ち良いと思います。


・見た目の部分については全てのページがレスポンシブ対応しています。
スマホでも操作しやすいように意識しました。