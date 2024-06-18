<?php

session_start();

// if ($_GET) {
if (isset($_SESSION['id'])) {
    if ($_SESSION['account_type'] == '1' && $_SESSION['account_status'] == '1') {
        include ("../connection.php");

        $id = $_GET['id'];

        $conn->query("DELETE FROM appointments WHERE id='$id'");

    }
}
header("location: appointment.php");
?>