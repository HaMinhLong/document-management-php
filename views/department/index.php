<?php
session_unset();
require_once "controllers/department/department.controller.php";
$controller = new DepartmentController();
$controller->mvcHandler();
?>