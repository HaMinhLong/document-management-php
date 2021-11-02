<?php

require_once __DIR__ . "/router.php";

get("/final-php", "views/dashboard/index.php");

get("/final-php/menu", "views/menu/index.php");
post("/final-php/menu", "views/menu/index.php");
get("/final-php/menu/insert", "views/menu/insert.php");
get("/final-php/menu/update", "views/menu/update.php");

get("/final-php/user-group", "views/userGroup/index.php");
post("/final-php/user-group", "views/userGroup/index.php");
get("/final-php/user-group/insert", "views/userGroup/insert.php");
get("/final-php/user-group/update", "views/userGroup/update.php");

get("/final-php/user", "views/user/index.php");
post("/final-php/user", "views/user/index.php");
get("/final-php/user/insert", "views/user/insert.php");
get("/final-php/user/update", "views/user/update.php");

get("/final-php/department", "views/department/index.php");
post("/final-php/department", "views/department/index.php");
get("/final-php/department/insert", "views/department/insert.php");
get("/final-php/department/update", "views/department/update.php");

get("/final-php/section", "views/section/index.php");
post("/final-php/section", "views/section/index.php");
get("/final-php/section/insert", "views/section/insert.php");
get("/final-php/section/update", "views/section/update.php");

get("/final-php/majors", "views/majors/index.php");
post("/final-php/majors", "views/majors/index.php");
get("/final-php/majors/insert", "views/majors/insert.php");
get("/final-php/majors/update", "views/majors/update.php");

get("/final-php/subject", "views/subject/index.php");
post("/final-php/subject", "views/subject/index.php");
get("/final-php/subject/insert", "views/subject/insert.php");
get("/final-php/subject/update", "views/subject/update.php");

get("/final-php/subjectType", "views/subjectType/index.php");
post("/final-php/subjectType", "views/subjectType/index.php");
get("/final-php/subjectType/insert", "views/subjectType/insert.php");
get("/final-php/subjectType/update", "views/subjectType/update.php");

any("/404", "views/errors/404.php");

?>