<?php
global $searchResults, $pdo;
include "../debug/debug.php";
include "../model/requests.php";

$searchErr = ''; // Initialize an error message variable

if (isset($_POST['searchButton'])) {
    $searchTitle = isset($_POST['searchTitle']) ? $_POST['searchTitle'] : '';
    $searchAuteur = isset($_POST['searchAuteur']) ? $_POST['searchAuteur'] : '';
    $searchEditeur = isset($_POST['searchEditeur']) ? $_POST['searchEditeur'] : '';
    $searchDisponible = isset($_POST['searchDisponible']) ? 1 : 0;

    if (empty($searchTitle) && empty($searchAuteur) && empty($searchEditeur) && !$searchDisponible) {
        $searchErr = "Please enter the information";
    } else {
        $searchResults = searchBooks($searchTitle, $searchAuteur, $searchEditeur,$searchDisponible, $pdo); // Pass all parameters
    }
}

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


<?php

if (!empty($searchErr)) {
    echo "<p>$searchErr</p>";
}

if (!empty($searchResults)) {
    // Display the search results
    foreach ($searchResults as $result) {
        echo "Titre: " . $result['titre'] . "<br>";
        echo "Auteur: " . $result['auteur'] . "<br>";
        echo "Éditeur: " . $result['editeur'] . "<br>";
        echo "date_dernier_emprunt: " . $result['date_retour'] . "<br>";
        echo "===". "<br>";
        // Add more formatting as needed
    }
}
?>

</body>
</html>
