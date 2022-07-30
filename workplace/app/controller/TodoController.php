<?php
require_once('./../../model/Todos.php');
require_once('./../../validation/TodoValidation.php');

class TodoController{
    public function indexTask(){
        $todo_Tasklist = Todo::findTask();
        return $todo_Tasklist;
    }

    public function indexComplete(){
        $todo_Completelist = Todo::findCompleteTask();
        return $todo_Completelist;
    }

    public function new(){
        //XSS対策
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES, "UTF-8");
        $detail = htmlspecialchars($_POST['detail'], ENT_QUOTES, "UTF-8");

        $data = array(
            "title" => $title,
            "detail" => $detail,
        );

        //バリデーションチェック
        $validation = new TodoValidation;
        $validation->setData($data);
        if($validation->check() === false){
            //エラーメッセージを受け取る
            $error_msgs = $validation->getErrorMessages();
            session_start();
            $_SESSION['error_msgs'] = $error_msgs;
            $params = sprintf("?title=%s&detail=%s", $title, $detail);
            header(sprintf("Location: ./index.php%s",$params));
            return;
        }

        $valid_data = $validation->getData();

        $todo = new Todo;
        $todo->setTitle($valid_data['title']);
        $todo->setDetail($valid_data['detail']);
        $result = $todo->save();

        
        if($result === false){
            $params = sprintf("?title=%s&detail=%s", $valid_data['title'], $valid_data['detail']);
            header(sprintf("Location: ./index.php%s",$params));
            return;
        }

        header("Location: ./index.php");
    }


    public function update(){
        //XSS対策
        $todo_id = htmlspecialchars($_POST['todo_id'], ENT_QUOTES, "UTF-8");
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES, "UTF-8");
        $detail = htmlspecialchars($_POST['detail'], ENT_QUOTES, "UTF-8");
        //値を受け取っているかチェック
        if(!$todo_id){
            error_log(sprintf("[TodoController][update]todo_id is not found. todo_id:%s",$todo_id));
            return false;
        }
        if(Todo::isExistById($todo_id) === false){
            error_log(sprintf("[TodoController][update]record is not found. todo_id:%s",$todo_id));
                return false;
        }
        //値を受け取る
        $data = array(
            "todo_id" => $todo_id,
            "title" => $title,
            "detail" => $detail,
        );

        //DB更新メソッド呼び出し
        $todo = new Todo;
        $todo->setId($data['todo_id']);
        $todo->setTitle($data['title']);
        $todo->setDetail($data['detail']);
        $result = $todo->update();

        //エラー処理
        if($result === false){
            $params = sprintf("?title=%s&detail=%s", $data['title'], $data['detail']);
            header(sprintf("Location: ./index.php%s",$params));
            return false;
        }

        return $result;
        }

    public function delete(){
        //値を受け取っているかチェック
        $todo_id = htmlspecialchars($_POST['todo_id'], ENT_QUOTES, "UTF-8");
        if(!$todo_id){
            error_log(sprintf("[TodoController][delete]todo_id is not found. todo_id:%s",$todo_id));
            return false;
        }

        if(Todo::isExistById($todo_id) === false){
            error_log(sprintf("[TodoController][delete]record is not found. todo_id:%s",$todo_id));
                return false;
        }
    
        $todo = new Todo;
        $todo->setId($todo_id);
        $result = $todo->delete();
        
        return $result;
    }

    public function updateStatus(){
        error_log("updateStatus call.");
        $todo_id = htmlspecialchars($_POST['todo_id'], ENT_QUOTES, "UTF-8");
        if(!$todo_id){
            error_log(sprintf("[TodoController][updateStatus]todo_id is not found. todo_id:%s",$todo_id));
            return false;
        }

        if(Todo::isExistById($todo_id) === false){
            error_log(sprintf("[TodoController][updateStatus]record is not found. todo_id:%s",$todo_id));
            return false;
        }

        $todo = Todo::findById($todo_id);
        if(!$todo){
            error_log(sprintf("[TodoController][updateStatus]record is not found. todo_id:%s",$todo_id));
            return false;
        }

        //ステータスを変換
        $status = $todo['status'];
        if($status == Todo::STATUS_INCOMPLETE){
            $status = Todo::STATUS_COMPLETED;
        }elseif($status == Todo::STATUS_COMPLETED){
            $status = Todo::STATUS_INCOMPLETE;
        }

        $todo = new Todo;
        $todo->setId($todo_id);
        $todo->setStatus($status);
        $result = $todo->updateStatus();

        error_log(print_r($result,true));
        
        return $result;
    }
}
