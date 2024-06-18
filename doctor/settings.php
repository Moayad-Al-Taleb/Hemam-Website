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

    <title>Settings</title>
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
        if ($_SESSION['account_type'] == '2' && $_SESSION['account_status'] == '1') {

            $username = $_SESSION['name'];

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
                                <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;"> Settings</p>
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
                                                <p style="font-size: 20px">&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">
                                                <a href="?action=edit&error=4" class="non-style-link">
                                                    <div class="dashboard-items setting-tabs"
                                                        style="padding:20px;margin:auto;width:95%;display: flex">
                                                        <div class="btn-icon-back dashboard-icons-setting"
                                                            style="background-image: url('../img/icons/doctors-hover.svg');">
                                                        </div>
                                                        <div>
                                                            <div class="h1-dashboard">
                                                                Account Settings &nbsp;
                                                            </div><br>
                                                            <div class="h3-dashboard" style="font-size: 15px;">
                                                                Edit your Account Details & Change Password
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4">
                                                <p style="font-size: 5px">&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">
                                                <a href="?action=view" class="non-style-link">
                                                    <div class="dashboard-items setting-tabs"
                                                        style="padding:20px;margin:auto;width:95%;display: flex;">
                                                        <div class="btn-icon-back dashboard-icons-setting "
                                                            style="background-image: url('../img/icons/view-iceblue.svg');">
                                                        </div>
                                                        <div>
                                                            <div class="h1-dashboard">
                                                                View Account Details
                                                            </div><br>
                                                            <div class="h3-dashboard" style="font-size: 15px;">
                                                                View Personal information About Your Account
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="4">
                                                <p style="font-size: 5px">&nbsp;</p>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- END SECTION Dash Body -->

                <!-- START SECTION $_GET -->
                <?php
                if ($_GET) {
                    $id = $_SESSION['id'];
                    $action = $_GET["action"];

                    if ($action == 'edit') {
                        $sqlmain = "select * from doctors where id='$id'";
                        $result = $conn->query($sqlmain);
                        $row = $result->fetch_assoc();
                        $name = !empty($row['name']) ? $row['name'] : null;
                        $email = !empty($row['email']) ? $row['email'] : null;
                        $password = !empty($row['password']) ? $row['password'] : null;
                        $address = !empty($row['address']) ? $row['address'] : null;
                        $mobile_number = !empty($row['mobile_number']) ? $row['mobile_number'] : null;
                        $start_date = !empty($row['start_date']) ? $row['start_date'] : null;
                        $availability_hours = !empty($row['availability_hours']) ? $row['availability_hours'] : null;
                        $number_of_allowed_sessions_per_day = !empty($row['number_of_allowed_sessions_per_day']) ? $row['number_of_allowed_sessions_per_day'] : null;
                        $session_fee = !empty($row['session_fee']) ? $row['session_fee'] : null;
                        $specialtie_id = !empty($row['specialtie_id']) ? $row['specialtie_id'] : null;

                        $sql_specialtie = "select * from specialties where id='$specialtie_id' LIMIT 1";
                        $result_specialtie = $conn->query($sql_specialtie);
                        $row_specialtie = $result_specialtie->fetch_assoc();
                        $specialtie_name = !empty($row_specialtie['name']) ? $row_specialtie['name'] : null;

                        $error_1 = $_GET["error"];
                        $errorlist = array(
                            '1' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>',
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
                                        <a class="close" href="settings.php">&times;</a>
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
                                                            <p
                                                                style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">
                                                                Edit Doctor Details.</p>
                                                            Doctor ID : <?php echo $id; ?><br><br>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <form action="edit-doc.php" method="POST" class="add-new-form">
                                                            <td class="label-td" colspan="2">
                                                                <input type="hidden" name="id" value="<?php echo $id; ?>"><br>

                                                                <label for="name" class="form-label">Name: </label>
                                                            </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input type="text" name="name" class="input-text" placeholder="Doctor Name"
                                                                required value="<?php echo $name; ?>"><br>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="Email" class="form-label">Email: </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input type="email" name="email" class="input-text" placeholder="Email Address"
                                                                required value="<?php echo $email; ?>"><br>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="Password" class="form-label">Password: </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input type="password" name="password" class="input-text" placeholder="Password"
                                                                required value="<?php echo $password; ?>"><br>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="Address" class="form-label">Address: </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input type="text" name="address" class="input-text" placeholder="Address"
                                                                required value="<?php echo $address; ?>"><br>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="mobileNumber" class="form-label">Mobile Number: </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input type="text" name="mobile_number" class="input-text"
                                                                placeholder="Mobile Number" required
                                                                value="<?php echo $mobile_number; ?>"><br>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="startDate" class="form-label">Start Date: </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input type="time" name="start_date" class="input-text" placeholder="Start Date"
                                                                value="<?php echo $start_date; ?>" disabled><br>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="availabilityHours" class="form-label">Availability Hours:
                                                                (Current
                                                                <?php echo $availability_hours; ?>) </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <select name="availability_hours" class="box" disabled>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="numberOfAllowedSessionsPerDay" class="form-label">Number Of
                                                                Allowed
                                                                Sessions Per Day: </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input type="number" name="number_of_allowed_sessions_per_day"
                                                                class="input-text" placeholder="Number Of Allowed Sessions Per Day"
                                                                value="<?php echo $number_of_allowed_sessions_per_day; ?>" disabled><br>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="sessionFee" class="form-label">Session Fee: </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input type="number" name="session_fee" class="input-text"
                                                                placeholder="Session Fee" value="<?php echo $session_fee; ?>" disabled><br>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="specialtie_id" class="form-label">specialtie: (Current
                                                                <?php echo $specialtie_name; ?>)</label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <select name="specialtie_id" class="box" disabled>
                                                                <option value="" disabled selected hidden>Choose Specialtie from the
                                                                    list
                                                                </option><br />

                                                                <?php
                                                                $result_specialties = $conn->query("select * from specialties");
                                                                while ($row_specialties = $result_specialties->fetch_assoc()) {
                                                                    $specialtie_id = $row_specialties["id"];
                                                                    $specialtie_name = $row_specialties["name"];
                                                                    ?>
                                                                    <option value="<?php echo $specialtie_id; ?>">
                                                                        <?php echo $specialtie_name; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <input type="reset" value="Reset"
                                                                class="login-btn btn-primary-soft btn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <input type="submit" value="Save" class="login-btn btn-primary btn"
                                                                name="submit">
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
                                        <a class="close" href="settings.php">&times;</a>
                                        <div class="content">
                                        </div>
                                        <div style="display: flex;justify-content: center;">
                                            <a href="settings.php" class="non-style-link"><button class="btn-primary btn"
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

                    } elseif ($action == 'view') {
                        $sqlmain = "select * from doctors where id='$id'";
                        $result = $conn->query($sqlmain);
                        $row = $result->fetch_assoc();
                        $name = !empty($row['name']) ? $row['name'] : null;
                        $email = !empty($row['email']) ? $row['email'] : null;
                        $password = !empty($row['password']) ? $row['password'] : null;
                        $address = !empty($row['address']) ? $row['address'] : null;
                        $mobile_number = !empty($row['mobile_number']) ? $row['mobile_number'] : null;
                        $start_date = !empty($row['start_date']) ? $row['start_date'] : null;
                        $availability_hours = !empty($row['availability_hours']) ? $row['availability_hours'] : null;
                        $number_of_allowed_sessions_per_day = !empty($row['number_of_allowed_sessions_per_day']) ? $row['number_of_allowed_sessions_per_day'] : null;
                        $session_fee = !empty($row['session_fee']) ? $row['session_fee'] : null;
                        $specialtie_id = !empty($row['specialtie_id']) ? $row['specialtie_id'] : null;

                        $sql_specialtie = "select * from specialties where id='$specialtie_id' LIMIT 1";
                        $result_specialtie = $conn->query($sql_specialtie);
                        $row_specialtie = $result_specialtie->fetch_assoc();
                        $specialtie_name = !empty($row_specialtie['name']) ? $row_specialtie['name'] : null;

                        ?>

                        <div id="popup1" class="overlay">
                            <div class="popup">
                                <center>
                                    <h2></h2>
                                    <h2></h2>
                                    <a class="close" href="settings.php">&times;</a>
                                    <div class="content">
                                        Hemam Web App<br>
                                    </div>

                                    <div style="display: flex;justify-content: center;">
                                        <div class="abc">
                                            <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">

                                                <tr>
                                                    <td>
                                                        <p
                                                            style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">
                                                            View
                                                            Details.</p><br><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="name" class="form-label">Name: </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php echo $name; ?><br><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="email" class="form-label">Email: </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php echo $email; ?><br><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="password" class="form-label">Password: </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php echo $password; ?><br><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="address" class="form-label">Address: </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php echo $address; ?><br><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="mobileNumber" class="form-label">Mobile Number: </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php echo $mobile_number; ?><br><br>
                                                    </td>
                                                </tr>
                                                <?php
                                                $start_time_12hr = date("h:i A", strtotime($start_date));
                                                ?>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="startDate" class="form-label">Start Date: </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php echo $start_time_12hr; ?><br><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="availabilityHours" class="form-label">Availability Hours:
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php echo $availability_hours; ?><br><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="numberOfAllowedSessionsPerDay" class="form-label">Number Of
                                                            Allowed
                                                            Sessions
                                                            Per Day: </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php echo $number_of_allowed_sessions_per_day; ?><br><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="sessionFee" class="form-label">Session Fee: </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php echo $session_fee; ?><br><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <label for="specialtieName" class="form-label">Specialtie Name: </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <?php echo $specialtie_name; ?><br><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2">
                                                        <a href="settings.php"><input type="button" value="OK"
                                                                class="login-btn btn-primary-soft btn"></a>
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </div>

                        <?php
                    }
                }
                ?>
                <!-- END SECTION $_GET -->
            </div>
            <?php
        } else {
            header("location: ../login.php");
        }
    }
    ?>
</body>

</html>