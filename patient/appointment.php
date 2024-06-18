<?php
session_start();

require ('../connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">

    <title>My Bookings</title>
    <style>
        .dashbord-tables {
            animation: transitionIn-Y-over 0.5s;
        }

        .filter-container {
            animation: transitionIn-Y-bottom 0.5s;
        }

        .sub-table,
        .anime {
            animation: transitionIn-Y-bottom 0.5s;
        }

        /*  */
        .custom-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff7f0e;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .custom-link:hover {
            background-color: #e65c00;
        }

        /*  */
    </style>
</head>

<body>
    <?php
    if (!isset($_SESSION['id'])) {
        header("location: ../login.php");
    } else {
        if ($_SESSION['account_type'] == '1' && $_SESSION['account_status'] == '1') {

            ?>
            <div class="container">

                <!-- START SECTION MENU -->
                <div class="menu">
                    <?php require ('dashboard.php') ?>
                </div>
                <!-- END SECTION MENU -->

                <!-- START SECTION Dash Body -->

                <?php
                $session_patient_id = $_SESSION['id'];
                $sqlmain = "SELECT appointments.id, appointments.session_date_id, appointments.patient_id, appointments.appointment_status, appointments.date, appointments.meeting_link, session_dates.id AS 'session_id', session_dates.title, session_dates.time, session_dates.doctor_id, doctors.id AS 'doctor_id', doctors.name, doctors.email, doctors.session_fee FROM appointments INNER JOIN session_dates ON appointments.session_date_id = session_dates.id INNER JOIN doctors ON appointments.doctor_id = doctors.id WHERE patient_id = $session_patient_id AND appointments.appointment_status != 3";

                if ($_POST) {
                    if (!empty($_POST["date"])) {
                        $date = $_POST["date"];
                        $sqlmain .= " AND date='$date'";
                    }

                }

                $sqlmain .= " ORDER BY appointments.date, session_dates.time ASC";
                $result = $conn->query($sqlmain);
                ?>


                <div class="dash-body" style="margin-top: 15px">
                    <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                        <tr>
                            <td width="13%">
                                <a href="appointment.php"><button class="login-btn btn-primary-soft btn btn-icon-back"
                                        style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                                        <font class="tn-in-text">Back</font>
                                    </button></a>
                            </td>

                            <td>
                                <p style="font-size: 23px;padding-left:12px;font-weight: 600;">My Bookings history</p>
                            </td>

                            <td width="15%">
                                <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                                    Today's Date
                                </p>
                                <p class="heading-sub12" style="padding: 0;margin: 0;">
                                    <?php
                                    date_default_timezone_set('Asia/Riyadh');
                                    $today = date('Y-m-d');
                                    echo $today;

                                    $doctorrow = $conn->query('select * from doctors WHERE account_status=1');
                                    ?>
                                </p>
                            </td>

                            <td width="10%">
                                <button class="btn-label"
                                    style="display: flex;justify-content: center;align-items: center;"><img
                                        src="../img/calendar.svg" width="100%"></button>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" style="padding-top:10px;width: 100%;">
                                <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">My
                                    Bookings
                                    (<?php echo $result->num_rows; ?>)</p>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" style="padding-top:0px;width: 100%;">
                                <center>
                                    <table class="filter-container" border="0">
                                        <tr>
                                            <td width="10%">

                                            </td>
                                            <td width="5%" style="text-align: center;">
                                                Date:
                                            </td>
                                            <td width="30%">
                                                <form action="" method="post">
                                                    <input type="date" name="date" id="date"
                                                        class="input-text filter-container-items" style="margin: 0;width: 95%;">
                                            </td>
                                            <td width="12%">
                                                <input type="submit" name="filter" value=" Filter"
                                                    class=" btn-primary-soft btn button-icon btn-filter"
                                                    style="padding: 15px; margin :0;width:100%">
                                                </form>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>

                        <!-- START SECTION DATA DUSPLAY TABLE -->
                        <tr>
                            <td colspan="4">
                                <center>
                                    <div class="abc scroll">
                                        <table width="93%" class="sub-table scrolldown" border="0" style="border:none">
                                            <tbody>
                                                <?php
                                                if ($result->num_rows == 0) {
                                                    ?>
                                                    <tr>
                                                        <td colspan="7">
                                                            <br><br><br><br>
                                                            <center>
                                                                <img src="../img/notfound.svg" width="25%">
                                                                <br>
                                                                <p class="heading-main12"
                                                                    style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">
                                                                    We couldnt find anything related to your keywords !</p>
                                                                <a class="non-style-link" href="appointment.php"><button
                                                                        class="login-btn btn-primary-soft btn"
                                                                        style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp;
                                                                        Show all Appointments &nbsp;</font></button>
                                                                </a>
                                                            </center>
                                                            <br><br><br><br>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <?php
                                                    while ($row = $result->fetch_assoc()) {
                                                        $id = $row['id'];
                                                        $session_date_id = $row['session_date_id'];
                                                        $patient_id = $row['patient_id'];
                                                        $appointment_status = $row['appointment_status'];
                                                        $date = $row['date'];
                                                        $meeting_link = $row['meeting_link'];

                                                        // session_dates
                                                        $session_id = $row['session_id'];
                                                        $title = $row['title'];
                                                        $time = $row['time'];

                                                        // doctors
                                                        $doctor_id = $row['doctor_id'];
                                                        $name = $row['name'];
                                                        $email = $row['email'];
                                                        $session_fee = $row['session_fee'];
                                                        ?>
                                                        <tr>
                                                            <td style="width: 25%;">
                                                                <div class="dashboard-items search-items">
                                                                    <div style="width:100%;">

                                                                        <div class="h3-search">
                                                                            Booking Date: <?php echo $date; ?><br>
                                                                            Booking Time: <?php

                                                                            $time_12hr = date("h:i A", strtotime($time));
                                                                            echo $time_12hr;
                                                                            ?>
                                                                        </div>

                                                                        <div class="h1-search">
                                                                            Title: <?php echo substr($title, 0, 21) ?><br>
                                                                        </div>

                                                                        <div class="h3-search">
                                                                            Doctor Name: <?php echo $name; ?><br>
                                                                            Doctor Email: <?php echo $email; ?><br>
                                                                        </div>

                                                                        <div class="h4-search">
                                                                            Session Fee: <b><u><?php echo $session_fee; ?></u></b><br>
                                                                        </div><br>

                                                                        <div class="h4-search">
                                                                            Meeting Link:
                                                                            <?php
                                                                            if ($appointment_status == 1) {
                                                                                ?>
                                                                                <b style="color: #2A19E9;">Sorry, no meeting link has been
                                                                                    created
                                                                                    yet</b>
                                                                                <?php
                                                                            } elseif ($appointment_status == 2) {
                                                                                ?>
                                                                                &nbsp;<a href="<?php echo $meeting_link; ?>"
                                                                                    class="custom-link" target="_blank">Click
                                                                                    here</a>
                                                                                <?php
                                                                            } elseif ($appointment_status == 3) {
                                                                                ?>
                                                                                <b style="color: #2A19E9;">The meeting has ended</b>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </div><br>

                                                                        <a
                                                                            href="?action=drop&id=<?php echo $row['id']; ?>&name=<?php echo $name; ?>&title=<?php echo $title; ?>"><button
                                                                                class="login-btn btn-primary-soft btn "
                                                                                style="padding-top:11px;padding-bottom:11px;width:100%">
                                                                                <font class="tn-in-text">Cancel Booking</font>
                                                                            </button></a>

                                                                        <div style="width:100%;">
                                                                        </div>

                                                                    </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </center>
                            </td>
                        </tr>
                        <!-- END SECTION DATA DUSPLAY TABLE -->

                    </table>
                </div>
                <!-- END SECTION Dash Body -->
            </div>

            <!-- START SECTION $_GET -->

            <?php
            if ($_GET) {
                $id = $_GET["id"];
                $action = $_GET["action"];

                if ($action == 'booking-added') {
                    $date = $_GET["date"];
                    $time = $_GET["time"];

                    ?>
                    <div id="popup1" class="overlay">
                        <div class="popup">
                            <center>
                                <br><br>
                                <h2>Booking Successfully.</h2>
                                <a class="close" href="appointment.php">&times;</a>
                                <div class="content">
                                    Your appointment date is: <?php echo $date; ?> - <?php
                                        $time_12hr = date("h:i A", strtotime($time));
                                        echo $time_12hr;
                                        ?><br><br>
                                </div>
                                <div style="display: flex;justify-content: center;">
                                    <a href="appointment.php" class="non-style-link"><button class="btn-primary btn"
                                            style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                            <font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font>
                                        </button></a>
                                    <br><br><br><br>
                                </div>
                            </center>
                        </div>
                    </div>
                    <?php
                } elseif ($action == "drop") {
                    $title = $_GET["title"];
                    $docname = $_GET["name"];
                    ?>
                    <div id="popup1" class="overlay">
                        <div class="popup">
                            <center>
                                <h2>Are you sure?</h2>

                                <a class="close" href="appointment.php">&times;</a>

                                <div class="content">
                                    You want to Cancel this Appointment?<br><br>
                                    Session Title: &nbsp;<b><?php echo substr($title, 0, 40) ?></b><br>
                                    Doctor name&nbsp; : <b><?php echo substr($name, 0, 40) ?></b><br><br>
                                </div>

                                <div style="display: flex;justify-content: center;">
                                    <a href="delete-appointment.php?id=<?php echo $id; ?>" class="non-style-link"><button
                                            class="btn-primary btn"
                                            style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"
                                            <font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                                    <a href="appointment.php" class="non-style-link"><button class="btn-primary btn"
                                            style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                            <font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font>
                                        </button></a>
                                </div>
                            </center>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>

            <!-- END SECTION $_GET -->


            <?php
        } else {
            header("location: ../login.php");
        }
    }
    ?>
</body>

</html>