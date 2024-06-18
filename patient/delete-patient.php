<?php

session_start();

// if ($_GET) {
if (isset($_SESSION['id'])) {
    if ($_SESSION['account_type'] == '1' && $_SESSION['account_status'] == '1') {
        include ("../connection.php");

        $id = $_SESSION['id'];

        $conn->query("DELETE FROM patients WHERE id='$id'");

    }
}
header("location: ../signup.php");
?>