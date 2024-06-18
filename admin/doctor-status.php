<?php
session_start();

if (isset($_SESSION['id'])) {
    if ($_SESSION['account_type'] == '3') {


        if ($_GET) {
            include ("../connection.php");

            $id = $_GET["id"];
            $action = $_GET['action'];

            if ($action == 'freeze') {
                $conn->query("UPDATE doctors SET account_status='2' WHERE id='$id'");
            } elseif ($action == 'activate') {
                $conn->query("UPDATE doctors SET account_status='1' WHERE id='$id'");
            }

        }

        header("location: doctors.php");
        exit();

    }
}
header("location: ../login.php");

?>