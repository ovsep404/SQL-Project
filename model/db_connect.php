<?php
$host = 'localhost';
$db = 'SQLschool';
$user = 'ovsep';
$pass = 'password';

try{
    $pdo = new PDO("mysql:host=$host;dbname=$db;", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch(PDOException $e){
    echo $e->getMessage()
    ;}
