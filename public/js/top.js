$(document).ready(function () {
  $('#exec_search').click(function () {
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
      url: "/top-selected-category",
      data: { category_id: val, sub_cat_flg: subcatFlg }
    }).done(function (data) {

      // 要素の削除
      $('.first .article_list article').remove();
      if (data.length > 0) {
        if ($('.first .article_list').find('h2').length > 0) {
          $('.first .article_list h2').remove();
        }
        // ここでランキングデータを再構築する
        $.each(data, function (index, value) {

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
            + '<h1 style="cursor: pointer;">' + (index + 1) + '位: ' + escapeHTML(value.title) + '</h1>'
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
        // ない時
        if ($('.first .article_list').find('h2').length > 0) {
          // なにもしない
        } else {
          $('.first .article_list').append('<h2>ランキングデータはありません。</h2>');
        }
      }
    }).fail(function (date) {
      alert("エラー発生")
    });
  });
});