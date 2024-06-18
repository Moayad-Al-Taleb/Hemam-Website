<?php
session_start();

// Including the connection file
include ("../connection.php");

if (isset($_SESSION['id'])) {
    if ($_SESSION['account_type'] == '3') {

        // Checking if the form is submitted
        if ($_POST) {

            $doctor_id = $title = $time = '';

            // Checking if the form is submitted via 'submit' button
            if (isset($_POST['submit'])) {
                $id = !empty($_POST['id']) ? $_POST['id'] : null;
                $doctor_id = !empty($_POST['doctor_id']) ? $_POST['doctor_id'] : null;
                $title = !empty($_POST['title']) ? $_POST['title'] : null;
                // $time = !empty($_POST['time']) ? $_POST['time'] : null;
                $time = !empty($_POST['time']) ? substr($_POST['time'], 0, 5) : null;
            }

            // Checking if all necessary fields are filled
            if (!empty($doctor_id) && !empty($title) && !empty($time)) {
                require ('../connection.php');

                // Fetching doctor's information from the database
                $doctors_query = "SELECT doctors.start_date, doctors.availability_hours ,doctors.number_of_allowed_sessions_per_day FROM doctors WHERE id='$doctor_id' LIMIT 1";
                $doctors_result = $conn->query($doctors_query);
                $doctors_row = $doctors_result->fetch_assoc();
                $number_of_allowed_sessions_per_day = $doctors_row['number_of_allowed_sessions_per_day'];
                $start_date = $doctors_row['start_date'];
                $availability_hours = $doctors_row['availability_hours'];

                // Fetching session information from the database
                $sqlmain = "select * from session_dates where id='$id'";
                $result = $conn->query($sqlmain);
                $row = $result->fetch_assoc();

                if ($row['doctor_id'] == $doctor_id) {
                    $number_sessiones_query = "SELECT COUNT(*) AS 'COUNT' FROM session_dates WHERE doctor_id='$doctor_id'";
                    $number_sessiones_result = $conn->query($number_sessiones_query);
                    $number_sessiones_row = $number_sessiones_result->fetch_assoc();
                    $count = $number_sessiones_row['COUNT'];
                    $count--;
                } else {
                    $number_sessiones_query = "SELECT COUNT(*) AS 'COUNT' FROM session_dates WHERE doctor_id='$doctor_id'";
                    $number_sessiones_result = $conn->query($number_sessiones_query);
                    $number_sessiones_row = $number_sessiones_result->fetch_assoc();
                    $count = $number_sessiones_row['COUNT'];
                }

                $start_date = DateTime::createFromFormat('H:i:s', $start_date);
                $end_date = clone $start_date;
                $end_date->modify("+$availability_hours hours");
                $session_time = DateTime::createFromFormat('H:i', $time);

                // Checking if the session time is available
                $time_check_query = "SELECT * FROM session_dates WHERE session_dates.time LIKE '$time%' AND doctor_id='$doctor_id' AND id!='$id' LIMIT 1";
                $time_result = $conn->query($time_check_query);

                // Checking for errors
                if ($count >= $number_of_allowed_sessions_per_day) {
                    $error = 1;
                } elseif ($time_result->num_rows > 0) {
                    $error = 2;
                } elseif (!($session_time >= $start_date && $session_time <= $end_date)) {
                    $error = 3;
                } else {
                    // Updating session information
                    $sql = "UPDATE session_dates SET doctor_id='$doctor_id', title='$title', time='$time' WHERE id='$id'";
                    if ($conn->query($sql) === TRUE) {
                        $error = '0';
                    } else {
                        $error = '5';
                    }
                }

            } else {
                $error = '6';
            }
        }

        // Redirecting with error message
        header("location: session-dates.php?action=edit&id=" . $id . "&error=" . $error);
        exit();

    }
}
header("location: ../login.php");

?>