<?php
session_start();

include ("../connection.php");

if (isset($_SESSION['id'])) {
    if ($_SESSION['account_type'] == '3') {


        if ($_POST) {

            $name = $email = $password = $address = $mobile_number = $start_date = $availability_hours = $number_of_allowed_sessions_per_day = $session_fee = $specialtie_id = '';

            if (isset($_POST['submit'])) {
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
                if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                    $error = '6';
                } elseif (!preg_match('/^05/', $mobile_number)) {
                    $error = '5';
                } else {
                    require ('../connection.php');

                    $email_check_query_patients = "SELECT * FROM patients WHERE email='$email' LIMIT 1";
                    $email_result_patients = $conn->query($email_check_query_patients);

                    $email_check_query_doctors = "SELECT * FROM doctors WHERE email='$email' LIMIT 1";
                    $email_result_doctors = $conn->query($email_check_query_doctors);

                    $email_check_query_admins = "SELECT * FROM admins WHERE email='$email' LIMIT 1";
                    $email_result_admins = $conn->query($email_check_query_admins);

                    if (($email_result_patients->num_rows > 0) || ($email_result_doctors->num_rows > 0) || ($email_result_admins->num_rows > 0)) {
                        $error = '1';
                    } else {
                        $sql = "INSERT INTO doctors (name, email, password, address, mobile_number, start_date, availability_hours, number_of_allowed_sessions_per_day, session_fee, specialtie_id) VALUES ('$name', '$email', '$password', '$address', '$mobile_number', '$start_date', '$availability_hours', '$number_of_allowed_sessions_per_day', '$session_fee', '$specialtie_id')";
                        if ($conn->query($sql) === TRUE) {
                            $error = '0';
                        } else {
                            $error = '3';
                        }
                    }
                    $conn->close();

                }
            } else {
                $error = '2';
            }
        }

        header("location: doctors.php?action=add&error=" . $error);
        exit();

    }
}
header("location: ../login.php");

?>