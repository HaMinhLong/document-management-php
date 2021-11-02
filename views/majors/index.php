<?php
session_unset();
require_once "controllers/majors/majors.controller.php";
$controller = new MajorsController();
$controller->mvcHandler();
?>