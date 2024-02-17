<?php
global $pdo;
include "db_connect.php";
include "../debug/debug.php";
include "ConnexionHandler.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $handler = new ConnexionHandler($pdo);
    $result = $handler->handleLogin($email, $password);

    $redirect = $result['redirect'];

    header('Location: ' . $redirect);
    exit();
}