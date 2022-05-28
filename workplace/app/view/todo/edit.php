<?php
require_once('./../../controller/TodoController.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $controller = new TodoController();
    $controller->update();
    exit;
}

$controller = new TodoController();
$data = $controller->edit();
$todo = $data['todo'];
$params = $data['params'];


session_start();
$error_msgs = $_SESSION['error_msgs'];
unset($_SESSION['error_msgs']);

?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name = "viewport"
              content = "width=device-width, user-scalable=no, inirial-scale=1.0, maximum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>編集</title>
    </head>
    <body>
        <h1>編集</h1>
        <form action="./edit.php" method="post">
            <div>
                <div>タイトル</div>
                <input name="title" type="text" value="<?php if(isset($params['title'])):?><?php echo $params['title'];?><?php else:?><?php echo $todo['title'];?><?php endif;?>">
            </div>
            <div>
                <div>詳細</div>
                <textarea name="detail"><?php if(isset($params['detail'])):?><?php echo $params['detail'];?><?php else:?><?php echo $todo['detail'];?><?php endif;?>"</textarea>
            </div>
            <input name="todo_id"type="hidden" value="<?php echo $todo['id'];?>">
            <button type="submit">更新</button>
        </form>
        <?php if($error_msgs):?>
            <div>
                <ul>
                    <?php foreach($error_msgs as $error_msg):?>
                        <li><?php echo $error_msg;?></li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php endif;?>
    </body>
</html>