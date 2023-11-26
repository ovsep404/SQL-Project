<?php
include "../debug/debug.php";
include "../requests/LivreRequest.php";
$pageTitle = 'Gestion de bibliothèque ';
include './view/header.php';
session_start();


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<br>

<p>Gestion de biobliothèque:</p>

<a href="./view/livre.php">Ecran de recherche de livres</a> <br>
<a href="./view/abonne.php">Ecran de recherche d'abonné</a>


<?php
include "./view/footer.php";
?>
