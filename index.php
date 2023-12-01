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

<nav>
    <a href="index.php">Accueil</a>
    <a href="./view/livre.php">Ecran de recherche de livres</a>
    <a href="./view/abonne.php">Ecran de recherche d'abonné</a>
    <a href="./view/connexion.php">Connexion</a>
</nav>
<main>
    <p>Bienvenue dans l'application de gestion de bibliothèque!</p>
    <p>Cette application vous permet de rechercher des livres, des abonnés, et gérer vos informations.</p>
    <p>Explorez les fonctionnalités à partir des liens ci-dessus dans le header.</p>
</main>

</body>
</html>
