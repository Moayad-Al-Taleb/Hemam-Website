<?php
session_start();

if (isset($_SESSION['id'])) {
    if ($_SESSION['account_type'] == '3') {
        if ($_GET) {
            include ("../connection.php");

            $id = $_GET["id"];

            $conn->query("DELETE FROM session_dates WHERE id='$id'");

        }
    }
}
header("location: session-dates.php");

?>