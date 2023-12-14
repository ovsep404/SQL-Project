<?php

/*session_start();*/
var_dump($_SESSION['user_type']);
if ($_SESSION['user_type'] !== 'gestionnaire') {
    header('Location: ../rights/error.php');
    exit();
}