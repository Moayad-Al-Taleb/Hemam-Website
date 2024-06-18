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

        .sub-table,
        .anime {
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

                <?php
                // تحديد الاستعلام الأساسي
                $sqlmain = "SELECT session_dates.*, doctors.name, doctors.email FROM session_dates INNER JOIN doctors WHERE session_dates.doctor_id = doctors.id AND session_status=1";
                $insertkey = "";
                $q = "";
                $searchtype = "All";

                // التحقق مما إذا كانت هناك بيانات POST
                if ($_POST) {
                    // الحصول على الكلمة المفتاحية من البيانات POST
                    $keyword = $_POST["search"];

                    // تحديد الاستعلام للبحث عن الجلسات بناءً على الكلمة المفتاحية
                    $sqlmain = "(SELECT session_dates.*, doctors.name, doctors.email FROM session_dates INNER JOIN doctors WHERE session_dates.doctor_id = doctors.id AND (session_dates.title LIKE '$keyword%' OR session_dates.title LIKE '%$keyword' OR session_dates.title LIKE '%$keyword%')) UNION (SELECT session_dates.*, doctors.name, doctors.email FROM session_dates INNER JOIN doctors WHERE session_dates.doctor_id = doctors.id AND (doctors.email='$keyword' OR doctors.name='$keyword' OR doctors.name LIKE '$keyword%' OR doctors.name LIKE '%$keyword' OR doctors.name LIKE '%$keyword%'))";

                    // تخزين الكلمة المفتاحية
                    $insertkey = $keyword;

                    // تحديد نوع البحث
                    $searchtype = "Search Result : ";

                    // تحديد قيمة q
                    $q = '"';
                }

                $result = $conn->query($sqlmain);
                ?>

                <div class="dash-body" style="margin-top: 15px">
                    <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;">
                        <tr>
                            <td width="13%">
                                <a href="session-dates.php"><button class="login-btn btn-primary-soft btn btn-icon-back"
                                        style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                                        <font class="tn-in-text">Back</font>
                                    </button></a>
                            </td>
                            <td>
                                <form action="session-dates.php" method="post" class="header-search">
                                    <input type="search" name="search" class="input-text header-searchbar"
                                        placeholder="Search Doctor name or Title session" list="doctors"
                                        value="<?php echo $insertkey; ?>">&nbsp;&nbsp;

                                    <datalist id="doctors">
                                        <?php
                                        $list11 = $conn->query("select * from doctors;");
                                        $list12 = $conn->query("select * from  session_dates;");

                                        while ($row_list11 = $list11->fetch_assoc()) {
                                            $d = $row_list11['name'];

                                            ?>
                                            <option value="<?php echo $d; ?>"></option>
                                            <?php
                                        }

                                        while ($row_list12 = $list12->fetch_assoc()) {
                                            $d = $row_list12['title'];
                                            ?>
                                            <option value="<?php echo $d; ?>"></option>
                                            <?php
                                        }
                                        ?>
                                    </datalist>
                                    <input type="Submit" value="Search" class="login-btn btn-primary btn"
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
                            <td colspan="4" style="padding-top:10px;width: 100%;">
                                <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">
                                    <?php echo $searchtype . " Sessions" . "(" . $result->num_rows . ")"; ?>
                                </p>
                                <p class="heading-main12" style="margin-left: 45px;font-size:22px;color:rgb(49, 49, 49)">
                                    <?php echo $q . $insertkey . $q; ?>
                                </p>
                            </td>
                        </tr>

                        <!-- START SECTION DATA DUSPLAY TABLE -->
                        <tr>
                            <td colspan="4">
                                <center>
                                    <div class="abc scroll">
                                        <table width="100%" class="sub-table scrolldown" border="0"
                                            style="padding: 50px;border:none">
                                            <tbody>
                                                <?php

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
                                                        $doctor_id = $row["doctor_id"];
                                                        $title = $row["title"];
                                                        $time = $row["time"];
                                                        $time_12hr = date("h:i A", strtotime($time));

                                                        $name = $row["name"];
                                                        $email = $row["email"];
                                                        ?>

                                                        <td style="width: 25%;">
                                                            <div class="dashboard-items search-items">
                                                                <div style="width:100%">
                                                                    <div class="h1-search">
                                                                        <?php echo substr($title, 0, 30); ?>
                                                                    </div><br>
                                                                    <div class="h3-search">
                                                                        <?php echo substr($name, 0, 30); ?>
                                                                    </div>
                                                                    <div class="h3-search">
                                                                        <?php echo substr($email, 0, 30); ?>
                                                                    </div>
                                                                    <div class="h4-search">
                                                                        <?php
                                                                        $time = $row["time"];
                                                                        $time_12hr = date("h:i A", strtotime($time));
                                                                        ?>
                                                                        Starts: <b><?php echo substr($time, 0, 30) ?></b> (12h)
                                                                    </div><br>
                                                                    <!-- id: A session ID that contains the session time and the doctor ID -->
                                                                    <a href="booking.php?id=<?php echo $id; ?>"><button
                                                                            class="login-btn btn-primary-soft btn "
                                                                            style="padding-top:11px;padding-bottom:11px;width:100%">
                                                                            <font class="tn-in-text">Book Now</font>
                                                                        </button></a>
                                                                </div>
                                                            </div>
                                                        </td>

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