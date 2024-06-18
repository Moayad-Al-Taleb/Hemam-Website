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

    <title>My Appointments</title>
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
                        $sqlmain = "SELECT appointments.id, appointments.session_date_id, appointments.patient_id, appointments.appointment_status, appointments.date, appointments.meeting_link, appointments.doctor_id, session_dates.id AS 'session_id', session_dates.title, session_dates.time, session_dates.doctor_id, patients.id AS 'patient_id', patients.name, patients.email, patients.mobile_number FROM appointments INNER JOIN session_dates ON appointments.session_date_id = session_dates.id INNER JOIN patients ON appointments.patient_id = patients.id WHERE appointments.doctor_id = $session_doctor_id";

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
                                <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">My
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
                                                    <th class="table-headin">Date</th>
                                                    <th class="table-headin">Time</th>
                                                    <th class="table-headin">Title</th>
                                                    <th class="table-headin">Meeting Link</th>
                                                    <th class="table-headin">Events</th>
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

                                                        $name = $row['name'];
                                                        $email = $row['email'];
                                                        $mobile_number = $row['mobile_number'];
                                                        $date = $row['date'];
                                                        $time = $row['time'];
                                                        $title = $row['title'];
                                                        $appointment_status = $row['appointment_status'];
                                                        $meeting_link = $row['meeting_link'];
                                                        ?>
                                                        <tr>
                                                            <td>&nbsp
                                                                <?php echo substr($name, 0, 30) ?>
                                                            </td>
                                                            <td>&nbsp
                                                                <?php echo substr($email, 0, 30) ?>
                                                            </td>
                                                            <td>&nbsp
                                                                <?php echo substr($mobile_number, 0, 30) ?>
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
                                                                <td><b style="color: #2A19E9;">Sorry, no meeting link has been created
                                                                        yet</b></td>
                                                                <?php
                                                            } elseif ($appointment_status == 2) {
                                                                ?>
                                                                <td>&nbsp;<a href="<?php echo $meeting_link; ?>" class="custom-link"
                                                                        target="_blank">Click
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
                                                                        <a href="https://meet.google.com/" class="non-style-link"
                                                                            target="_blank">
                                                                            <div class="medical-button">Create a meeting</div>
                                                                        </a>
                                                                        &nbsp;&nbsp;&nbsp;

                                                                        <!-- add_link -->
                                                                        <a href="?action=add_link&id=<?php echo $row['id']; ?>&name=none&error=4"
                                                                            class="non-style-link">
                                                                            <div class="medical-button">Add a link to the patient</div>
                                                                        </a>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                        <?php
                                                                    } elseif ($appointment_status == 2) {
                                                                        ?>
                                                                        <a href="https://meet.google.com/" class="non-style-link"
                                                                            target="_blank">
                                                                            <div class="medical-button">Create a meeting</div>
                                                                        </a>
                                                                        &nbsp;&nbsp;&nbsp;

                                                                        <!-- add_link -->
                                                                        <a href="?action=add_link&id=<?php echo $row['id']; ?>&name=none&error=4"
                                                                            class="non-style-link">
                                                                            <div class="medical-button">Add a link to the patient</div>
                                                                        </a>
                                                                        &nbsp;&nbsp;&nbsp;

                                                                        <!-- drop_link -->
                                                                        <a href="?action=drop_link&id=<?php echo $row['id']; ?>&name=none&error=4"
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

                if ($action == 'drop_link') {

                    ?>
                    <div id="popup1" class="overlay">
                        <div class="popup">
                            <center>
                                <h2>Are you sure?</h2>
                                <a class="close" href="appointment.php">&times;</a>
                                <div class="content">
                                    You want to end the meeting.
                                </div>
                                <div style="display: flex;justify-content: center;">
                                    <a href="drop_link.php?id=<?php echo $id; ?>" class="non-style-link"><button class="btn-primary btn"
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
                } elseif ($action == 'add_link') {
                    $sqlmain = "select * from appointments where id='$id'";
                    $result = $conn->query($sqlmain);
                    $row = $result->fetch_assoc();
                    $meeting_link = !empty($row['meeting_link']) ? $row['meeting_link'] : null;

                    $error_1 = $_GET["error"];
                    $errorlist = array(
                        '1' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Sorry, it seems that the link you entered does not match the required format. The link should start with "https://meet.google.com/" followed by a random text. For example, the link should look like this: "https://meet.google.com/xxxxxxxxx" where "xxxxxxxxx" is a random string of lowercase letters, numbers, and hyphens. Please try again.</label>',
                        '2' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">The addition process could not be completed. There are missing data that need to be filled out!</label>',
                        '3' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">An error occurred while adding data ' . $conn->error . '</label>',
                        '4' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>',
                        '0' => "",
                    );

                    if ($error_1 != '0') {
                        ?>

                        <div id="popup1" class="overlay">
                            <div class="popup">
                                <center>
                                    <a class="close" href="appointment.php">&times;</a>
                                    <div style="display: flex;justify-content: center;">
                                        <div class="abc">
                                            <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php
                                                        echo $errorlist[$error_1];
                                                        ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">
                                                            Add a link to the patient.</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <form action="add_link.php" method="POST" class="add-new-form">
                                                        <td class="label-td" colspan="2">
                                                            <input type="hidden" name="id" value="<?php echo $id; ?>"><br>

                                                            <label for="meetingLink" class="form-label">Meeting Link: </label>
                                                        </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <input type="url" name="meeting_link" class="input-text" placeholder="Meeting Link"
                                                            required value="<?php echo $meeting_link; ?>"><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2">
                                                        <input type="reset" value="Reset"
                                                            class="login-btn btn-primary-soft btn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="submit" value="Save" class="login-btn btn-primary btn" name="submit">
                                                    </td>
                                                </tr>
                                                </form>
                                            </table>
                                        </div>
                                    </div>
                                </center>
                                <br><br>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div id="popup1" class="overlay">
                            <div class="popup">
                                <center>
                                    <br><br><br><br>
                                    <h2>Meeting Created Successfully!</h2>
                                    <a class="close" href="appointment.php">&times;</a>
                                    <div class="content">
                                    </div>
                                    <div style="display: flex;justify-content: center;">
                                        <a href="appointment.php" class="non-style-link"><button class="btn-primary btn"
                                                style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                                <font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font>
                                            </button></a>

                                    </div>
                                    <br><br>
                                </center>
                            </div>
                        </div>
                        <?php
                    }

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