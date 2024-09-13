<?php

// DB 설정 파일을 읽어서 DB 를 연결한다.
$mysql_host = "";
$mysql_user = "";
$mysql_pass = "";
$mysql_db = "";

$mysql_host = "localhost";
$mysql_user = "samsanphone";
$mysql_pass = "samsanphone@@22";
$mysql_db   = "samsanphone";

$connect = mysqli_connect($mysql_host, $mysql_user, $mysql_pass,$mysql_db);
mysqli_set_charset($connect,'utf8');
?>
