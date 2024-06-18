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

    <title>My Sessions</title>
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
                                <p style="font-size: 23px;padding-left:12px;font-weight: 600;">My Sessions</p>
                            </td>
                            <td width="15%">
                                <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                                    Today's Date</p>
                                <p class="heading-sub12" style="padding: 0;margin: 0;">
                                    <?php
                                    date_default_timezone_set('Asia/Riyadh');
                                    $today = date('Y-m-d');
                                    echo $today;
                                    $sessiondatesrow = $conn->query("select * from session_dates WHERE session_status=1");
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
                                    Sessions (<?php echo $sessiondatesrow->num_rows; ?>)</p>
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
                                $sqlpt1 = " time LIKE '$sessiondatestime%' ";
                            }

                            $doctor_id = $_SESSION['id'];
                            $sqlmain = "SELECT * FROM session_dates WHERE doctor_id='$doctor_id' AND session_status=1";

                            if (!empty($sqlpt1)) {
                                $sqlmain .= " AND $sqlpt1";
                            }

                            $sqlmain .= " ORDER BY time DESC";
                        } else {
                            $doctor_id = $_SESSION['id'];
                            $sqlmain = "SELECT * FROM session_dates WHERE doctor_id='$doctor_id' AND session_status=1 ORDER BY time DESC";
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
                                                    <th class="table-headin">Session Time</th>
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
                                                        $id = $row["id"];
                                                        $title = $row["title"];
                                                        $time = $row["time"];
                                                        ?>
                                                        <tr>
                                                            <td>&nbsp
                                                                <?php echo substr($title, 0, 30) ?>
                                                            </td>

                                                            <?php
                                                            $time_12hr = date("h:i A", strtotime($time));
                                                            ?>
                                                            <td>&nbsp
                                                                <?php echo $time_12hr; ?>
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

            <?php
        } else {
            header("location: ../login.php");
        }
    }
    ?>
</body>

</html>