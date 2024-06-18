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

    <title>Dashboard</title>
    <style>
        .dashbord-tables {
            animation: transitionIn-Y-over 0.5s;
        }

        .filter-container {
            animation: transitionIn-Y-bottom 0.5s;
        }

        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>

<body>
    <?php
    if (!isset($_SESSION['id'])) {
        header("location: ../login.php");
    } else {
        if ($_SESSION['account_type'] == '3') {

            ?>
            <div class="container">

                <!-- START SECTION MENU -->
                <div class="menu">
                    <?php require ('dashboard.php') ?>
                </div>
                <!-- END SECTION MENU -->

                <!-- START SECTION Dash Body -->
                <div class="dash-body" style="margin-top: 15px">
                    <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;">
                        <tr>
                            <td colspan="2" class="nav-bar">
                                <form action="doctors.php" method="post" class="header-search">
                                    <input type="search" name="search" class="input-text header-searchbar"
                                        placeholder="Search Doctor name or Email">&nbsp;&nbsp;
                                    <input type="Submit" value="Search" class="login-btn btn-primary-soft btn"
                                        style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                                </form>
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
                                    $patientrow = $conn->query('select * from patients');
                                    $doctorrow = $conn->query('select * from doctors');
                                    $sessiondaterow = $conn->query('select * from session_dates');
                                    $appointmentrow = $conn->query("select * from appointments WHERE date = '$today'");
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
                            <td colspan="4">
                                <center>
                                    <table class="filter-container" style="border: none;" border="0">
                                        <tr>
                                            <td colspan="4">
                                                <p style="font-size: 20px;font-weight:600;padding-left: 12px;">Status</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">
                                                <div class="dashboard-items"
                                                    style="padding:20px;margin:auto;width:95%;display: flex">
                                                    <div>
                                                        <div class="h1-dashboard">
                                                            <?php echo $doctorrow->num_rows ?>
                                                        </div><br>
                                                        <div class="h3-dashboard">
                                                            Doctors &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="btn-icon-back dashboard-icons"
                                                        style="background-image: url('../img/icons/doctors-hover.svg');"></div>
                                                </div>
                                            </td>
                                            <td style="width: 25%;">
                                                <div class="dashboard-items"
                                                    style="padding:20px;margin:auto;width:95%;display: flex;">
                                                    <div>
                                                        <div class="h1-dashboard">
                                                            <?php echo $patientrow->num_rows ?>
                                                        </div><br>
                                                        <div class="h3-dashboard">
                                                            Patients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="btn-icon-back dashboard-icons"
                                                        style="background-image: url('../img/icons/patients-hover.svg');"></div>
                                                </div>
                                            </td>
                                            <td style="width: 25%;">
                                                <div class="dashboard-items"
                                                    style="padding:20px;margin:auto;width:95%;display: flex;">
                                                    <div>
                                                        <div class="h1-dashboard">
                                                            <?php echo $sessiondaterow->num_rows ?>
                                                        </div><br>
                                                        <div class="h3-dashboard">
                                                            Session Dates &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="btn-icon-back dashboard-icons"
                                                        style="background-image: url('../img/icons/schedule-hover.svg');"></div>
                                                </div>
                                            </td>

                                            <td style="width: 25%;">
                                                <div class="dashboard-items"
                                                    style="padding:20px;margin:auto;width:95%;display: flex;">
                                                    <div>
                                                        <div class="h1-dashboard">
                                                            <?php echo $appointmentrow->num_rows ?>
                                                        </div><br>
                                                        <div class="h3-dashboard">
                                                            Today Sessions &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="btn-icon-back dashboard-icons"
                                                        style="background-image: url('../img/icons/session-iceblue.svg');">
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4">
                                <table width="100%" border="0" class="dashbord-tables">
                                    <tr>
                                        <td>
                                            <p
                                                style="padding:10px;padding-left:48px;padding-bottom:0;font-size:23px;font-weight:700;color:var(--primarycolor);">
                                                Upcoming Appointments until Next <?php
                                                echo date("l", strtotime("+1 week"));
                                                ?>
                                            </p>
                                            <p
                                                style="padding-bottom:19px;padding-left:50px;font-size:15px;font-weight:500;color:#212529e3;line-height: 20px;">
                                                Here's Quick access to Upcoming Appointments until 7 days<br>
                                                More details available in @Appointment section.
                                            </p>

                                        </td>

                                        <td>
                                            <p
                                                style="text-align:right;padding:10px;padding-right:48px;padding-bottom:0;font-size:23px;font-weight:700;color:var(--primarycolor);">
                                                Sessions
                                            </p>
                                            <p
                                                style="padding-bottom:19px;text-align:right;padding-right:50px;font-size:15px;font-weight:500;color:#212529e3;line-height: 20px;">
                                                Here's Quick access to Sessions<br>
                                            </p>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td width="50%">
                                            <center>
                                                <div class="abc scroll" style="height: 200px;">
                                                    <table width="85%" class="sub-table scrolldown" border="0">
                                                        <thead>
                                                            <tr>
                                                                <th class="table-headin">Patient name</th>
                                                                <th class="table-headin">Doctor</th>
                                                                <th class="table-headin">Session Title</th>
                                                                <th class="table-headin">Date</th>
                                                                <th class="table-headin">Time</th>
                                                                <th class="table-headin">Meeting Status</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php
                                                            $week_start = date("Y-m-d", strtotime("this week"));
                                                            $week_end = date("Y-m-d", strtotime("this week +6 days"));

                                                            $sqlmain = "SELECT appointments.id, appointments.session_date_id, appointments.patient_id, appointments.appointment_status, appointments.date, appointments.doctor_id, session_dates.id AS 'session_id', session_dates.title, session_dates.time, session_dates.doctor_id, patients.id AS 'patient_id', patients.name AS 'patient_name', patients.email AS 'patient_email', patients.mobile_number AS 'patient_mobile_number', doctors.id AS 'doctor_id', doctors.name AS 'doctor_name' FROM appointments INNER JOIN session_dates ON appointments.session_date_id = session_dates.id INNER JOIN patients ON appointments.patient_id = patients.id INNER JOIN doctors ON appointments.doctor_id = doctors.id WHERE date BETWEEN '$week_start' AND '$week_end' ORDER BY appointments.date, session_dates.time ASC";

                                                            $result = $conn->query($sqlmain);

                                                            if ($result->num_rows == 0) {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="3">
                                                                        <br><br><br><br>
                                                                        <center>
                                                                            <img src="../img/notfound.svg" width="25%">

                                                                            <br>
                                                                            <p class="heading-main12"
                                                                                style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">
                                                                                We couldnt find anything related to your keywords !
                                                                            </p>
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
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $patient_name = $row["patient_name"];
                                                                    $doctor_name = $row["doctor_name"];
                                                                    $title = $row["title"];
                                                                    $date = $row["date"];
                                                                    $time = $row["time"];

                                                                    $appointment_status = $row['appointment_status'];
                                                                    ?>
                                                                    <tr>
                                                                        <td style="font-weight:600;"> &nbsp;
                                                                            <?php echo $patient_name; ?>
                                                                        </td>

                                                                        <td style="font-weight:600;"> &nbsp;
                                                                            <?php echo $doctor_name; ?>
                                                                        </td>

                                                                        <td style="font-weight:600;"> &nbsp;
                                                                            <?php echo $title; ?>
                                                                        </td>

                                                                        <td style="font-weight:600;"> &nbsp;
                                                                            <?php echo $date; ?>
                                                                        </td>

                                                                        <td style="font-weight:600;"> &nbsp;
                                                                            <?php
                                                                            $time_12hr = date("h:i A", strtotime($time));
                                                                            echo $time_12hr;
                                                                            ?>
                                                                        </td>

                                                                        <?php
                                                                        if ($appointment_status == 1) {
                                                                            ?>
                                                                            <td><b style="color: #2A19E9;">Sorry, no meeting link has been
                                                                                    created
                                                                                    yet</b></td>
                                                                            <?php
                                                                        } elseif ($appointment_status == 2) {
                                                                            ?>
                                                                            <td><b style="color: #2A19E9;">The meeting has been created</b>
                                                                            </td>
                                                                            <?php
                                                                        } elseif ($appointment_status == 3) {
                                                                            ?>
                                                                            <td><b style="color: #2A19E9;">The meeting has ended</b></td>
                                                                            <?php
                                                                        }
                                                                        ?>

                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }

                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </center>
                                        </td>

                                        <td width="50%" style="padding: 0;">
                                            <center>
                                                <div class="abc scroll" style="height: 200px;padding: 0;margin: 0;">
                                                    <table width="85%" class="sub-table scrolldown" border="0">
                                                        <thead>
                                                            <tr>
                                                                <th class="table-headin">
                                                                    Session Title
                                                                </th>
                                                                <th class="table-headin">
                                                                    Doctor
                                                                </th>
                                                                <th class="table-headin">
                                                                    Session Time
                                                                </th>
                                                                <th class="table-headin">
                                                                    Session Fee
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $sqlmain = "SELECT session_dates.id, session_dates.doctor_id, session_dates.title, session_dates.time, session_dates.session_status, doctors.id AS 'doctor_id', doctors.name, doctors.session_fee FROM session_dates INNER JOIN doctors ON session_dates.doctor_id = doctors.id";

                                                            $result = $conn->query($sqlmain);

                                                            if ($result->num_rows == 0) {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="4">
                                                                        <br><br><br><br>
                                                                        <center>
                                                                            <img src="../img/notfound.svg" width="25%">

                                                                            <br>
                                                                            <p class="heading-main12"
                                                                                style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">
                                                                                We couldnt find anything related to your keywords !
                                                                            </p>
                                                                            <a class="non-style-link" href="schedule.php"><button
                                                                                    class="login-btn btn-primary-soft btn"
                                                                                    style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp;
                                                                                    Show all Sessions &nbsp;</font></button>
                                                                            </a>
                                                                        </center>
                                                                        <br><br><br><br>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            } else {
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $title = $row['title'];
                                                                    $name = $row['name'];
                                                                    $time = $row['time'];
                                                                    $session_fee = $row['session_fee'];
                                                                    ?>
                                                                    <tr>
                                                                        <td style="padding:20px;"> &nbsp;
                                                                            <?php echo substr($title, 0, 30) ?>
                                                                        </td>
                                                                        <td><?php echo $name; ?></td>
                                                                        <td><?php
                                                                        $time_12hr = date("h:i A", strtotime($time));
                                                                        echo $time_12hr;
                                                                        ?></td>
                                                                        <td style="text-align:center;"><?php echo $session_fee; ?> $
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }

                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </center>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <center>
                                                <a href="appointment.php" class="non-style-link"><button class="btn-primary btn"
                                                        style="width:85%">Show all Appointments</button></a>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <a href="session-dates.php" class="non-style-link"><button
                                                        class="btn-primary btn" style="width:85%">Show all Sessions</button></a>
                                            </center>
                                        </td>
                                    </tr>

                                </table>
                            </td>
                        </tr>

                    </table>
                </div>
                <!-- END SECTION Dash Body -->
            </div>
            <?php
        } else {
            header("location: ../login.php");
        }
    }
    ?>
</body>

</html>