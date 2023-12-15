<?php
global $pdo;
include "db_connect.php";
include "../debug/debug.php";

// La requête de ecran de recherche de abonnes
function searchAbonnes($searchNom, $searchPrenom, $searchVille, $SearchabonneOUexpire, $page, $perPage, $pdo, $limit = false, $offsetEnable = false)
{
    $offset = ($page - 1) * $perPage;

    $sql = "SELECT
                abonne.id,
                abonne.prenom,
                abonne.nom,
                abonne.date_naissance,
                abonne.ville,
                abonne.date_inscription,
                abonne.date_fin_abo
            FROM
                abonne
            WHERE
                (abonne.prenom LIKE :searchPrenom)
                AND (abonne.nom LIKE :searchNom)
                AND (abonne.ville LIKE :searchVille)";

    if ($SearchabonneOUexpire === 'abonne') {
        $sql .= " AND (date_fin_abo >= NOW())"; // Check if the subscription is active
    } elseif ($SearchabonneOUexpire === 'expire') {
        $sql .= " AND (date_fin_abo < NOW())"; // Check if the subscription has expired
    }

    $sql .= " GROUP BY abonne.id";

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

    $searchParamNom = '%' . $searchNom . '%';
    $searchParamPrenom = '%' . $searchPrenom . '%';
    $searchParamVille = '%' . $searchVille . '%';


    $stmt->bindParam(':searchNom', $searchParamNom, PDO::PARAM_STR);
    $stmt->bindParam(':searchPrenom', $searchParamPrenom, PDO::PARAM_STR);
    $stmt->bindParam(':searchVille', $searchParamVille, PDO::PARAM_STR);

    $stmt->execute();
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $searchResults;
}

// La requête pour Fiche d'un abonné
function getUserDetailsByID($pdo): void
{
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];


        $sql = "SELECT 
            abonne.id,
            abonne.prenom, 
            abonne.nom, 
            abonne.date_naissance, 
            abonne.adresse, 
            abonne.code_postal, 
            abonne.ville, 
            abonne.date_inscription, 
            abonne.date_fin_abo, 
            (
                SELECT JSON_ARRAYAGG(JSON_OBJECT('titre', livre.titre, 'date_emprunt', emprunt.date_emprunt))
                FROM emprunt
                INNER JOIN livre ON emprunt.id_livre = livre.id
                WHERE emprunt.id_abonne = abonne.id
                ORDER BY emprunt.date_emprunt DESC
            ) AS livres_empruntes,
            (
                SELECT JSON_ARRAYAGG(JSON_OBJECT('titre', sb.titre, 'categorie', sb.categorie))
                FROM (
                    SELECT l.titre, l.categorie
                    FROM livre l
                    WHERE l.categorie = (
                        SELECT MAX(cat.categorie)
                        FROM (
                            SELECT l.categorie, COUNT(e.id_livre) AS emprunts
                            FROM emprunt e
                            INNER JOIN livre l ON e.id_livre = l.id
                            WHERE e.date_emprunt >= DATE_SUB(NOW(), INTERVAL 1 YEAR)
                            AND e.id_abonne = abonne.id
                            GROUP BY l.categorie
                            ORDER BY emprunts DESC
                            LIMIT 5
                        ) AS cat
                    )
                    AND l.id NOT IN (
                        SELECT id_livre
                        FROM emprunt
                        WHERE id_abonne = abonne.id
                    )
                    LIMIT 5
                ) AS sb
            ) AS suggested_books
        FROM abonne
        WHERE abonne.id = :user_id;";


        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            header('Content-Type: application/json');
            echo json_encode($user);
        } else {

            http_response_code(404);

        }
    } else {

        http_response_code(400);


    }
}


getUserDetailsByID($pdo);


