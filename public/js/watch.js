$(document).ready(function () {
  let buttonClickCnt = 0;


  $('#good-button').click(function () {
    buttonClickCheck();
    let goodBtnState = $('#good-button').hasClass('btn-flat');// 色がついていない標準状態ならtrue
    let badBtnState = $('#bad-button').hasClass('btn-flat'); // 色がついていない標準状態ならtrue
    // ログインしてるかどうか
    if ($('#loginflg').val() == 1) {

      // どちらも押されていない
      if (goodBtnState && badBtnState) {

        let goodPoint = Number($('#good-point').text());
        $('#good-point').text(goodPoint + 1)
        // ボタンに色がつく
        $(this).removeClass('btn-flat');
        $(this).addClass('indigo btn');
        $(this).css({ border: '1px solid #3f51b5', color: '' });

        // レコードがあれば、 good=1, bad=0 で更新、なければ作成
        voteCreateOrUpdate(1, 0);

      } else {
        // どちらかに色がついている
        if (goodBtnState) {
          // badがついている
          let goodPoint = Number($('#good-point').text());
          $('#good-point').text(goodPoint + 1)
          let badPoint = Number($('#bad-point').text());
          $('#good-point').text(goodPoint + 1)
          $('#bad-point').text(badPoint - 1)
          // ボタンに色がつく
          $(this).removeClass('btn-flat');
          $(this).addClass('indigo btn');
          $(this).css({ border: '1px solid #3f51b5', color: '' });

          // badの色を戻す
          $('#bad-button').removeClass('pink accent-2 btn');
          $('#bad-button').addClass('btn-flat');
          $('#bad-button').css({ border: '1px solid #ff4081', color: '#ff4081' });

          // レコードがあれば、 good=1, bad=0 で更新、なければ作成
          voteCreateOrUpdate(1, 0);

        } else {
          // goodがついている
          let goodPoint = Number($('#good-point').text());
          $('#good-point').text(goodPoint - 1);
          // ボタンに色をはずす
          $(this).removeClass('indigo btn');
          $(this).addClass('btn-flat');
          $(this).css({ border: '1px solid #3f51b5', color: '#3f51b5' });

          // レコードがあれば、 good=0, bad=0 で更新、なければ作成
          voteCreateOrUpdate(0, 0);
        }
      }

    } else {
      // ログインしていないとき
      alert('ログインしていないため、評価ボタンは押せません。')
    }
  });

  $('#bad-button').click(function () {
    buttonClickCheck();
    let goodBtnState = $('#good-button').hasClass('btn-flat');// 色がついていない標準状態ならtrue
    let badBtnState = $('#bad-button').hasClass('btn-flat'); // 色がついていない標準状態ならtrue

    // ログインしてるかどうか
    if ($('#loginflg').val() == 1) {

      // どちらも押されていない
      if (goodBtnState && badBtnState) {

        let badPoint = Number($('#bad-point').text());
        $('#bad-point').text(badPoint + 1)
        // badボタンに色がつく
        $(this).removeClass('btn-flat');
        $(this).addClass('pink accent-2 btn');
        $(this).css({ border: '1px solid #ff4081', color: '' });

        // レコードがあれば、 good=0, bad=1 で更新、なければ作成
        voteCreateOrUpdate(0, 1);
      } else {
        // どちらかに色がついている
        if (badBtnState) {
          // goodがついている
          let badPoint = Number($('#bad-point').text());
          $('#bad-point').text(badPoint + 1)
          let goodPoint = Number($('#good-point').text());
          $('#good-point').text(goodPoint - 1)
          // badボタンに色がつく
          $(this).removeClass('btn-flat');
          $(this).addClass('pink accent-2 btn');
          $(this).css({ border: '1px solid #ff4081', color: '' });

          // goodの色を戻す
          $('#good-button').removeClass('indigo btn');
          $('#good-button').addClass('btn-flat');
          $('#good-button').css({ border: '1px solid #3f51b5', color: '#3f51b5' });

          // レコードがあれば、 good=0, bad=1 で更新、なければ作成
          voteCreateOrUpdate(0, 1);
        } else {
          // badがついている
          let badPoint = Number($('#bad-point').text());
          $('#bad-point').text(badPoint - 1);
          // badボタンに色をはずす
          $(this).removeClass('pink accent-2 btn');
          $(this).addClass('btn-flat');
          $(this).css({ border: '1px solid #ff4081', color: '#ff4081' });

          // レコードがあれば、 good=0, bad=0 で更新、なければ作成
          voteCreateOrUpdate(0, 0);
        }
      }

    } else {
      // ログインしていない
      alert('ログインしていないため、評価ボタンは押せません。')

    }

  });

  //* note RPAのようなツールでボタン連打の攻撃を想定してクリック数の上限を実装
  function buttonClickCheck() {
    buttonClickCnt++;
    if (buttonClickCnt >= 30) alert('評価ボタンの連打はお控えください。よろしくお願いします。');
  }

  // 評価を保存/更新する
  function voteCreateOrUpdate(goodPoint, badPoint) {
    let postId = $('#post_id').val();
    $.ajax({
      url: '/vote-update',
      type: 'POST',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      dataType: 'json',
      data: { good_point: goodPoint, bad_point: badPoint, post_id: postId },
      timeout: 3000,
    }).done(function (data) {
      // no op
    }).fail(function (error) {
      alert('評価の登録に失敗しました。');
    })
  }
});