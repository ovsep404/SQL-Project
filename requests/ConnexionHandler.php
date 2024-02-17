<?php

class ConnexionHandler
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function handleLogin($email, $password)
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['abonne_id'];
            $_SESSION['user_type'] = $user['type_utilisateur'];

            if ($_SESSION['user_type'] === 'abonne') {
                return ['redirect' => '../view/livre.php', 'user_id' => $user['abonne_id'], 'user_type' => $user['type_utilisateur']];
            } elseif ($_SESSION['user_type'] === 'gestionnaire') {
                return ['redirect' => '../view/abonne.php', 'user_id' => $user['abonne_id'], 'user_type' => $user['type_utilisateur']];
            }

        } else {
            return ['redirect' => '../view/connexion.php?message=Invalid email or password'];
        }
    }
}