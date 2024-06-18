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

    <title>My Patients</title>
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

                        <?php
                        // استيراد رقم التعريف الخاص بالطبيب من الجلسة
                        $doctor_id = $_SESSION['id'];

                        // تهيئة المتغير لاحتواء القيمة الحالية
                        $current = '';

                        // فحص ما إذا كان هناك استعلام POST
                        if ($_POST) {
                            // فحص ما إذا كان هناك بحث
                            if (isset($_POST['search'])) {
                                // استيراد الكلمة المفتاحية المدخلة في حقل البحث
                                $keyword = $_POST["search"];
                                // بناء استعلام SQL يستخدم الكلمة المفتاحية ورقم التعريف الخاص بالطبيب لاستعلام المرضى
                                $sqlmain = "select * from patients where (patients.account_status=1 AND patients.id IN (SELECT DISTINCT appointments.patient_id FROM appointments WHERE appointments.doctor_id = $doctor_id)) AND (email='$keyword' or name='$keyword' or name like '$keyword%' or name like '%$keyword' or name like '%$keyword%')";
                            } elseif (isset($_POST['filter'])) {
                                // استيراد القيمة المحددة للفلتر (عرض جميع المرضى أو المرضى الخاصين بالطبيب)
                                $showonly = !empty($_POST['showonly']) ? $_POST['showonly'] : null;

                                if ($showonly == 'my') {
                                    // تحديد القيمة الحالية كـ "My patients Only"
                                    $current = "My patients Only";
                                    // بناء استعلام SQL لعرض مرضى الطبيب فقط
                                    $sqlmain = "select * from patients WHERE patients.account_status=1 AND patients.id IN (SELECT DISTINCT appointments.patient_id FROM appointments WHERE appointments.doctor_id = $doctor_id)";
                                } elseif ($showonly == 'all') {
                                    // تحديد القيمة الحالية كـ "All patients"
                                    $current = "All patients";
                                    // بناء استعلام SQL لعرض جميع المرضى
                                    $sqlmain = "select * from patients WHERE patients.account_status=1";
                                } else {
                                    // بناء استعلام SQL لعرض مرضى الطبيب فقط بشكل افتراضي
                                    $sqlmain = "select * from patients WHERE patients.account_status=1 AND patients.id IN (SELECT DISTINCT appointments.patient_id FROM appointments WHERE appointments.doctor_id = $doctor_id)";
                                }
                            }
                        } else {
                            // بناء استعلام SQL لعرض مرضى الطبيب فقط بشكل افتراضي
                            $sqlmain = "select * from patients WHERE patients.account_status=1 AND patients.id IN (SELECT DISTINCT appointments.patient_id FROM appointments WHERE appointments.doctor_id = $doctor_id)";
                        }

                        // تنفيذ الاستعلام الرئيسي
                        $result = $conn->query($sqlmain);

                        ?>


                        <tr>
                            <td width="13%">
                                <a href="patients.php"><button class="login-btn btn-primary-soft btn btn-icon-back"
                                        style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                                        <font class="tn-in-text">Back</font>
                                    </button></a>
                            </td>
                            <td>
                                <form action="patients.php" method="post" class="header-search">
                                    <input type="search" name="search" class="input-text header-searchbar"
                                        placeholder="Search Patient name or Email">&nbsp;&nbsp;
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
                            <td colspan="4" style="padding-top:0px;width: 100%;">
                                <center>
                                    <table class="filter-container" border="0">
                                        <form action="" method="post">
                                            <td style="text-align: right;">
                                                Show Details About : &nbsp;
                                            </td>
                                            <td width="30%">
                                                <select name="showonly" id="" class="box filter-container-items"
                                                    style="width:90% ;height: 37px;margin: 0;">
                                                    <option value="" disabled selected hidden><?php echo $current ?></option>
                                                    <br />
                                                    <option value="my">My Patients Only</option><br />
                                                    <option value="all">All Patients</option><br />
                                                </select>
                                            </td>
                                            <td width="12%">
                                                <input type="submit" name="filter" value=" Filter"
                                                    class=" btn-primary-soft btn button-icon btn-filter"
                                                    style="padding: 15px; margin :0;width:100%">
                                        </form>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" style="padding-top:10px;">
                                <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">
                                    Patients (
                                    <?php echo $result->num_rows; ?>)
                                </p>
                            </td>
                        </tr>

                        <?php
                        // if ($_POST) {
                        //     $keyword = $_POST["search"];
                        //     $sqlmain = "select * from patients where email='$keyword' or name='$keyword' or name like '$keyword%' or name like '%$keyword' or name like '%$keyword%'";
                        // } else {
                        //     $sqlmain = "select * from patients order by id desc";
                        // }
                        ?>

                        <!-- START SECTION DATA DUSPLAY TABLE -->
                        <tr>
                            <td colspan="4">
                                <center>
                                    <div class="abc scroll">
                                        <table width="93%" class="sub-table scrolldown" border="0">
                                            <thead>
                                                <tr>
                                                    <th class="table-headin">Patient Name</th>
                                                    <th class="table-headin">Email</th>
                                                    <th class="table-headin">Events</th>
                                                </tr>
                                            </thead>
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
                                                                <a class="non-style-link" href="patients.php"><button
                                                                        class="login-btn btn-primary-soft btn"
                                                                        style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp;
                                                                        Show all Patients &nbsp;</font></button>
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

                                                        $status = $row["account_status"];

                                                        ?>
                                                        <tr>
                                                            <td>&nbsp
                                                                <?php echo substr($name, 0, 30) ?>
                                                            </td>

                                                            <td>&nbsp
                                                                <?php echo substr($email, 0, 30) ?>
                                                            </td>

                                                            <td>
                                                                <div style="display: flex; justify-content: center;">
                                                                    <a href="?action=view&id=<?php echo $row['id']; ?>&namenone&error=none"
                                                                        class="non-style-link">
                                                                        <button class="btn-primary-soft btn button-icon btn-view"
                                                                            style="padding-left: 40px; padding-top: 12px; padding-bottom: 12px; margin-top: 10px;">
                                                                            <font class="tn-in-text">View</font>
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

                if ($action == 'drop') {
                    $nameget = $_GET["name"];

                    ?>
                    <div id="popup1" class="overlay">
                        <div class="popup">
                            <center>
                                <h2>Are you sure?</h2>
                                <a class="close" href="patients.php">&times;</a>
                                <div class="content">
                                    You want to delete this record<br>(<?php echo substr($nameget, 0, 40) ?>).
                                </div>
                                <div style="display: flex;justify-content: center;">
                                    <a href="delete-patient.php?id=<?php echo $id; ?>" class="non-style-link"><button
                                            class="btn-primary btn"
                                            style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"
                                            <font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                                    <a href="patients.php" class="non-style-link"><button class="btn-primary btn"
                                            style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                            <font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font>
                                        </button></a>
                                </div>
                            </center>
                        </div>
                    </div>
                    <?php
                } elseif ($action == 'view') {
                    $sqlmain = "select * from patients where id='$id'";
                    $result = $conn->query($sqlmain);
                    $row = $result->fetch_assoc();
                    $name = !empty($row['name']) ? $row['name'] : null;
                    $email = !empty($row['email']) ? $row['email'] : null;
                    $address = !empty($row['address']) ? $row['address'] : null;
                    $mobile_number = !empty($row['mobile_number']) ? $row['mobile_number'] : null;
                    ?>

                    <div id="popup1" class="overlay">
                        <div class="popup">
                            <center>
                                <h2></h2>
                                <h2></h2>
                                <a class="close" href="patients.php">&times;</a>
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

                                            <tr>
                                                <td colspan="2">
                                                    <a href="patients.php"><input type="button" value="OK"
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
            <?php
        } else {
            header("location: ../login.php");
        }
    }
    ?>
</body>

</html>