<?php

$host = "localhost";
$db = "CLINICA";
$user = "root";
$pass = "";

try {

$pdo = new PDO("mysql:host=$host;dbname=$db",$user,$pass);

} catch (PDOException $e) {

echo "Error de conexión";

}
?>