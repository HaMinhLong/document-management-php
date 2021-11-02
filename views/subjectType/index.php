<?php
session_unset();
require_once "controllers/subjectType/subjectType.controller.php";
$controller = new SubjectTypeController();
$controller->mvcHandler();
?>