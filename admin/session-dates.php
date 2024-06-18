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

    <title>Session Dates</title>
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
                                <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Session Dates Manager</p>
                            </td>
                            <td width="15%">
                                <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                                    Today's Date</p>
                                <p class="heading-sub12" style="padding: 0;margin: 0;">
                                    <?php
                                    date_default_timezone_set('Asia/Riyadh');
                                    $today = date('Y-m-d');
                                    echo $today;
                                    $sessiondatesrow = $conn->query("select * from session_dates");
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
                                <div style="display: flex;margin-top: 40px;">
                                    <div class="heading-main12"
                                        style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49);margin-top: 5px;">Session
                                        Dates
                                        a Session</div>
                                    <a href="?action=add&id=none&name=none&error=4" class="non-style-link"><button
                                            class="login-btn btn-primary btn button-icon"
                                            style="margin-left:25px;background-image: url('../img/icons/add.svg');">Add a
                                            Session</font></button>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" style="padding-top:10px;width: 100%;">
                                <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All
                                    Session Dates (<?php echo $sessiondatesrow->num_rows; ?>)</p>
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
                                                    <input type="time" name="sessiondatestime" id="date"
                                                        class="input-text filter-container-items" style="margin: 0;width: 95%;">
                                            </td>

                                            <td width="5%" style="text-align: center;">
                                                Doctor:
                                            </td>

                                            <td width="30%">
                                                <select name="doctor_id" id="" class="box filter-container-items"
                                                    style="width:90% ;height: 37px;margin: 0;">
                                                    <option value="" disabled selected hidden>Choose Doctor Name from the list
                                                    </option><br />
                                                    <?php
                                                    $result_doctors = $conn->query("select * from doctors order by name asc");
                                                    while ($row = $result_doctors->fetch_assoc()) {
                                                        $doctor_id = $row['id'];
                                                        $doctor_name = $row['name'];
                                                        ?>
                                                        <option value="<?php echo $doctor_id; ?>"><?php echo $doctor_name; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
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

                        <?php
                        if ($_POST) {
                            $sqlpt1 = "";
                            if (!empty($_POST["sessiondatestime"])) {
                                $sessiondatestime = $_POST["sessiondatestime"];
                                $sqlpt1 = " session_dates.time LIKE '$sessiondatestime%' ";
                            }

                            $sqlpt2 = "";
                            if (!empty($_POST["doctor_id"])) {
                                $doctor_id = $_POST["doctor_id"];
                                $sqlpt2 = " session_dates.doctor_id=$doctor_id ";
                            }

                            $sqlmain = "SELECT session_dates.id, session_dates.doctor_id, session_dates.title, session_dates.time, session_dates.session_status, doctors.name AS 'doctor_name' FROM session_dates INNER JOIN doctors ON session_dates.doctor_id = doctors.id ";

                            $sqllist = array($sqlpt1, $sqlpt2);
                            $sqlkeywords = array(" where ", " and ");
                            $key2 = 0;

                            foreach ($sqllist as $key) {
                                if (!empty($key)) {
                                    $sqlmain .= $sqlkeywords[$key2] . $key;
                                    $key2++;
                                }
                            }

                            $sqlmain .= " order by session_dates.time desc, doctor_name asc";

                        } else {
                            $sqlmain = "SELECT session_dates.id, session_dates.doctor_id, session_dates.title, session_dates.time, session_dates.session_status, doctors.name AS 'doctor_name' FROM session_dates INNER JOIN doctors ON session_dates.doctor_id = doctors.id order by session_dates.time desc, doctor_name asc";
                        }
                        ?>
                        <!-- START SECTION DATA DUSPLAY TABLE -->
                        <tr>
                            <td colspan="4">
                                <center>
                                    <div class="abc scroll">
                                        <table width="93%" class="sub-table scrolldown" border="0">
                                            <thead>
                                                <tr>
                                                    <th class="table-headin">Session Title</th>
                                                    <th class="table-headin">Doctor</th>
                                                    <th class="table-headin">Session Time</th>
                                                    <th class="table-headin">Session Status</th>
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
                                                                        Show all Session Dates &nbsp;</font></button>
                                                                </a>
                                                            </center>
                                                            <br><br><br><br>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $id = $row["id"];
                                                        $doctor_id = $row["doctor_id"];
                                                        $title = $row["title"];
                                                        $time = $row["time"];
                                                        $session_status = $row["session_status"];
                                                        $doctor_name = $row["doctor_name"];
                                                        ?>
                                                        <tr>
                                                            <td>&nbsp
                                                                <?php echo substr($title, 0, 30) ?>
                                                            </td>

                                                            <td>&nbsp
                                                                <?php echo substr($doctor_name, 0, 30) ?>
                                                            </td>

                                                            <td>&nbsp
                                                                <?php
                                                                $time_12hr = date("h:i A", strtotime($time));
                                                                echo $time_12hr;
                                                                ?>
                                                            </td>

                                                            <td>&nbsp
                                                                <?php echo ($row["session_status"] == 1) ? "active" : "frozen"; ?>
                                                            </td>

                                                            <td>
                                                                <div style="display: flex; justify-content: center;">
                                                                    <a href="?action=edit&id=<?php echo $row['id']; ?>&name=none&error=4"
                                                                        class="non-style-link">
                                                                        <button class="btn-primary-soft btn button-icon btn-edit"
                                                                            style="padding-left: 40px; padding-top: 12px; padding-bottom: 12px; margin-top: 10px;">
                                                                            <font class="tn-in-text">Edit</font>
                                                                        </button>
                                                                    </a>
                                                                    &nbsp;&nbsp;&nbsp;
                                                                    <a href="?action=drop&id=<?php echo $row['id']; ?>&name=<?php echo $title; ?>&error=none"
                                                                        <button class="btn-primary-soft btn button-icon btn-delete"
                                                                        style="padding-left: 40px; padding-top: 12px; padding-bottom: 12px; margin-top: 10px;">
                                                                        <font class="tn-in-text">Remove</font>
                                                                        </button>
                                                                    </a>
                                                                    <?php
                                                                    if ($row["session_status"] == 1) {
                                                                        ?>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                        <a href="session-date-status.php?action=freeze&id=<?php echo $row['id']; ?>"
                                                                            class="non-style-link">
                                                                            <!-- btn-freeze -->
                                                                            <button class="btn-primary-soft btn button-icon"
                                                                                style="padding-left: 40px; padding-top: 12px; padding-bottom: 12px; margin-top: 10px;">
                                                                                <font class="tn-in-text">Freeze</font>
                                                                            </button>
                                                                        </a>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                        <a href="session-date-status.php?action=activate&id=<?php echo $row['id']; ?>"
                                                                            class="non-style-link">
                                                                            <!-- btn-activate -->
                                                                            <button class="btn-primary-soft btn button-icon"
                                                                                style="padding-left: 40px; padding-top: 12px; padding-bottom: 12px; margin-top: 10px;">
                                                                                <font class="tn-in-text">Activate</font>
                                                                            </button>
                                                                        </a>
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

                if ($action == 'add') {
                    $error_1 = $_GET["error"];
                    $errorlist = array(
                        '1' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">A session cannot be created because the number of sessions available to the doctor has expired.</label>',
                        '2' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">There is a session at this time. Set the time.</label>',
                        '3' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">The time entered for the session exceeds the doctor’s working time.</label>',
                        '4' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>',
                        '5' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">An error occurred while adding data ' . $conn->error . '</label>',
                        '6' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">The addition process could not be completed. There are missing data that need to be filled out!</label>',
                        '0' => "",
                    );
                    if ($error_1 != '0') {
                        ?>

                        <div id="popup1" class="overlay">
                            <div class="popup">
                                <center>
                                    <a class="close" href="session-dates.php">&times;</a>
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
                                                            Add New Session Date.</p><br><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <form action="add-new-session-date.php" method="POST" class="add-new-form">
                                                        <td class="label-td" colspan="2">
                                                            <label for="doctor_id" class="form-label">Select Doctor: </label>
                                                        </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <select name="doctor_id" id="" class="box filter-container-items"
                                                            style="width:90% ;height: 37px;margin: 0;">
                                                            <option value="" disabled selected hidden>Choose Doctor Name from the list
                                                            </option><br />
                                                            <?php
                                                            $result_doctors = $conn->query("select * from doctors order by name asc");
                                                            while ($row = $result_doctors->fetch_assoc()) {
                                                                $doctor_id = $row['id'];
                                                                $doctor_name = $row['name'];
                                                                ?>
                                                                <option value="<?php echo $doctor_id; ?>"><?php echo $doctor_name; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="Title" class="form-label">Title: </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <input type="text" name="title" class="input-text" placeholder="Title" required><br>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="Time" class="form-label">Time: </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <input type="time" name="time" class="input-text" placeholder="Time" required><br>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td colspan="2">
                                                        <input type="reset" value="Reset"
                                                            class="login-btn btn-primary-soft btn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="submit" value="Add" class="login-btn btn-primary btn" name="submit">
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
                                    <h2>New Record Added Successfully!</h2>
                                    <a class="close" href="session-dates.php">&times;</a>
                                    <div class="content">
                                    </div>
                                    <div style="display: flex;justify-content: center;">
                                        <a href="session-dates.php" class="non-style-link"><button class="btn-primary btn"
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
                } elseif ($action == 'drop') {
                    $nameget = $_GET["name"];

                    ?>
                    <div id="popup1" class="overlay">
                        <div class="popup">
                            <center>
                                <h2>Are you sure?</h2>
                                <a class="close" href="session-dates.php">&times;</a>
                                <div class="content">
                                    You want to delete this record<br>(<?php echo substr($nameget, 0, 40) ?>).
                                </div>
                                <div style="display: flex;justify-content: center;">
                                    <a href="delete-session-date.php?id=<?php echo $id; ?>" class="non-style-link"><button
                                            class="btn-primary btn"
                                            style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"
                                            <font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                                    <a href="session-dates.php" class="non-style-link"><button class="btn-primary btn"
                                            style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                            <font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font>
                                        </button></a>
                                </div>
                            </center>
                        </div>
                    </div>
                    <?php
                } elseif ($action == 'edit') {
                    $sqlmain = "select * from session_dates where id='$id'";
                    $result = $conn->query($sqlmain);
                    $row = $result->fetch_assoc();
                    $doctor_id = !empty($row['doctor_id']) ? $row['doctor_id'] : null;
                    $title = !empty($row['title']) ? $row['title'] : null;
                    $time = !empty($row['time']) ? $row['time'] : null;

                    $sql_doctor = "select * from doctors where id='$doctor_id' LIMIT 1";
                    $result_doctor = $conn->query($sql_doctor);
                    $row_doctor = $result_doctor->fetch_assoc();
                    $doctor_name = !empty($row_doctor['name']) ? $row_doctor['name'] : null;

                    $error_1 = $_GET["error"];
                    $errorlist = array(
                        '1' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">A session cannot be created because the number of sessions available to the doctor has expired.</label>',
                        '2' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">There is a session at this time. Set the time.</label>',
                        '3' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">The time entered for the session exceeds the doctor’s working time.</label>',
                        '4' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>',
                        '5' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">An error occurred while adding data ' . $conn->error . '</label>',
                        '6' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">The addition process could not be completed. There are missing data that need to be filled out!</label>',
                        '0' => "",
                    );

                    if ($error_1 != '0') {
                        ?>

                        <div id="popup1" class="overlay">
                            <div class="popup">
                                <center>
                                    <a class="close" href="session-dates.php">&times;</a>
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
                                                            Edit Session Date Details.</p>
                                                        Session Date ID : <?php echo $id; ?><br><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <form action="edit-session-date.php" method="POST" class="add-new-form">
                                                        <td class="label-td" colspan="2">
                                                            <input type="hidden" name="id" value="<?php echo $id; ?>"><br>

                                                            <label for="doctor_id" class="form-label">Select Doctor: (Current
                                                                <?php echo $doctor_name; ?>)</label>

                                                        </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <select name="doctor_id" id="" class="box filter-container-items"
                                                            style="width:90% ;height: 37px;margin: 0;">
                                                            <option value="" disabled selected hidden>Choose Doctor Name from the list
                                                            </option><br />
                                                            <?php
                                                            $result_doctors = $conn->query("select * from doctors order by name asc");
                                                            while ($row = $result_doctors->fetch_assoc()) {
                                                                $doctor_id = $row['id'];
                                                                $doctor_name = $row['name'];
                                                                ?>
                                                                <option value="<?php echo $doctor_id; ?>"><?php echo $doctor_name; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="Title" class="form-label">Title: </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <input type="text" name="title" class="input-text" placeholder="Title" required
                                                            value="<?php echo $title; ?>"><br>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="Time" class="form-label">Time: </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <input type="time" name="time" class="input-text" placeholder="Time" required
                                                            value="<?php echo $time; ?>"><br>
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
                                    <h2>Edit Successfully!</h2>
                                    <a class="close" href="session-dates.php">&times;</a>
                                    <div class="content">
                                    </div>
                                    <div style="display: flex;justify-content: center;">
                                        <a href="session-dates.php" class="non-style-link"><button class="btn-primary btn"
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