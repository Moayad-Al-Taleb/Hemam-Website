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

        .error-message {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
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
                // $sqlmain = "";
                // $result = $conn->query($sqlmain);
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
                                        placeholder="Search Doctor name or Title session" list="doctors">&nbsp;&nbsp;

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

                        <!-- START SECTION DATA DUSPLAY TABLE -->
                        <tr>
                            <td colspan="4">
                                <center>
                                    <div class="abc scroll">
                                        <table width="100%" class="sub-table scrolldown" border="0"
                                            style="padding: 50px;border:none">
                                            <tbody>

                                                <?php
                                                if (($_GET)) {
                                                    if (isset($_GET["id"])) {
                                                        // id: A session ID that contains the session time and the doctor ID
                                                        $id = $_GET["id"];

                                                        $error = !empty($_GET['error']) ? $_GET['error'] : null;
                                                        $errorlist = array(
                                                            '1' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Session already booked on this day.</label>',
                                                            '2' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">An error occurred while adding data ' . $conn->error . '</label>',
                                                            '3' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">All fields are required.</label>',
                                                            '4' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Enter the date of the session today or after today date.</label>',
                                                        );

                                                        if ($error != null) {
                                                            ?>
                                                            <div class="error-message">
                                                                <?php echo $errorlist[$error]; ?>
                                                            </div>
                                                            <?php
                                                        }


                                                        $sqlmain = "SELECT session_dates.id, session_dates.doctor_id, session_dates.title, session_dates.time, session_dates.session_status, doctors.id AS 'doctors_id', doctors.name, doctors.email, doctors.session_fee FROM session_dates INNER JOIN doctors ON session_dates.doctor_id = doctors.id WHERE session_dates.id='$id'";
                                                        $result = $conn->query($sqlmain);
                                                        $row = $result->fetch_assoc();

                                                        $session_id = $row['id'];
                                                        $doctor_id = $row['doctor_id'];
                                                        $title = $row['title'];
                                                        $time = $row['time'];
                                                        $name = $row['name'];
                                                        $email = $row['email'];
                                                        $session_fee = $row['session_fee'];

                                                        ?>
                                                        <!-- booking-complete.php -->
                                                        <form action="booking-complete.php" method="post">
                                                            <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                                                            <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
                                                            <input type="hidden" name="title" value="<?php echo $title; ?>">
                                                            <input type="hidden" name="time" value="<?php echo $time; ?>">
                                                            <input type="hidden" name="name" value="<?php echo $name; ?>">
                                                            <input type="hidden" name="email" value="<?php echo $email; ?>">
                                                            <input type="hidden" name="session_fee" value="<?php echo $session_fee; ?>">

                                                            <td style="width: 50%;" rowspan="2">
                                                                <div class="dashboard-items search-items">
                                                                    <div style="width:100%">
                                                                        <div class="h1-search" style="font-size:25px;">
                                                                            Session Details
                                                                        </div><br><br>

                                                                        <div class="h3-search" style="font-size:18px;line-height:30px">
                                                                            Doctor name: &nbsp;&nbsp;<b><?php echo $name; ?></b><br>
                                                                            Doctor Email: &nbsp;&nbsp;<b><?php echo $email; ?>
                                                                            </b>
                                                                        </div>

                                                                        <div class="h3-search" style="font-size:18px;">
                                                                        </div><br>

                                                                        <div class="h3-search" style="font-size:18px;">
                                                                            Session Title: <?php echo $title; ?><br>

                                                                            Session Scheduled Time:
                                                                            <?php
                                                                            $time_12hr = date("h:i A", strtotime($time));
                                                                            echo $time_12hr;
                                                                            ?><br>

                                                                            Session Fee : <b><?php echo $session_fee; ?> $</b><br>

                                                                            Session Date: <input type="date" name="date"
                                                                                class="input-text" placeholder="The Day That Suits You"
                                                                                required value="<?php echo $today; ?>">
                                                                            <br>

                                                                        </div>
                                                                        <br>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td style="width: 25%;">
                                                                <div class="dashboard-items search-items">
                                                                    <div style="width:100%;padding-top: 15px;padding-bottom: 15px;">
                                                                        <div class="h1-search"
                                                                            style="font-size:20px;line-height: 35px;margin-left:8px;text-align:center;">
                                                                            Are you sure about the booking process? <br>
                                                                            You will be transferred to the <u>payment form</u>!
                                                                        </div>
                                                                        <!-- <center>
                                                                            <div class=" dashboard-icons"
                                                                                style="margin-left: 0px;width:90%;font-size:70px;font-weight:800;text-align:center;color:var(--btnnictext);background-color: var(--btnice)">
                                                                                1</div>
                                                                        </center> -->

                                                                        <br>
                                                                        <br>

                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <tr>
                                                                <td>
                                                                    <!-- BTN_BOOKNOW Name: booknow -->
                                                                    <input type="Submit" class="login-btn btn-primary btn btn-book"
                                                                        style="margin-left:10px;padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;width:95%;text-align: center;"
                                                                        value="Book now" name="booknow"></button>
                                                                </td>
                                                            </tr>

                                                        </form>
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