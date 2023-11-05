<?php
global $searchResults, $pdo;
include "../debug/debug.php";
include "../requests/AbonneRequest.php";
session_start();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/livre.css">
    <title>Document</title>
</head>
<body>

<p>Abonné:</p>

<form action="#" method="post">

    <div class="test">
        <input type="text" name="searchNom" placeholder="Nom">

        <input type="text" name="searchPrenom" placeholder="Prénom">

        <input type="text" name="searchVille" placeholder="Ville">

        <label for="abonneOUexpire"></label><select id="abonneOUexpire" name="SearchabonneOUexpire">
            <option value="abonne">Abonné</option>
            <option value="expire">Expiré</option>
            <option value="all">all</option>
        </select>

        <input type="submit" name="searchButton" value="Rechercher">
    </div>
</form>

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



<?php
$perPage = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$searchErr = '';
$nbRes = 0;

if (isset($_POST['searchButton'])) {
    $_SESSION['Nom'] = '';
    $_SESSION['Prenom'] = '';
    $_SESSION['Ville'] = '';
    if (isset($_POST['searchNom']) and $_POST['searchNom'] != '') {
        $_SESSION['Nom'] = $_POST['searchNom'];
    }
    if (isset($_POST['searchPrenom']) and $_POST['searchPrenom'] != '') {
        $_SESSION['Prenom'] = $_POST['searchPrenom'];
    }
    if (isset($_POST['searchVille']) and $_POST['searchVille'] != '') {
        $_SESSION['Ville'] = $_POST['searchVille'];
    }
    if (isset($_POST['SearchabonneOUexpire']) and $_POST['SearchabonneOUexpire'] != '') {
        $_SESSION['abonneOUexpire'] = $_POST['SearchabonneOUexpire'];
    }
}

$searchResults = searchAbonnes($_SESSION['Nom'], $_SESSION['Prenom'], $_SESSION['Ville'], $_SESSION['abonneOUexpire'], $page, $perPage, $pdo, 20, true);
$nbRes = count(searchAbonnes($_SESSION['Nom'], $_SESSION['Prenom'], $_SESSION['Ville'], $_SESSION['abonneOUexpire'], $page, $perPage, $pdo));

if (empty($searchResults)) {
    $searchErr = "No results found. Please refine your search criteria.";
}


$sql = "SELECT COUNT(*) FROM abonne";
$totalAbonne = $pdo->query($sql)->fetchColumn();
$totalPages = $nbRes / $perPage;

?>


<?php
if (!empty($searchErr)) {
    echo "<p>$searchErr</p>";
}

if (!empty($searchResults)) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Nom</th>';
    echo '<th>Prenom</th>';
    echo '<th>Ville</th>';
    echo '<th>Date de naissance</th>';
    echo '<th>Date fin abonnement</th>';
    echo '<th>Abonné ou expiré</th>';
    echo '<th>Voir fiche</th>';

    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($searchResults as $result) {
        echo '<tr>';
        echo '<td>' . $result['id'] . '</td>';
        echo '<td>' . $result['nom'] . '</td>';
        echo '<td>' . $result['prenom'] . '</td>';
        echo '<td>' . $result['ville'] . '</td>';
        echo '<td>' . $result['date_naissance'] . '</td>';
        echo '<td>' . $result['date_fin_abo'] . '</td>';
        echo '<td>' . ($result['date_fin_abo'] >= date("Y-m-d H:i:s") ? 'Abonné' : 'Expire') . '</td>';
        echo '<td><a href="#" class="voirFicheLink" data-user-id="' . $result['id'] . '">Voir fiche</a></td>';

        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    echo '<div class ="paginationContainer">';
    for ($i = 0; $i <= $totalPages; $i++) {
        echo '<a class="pagination" href="abonne.php?page=' . ($i + 1) . '">' . $i . '</a> ';
    }
    echo '</div>';
    echo '</div>';

}
?>


<script src="UpdateAbonneDetails.js"></script>
</body>
</html>






