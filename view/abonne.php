<?php
global $searchResults, $pdo;
include "../debug/debug.php";
include "../requests/AbonneRequest.php";
$pageTitle = 'Gestion de bibliothèque abonné';
include "header.php";
include "../rights/rights.php";
/*session_start();*/
?>

<form action="#" method="post">

    <div class="test">
        <input type="text" name="searchNom" id="searchNom" value="<?php echo $_SESSION['searchNom'] ?? ''; ?>"
               placeholder="Nom">

        <input type="text" name="searchPrenom" id="searchPrenom" value="<?php echo $_SESSION['searchPrenom'] ?? ''; ?>"
               placeholder="Prénom">

        <input type="text" name="searchVille" id="searchVille" value="<?php echo $_SESSION['searchVille'] ?? ''; ?>"
               placeholder="Ville">

        <label for="abonneOUexpire"></label>
        <select id="abonneOUexpire" name="SearchabonneOUexpire">
            <option value="all" <?php echo ($_SESSION['searchFilter'] ?? '') === 'all' ? 'selected' : ''; ?>>Tout
            </option>
            <option value="abonne" <?php echo ($_SESSION['searchFilter'] ?? '') === 'abonne' ? 'selected' : ''; ?>>
                Abonné
            </option>
            <option value="expire" <?php echo ($_SESSION['searchFilter'] ?? '') === 'expire' ? 'selected' : ''; ?>>
                Expiré
            </option>
        </select>

        <input type="submit" name="searchButton" value="Rechercher">
        <button id="clearStorage" class="clearStorageBtn" name="clearStorage">Effacer la recherche</button>
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

$searchResults = searchAbonnes(
    $_SESSION['Nom'] ?? '',
    $_SESSION['Prenom'] ?? '',
    $_SESSION['Ville'] ?? '',
    $_SESSION['abonneOUexpire'] ?? '',
    $page,
    $perPage,
    $pdo,
    20,
    true
);

$nbRes = count(searchAbonnes(
    $_SESSION['Nom'] ?? '',
    $_SESSION['Prenom'] ?? '',
    $_SESSION['Ville'] ?? '',
    $_SESSION['abonneOUexpire'] ?? '',
    $page,
    $perPage,
    $pdo
));


if (empty($searchResults)) {
    $searchErr = "No results found. Please refine your search criteria.";
}


$sql = "SELECT COUNT(*) FROM abonne";
$totalAbonne = $pdo->query($sql)->fetchColumn();
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
            <th>Nom</th>
            <th>Prenom</th>
            <th>Ville</th>
            <th>Date de naissance</th>
            <th>Date fin abonnement</th>
            <th>Abonné ou expiré</th>
            <th>Voir fiche</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($searchResults as $result): ?>
            <?php
            $abonnementStatus = $result['date_fin_abo'] >= date("Y-m-d H:i:s") ? 'Abonné' : 'Expire';
            ?>
            <tr>
                <td><?= $result['id'] ?></td>
                <td><?= $result['nom'] ?></td>
                <td><?= $result['prenom'] ?></td>
                <td><?= $result['ville'] ?></td>
                <td><?= $result['date_naissance'] ?></td>
                <td><?= $result['date_fin_abo'] ?></td>
                <td class="<?= $abonnementStatus === 'Abonné' ? 'abonne-green' : 'abonne-red' ?>"><?= $abonnementStatus ?></td>
                <td><a href="#" class="voirFicheLink" data-user-id="<?= $result['id'] ?>">Voir fiche</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="paginationContainer">
        <?php
        // Check if the "Show All" button is clicked
        $showAll = isset($_GET['showAll']) && $_GET['showAll'] == 1;

        // Toggle between "Show All" and "Hide All" buttons
        $toggleButtonText = $showAll ? 'Hide All' : 'Show All';
        $toggleAction = $showAll ? '0' : '1';

        // Determine the class based on the showAll state
        $buttonClass = $showAll ? 'hide-all' : 'show-all';

        // Display the "Show All" or "Hide All" button with the determined class
        echo '<a class="pagination ' . $buttonClass . '" href="abonne.php?showAll=' . $toggleAction . '">' . $toggleButtonText . '</a>';

        // Assuming you want to display 10 buttons at a time
        $buttonsToShow = 10;

        // Display the numbered buttons
        if (!$showAll) {
            // Calculate the starting and ending points for the buttons
            $start = max(1, (int)$page - $buttonsToShow + 2);
            $end = min($start + $buttonsToShow - 1, (int)$totalPages);

            // If there are not enough buttons to fill $buttonsToShow, adjust the starting point
            $start = max(1, $end - $buttonsToShow + 1);

            // Add a "Previous" button
            if ($page > 1) {
                echo '<a class="pagination" href="abonne.php?page=' . ((int)$page - 1) . '">Previous</a>';
            }

            // Display the numbered buttons
            for ($i = $start; $i <= $end; $i++) {
                $activeClass = ($i === (int)$page) ? 'active' : '';
                echo '<a class="pagination ' . $activeClass . '" href="abonne.php?page=' . $i . '">' . $i . '</a>';
            }

            // Add a "Next" button
            if ($page < $totalPages) {
                echo '<a class="pagination" href="abonne.php?page=' . ((int)$page + 1) . '">Next</a>';
            }
        } else {
            // Display all pages without pagination
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a class="pagination" href="abonne.php?page=' . $i . '">' . $i . '</a>';
            }
        }
        ?>

    </div>

<?php endif; ?>


<script src="UpdateAbonneDetails.js"></script>
<script src="../js/SearchAbonneStorage.js"></script>
<?php
include "footer.php";
?>






