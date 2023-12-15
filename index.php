<?php
include "../debug/debug.php";
include "../requests/LivreRequest.php";
$pageTitle = 'Gestion de bibliothèque';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/header.css">
    <link rel="stylesheet" type="text/css" href="./css/main.css">
    <link rel="stylesheet" type="text/css" href="./css/footer.css">
    <title>Gestion de bibliothèque</title>
</head>
<body id="indexBody">

<header>
    <h2><?php echo $pageTitle; ?></h2>
</header>


<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>

        <h2>Fiche d'un abonné</h2>
        <div id="userDetails">
        </div>
    </div>
    <div id="successNotification" style="display: none;">User updated successfully!</div>
    <div id="errorNotification" class="notification error-notification">Error: User not updated</div>
</div>


<nav>
    <a href="index.php">Accueil</a>
    <a href="./view/livre.php">Ecran de recherche de livres</a>
    <?php
    $userType = $_SESSION['user_type'] ?? '';
    $userId = $_SESSION['user_id'] ?? '';
    if ($userType === 'gestionnaire') {
        echo '<a href="./view/abonne.php">Ecran de recherche d\'abonné</a>';
        echo '<a class="test" href="">  &#128100 À tant que gestionnaire </a>';
    } elseif ($userType === 'abonne') {
        echo '<a href="#" class="voirFicheLinkUser"  data-user-id="' . $userId . '">Voir fiche</a>';
        echo '<a class="test" href=""> &#128100 À tant qu\'abonné </a>';
    } else {
        echo '<a class="test" href=""> &#128100 À tant que visiteur</a>';
    }
    ?>
    <a href="./view/connexion.php">Connexion</a>
</nav>

<main>
    <p>Bienvenue dans l'application de gestion de bibliothèque!</p>
    <p>Cette application vous permet de rechercher des livres, des abonnés, et gérer vos informations.</p>
    <p>Explorez les fonctionnalités à partir des liens ci-dessus dans le header.</p>
</main>

<script src="./view/UpdateUserDetails.js"></script>
</body>
</html>
