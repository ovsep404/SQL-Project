<?php
if ($_SESSION['user_type'] !== 'gestionnaire') {
    header('Location: ../rights/error.php');
    exit();
}