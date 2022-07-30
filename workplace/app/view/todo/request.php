<?php
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    //methodがPOSTでない場合変数をすべて空にする
    $name = '';
    $email = '';
    $message = '';
}else{
    //取得した値を変数に代入
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, "UTF-8");
    $subject = '【ToDoアプリ】お問合せ';
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, "UTF-8");
    //完了メッセージの初期化
    $complete_msg = '';
    //送り先のアドレス
    $to = '15yukigiants12@gmail.com';
    //送信者情報
    $headrs = "From: ".$email."\r\n";
    $message .= "\r\n\r\n".$name;
    //送信処理
    mb_send_mail($to,$subject,$message,$headrs);
    // 初期化
    $name = '';
    $email = '';
    $message = '';
}
?>
<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Original CSS -->
    <link rel="stylesheet" type="text/css" href="./../../public/css/request.css">

    <title>問合せ | TodoList</title>
  </head>
  <body>
        <!-- ヘッダー -->
        <header>
            <nav class="navbar navbar-expand-lg navbar-light navbar-dark bg-primary">
                <div class="container-fluid">
                    <a class="navbar-brand fw-bold" href="./request.php">Request</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </nav>
        </header>
        <main>
            <form class="border rounded bg-white form-request" id="contact-form" method="post">
                <div class="my-3 fs-3">
                    <label for="exampleFormControlInput1" class="form-label text-primary">お名前</label>
                    <input type="text" name="name" class="form-control" id="exampleFormControlInput1">
                </div>
                <div class="my-3 fs-3">
                    <label for="exampleFormControlInput1" class="form-label text-primary">メールアドレス</label>
                    <input type="email" name="email" class="form-control" id="exampleFormControlInput1">
                </div>
                <div class="my-3 fs-3">
                    <label for="exampleFormControlTextarea1" class="form-label text-primary">具体的な内容</label>
                    <textarea name="message" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary px-5">送信</button>
            </form>
        </main>
    <script src="./../../public/js/jquery-3.6.0.min.js"></script>
    <script>
        $('#contact-form').submit(function(event){
            //送信を確認
            if(confirm("送信しますがよろしいですか？")){
                //submit実行を止める
                event.preventDefault();
                var $form = $(this);
                var $button = $form.find('.send-btn');
                $.ajax({
                    url:$form.attr('action'),
                    type:$form.attr('method'),
                    data: $form.serialize(),
                    //10秒でタイムアウト
                    timeout:10000,
                    //重複送信を避けるためにボタンを無効化
                    beforeSend: function(xhr, settings) {
                        $button.attr('disabled', true);
                    },
                    //完了後ボタンを押せるように
                    complete: function(xhr, textStatus) {
                        $button.attr('disabled', false);
                    }
                }).done(function(data, textStatus, jqXHR){
                    // 成功時の処理
                    //フォームを初期化
                    $form[0].reset();
                    alert("送信完了しました。");
                }).fail(function(jqXHR, textStatus, errorThrown){
                    // 失敗時の処理
                    console.log("エラーが発生しました。ステータス：" + jqXHR.status);
                    alert("送信失敗しました。");
                });
            }
        });
    </script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>