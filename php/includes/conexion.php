<?php
$host = "sqlXXX.infinityfree.com";
$user = "if0_40707872";
$pass = "Robleritos37";
$db   = "if0_40707872_iglesia_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Error de conexión");
}
?>
