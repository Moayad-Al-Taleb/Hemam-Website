<?php
session_start();

include ("../connection.php");

if (isset($_SESSION['id'])) {
    if ($_SESSION['account_type'] == '3') {

        if ($_POST) {

            $name = $email = $password = $address = $mobile_number = $start_date = $availability_hours = $number_of_allowed_sessions_per_day = $session_fee = $specialtie_id = '';

            if (isset($_POST['submit'])) {
                $id = !empty($_POST['id']) ? $_POST['id'] : null;
                $name = !empty($_POST['name']) ? $_POST['name'] : null;
                $email = !empty($_POST['email']) ? $_POST['email'] : null;
                $password = !empty($_POST['password']) ? $_POST['password'] : null;
                $address = !empty($_POST['address']) ? $_POST['address'] : null;
                $mobile_number = !empty($_POST['mobile_number']) ? $_POST['mobile_number'] : null;
                $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
                $availability_hours = !empty($_POST['availability_hours']) ? $_POST['availability_hours'] : null;
                $number_of_allowed_sessions_per_day = !empty($_POST['number_of_allowed_sessions_per_day']) ? $_POST['number_of_allowed_sessions_per_day'] : null;
                $session_fee = !empty($_POST['session_fee']) ? $_POST['session_fee'] : null;
                $specialtie_id = !empty($_POST['specialtie_id']) ? $_POST['specialtie_id'] : null;
            }

            if (!empty($name) && !empty($email) && !empty($password) && !empty($address) && !empty($mobile_number) && !empty($start_date) && !empty($availability_hours) && !empty($number_of_allowed_sessions_per_day) && !empty($session_fee) && !empty($specialtie_id)) {
                require ('../connection.php');

                $email_check_query = "SELECT * FROM doctors WHERE email='$email' AND id!='$id' LIMIT 1";

                $email_result = $conn->query($email_check_query);

                if ($email_result->num_rows > 0) {
                    $error = '1';
                } else {
                    $sql = "UPDATE doctors SET name='$name', email='$email', password='$password', address='$address', mobile_number='$mobile_number', start_date='$start_date', availability_hours='$availability_hours', number_of_allowed_sessions_per_day='$number_of_allowed_sessions_per_day', session_fee='$session_fee', specialtie_id='$specialtie_id' WHERE id='$id'";
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

        header("location: doctors.php?action=edit&id=" . $id . "&error=" . $error);
        exit();

    }
}
header("location: ../login.php");

?>