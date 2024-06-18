<?php
session_start();

include ("../connection.php");

if (isset($_SESSION['id'])) {
    if ($_SESSION['account_type'] == '1') {

        if ($_POST) {

            $name = $email = $password = $address = $mobile_number = '';

            if (isset($_POST['submit'])) {
                // $id = !empty($_POST['id']) ? $_POST['id'] : null;
                $id = !empty($_SESSION['id']) ? $_SESSION['id'] : null;
                $name = !empty($_POST['name']) ? $_POST['name'] : null;
                $email = !empty($_POST['email']) ? $_POST['email'] : null;
                $password = !empty($_POST['password']) ? $_POST['password'] : null;
                $address = !empty($_POST['address']) ? $_POST['address'] : null;
                $mobile_number = !empty($_POST['mobile_number']) ? $_POST['mobile_number'] : null;
            }

            if (!empty($name) && !empty($email) && !empty($password) && !empty($address) && !empty($mobile_number)) {
                require ('../connection.php');

                $email_check_query = "SELECT * FROM patients WHERE email='$email' AND id!='$id' LIMIT 1";

                $email_result = $conn->query($email_check_query);

                if ($email_result->num_rows > 0) {
                    $error = '1';
                } else {
                    $sql = "UPDATE patients SET name='$name', email='$email', password='$password', address='$address', mobile_number='$mobile_number' WHERE id='$id'";
                    if ($conn->query($sql) === TRUE) {
                        $error = '0';
                    } else {
                        $error = '3';
                    }
                }
                $conn->close();
            } else {
                $error = '2';
            }
        }

        header("location: settings.php?action=edit&error=" . $error);
        exit();
    }
}
header("location: ../login.php");

?>