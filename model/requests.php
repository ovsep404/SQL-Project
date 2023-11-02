<?php
include "db_connect.php";
include "../debug/debug.php";


function searchBooks($searchTitle, $searchAuteur, $searchEditeur,$searchDisponible, $pdo)
{
    $stmt = $pdo->prepare("SELECT livre.titre, auteur.nom AS auteur, editeur.nom AS editeur, emprunt.date_retour
            FROM livre
            INNER JOIN auteur ON livre.id_auteur = auteur.id
            INNER JOIN editeur ON livre.id_editeur = editeur.id
            LEFT JOIN emprunt ON livre.id = emprunt.id_livre
            WHERE (livre.titre LIKE :searchTitle
            AND auteur.nom LIKE :searchAuteur
            AND editeur.nom LIKE :searchEditeur);");

//    if ($searchDisponible === 'disponible') {
//        $stmt .= " AND emprunt.date_retour IS NULL";
//    } else if ($searchDisponible === 'non disponible') {
//        $stmt .= " AND emprunt.date_retour IS NOT NULL";
//    }

    $searchParamTitle = '%' . $searchTitle . '%';
    $searchParamAuteur = '%' . $searchAuteur . '%';
    $searchParamEditeur = '%' . $searchEditeur . '%';

    $stmt->bindParam(':searchTitle', $searchParamTitle, PDO::PARAM_STR);
    $stmt->bindParam(':searchAuteur', $searchParamAuteur, PDO::PARAM_STR);
    $stmt->bindParam(':searchEditeur', $searchParamEditeur, PDO::PARAM_STR);

    $stmt->execute();
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $searchResults;
}

