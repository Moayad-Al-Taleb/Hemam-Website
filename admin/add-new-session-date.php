<?php
session_start();

include ("../connection.php"); // Include the connection file

if (isset($_SESSION['id'])) {
    if ($_SESSION['account_type'] == '3') {

        if ($_POST) { // Check if the form is submitted

            $doctor_id = $title = $time = ''; // Initialize variables

            if (isset($_POST['submit'])) { // Check if the form submit button is clicked
                $doctor_id = !empty($_POST['doctor_id']) ? $_POST['doctor_id'] : null; // Get doctor ID from form data
                $title = !empty($_POST['title']) ? $_POST['title'] : null; // Get title from form data
                $time = !empty($_POST['time']) ? $_POST['time'] : null; // Get time from form data
            }

            if (!empty($doctor_id) && !empty($title) && !empty($time)) { // Check if all required fields are not empty
                require ('../connection.php'); // Include the connection file again

                // Query to get doctor's information
                $doctors_query = "SELECT doctors.start_date, doctors.availability_hours ,doctors.number_of_allowed_sessions_per_day FROM doctors WHERE id='$doctor_id' LIMIT 1";
                $doctors_result = $conn->query($doctors_query);
                $doctors_row = $doctors_result->fetch_assoc();
                $number_of_allowed_sessions_per_day = $doctors_row['number_of_allowed_sessions_per_day'];
                $start_date = $doctors_row['start_date'];
                $availability_hours = $doctors_row['availability_hours'];

                // Query to count existing sessions for the doctor
                $number_sessiones_query = "SELECT COUNT(*) AS 'COUNT' FROM session_dates WHERE doctor_id='$doctor_id'";
                $number_sessiones_result = $conn->query($number_sessiones_query);
                $number_sessiones_row = $number_sessiones_result->fetch_assoc();
                $count = $number_sessiones_row['COUNT'];

                $start_date = DateTime::createFromFormat('H:i:s', $start_date); // Convert start date to DateTime object
                $end_date = clone $start_date; // Clone start date for end date calculation
                $end_date->modify("+$availability_hours hours"); // Calculate end date based on availability hours
                $session_time = DateTime::createFromFormat('H:i', $time); // Convert session time to DateTime object

                // Query to check if session time already exists
                $time_check_query = "SELECT * FROM session_dates WHERE session_dates.time LIKE '$time%' AND doctor_id='$doctor_id' LIMIT 1";
                $time_result = $conn->query($time_check_query);

                if ($count >= $number_of_allowed_sessions_per_day) { // Check if the allowed session limit is reached
                    $error = 1;
                } elseif ($time_result->num_rows > 0) { // Check if the session time already exists
                    $error = 2;
                } elseif (!($session_time >= $start_date && $session_time <= $end_date)) { // Check if session time is within doctor's availability
                    $error = 3;
                } else {
                    // Insert session into database
                    $sql = "INSERT INTO session_dates (doctor_id, title, time) VALUES ('$doctor_id', '$title', '$time')";
                    if ($conn->query($sql) === TRUE) {
                        $error = '0'; // Success
                    } else {
                        $error = '5'; // Error in SQL execution
                    }
                }

            } else {
                $error = '6'; // Required fields are empty
            }
        }

        // Redirect to session-dates.php with error code
        header("location: session-dates.php?action=add&error=" . $error);
        exit();
    }
}
header("location: ../login.php");

?>