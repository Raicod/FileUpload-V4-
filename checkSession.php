<?php
    require_once("database.php");

    if(!$_SESSION['role'] || $_SESSION['role'] === 'viewer'){
        header("Location: Login.php");
    }
?>