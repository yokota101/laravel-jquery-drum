$(document).ready(function () {
  let imageURL;
  let imageFile;

  $('#show-count').text($('#textarea').val().length);

  $('#textarea').on('input', function () {
    $('#show-count').text($(this).val().length);
  });

  // 記事削除ボタンをクリック
  $('#delete_account').click(function () {
    deleteAccount();
  });

  const selectElement = document.querySelector("#file");

  selectElement.addEventListener("change", (e) => {
    const files = e.target.files;

    if (files.length > 0) {
      //* note サーバー料金や負荷軽減のためサイズのバリデーションを実装
      // また重たい画像がアップされると、読み込みも重くなりUXが悪化する点も考慮
      if (files[0].size > 500000) {
        alert('画像ファイルが大きすぎます。500KB以下にしてください。');
        // リセット
        imageURL = null;
        imageFile = null;
        return;
      }

      if (files[0].type == 'image/jpg' || files[0].type == 'image/jpeg' || files[0].type == 'image/png' || files[0].type == 'image/gif') {

        //条件を満たした場合にセットする
        let createObjectURL = (window.URL || window.webkitURL).createObjectURL || window.createObjectURL;
        imageURL = createObjectURL(files[0]);
        imageFile = files;
      } else {
        alert('使用可能なファイルは「jpg」「png」「gif」のみです');
        // リセット
        imageURL = null;
        imageFile = null;
        return;
      }

    } else {
      // リセット
      imageURL = null;
      imageFile = null;
    }

  });

  // ファイルアップロード処理
  $('#uploadimage').click(function () {
    if (!imageFile) {
      alert("正しく画像が選択されていません。");
      return;
    } else {
      try {
        let user_id = $('#user_id').val();
        let timestamp = String(new Date().getTime());

        // ハッシュ値を利用しファイル名とする
        sha256(timestamp).then(hash => {

          const formData = new FormData();
          //拡張子を抽出
          let bufTitle = imageFile[0].name;
          let idxTitle = bufTitle.lastIndexOf('.');
          //ファイル名を作成
          const newName = 'prof_' + String(user_id) + '_' + String(timestamp) + '_' + String(hash) + bufTitle.substr(idxTitle);
          formData.append("file", imageFile[0], newName);

          $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: '/post-image',
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
          }).done(function (data) {
            alert('画像を更新しました。');
            // 成功時の処理
            window.location.href = '/profile-edit';
            //history.push('/mypage');
          }).fail(function (error) {
            // 失敗時の処理
            alert("投稿に失敗しました。");

          });

        }).catch(error => {
          alert("アップロード中にエラーが発生しました。");

        });

      } catch (error) {
        alert("画像の送信に失敗しました");
      }
    }
  });

  const sha256 = async (text) => {
    const uint8 = new TextEncoder().encode(text);
    const digest = await crypto.subtle.digest('SHA-256', uint8);
    return Array.from(new Uint8Array(digest)).map(v => v.toString(16).padStart(2, '0')).join('');
  }
});
function deleteAccount() {
  $.ajax({
    url: "/delete-account",
    type: "POST",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  }).done(function (data) {
    // 削除後はログアウトしtopのviewへ遷移
    window.location.href = '/';
  }).fail(function (error) {
    // 失敗時の処理
    alert("アカウントの削除に失敗しました。");
    console.log(error);
  });
}