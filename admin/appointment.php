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

    <title>Appointment</title>
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

        .popup {
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
                <div class="dash-body">
                    <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                        <tr>
                            <td width="13%">
                                <a href="session-dates.php"><button class="login-btn btn-primary-soft btn btn-icon-back"
                                        style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                                        <font class="tn-in-text">Back</font>
                                    </button></a>
                            </td>
                            <td>
                                <p style="font-size: 23px;padding-left:12px;font-weight: 600;">My Appointment</p>
                            </td>
                            <td width="15%">
                                <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                                    Today's Date</p>
                                <p class="heading-sub12" style="padding: 0;margin: 0;">
                                    <?php
                                    date_default_timezone_set('Asia/Riyadh');
                                    $today = date('Y-m-d');
                                    echo $today;
                                    ?>
                                </p>
                            </td>
                            <td width="10%">
                                <button class="btn-label"
                                    style="display: flex;justify-content: center;align-items: center;"><img
                                        src="../img/calendar.svg" width="100%"></button>
                            </td>
                        </tr>

                        <?php
                        $session_doctor_id = $_SESSION['id'];
                        $sqlmain = "SELECT appointments.id, appointments.session_date_id, appointments.patient_id, appointments.appointment_status, appointments.date, appointments.doctor_id, session_dates.id AS 'session_id', session_dates.title, session_dates.time, session_dates.doctor_id, patients.id AS 'patient_id', patients.name AS 'patient_name', patients.email AS 'patient_email', patients.mobile_number AS 'patient_mobile_number', doctors.id AS 'doctor_id', doctors.name AS 'doctor_name' FROM appointments INNER JOIN session_dates ON appointments.session_date_id = session_dates.id INNER JOIN patients ON appointments.patient_id = patients.id INNER JOIN doctors ON appointments.doctor_id = doctors.id";

                        if ($_POST) {
                            if (!empty($_POST["date"])) {
                                $date = $_POST["date"];
                                $sqlmain .= " AND date='$date'";
                            }

                        }

                        $sqlmain .= " ORDER BY appointments.date, session_dates.time ASC";
                        $result = $conn->query($sqlmain);
                        ?>

                        <tr>
                            <td colspan="4" style="padding-top:10px;width: 100%;">
                                <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)"> All
                                    Appointment (<?php echo $result->num_rows; ?>)</p>
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
                                                Time:
                                            </td>

                                            <td width="30%">
                                                <form action="#" method="post">
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
                                        <table width="93%" class="sub-table scrolldown" border="0">
                                            <thead>
                                                <tr>
                                                    <th class="table-headin">Patient Name</th>
                                                    <th class="table-headin">Patient Email</th>
                                                    <th class="table-headin">Patient Mobile Number</th>
                                                    <th class="table-headin">Doctor Name</th>
                                                    <th class="table-headin">Date</th>
                                                    <th class="table-headin">Time</th>
                                                    <th class="table-headin">Title</th>
                                                    <th class="table-headin">Meeting Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
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
                                                                    We couldnt find anything related to your keywords !</p>
                                                                <a class="non-style-link" href="session-dates.php"><button
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

                                                        $patient_name = $row['patient_name'];
                                                        $patient_email = $row['patient_email'];
                                                        $patient_mobile_number = $row['patient_mobile_number'];
                                                        $doctor_name = $row['doctor_name'];
                                                        $date = $row['date'];
                                                        $time = $row['time'];
                                                        $title = $row['title'];

                                                        $appointment_status = $row['appointment_status'];
                                                        ?>
                                                        <tr>
                                                            <td>&nbsp
                                                                <?php echo substr($patient_name, 0, 30) ?>
                                                            </td>
                                                            <td>&nbsp
                                                                <?php echo substr($patient_email, 0, 30) ?>
                                                            </td>
                                                            <td>&nbsp
                                                                <?php echo substr($patient_mobile_number, 0, 30) ?>
                                                            </td>
                                                            <td>&nbsp
                                                                <?php echo substr($doctor_name, 0, 30) ?>
                                                            </td>
                                                            <td>&nbsp
                                                                <?php echo substr($date, 0, 30) ?>
                                                            </td>

                                                            <?php
                                                            $time_12hr = date("h:i A", strtotime($time));
                                                            ?>
                                                            <td>&nbsp
                                                                <?php echo $time_12hr; ?>
                                                            </td>

                                                            <td>&nbsp
                                                                <?php echo substr($title, 0, 30) ?>
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
                        </tr>
                        <!-- END SECTION DATA DUSPLAY TABLE -->
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