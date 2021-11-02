<?php
session_unset();
require_once "controllers/section/section.controller.php";
$controller = new SectionController();
$controller->mvcHandler();
?>