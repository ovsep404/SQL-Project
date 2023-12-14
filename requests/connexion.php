<?php
global $pdo;
include "db_connect.php";
include "../debug/debug.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['abonne_id'];
        $_SESSION['user_type'] = $user['type_utilisateur'];
        var_dump($_SESSION['user_id']);
        var_dump($_SESSION['user_type']);

        /*header('Location: dashboard.php');*/
    } else {
        echo "Invalid email or password";
    }
}
