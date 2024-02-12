$(document).ready(function () {
  $('select').formSelect();

  $('#textarea').keyup(function () {
    $('#show-count').text($(this).val().length);
  });

  $('#movie_url').change(function () {
    getMovieInfo($(this).val());

  });
  // 記事削除ボタンをクリック
  $('#delete_post').click(function () {
    deletePost();
  });
});
function getMovieInfo(url) {

  let strId = '';

  //ノーマルURL
  let resultNormal = url.indexOf('v=');

  //短縮URL
  let resultShort = url.indexOf('youtu.be/');

  if (resultNormal !== -1) {
    strId = url.substr(resultNormal + 2);//v=の2文字分ずらす

  } else if (resultShort !== -1) {
    strId = url.substr(resultShort + 9);//9文字ずらす
  } else {
    alert('URLの再入力をお願いします。', url);
    return null;
  }
  $.ajax({
    type: "GET",
    url: "/youtubeinfo",
    data: { url: strId }
  }).done(function (data) {
    $('.skeleton').remove();
    $('.movie_area .added').remove();

    $('.movie_area').prepend('<iframe class="added" frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" ' +
      'width="640" height="360" src="https://www.youtube.com/embed/' + data.videoid + '"></iframe>'
    );
    $('.movie_area').prepend('<h6 class="added">' + data.title + '</h6>');
    $('.movie_area').prepend('<input class="added" type="hidden" name="thumbnail" value="' + data.thumbnail + '">');
    $('.movie_area').prepend('<input class="added" type="hidden" name="origin_title" value="' + data.title + '">');
    $('.movie_area').prepend('<input class="added" type="hidden" name="videoid" value="' + data.videoid + '">');


  }).fail(function (error) {
    // 失敗時の処理
    alert("youtubeの情報取得に失敗しました。");
  });
}
function deletePost() {
  let postId = $('[name=post_id]').val();
  $.ajax({
    url: "/delete-post",
    type: "POST",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { post_id: postId }
  }).done(function (data) {
    // 削除後はマイページへリダイレクト
    window.location.href = '/mypage';

  }).fail(function (error) {
    // 失敗時の処理
    alert("記事の削除に失敗しました。");
    console.log(error);
  });
}