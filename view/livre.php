<?php
global $searchResults, $pdo;
include "../debug/debug.php";
include "../requests/LivreRequest.php";
$pageTitle = 'Gestion de bibliothèque livre:';
include 'header.php';
session_start();
?>


<form action="#" method="post">

    <div class="test">
        <input type="text" name="searchTitle" placeholder="Titre" id="titre">

        <input type="text" name="searchAuteur" placeholder="Auteur" id="auteur">

        <input type="text" name="searchEditeur" placeholder="Éditeur" id="editeur">

        <label for="disponible"></label><select id="disponible" name="searchDisponible">
            <option value="disponible">Disponible</option>
            <option value="nondisponible">Non disponible</option>
            <option value="all">all</option>

        </select>

        <input type="submit" name="searchButton" value="Rechercher">
    </div>
</form>


<?php
$perPage = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$searchErr = '';
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

$searchResults = searchBooks(
    $_SESSION['Title'] ?? '',
    $_SESSION['Auteur'] ?? '',
    $_SESSION['Editeur'] ?? '',
    $_SESSION['Disponible'] ?? '',
    $page,
    $perPage,
    $pdo,
    20,
    true
);

$nbRes = count(searchBooks(
    $_SESSION['Title'] ?? '',
    $_SESSION['Auteur'] ?? '',
    $_SESSION['Editeur'] ?? '',
    $_SESSION['Disponible'] ?? '',
    $page,
    $perPage,
    $pdo
));


if (empty($searchResults)) {
    $searchErr = "No results found. Please refine your search criteria.";
}


$sql = "SELECT COUNT(*) FROM livre";
$totalBooks = $pdo->query($sql)->fetchColumn();
$totalPages = $nbRes / $perPage;

?>


<?php if (!empty($searchErr)): ?>
    <p><?= $searchErr ?></p>
<?php endif; ?>

<?php if (!empty($searchResults)): ?>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Éditeur</th>
            <th>Date du dernier emprunt</th>
            <th>Disponibilité</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($searchResults as $result): ?>
            <tr>
                <td><?= $result['id'] ?></td>
                <td><?= $result['titre'] ?></td>
                <td><?= $result['auteur'] ?></td>
                <td><?= $result['editeur'] ?></td>
                <td><?= $result['Date_du_dernier_emprunt'] ?></td>
                <td class="<?= $result['emprunt'] !== 0 ? 'non' : 'oui' ?>"><?= $result['emprunt'] !== 0 ? 'non' : 'oui' ?></td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


    <div class="paginationContainer">
        <?php
        // Assuming you want to display 10 buttons at a time
        $buttonsToShow = 10;
        $halfButtonsToShow = floor($buttonsToShow / 2);

        // Calculate the starting and ending points for the buttons
        $start = max(1, (int)$page - $buttonsToShow + 2);
        $end = min($start + $buttonsToShow - 1, (int)$totalPages);

        // If there are not enough buttons to fill $buttonsToShow, adjust the starting point
        $start = max(1, $end - $buttonsToShow + 1);

        // Add a "Previous" button
        if ($page > 1) {
            echo '<a class="pagination" href="livre.php?page=' . ((int)$page - 1) . '">Previous</a>';
        }

        // Display the numbered buttons
        for ($i = $start; $i <= $end; $i++) {
            $activeClass = ($i === (int)$page) ? 'active' : '';
            echo '<a class="pagination ' . $activeClass . '" href="livre.php?page=' . $i . '">' . $i . '</a>';
        }

        // Add a "Next" button
        if ($page < $totalPages) {
            echo '<a class="pagination" href="livre.php?page=' . ((int)$page + 1) . '">Next</a>';
        }
        ?>
    </div>
<?php endif; ?>
<?php
include "footer.php";
?>




