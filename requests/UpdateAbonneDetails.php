<?php
global $pdo;
include "db_connect.php";
include "../debug/debug.php";

// La requête pour modifier les details de abonne
function updateUserDetails($pdo)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents('php://input'));


        $sql = "UPDATE
                    abonne
                SET
                    prenom = :prenom,
                    nom = :nom,
                    adresse =:adresse,
                    code_postal =:code_postal,
                    ville = :ville,
                    date_inscription = :date_inscription,
                    date_naissance = :date_naissance,
                    date_fin_abo = :date_fin_abo
                WHERE
                    id = :user_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':prenom', $data->prenom, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $data->nom, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $data->adresse, PDO::PARAM_STR);
        $stmt->bindParam(':code_postal', $data->code_postal, PDO::PARAM_STR);
        $stmt->bindParam(':ville', $data->ville, PDO::PARAM_STR);
        $stmt->bindParam(':date_inscription', $data->date_inscription, PDO::PARAM_STR);
        $stmt->bindParam(':date_naissance', $data->date_naissance, PDO::PARAM_STR);
        $stmt->bindParam(':date_fin_abo', $data->date_fin_abo, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $data->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $updatedUserData = (array)$data;


            header('Content-Type: application/json');
            echo json_encode($updatedUserData);
        } else {

            http_response_code(500);
        }
    } else {

        http_response_code(400);

    }
}

updateUserDetails($pdo);