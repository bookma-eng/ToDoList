<?php
require_once('./../../controller/TodoController.php');
$controller = new TodoController;
$todo_TaskList = $controller->indexTask();
$todo_Completelist = $controller->indexComplete();

//新規作成時POST処理
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $controller = new TodoController();
    $controller->new();
    exit;
}

//新規作成時、エラーで弾かれた際に既に入力した値を残す処理
$title = '';
$detail = '';
$getTitle= htmlspecialchars($_GET['title'], ENT_QUOTES, "UTF-8");
$getdetail= htmlspecialchars($_GET['detail'], ENT_QUOTES, "UTF-8");
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($getTitle)){
        $title = $getTitle;
    }
    if(isset($getdetail)){
        $detail = $getdetail;
    }
}

//新規作成時、エラーが起きた際にアラートを表示させる
session_start();
$error_msgs = $_SESSION['error_msgs'];
unset($_SESSION['error_msgs']);
if($error_msgs){
    foreach($error_msgs as $error_msg){
        $alert = "<script type='text/javascript'>alert('".$error_msg."');</script>";
        echo $alert;
    }
}



?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name = "viewport"
              content = "width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>ホーム | TodoList<</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../public/css/main.css">
    </head>
    <body>
        <!-- ヘッダー -->
        <header>
            <nav class="navbar navbar-expand-lg navbar-light navbar-dark bg-primary">
                <div class="container-fluid">
                    <a class="navbar-brand fw-bold" href="./index.php">ToDo List</a>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-0 mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="./request.php">問合せ</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <!-- メイン -->
        <main>
            <div class="container">
                <div class="row">
                    <div class="col p-3">
                        <div class="border bg-light m-3">
                            <h2>Task</h2>
                            <?php if($todo_TaskList):?>
                                <ul class="list-group my-4" id="task-group">
                                        <?php foreach($todo_TaskList as $todo):?>
                                            <li class="list-group-item" id="task" data-title="<?php echo $todo['title'];?>" data-detail="<?php echo $todo['detail'];?>">
                                                <?php echo $todo['title'];?>
                                                <div class="complete-btn"data-id="<?php echo $todo['id'];?>">
                                                    <button >完了</button>
                                                </div>
                                                <div class="delete-btn"data-id="<?php echo $todo['id'];?>">
                                                    <button >削除</button>
                                                </div>
                                                <button type="button" class="btn btn-default h-auto py-0" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $todo['id']?>">
                                                    <div class="info-item">
                                                        <img src="./../../public/image/info_black_24dp.svg" alt="" width="30" height="24">
                                                    </div>
                                                </button>
                                            </li>
                                        <?php endforeach;?>
                                        <li class="list-group-item text-start">
                                            <button id="edit-btn" type="button" class="btn btn-default h-auto py-0" data-bs-toggle="modal" data-bs-target="#newModal">
                                                <div class="info-item">
                                                    <img src="./../../public/image/data_add.svg" alt="" width="30" height="24">
                                                </div>
                                            </button>
                                            <div class="new-text">新規作成</div>
                                        </li>     
                                </ul>
                            <?php else: ?>
                                <p>データなし</p>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="col p-3">
                        <div class="border bg-light m-3">
                            <h2>CompletedTask</h2>
                            <?php if($todo_Completelist):?>
                                <ul class="list-group my-4">
                                        <?php foreach($todo_Completelist as $todo):?>
                                            <li class="list-group-item" data-title="<?php echo $todo['title'];?>" data-detail="<?php echo $todo['detail'];?>">
                                                <?php echo $todo['title'];?>
                                                <div class="restore-btn"data-id="<?php echo $todo['id'];?>">
                                                    <button >復元</button>
                                                </div>
                                                <div class="delete-btn"data-id="<?php echo $todo['id'];?>">
                                                    <button >削除</button>
                                                </div>
                                                <button id="edit-btn" type="button" class="btn btn-default h-auto py-0" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $todo['id']?>">
                                                    <div class="info-item">
                                                        <img src="./../../public/image/info_black_24dp.svg" alt="" width="30" height="24">
                                                    </div>
                                                </button>
                                            </li>
                                        <?php endforeach;?>     
                                </ul>
                            <?php else: ?>
                                <p>データなし</p>
                            <?php endif;?>
                            <!-- <?php if($error_msgs):?>
                                <?php foreach($error_msgs as $error_msg):?>
                                    <?php function func_alert($error_msg){echo "<script>alert('$error_msg');</script>";}?>
                                <?php endforeach;?>
                            <?php endif;?> -->
                        </div>
                    </div>
                </div>
            </div>
                                                
            <!-- Modal 新規作成-->
            <form method="post">
                <div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">新規作成</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="mb-3">
                                <label for="newFormControlInput" class="form-label text-primary">タイトル</label>
                                <input name ="title" type="text" value="<?php echo $title;?>" class="form-control" id="newFormControlInput">
                            </div>
                            <div class="mb-3">
                                <label for="newFormControlTextarea" class="form-label text-primary">詳細</label>
                                <textarea name ="detail" class="form-control" id="newFormControlTextarea" rows="3"><?php echo $detail;?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">登録</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Button trigger modal 詳細/編集-->
            <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">詳細</button> -->
                                                
            <!-- Modal 詳細/編集-->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">タスク詳細</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="mb-3">
                            <label for="editFormControlInput" class="form-label text-primary">タイトル</label>
                            <input type="title" class="form-control" id="editFormControlInput">
                        </div> 
                        <div class="mb-3">
                            <label for="editFormControlTextarea" class="form-label text-primary">詳細</label>
                            <textarea class="form-control" id="editFormControlTextarea" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="update" class="btn btn-primary">更新</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    </div>
                    </div>
                </div>
            </div>
            <script src="./../../public/js/jquery-3.6.0.min.js"></script>
            <script>
                $(".delete-btn").click(function(){
                    //idの取得
                    let todo_id = $(this).data('id');
                    if(confirm("削除しますがよろしいですか？ id:" + todo_id)){
                        $(".delete-btn").prop("disabled",true);                            
                            let data = {};
                            data.todo_id = todo_id;
                            $.ajax({
                                url: './delete.php',
                                type: 'post',
                                data: data
                            }).then(
                                function(data){
                                    let json = JSON.parse(data);
                                    console.log("success",json);
                                    if(json.result == 'success'){
                                        window.location.href = "./index.php";
                                    }else{
                                        console.log("failed to delete.");
                                        alert("failed to delete.");
                                        $(".delete-btn").prop("disabled", false);
                                    }
                                },
                                function(){
                                    console.log("fail"); 
                                    alert("fail");
                                    $(".delete-btn").prop("disabled", false);
                                });
                    }
                    
                });

                $(".complete-btn").click(function(){
                    //idの取得
                    let todo_id = $(this).data('id');
                    $(".complete-btn").prop("disabled",true);
                    //配列に格納
                    let data = {};
                    data.todo_id = todo_id;
                    $.ajax({
                                url: './update_status.php',
                                type: 'post',
                                data: data
                                }).then(
                                    function(data){
                                    let json = JSON.parse(data);
                                    console.log("success",json);
                                      if(json.result == 'success'){
                                        window.location.href = "./index.php";
                                        alert("タスクが完了しました");
                                    }else{
                                        console.log("failed to delete.");
                                        alert("failed to delete.");
                                        $(".complete-btn").prop("disabled", false);
                                    }
                                },
                                function(){
                                    console.log("fail"); 
                                    alert("fail");
                                    $(".complete-btn").prop("disabled", false);
                                });
                });

                $(".restore-btn").click(function(){
                    //idの取得
                    let todo_id = $(this).data('id');
                    $(".restore-btn").prop("disabled",true);
                    //配列に格納
                    let data = {};
                    data.todo_id = todo_id;
                    $.ajax({
                                url: './update_status.php',
                                type: 'post',
                                data: data
                                }).then(
                                    function(data){
                                    let json = JSON.parse(data);
                                    console.log("success",json);
                                    if(json.result == 'success'){
                                        window.location.href = "./index.php";
                                        alert("タスクを復元しました");
                                    }else{
                                        console.log("failed to delete.");
                                        alert("failed to delete.");
                                        $(".complete-btn").prop("disabled", false);
                                    }
                                },
                                function(){
                                    console.log("fail"); 
                                    alert("fail");
                                    $(".complete-btn").prop("disabled", false);
                                });
                });

                $("#editModal").on('show.bs.modal',function(event){
                    // モーダルに対象のリストの内容を表示
                    console.log("test");
                    var button =$(event.relatedTarget)
                    var todo_id = button.data('id');
                    console.log(todo_id);

                    //ボタンが押された対象の情報を取得
                    var todo_title = button.closest('li').data('title');
                    var todo_detail = button.closest('li').data('detail');
                    $('#editFormControlInput').val(todo_title);
                    $('#editFormControlTextarea').val(todo_detail);

                    //更新ボタンを押下時、DBを更新
                    $("#update").click(function(){
                        todo_title = $("#editFormControlInput").val();
                        todo_detail = $("#editFormControlTextarea").val();
                        if(confirm("更新しますがよろしいですか？ id:" + todo_id)){
                            $("#update").prop("disabled",true);
                            // バリデーションチェック
                            if($('#editFormControlInput').val() === ''){
                                alert('タイトルが空です。');
                                if($('#editFormControlTextarea').val() === ''){
                                    alert('詳細が空です。');
                                }
                                console.log("fail");
                                $("#update").prop("disabled", false);
                            }else if($('#editFormControlTextarea').val() === ''){
                                alert('詳細が空です。');
                                console.log("fail");
                                $("#update").prop("disabled", false);
                            }
                            var data = {'todo_id' : todo_id,
                                        'title' : todo_title,
                                        'detail' : todo_detail
                                        };
                                $.ajax({
                                    url: './update.php',
                                    type: 'post',
                                    data: data
                                }).then(
                                    function(data){
                                        let json = JSON.parse(data);
                                        console.log("success",json);
                                        if(json.result == 'success'){
                                            window.location.href = "./index.php";
                                        }else{
                                            console.log("failed to update.");
                                            alert("failed to update.");
                                            $("#update").prop("disabled", false);
                                        }
                                    },
                                    function(){
                                        console.log("fail"); 
                                        alert("fail");
                                        $("#update").prop("disabled", false);
                                    });
                        }
                    });
                });
            </script>
        </main>
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