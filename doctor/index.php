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

        /*  */
        .medical-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .medical-button:hover {
            background-color: #0056b3;
        }

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
        if ($_SESSION['account_type'] == '2' && $_SESSION['account_status'] == '1') {

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
                            <td colspan="1" class="nav-bar">
                                <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;"> Dashboard</p>
                            </td>
                            <td width="25%">
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
                                    $patientrow = $conn->query('select * from patients WHERE account_status=1');
                                    $doctorrow = $conn->query('select * from doctors WHERE account_status=1');

                                    $doctor_id = $_SESSION['id'];
                                    $sessiondaterow = $conn->query("select * from session_dates WHERE doctor_id='$doctor_id' AND session_status='1'");

                                    $session_doctor_id = $_SESSION['id'];
                                    $appointmentsrow = $conn->query("SELECT appointments.id, appointments.session_date_id, appointments.patient_id, appointments.appointment_status, appointments.date, appointments.doctor_id, session_dates.id AS 'session_id', session_dates.title, session_dates.time, session_dates.doctor_id, patients.id AS 'patient_id', patients.name, patients.email FROM appointments INNER JOIN session_dates ON appointments.session_date_id = session_dates.id INNER JOIN patients ON appointments.patient_id = patients.id WHERE appointments.doctor_id = $session_doctor_id AND date='$today'");
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
                                    <table class="filter-container doctor-header" style="border: none;width:95%" border="0">
                                        <tr>
                                            <td>
                                                <h3>Welcome!</h3>
                                                <?php
                                                $name = $_SESSION['name'];
                                                ?>
                                                <h1><?php echo $name ?>.</h1>
                                                <p>Thanks for joinnig with us. We are always trying to get you a complete
                                                    service<br>
                                                    You can view your dailly schedule, Reach Patients Appointment at
                                                    home!<br><br>
                                                </p>
                                                <a href="appointment.php" class="non-style-link"><button class="btn-primary btn"
                                                        style="width:30%">View My Appointments</button></a>
                                                <br><br>
                                            </td>
                                        </tr>
                                    </table>
                                </center>

                            </td>
                        </tr>

                        <tr>
                            <td colspan="4">
                                <table border="0" width="100%">
                                    <tr>
                                        <td width="50%">
                                            <center>
                                                <table class="filter-container" style="border: none;" border="0">
                                                    <tr>
                                                        <td colspan="4">
                                                            <p style="font-size: 20px;font-weight:600;padding-left: 12px;">
                                                                Status</p>
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
                                                                        All Doctors &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    </div>
                                                                </div>
                                                                <div class="btn-icon-back dashboard-icons"
                                                                    style="background-image: url('../img/icons/doctors-hover.svg');">
                                                                </div>
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
                                                                        All Patients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    </div>
                                                                </div>
                                                                <div class="btn-icon-back dashboard-icons"
                                                                    style="background-image: url('../img/icons/patients-hover.svg');">
                                                                </div>
                                                            </div>
                                                        </td>

                                                    </tr>

                                                    <tr>
                                                        <td style="width: 25%;">
                                                            <div class="dashboard-items"
                                                                style="padding:20px;margin:auto;width:95%;display: flex; ">
                                                                <div>
                                                                    <div class="h1-dashboard">
                                                                        <?php echo $sessiondaterow->num_rows ?>
                                                                    </div><br>
                                                                    <div class="h3-dashboard">
                                                                        My Session Dates &nbsp;&nbsp;
                                                                    </div>
                                                                </div>
                                                                <div class="btn-icon-back dashboard-icons"
                                                                    style="margin-left: 0px;background-image: url('../img/icons/schedule-hover.svg');">
                                                                </div>
                                                            </div>

                                                        </td>

                                                        <td style="width: 25%;">
                                                            <div class="dashboard-items"
                                                                style="padding:20px;margin:auto;width:95%;display: flex;padding-top:21px;padding-bottom:21px;">
                                                                <div>
                                                                    <div class="h1-dashboard">
                                                                        <?php echo $appointmentsrow->num_rows ?>
                                                                    </div><br>
                                                                    <div class="h3-dashboard" style="font-size: 15px">
                                                                        Today Sessions
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

                                        <td width="50%">
                                            <p id="anim" style="font-size: 20px;font-weight:600;padding-left: 40px;">Your Up
                                                Coming Sessions
                                                until Next week</p>

                                            <center>
                                                <div class="abc scroll" style="height: 250px;padding: 0;margin: 0;">

                                                    <table width="85%" class="sub-table scrolldown" border="0">
                                                        <thead>
                                                            <tr>
                                                                <th class="table-headin">Title</th>
                                                                <th class="table-headin">Patient Name</th>
                                                                <th class="table-headin">Date & Time</th>
                                                                <th class="table-headin">Meeting Link</th>
                                                                <th class="table-headin">Events</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php
                                                            $session_doctor_id = $_SESSION['id'];

                                                            // الحصول على تواريخ بداية ونهاية الأسبوع الحالي
                                                            $week_start = date("Y-m-d", strtotime("this week"));
                                                            $week_end = date("Y-m-d", strtotime("this week +6 days"));

                                                            $sqlmain = "SELECT appointments.id, appointments.session_date_id, appointments.patient_id, appointments.appointment_status, appointments.date, appointments.doctor_id, appointments.meeting_link, session_dates.id AS 'session_id', session_dates.title, session_dates.time, session_dates.doctor_id, patients.id AS 'patient_id', patients.name, patients.email FROM appointments INNER JOIN session_dates ON appointments.session_date_id = session_dates.id INNER JOIN patients ON appointments.patient_id = patients.id WHERE appointments.doctor_id = $session_doctor_id AND date BETWEEN '$week_start' AND '$week_end' ORDER BY appointments.date, session_dates.time ASC";

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
                                                                            <a class="non-style-link" href="appointment.php"><button
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
                                                                    $appointment_status = $row['appointment_status'];
                                                                    $meeting_link = $row['meeting_link'];
                                                                    ?>
                                                                    <tr>
                                                                        <td style="padding:20px;font-size:13px;">
                                                                            <?php echo $row['title']; ?>
                                                                        </td>
                                                                        <td style="padding:20px;font-size:13px;">
                                                                            <?php echo $row['name']; ?>
                                                                        </td>
                                                                        <td style="padding:20px;font-size:13px;"><?php
                                                                        $time_12hr = date("h:i A", strtotime($row['time']));

                                                                        echo $row['date'] . ' - ' . $time_12hr;
                                                                        ?></td>

                                                                        <?php
                                                                        if ($appointment_status == 1) {
                                                                            ?>
                                                                            <td><b style="color: #2A19E9;">Sorry, no meeting link has been
                                                                                    created
                                                                                    yet</b></td>
                                                                            <?php
                                                                        } elseif ($appointment_status == 2) {
                                                                            ?>
                                                                            <td>&nbsp;<a href="<?php echo $meeting_link; ?>"
                                                                                    class="custom-link" target="_blank">Click
                                                                                    here</a>
                                                                            </td>
                                                                            <?php
                                                                        } elseif ($appointment_status == 3) {
                                                                            ?>
                                                                            <td><b style="color: #2A19E9;">The meeting has ended</b></td>
                                                                            <?php
                                                                        }
                                                                        ?>

                                                                        <td>
                                                                            <div style="display: flex; justify-content: center;">

                                                                                <!-- appointment_status -->
                                                                                <?php
                                                                                if ($appointment_status == 1) {
                                                                                    ?>
                                                                                    <a href="https://meet.google.com/"
                                                                                        class="non-style-link" target="_blank">
                                                                                        <a href="https://meet.google.com/"
                                                                                            class="non-style-link" target="_blank">
                                                                                            <div class="medical-button">Create a meeting
                                                                                            </div>
                                                                                        </a>
                                                                                    </a>
                                                                                    &nbsp;&nbsp;&nbsp;

                                                                                    <!-- add_link -->
                                                                                    <a href="appointment.php?action=add_link&id=<?php echo $row['id']; ?>&name=none&error=4"
                                                                                        class="non-style-link">
                                                                                        <div class="medical-button">Add a link to the
                                                                                            patient</div>
                                                                                    </a>
                                                                                    &nbsp;&nbsp;&nbsp;
                                                                                    <?php
                                                                                } elseif ($appointment_status == 2) {
                                                                                    ?>
                                                                                    <a href="https://meet.google.com/"
                                                                                        class="non-style-link" target="_blank">
                                                                                        <div class="medical-button">Create a meeting</div>
                                                                                    </a>
                                                                                    &nbsp;&nbsp;&nbsp;

                                                                                    <!-- add_link -->
                                                                                    <a href="appointment.php?action=add_link&id=<?php echo $row['id']; ?>&name=none&error=4"
                                                                                        class="non-style-link">
                                                                                        <div class="medical-button">Add a link to the
                                                                                            patient</div>
                                                                                    </a>
                                                                                    &nbsp;&nbsp;&nbsp;

                                                                                    <!-- drop_link -->
                                                                                    <a href="appointment.php?action=drop_link&id=<?php echo $row['id']; ?>&name=none&error=4"
                                                                                        class="non-style-link">
                                                                                        <div class="medical-button">End the meeting</div>
                                                                                    </a>
                                                                                    &nbsp;&nbsp;&nbsp;
                                                                                    <?php
                                                                                } elseif ($appointment_status == 3) {
                                                                                    ?>
                                                                                    -
                                                                                    <?php
                                                                                }
                                                                                ?>
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
                                </table>
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