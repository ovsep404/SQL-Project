<?php

include "../debug/debug.php";
include "../model/requests.php";
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
<body>

<p>Livre:</p>
<form action="livre.php" method="post">
    <label for="titre">Titre:</label>
    <input type="text" name="searchTitle" id="titre">

    <label for="auteur">Auteur:</label>
    <input type="text" name="searchAuteur" id="auteur">

    <label for="editeur">Éditeur:</label>
    <input type="text" name="searchEditeur" id="editeur">

<!--    <label for="disponible">Disponible:</label>-->
<!--    <input type="checkbox" name="searchDisponible" id="disponible" value="1">-->

    <label for="disponible"></label><select id="disponible" name="searchDisponible">
        <option value="disponible">Disponible</option>
        <option value="nondisponible">Non disponible</option>
        <option value="all">all</option>

    </select
           >

    <input type="submit" name="searchButton" value="Rechercher">
</form>



</body>
</html>
