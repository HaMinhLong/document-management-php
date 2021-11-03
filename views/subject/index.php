<?php
session_unset();
require_once "controllers/subject/subject.controller.php";
$controller = new SubjectController();
$controller->mvcHandler();
?>