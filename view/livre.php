<?php
global $searchResults, $pdo;
include "../debug/debug.php";
include "../model/requests.php";
session_start();


$perPage = 20; // Number of books per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$searchErr = ''; // Initialize an error message variable
$nbRes = 0;

if (isset($_POST['searchButton'])) {
    $_SESSION['Title'] = '';
    $_SESSION['Auteur'] = '';
    $_SESSION['Editeur'] = '';
    if (isset($_POST['searchTitle']) and $_POST['searchTitle'] != '') {
        $_SESSION['Title'] = $_POST['searchTitle'];
    }
    if (isset($_POST['searchAuteur']) and $_POST['searchAuteur'] != '') {
        $_SESSION['Auteur'] = $_POST['searchAuteur'];
    }
    if (isset($_POST['searchEditeur']) and $_POST['searchEditeur'] != '') {
        $_SESSION['Editeur'] = $_POST['searchEditeur'];
    }
    if (isset($_POST['searchDisponible']) and $_POST['searchDisponible'] != '') {
        $_SESSION['Disponible'] = $_POST['searchDisponible'];
    }
}

$searchResults = searchBooks($_SESSION['Title'], $_SESSION['Auteur'], $_SESSION['Editeur'], $_SESSION['Disponible'], $page, $perPage, $pdo, 20, true);
$nbRes = count(searchBooks($_SESSION['Title'], $_SESSION['Auteur'], $_SESSION['Editeur'], $_SESSION['Disponible'], $page, $perPage, $pdo));

// Calculate the total number of pages
$sql = "SELECT COUNT(*) FROM livre";
$totalBooks = $pdo->query($sql)->fetchColumn();
$totalPages = $nbRes / $perPage;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <link rel="stylesheet" type="text/css" href="../css/livre.css">
    <title>Document</title>
</head>
<body>

<?php
if (!empty($searchErr)) {
    echo "<p>$searchErr</p>";
}

if (!empty($searchResults)) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Titre</th>';
    echo '<th>Auteur</th>';
    echo '<th>Éditeur</th>';
    echo '<th>Date du dernier emprunt</th>';
    echo '<th>Disponibilité</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Loop through user data and populate the table
    foreach ($searchResults as $result) {
        echo '<tr>';
        echo '<td>' . $result['id'] . '</td>';
        echo '<td>' . $result['titre'] . '</td>';
        echo '<td>' . $result['auteur'] . '</td>';
        echo '<td>' . $result['editeur'] . '</td>';
        echo '<td>' . $result['Date_du_dernier_emprunt'] . '</td>';
        echo '<td>' . ($result['emprunt'] !== 0 ? 'non' : 'oui') . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    // Display pagination links
    echo '<div>';
    for ($i = 0; $i <= $totalPages; $i++) {
        echo '<a href="livre.php?page=' . $i + 1 . '">' . $i . '</a> ';
    }
    echo '</div>';

}
?>
</div>
</body>
</html>



