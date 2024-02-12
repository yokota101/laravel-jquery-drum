let postedMenu;
let likeMenu;
$(document).ready(function () {

  // 初期表示のページネーション
  postedMenu = postedCount;
  likeMenu = goodPostCount
  pagination(postedMenu, false, '#posted');
  pagination(likeMenu, false, '#like');

  $('ul.tabs').tabs();
});

const pagination = (menu, getFlg, parentElement) => {
  // 初期値設定
  let postedPage = 1; // 現在のページ（何ページ目か）
  let likePage = 1;
  let startPage = 1;


  const step = 20; // ステップ数（1ページに表示する項目数）

  // 現在のページ/全ページ を表示
  const count = (page, step, menu, parentElement) => {

    const p = $(parentElement + ' .count').text();
    const total = (menu % step == 0) ? (menu / step) : (Math.floor(menu / step) + 1);
    $(parentElement + ' .count').text(page + "/" + total + "ページ");
  }

  // ページを表示
  const show = (page, step, getFlg, menu, parentElement) => {

    // ランキングの再構成
    if (getFlg) {
      // ランキングを空にする
      $(parentElement + ' .article_list article').remove();
      const startIndex = (page - 1) * step;
      getRank(startIndex, parentElement);
    }
    count(page, step, menu, parentElement);
  }

  // 最初に1ページ目を表示
  show(startPage, step, getFlg, menu, parentElement);

  // 最前ページ遷移トリガー
  $(document).off('click');
  $(document).on('click', '#first_1', function () {
    if (postedPage <= 1) return;
    postedPage = 1;
    let parendId = $(this).closest('.parentclass').attr('id');
    show(postedPage, step, true, postedMenu, '#' + parendId);
  });

  // 前ページ遷移トリガー
  $(document).on('click', '#prev_1', function () {

    if (postedPage <= 1) return;
    postedPage = postedPage - 1;
    let parendId = $(this).closest('.parentclass').attr('id');

    show(postedPage, step, true, postedMenu, '#' + parendId);
  });

  // 次ページ遷移トリガー
  $(document).on('click', '#next_1', function () {
    if (postedPage >= postedMenu / step) return;
    postedPage = postedPage + 1;
    let parendId = $(this).closest('.parentclass').attr('id');

    show(postedPage, step, true, postedMenu, '#' + parendId);
  });

  // 最終ページ遷移トリガー
  $(document).on('click', '#last_1', function () {
    if (postedPage >= postedMenu / step) return;
    postedPage = Math.ceil(menu / step);
    let parendId = $(this).closest('.parentclass').attr('id');
    show(postedPage, step, true, postedMenu, '#' + parendId);
  });




  // 最前ページ遷移トリガー
  $(document).on('click', '#first_2', function () {
    if (likePage <= 1) return;
    likePage = 1;
    let parendId = $(this).closest('.parentclass').attr('id');
    show(likePage, step, true, likeMenu, '#' + parendId);
  });

  // 前ページ遷移トリガー
  $(document).on('click', '#prev_2', function () {

    if (likePage <= 1) return;
    likePage = likePage - 1;
    let parendId = $(this).closest('.parentclass').attr('id');

    show(likePage, step, true, likeMenu, '#' + parendId);
  });

  // 次ページ遷移トリガー
  $(document).on('click', '#next_2', function () {
    if (likePage >= likeMenu / step) return;
    likePage = likePage + 1;
    let parendId = $(this).closest('.parentclass').attr('id');

    show(likePage, step, true, likeMenu, '#' + parendId);
  });

  // 最終ページ遷移トリガー
  $(document).on('click', '#last_2', function () {
    if (likePage >= likeMenu / step) return;
    likePage = Math.ceil(likeMenu / step);
    let parendId = $(this).closest('.parentclass').attr('id');
    show(likePage, step, true, likeMenu, '#' + parendId);
  });

}


const getRank = (startIndex, parentElement) => {

  let userId = $('#user_id').val();
  $.ajax({
    type: "GET",
    url: "/userpage-list",
    data: { kind: parentElement, start: startIndex, user_id: userId }
  }).done(function (datas) {

    // 要素の削除
    $(parentElement + ' .article_list article').remove();
    if (datas.cnt > 0) {
      // ここでランキングデータを再構築すること
      $.each(datas.lists, function (index, value) {

        let imageUrlStr = '/images/default_drummer.png';
        if (value.image) {
          imageUrlStr = '/prof_images/' + value.image;
        }

        let ele = '<article>'
          + '<div class="rank_main">'
          + '<figure>'
          + '<a href="watch?post_id=' + value.id + '&movie_id=' + value.movie_id + '">'
          + '<img src="' + escapeHTML(value.thumbnail_url) + '" alt="' + escapeHTML(value.title) + '" style="cursor: pointer;">'
          + '</a>'
          + '<h3> <i class="small material-icons">thumb_up</i>' + (value.good_point ?? 0) + '<i class="small material-icons">thumb_down</i>' + (value.bad_point ?? 0) + '<br> 合計ポイント → ' + (value.total ?? 0) + '</h3>'
          + '</figure>'
          + '<div class="rank_sub"> <span class="mute_txt">投稿日:' + value.created_at + '</span>'
          + '<a href="watch?post_id=' + value.id + '&movie_id=' + value.movie_id + '">'
          + '<h1 style="cursor: pointer;">' + escapeHTML(value.title) + '</h1>'
          + '</a>'
          + '<figure>'
          + '<a href="watch?post_id=' + value.id + '&movie_id=' + value.movie_id + '">'
          + '<img src="' + escapeHTML(value.thumbnail_url) + '" alt="' + escapeHTML(value.title) + '" style="cursor: pointer;">'
          + '</a>'
          + '<h3> <i class="small material-icons">thumb_up</i>' + (value.good_point ?? 0) + '<i class="small material-icons">thumb_down</i>' + (value.bad_point ?? 0) + '<br> 合計ポイント → ' + (value.total ?? 0) + '</h3>'
          + '</figure>'
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

        $(parentElement + ' .article_list').append(ele);

      })
    }
  }).fail(function (date) {
    alert("エラー発生")
  });
}