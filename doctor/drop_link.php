<?php

session_start();

if (isset($_SESSION['id'])) {
    if ($_SESSION['account_type'] == '2' && $_SESSION['account_status'] == '1') {
        if ($_GET) {
            include ("../connection.php");

            $id = $_GET['id'];

            $conn->query("UPDATE appointments SET appointment_status=3 WHERE id='$id'");

        }
    }
}
header("location: appointment.php");
?>