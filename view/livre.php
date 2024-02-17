<?php
global $searchResults, $pdo;
include "../debug/debug.php";
include "../requests/LivreRequest.php";
$pageTitle = 'Gestion de bibliothèque livre';
include 'header.php';
/*session_start();*/
?>


<form action="#" method="post">

    <div class="test">
        <input type="text" name="searchTitle" value="<?php echo $_SESSION['searchTitle'] ?? ''; ?>" placeholder="Titre"
               id="searchTitle">

        <input type="text" name="searchAuteur" value="<?php echo $_SESSION['searchAuteur'] ?? ''; ?>"
               placeholder="Auteur" id="auteur">

        <input type="text" name="searchEditeur" value="<?php echo $_SESSION['searchEditeur'] ?? ''; ?>"
               placeholder="Éditeur" id="editeur">

        <label for="disponible"></label>
        <select id="disponible" name="searchDisponible">
            <option value="all" <?php echo ($_SESSION['searchDisponible'] ?? '') === 'all' ? 'selected' : ''; ?>>Tout
            </option>
            <option value="disponible" <?php echo ($_SESSION['searchDisponible'] ?? '') === 'disponible' ? 'selected' : ''; ?>>
                Disponible
            </option>
            <option value="nondisponible" <?php echo ($_SESSION['searchDisponible'] ?? '') === 'nondisponible' ? 'selected' : ''; ?>>
                Non disponible
            </option>
        </select>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Enregistrez le changement de sélection dans le stockage local
                document.getElementById('disponible').addEventListener('change', function () {
                    let selectedValue = this.value;
                    localStorage.setItem('searchDisponible', selectedValue);
                });

                // Récupérez la valeur du stockage local et définissez-la comme valeur par défaut
                let storedDisponible = localStorage.getItem('searchDisponible');
                if (storedDisponible !== null) {
                    document.getElementById('disponible').value = storedDisponible;
                }
            });
        </script>

        <input type="submit" name="searchButton" value="Rechercher">
        <button id="clearStorage" class="clearStorageBtn" name="clearStorage">Effacer la recherche</button>
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
        // Check if the "Show All" button is clicked
        $showAll = isset($_GET['showAll']) && $_GET['showAll'] == 1;

        // Toggle between "Show All" and "Hide All" buttons
        $toggleButtonText = $showAll ? 'Hide All' : 'Show All';
        $toggleAction = $showAll ? '0' : '1';

        // Determine the class based on the showAll state
        $buttonClass = $showAll ? 'hide-all' : 'show-all';

        // Display the "Show All" or "Hide All" button
        echo '<a class="pagination ' . $buttonClass . '" href="livre.php?showAll=' . $toggleAction . '">' . $toggleButtonText . '</a>';

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
        } else {
            // Display all pages without pagination
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a class="pagination" href="livre.php?page=' . $i . '">' . $i . '</a>';
            }
        }
        ?>
    </div>

<?php endif; ?>
<script src="../js/SearchLivreStorage.js"></script>
<?php
include "footer.php";
?>




