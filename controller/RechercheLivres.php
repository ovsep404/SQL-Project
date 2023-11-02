<?php
global $pdo;
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
        $searchResults = searchBooks($searchTitle, $searchAuteur, $searchEditeur, $pdo); // Pass all parameters
    }
}


