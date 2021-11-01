<?php
session_unset();
require_once "controllers/user/user.controller.php";
$controller = new UserController();
$controller->mvcHandler();
?>