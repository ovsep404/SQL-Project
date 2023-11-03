<?php
include "db_connect.php";
include "../debug/debug.php";


function searchBooks($searchTitle, $searchAuteur, $searchEditeur, $searchDisponible, $page, $perPage, $pdo, $limit = false, $offsetEnable = false)
{
    $offset = ($page - 1) * $perPage;
    $sql = "SELECT livre.id, livre.titre, auteur.nom AS auteur, editeur.nom AS editeur, MAX(emprunt.date_retour) AS Date_du_dernier_emprunt, (
        SELECT COUNT(*) FROM emprunt WHERE emprunt.id_livre = livre.id AND date_retour IS NULL
    ) AS emprunt
    FROM livre
    INNER JOIN auteur ON livre.id_auteur = auteur.id
    INNER JOIN editeur ON livre.id_editeur = editeur.id
    LEFT JOIN emprunt ON livre.id = emprunt.id_livre
    WHERE (livre.titre LIKE :searchTitle
    AND auteur.nom LIKE :searchAuteur
    AND editeur.nom LIKE :searchEditeur)";

    if ($searchDisponible === 'disponible') {
        $sql .= " AND (SELECT COUNT(*) FROM emprunt WHERE emprunt.id_livre = livre.id AND date_retour IS NULL) = 0";
    } elseif ($searchDisponible === 'nondisponible') {
        $sql .= " AND (SELECT COUNT(*) FROM emprunt WHERE emprunt.id_livre = livre.id AND date_retour IS NULL) > 0";
    }

    $sql .= " GROUP BY livre.id";

    if ($limit) {
        $sql .= " LIMIT :perPage";
    }

    if ($offsetEnable) {
        $sql .= " offset :offset";
    }
    $stmt = $pdo->prepare($sql);

    if ($limit) {
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    }

    if ($offsetEnable) {
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    }

    $searchParamTitle = '%' . $searchTitle . '%';
    $searchParamAuteur = '%' . $searchAuteur . '%';
    $searchParamEditeur = '%' . $searchEditeur . '%';

    var_dump($searchParamEditeur);

    $stmt->bindParam(':searchTitle', $searchParamTitle, PDO::PARAM_STR);
    $stmt->bindParam(':searchAuteur', $searchParamAuteur, PDO::PARAM_STR);
    $stmt->bindParam(':searchEditeur', $searchParamEditeur, PDO::PARAM_STR);

    $stmt->execute();
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $searchResults;
}





