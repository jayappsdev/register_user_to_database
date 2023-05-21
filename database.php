<?php

$hostName = "localhost";
$dbUser = "root";
$dbPasswword = "";
$dbName = "users_register";
$dbConnection = mysqli_connect($hostName, $dbUser, $dbPasswword, $dbName);
if (!$dbConnection)
{
    die("Something went wrong");
}

?>