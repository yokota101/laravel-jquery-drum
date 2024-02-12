$(document).ready(function () {
  pagination(archiveCount, false);

  $('#exec_search').click(function () {
    getRank(true, null);
  });
});

const getRank = (firstFlg, startIndex) => {
  let val = $('[name=rank_list]').val();
  let txt = $('[name=rank_list] option:selected').text();
  $('#rank_label').text(txt);

  let subcatFlg;
  if ($('.checkbox input').prop("checked") == true) {
    subcatFlg = true;
  } else {
    subcatFlg = false;
  }
  $.ajax({
    type: "GET",
    url: "/archive-selected-category",
    data: { category_id: val, sub_cat_flg: subcatFlg, start: startIndex }
  }).done(function (datas) {

    // 要素の削除
    $('.first .article_list article').remove();
    if (datas.cnt > 0) {
      if ($('.first .article_list').find('h2').length > 0) {
        $('.first .article_list h2').remove();
      }
      if (firstFlg) {
        // カテゴリ取得時のページネーション
        pagination(datas.cnt, false);
      }

      // ここでランキングデータを再構築する
      $.each(datas.totalranks, function (index, value) {

        let rankNum = startIndex === null ? index : startIndex + index
        let imageUrlStr = '/images/default_drummer.png';
        if (value.image) {
          imageUrlStr = '/prof_images/' + value.image;
        }

        let ele = '<article>'
          + '<div class="rank_main">'
          + '<figure>'
          + '<a href="watch?post_id=' + value.id + '&movie_id=' + escapeHTML(value.movie_id) + '">'
          + '<img src="' + escapeHTML(value.thumbnail_url) + '" alt="' + escapeHTML(value.title) + '" style="cursor: pointer;">'
          + '</a>'
          + '<h3> <i class="small material-icons">thumb_up</i>' + (value.good_point ?? 0) + '<i class="small material-icons">thumb_down</i>' + (value.bad_point ?? 0) + '<br> 合計ポイント → ' + (value.total ?? 0) + '</h3>'
          + '</figure>'
          + '<div class="rank_sub"> <span class="mute_txt">投稿日:' + value.created_at + '</span>'
          + '<a href="watch?post_id=' + value.id + '&movie_id=' + value.movie_id + '">'
          + '<h1 style="cursor: pointer;">No.' + (rankNum + 1) + ': ' + escapeHTML(value.title) + '</h1>'
          + '</a>'
          + '<figure>'
          + '<a href="watch?post_id=' + value.id + '&movie_id=' + escapeHTML(value.movie_id) + '">'
          + '<img src="' + escapeHTML(value.thumbnail_url) + '" alt="' + escapeHTML(value.title) + '" style="cursor: pointer;">'
          + '</a>'
          + '<h3> <i class="small material-icons">thumb_up</i>' + (value.good_point ?? 0) + '<i class="small material-icons">thumb_down</i>' + (value.bad_point ?? 0) + '<br> 合計ポイント → ' + (value.total ?? 0) + '</h3>'
          + '</figure>'
          + '<div id="userDisplay">'
          + '<a href="user?user_id=' + value.user_id + '">'
          + '<div style="cursor: pointer;">'
          + '<img class="profimg" src="' + escapeHTML(imageUrlStr) + '" alt="' + escapeHTML(value.name) + '">'
          + '<strong>' + escapeHTML(value.name) + 'さん</strong>がアップしました。 </div>'
          + '</a>'
          + '</div>'
          + '<div class="tag_container">'
          + '<div class="chip green accent-3"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>' + escapeHTML(categoriesArry[value.main_category_id]) + '</div>';
        if (value.sub_category_id_first) ele += '<div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>' + escapeHTML(categoriesArry[value.sub_category_id_first]) + '</div>';
        if (value.sub_category_id_second) ele += '<div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>' + escapeHTML(categoriesArry[value.sub_category_id_second]) + '</div>';
        if (value.sub_category_id_third) ele += '<div class="chip green accent-1"><i class="material-icons left" style="line-height: 1.3;margin-right:6px;">local_offer</i>' + escapeHTML(categoriesArry[value.sub_category_id_third]) + '</div>';
        ele = ele + '</div>'
          + '<h2>内容</h2>'
          + '<pre>' + escapeHTML(value.content) + '</pre>'
          + '</div>'
          + '</div>'
          + '</article>';

        $('.first .article_list').append(ele);

      })
    } else {
      // ランキングがない時
      if ($('.first .article_list').find('h2').length > 0) {
        // なにもしない
      } else {
        $('.first .article_list').append('<h2>ランキングデータはありません。</h2>');
      }

    }

  }).fail(function (e) {
    alert("データ取得時にエラーが発生しました。")
  });
}

//* note １ページに表示する分のみ取得を行う実装としている(最低限のレコード取得によりロジックの負荷軽減のため)
const pagination = (menu, getFlg) => {
  // 初期値設定
  let page = 1; // 現在のページ（何ページ目か）
  const step = 20; // ステップ数（1ページに表示する項目数）

  // 現在のページ/全ページ を表示
  const count = (page, step) => {

    const p = $('.count').text();
    const total = (menu % step == 0) ? (menu / step) : (Math.floor(menu / step) + 1);
    $('.count').text(page + "/" + total + "ページ");
  }

  // ページを表示
  const show = (page, step, getFlg) => {

    // ランキングの再構成
    if (getFlg) {
      // ランキングを空にする
      $('.first .article_list article').remove();
      const startIndex = (page - 1) * step;
      getRank(false, startIndex);
    }
    count(page, step);
  }

  // 最初に1ページ目を表示
  show(page, step, getFlg);

  // 最前ページ遷移トリガー
  $(document).on('click', '#first', function () {
    if (page <= 1) return;
    page = 1;
    show(page, step, true);
  });

  // 前ページ遷移トリガー
  $(document).on('click', '#prev', function () {
    if (page <= 1) return;
    page = page - 1;
    show(page, step, true);
  });

  // 次ページ遷移トリガー
  $(document).on('click', '#next', function () {
    if (page >= menu / step) return;
    page = page + 1;
    show(page, step, true);
  });

  // 最終ページ遷移トリガー
  $(document).on('click', '#last', function () {
    if (page >= menu / step) return;
    page = Math.ceil(menu / step);
    show(page, step, true);
  });
}