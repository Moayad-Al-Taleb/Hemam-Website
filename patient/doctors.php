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

    <title>Doctors</title>
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
        if ($_SESSION['account_type'] == '1' && $_SESSION['account_status'] == '1') {
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
                            <td width="13%">
                                <a href="doctors.php"><button class="login-btn btn-primary-soft btn btn-icon-back"
                                        style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                                        <font class="tn-in-text">Back</font>
                                    </button></a>
                            </td>
                            <td>
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
                            <td colspan="4" style="padding-top:10px;">
                                <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All
                                    Doctors (
                                    <?php echo $doctorrow->num_rows; ?>)
                                </p>
                            </td>
                        </tr>

                        <?php
                        if ($_POST) {
                            $keyword = $_POST["search"];
                            $sqlmain = "select * from doctors where email='$keyword' or name='$keyword' or name like '$keyword%' or name like '%$keyword' or name like '%$keyword%' AND account_status=1";
                        } else {
                            $sqlmain = "select * from doctors WHERE account_status=1 order by id desc";
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
                                                    <th class="table-headin">Doctor Name</th>
                                                    <th class="table-headin">Email</th>
                                                    <th class="table-headin">Specialtie</th>
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
                                                                <a class="non-style-link" href="doctors.php"><button
                                                                        class="login-btn btn-primary-soft btn"
                                                                        style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp;
                                                                        Show all Doctors &nbsp;</font></button>
                                                                </a>
                                                            </center>
                                                            <br><br><br><br>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $id = $row["id"];
                                                        $name = $row["name"];
                                                        $email = $row["email"];

                                                        $specialtie_id = $row["specialtie_id"];
                                                        $result_specialties = $conn->query("select name from specialties where id='$specialtie_id' LIMIT 1");
                                                        $array_specialties = $result_specialties->fetch_assoc();
                                                        $specialtie = $array_specialties["name"];

                                                        ?>
                                                        <tr>
                                                            <td>&nbsp
                                                                <?php echo substr($name, 0, 30) ?>
                                                            </td>

                                                            <td>&nbsp
                                                                <?php echo substr($email, 0, 30) ?>
                                                            </td>

                                                            <td>&nbsp
                                                                <?php echo substr($specialtie, 0, 30) ?>
                                                            </td>

                                                            <td>
                                                                <div style="display: flex; justify-content: center;">
                                                                    <a href="?action=view&id=<?php echo $row['id']; ?>&name=none&error=none"
                                                                        class="non-style-link">
                                                                        <button class="btn-primary-soft btn button-icon btn-view"
                                                                            style="padding-left: 40px; padding-top: 12px; padding-bottom: 12px; margin-top: 10px;">
                                                                            <font class="tn-in-text">View</font>
                                                                        </button>
                                                                    </a> &nbsp;&nbsp;&nbsp;
                                                                    <a href="?action=session&id=<?php echo $row['id']; ?>&name=<?php echo $row['name']; ?>&error=none"
                                                                        class="non-style-link">
                                                                        <button
                                                                            class="btn-primary-soft btn button-icon menu-icon-session-active"
                                                                            style="padding-left: 40px; padding-top: 12px; padding-bottom: 12px; margin-top: 10px;">
                                                                            <font class="tn-in-text">Sessions</font>
                                                                        </button>
                                                                    </a>
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

                if ($action == 'view') {
                    $sqlmain = "select * from doctors where id='$id'";
                    $result = $conn->query($sqlmain);
                    $row = $result->fetch_assoc();
                    $name = !empty($row['name']) ? $row['name'] : null;
                    $email = !empty($row['email']) ? $row['email'] : null;
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
                                <a class="close" href="doctors.php">&times;</a>
                                <div class="content">
                                    Hemam Web App<br>
                                </div>

                                <div style="display: flex;justify-content: center;">
                                    <div class="abc">
                                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">

                                            <tr>
                                                <td>
                                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">
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
                                                    <label for="availabilityHours" class="form-label">Availability Hours: </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label-td" colspan="2">
                                                    <?php echo $availability_hours; ?><br><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label-td" colspan="2">
                                                    <label for="numberOfAllowedSessionsPerDay" class="form-label">Number Of Allowed
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
                                                    <a href="doctors.php"><input type="button" value="OK"
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
                } elseif ($action == 'session') {
                    $name = $_GET["name"];

                    ?>
                    <div id="popup1" class="overlay">
                        <div class="popup">
                            <center>
                                <h2>Redirect to Doctors sessions?</h2>
                                <a class="close" href="doctors.php">&times;</a>
                                <div class="content">
                                    You want to view All sessions by <br>(<?php echo substr($name, 0, 40) ?>).
                                </div>
                                <form action="session-dates.php" method="post" style="display: flex">
                                    <input type="hidden" name="search" value="<?php echo $name; ?>">
                                    <div style="display: flex;justify-content:center;margin-left:45%;margin-top:6%;;margin-bottom:6%;">
                                        <input type="submit" value="Yes" class="btn-primary btn">
                                    </div>
                            </center>
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