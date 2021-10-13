<?php

require_once __DIR__ . "/router.php";

get("/final-php", "views/dashboard/index.php");
get("/final-php/menu", "views/menu/index.php");

get("/final-php/user-group", "views/userGroup/index.php");
post("/final-php/user-group", "views/userGroup/index.php");
get("/final-php/user-group/insert", "views/userGroup/insert.php");
get("/final-php/user-group/update", "views/userGroup/update.php");

get("/final-php/user", "views/user/index.php");

get("/final-php/department", "views/department/index.php");
get("/final-php/section", "views/section/index.php");
get("/final-php/majors", "views/majors/index.php");

get("/final-php/subject", "views/subject/index.php");
get("/final-php/subject-type", "views/subjectType/index.php");

any("/404", "views/errors/404.php");

?>