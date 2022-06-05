<?php
require_once('./../../controller/TodoController.php');
$controller = new TodoController;
$todo = $controller->search();

