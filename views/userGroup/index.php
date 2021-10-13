<?php
session_unset();
require_once "controllers/userGroup/userGroup.controller.php";
$controller = new UserGroupController();
$controller->mvcHandler();
?>