<?php session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <link rel="stylesheet" type="text/css" href="../css/connexion.css">
    <title>Gestion de bibliothèque</title>
</head>
<body>

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
    <a href="../index.php">Accueil</a>
    <a href="livre.php">Ecran de recherche de livres</a>
    <?php
    if (isset($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] === 'gestionnaire') {
            echo '<a href="abonne.php">Ecran de recherche d\'abonné</a>';
        } elseif ($_SESSION['user_type'] === 'abonne') {
            echo '<a href="#" class="voirFicheLinkUser"  data-user-id="' . $_SESSION['user_id'] . '">Voir fiche</a>';
        }
    } else {
        echo '';
    } ?>
    <a href="connexion.php">Connexion</a>


    <?php
    if (isset($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] === 'gestionnaire') {
            echo '<a class="test" href="">  &#128100 À tant que gestionnaire </a>';
        } elseif ($_SESSION['user_type'] === 'abonne') {
            echo '<a class="test" href=""> &#128100 À tant qu\'abonné </a>';
        }
    } else {
        echo '<a class="test" href=""> &#128100 À tant que visiteur</a>';
    }
    ?>

</nav>

<script src="UpdateUserDetails.js"></script>

