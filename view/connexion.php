<?php
global $searchResults, $pdo;
include "../debug/debug.php";
include "../requests/AbonneRequest.php";
$pageTitle = 'Connexion';
include "header.php";
?>

<div class="connexion-container">
    <div class="mainLogin">
        <div class="login-container">
            <h2>User Login</h2>
            <?php
            if (isset($_GET['message'])) {
                echo '<p class="message">' . $_GET['message'] . '</p>';
            }
            ?>
            <form class="login-form" action="../requests/connexion.php" method="post">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" placeholder="email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password" required>

                <input type="submit" value="Login">
                <a href="../rights/session_delete.php" >Logout</a>
            </form>
        </div>
    </div>

   <!-- <div class="mainRegister">
        <div class="registration-container">
            <h2>User Registration</h2>
            <?php
/*            if (isset($_GET['message'])) {
                echo '<p class="message">' . $_GET['message'] . '</p>';
            }
            */?>
            <form class="registration-form" action="" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Username" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password" required>

                <input type="submit" value="Register">
            </form>
        </div>
    </div>-->
</div>
