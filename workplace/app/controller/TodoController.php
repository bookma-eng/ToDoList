<?php
require_once('./../../model/Todos.php');
require_once('./../../validation/TodoValidation.php');

class TodoController{
    public function index(){
        $todo_list = Todo::findAll();
        return $todo_list;
    }

    public function detail(){
        $todo_id = $_GET['todo_id'];
        if(!$todo_id){
            header("Location: ./../error/404.php");
            return;
        }
        if(Todo::isExistById($todo_id) === false){
            header("Location: ./../error/404.php");
            return;
        }
        $todo = Todo::findById($todo_id);

        return $todo;
    
    }

    public function new(){
       

        $data = array(
            "title" => $_POST['title'],
            "detail" => $_POST['detail'],
        );

        $validation = new TodoValidation;
        $validation->setData($data);
        if($validation->check() === false){
            $error_msgs = $validation->getErrorMessages();
            session_start();
            $_SESSION['error_msgs'] = $error_msgs;
            $params = sprintf("?title=%s&detail=%s", $_POST['title'], $_POST['detail']);
            header(sprintf("Location: ./new.php%s",$params));
        }

        $valid_data = $validation->getData();

        $todo = new Todo;
        $todo->setTitle($valid_data['title']);
        $todo->setDetail($valid_data['detail']);
        $result = $todo->save();

        
        if($result === false){
            $params = sprintf("?title=%s&detail=%s", $valid_data['title'], $valid_data['detail']);
            header(sprintf("Location: ./new.php%s",$params));
            return;
        }

        header("Location: ./index.php");
    }

    public function edit(){
        $todo_id = '';
        $params = array();
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            if(isset($_GET['todo_id'])){
                $todo_id = $_GET['todo_id'];
            }
            if(isset($_GET['title'])){
                $params['title'] = $_GET['title'];
            }
            if(isset($_GET['detail'])){
                $detail = $_GET['detail'];
            }
        }


        if(!$todo_id){
            header("Location: ./../error/404.php");
            return;
        }
        if(Todo::isExistById($todo_id) === false){
            header("Location: ./../error/404.php");
            return;
        }

        $todo = Todo::findById($todo_id);
        if(!$todo){
            header("Location: ./../error/404.php");
            return;
        }

        $data = array(
            "todo" => $todo,
            "paramas" => $params,
        );

        return $data;

    }

    public function update(){
        if(!$_POST['todo_id']){
            session_start();
            $_SESSION['error_msgs'] = "指定したIDに該当するデータがありません";
            
            header("Location: ./index.php");
            return;
        }

        if(Todo::isExistById($_POST['todo_id']) === false){
            $params = sprintf("?todo_id=%s&title=%s&detail=%s", $_POST['todo_id'], $_POST['title'], $_POST['detail']);
            header(sprintf("Location: ./edit.php%s",$params));
            return;
        }

        $data = array(
            "todo_id" => $_POST['todo_id'],
            "title" => $_POST['title'],
            "detail" => $_POST['detail'],
        );

        
        $validation = new TodoValidation;
        $validation->setData($data);
        if($validation->check() === false){
            $error_msgs = $validation->getErrorMessages();
            session_start();
            $_SESSION['error_msgs'] = $error_msgs;
            $params = sprintf("?todo_id=%s&title=%s&detail=%s", $_POST['todo_id'], $_POST['title'], $_POST['detail']);
            header(sprintf("Location: ./edit.php%s",$params));
            return;
        }

        $valid_data = $validation->getData();

        $todo = new Todo;
        $todo->setId($valid_data['todo_id']);
        $todo->setTitle($valid_data['title']);
        $todo->setDetail($valid_data['detail']);
        $result = $todo->update();

        
        if($result === false){
            $params = sprintf("?title=%s&detail=%s", $valid_data['title'], $valid_data['detail']);
            header(sprintf("Location: ./edit.php%s",$params));
            return;
        }

        header("Location: ./index.php");
    }

    public function delete(){
        $todo_id = $_POST['todo_id'];
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
        $todo_id = $_POST['todo_id'];
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
