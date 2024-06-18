<?php
// تبدأ الجلسة لاستخدام متغيرات الجلسة في الصفحة
session_start();

include ("../connection.php"); // تضمين ملف الاتصال بقاعدة البيانات

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Successful Payment Page</title>
    <!-- تصميم الصفحة بواسطة CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
            margin-bottom: 20px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:focus {
            outline: none;
        }
    </style>

    <!-- سكريبت جافا سكريبت لتحديد سلوك زر الاستمرار -->
    <script>
        document.getElementById("continueButton").addEventListener("click", function () {
            // يمكن توجيه المستخدم إلى الصفحة التالية أو تنفيذ أي عمل آخر هنا
            // في هذا المثال، سنقوم بعرض تنبيه
            alert("Redirecting you to the next page...");
            // يمكن استبدال التنبيه بمنطق التوجيه الفعلي
        });
    </script>
</head>

<body>
    <?php
    // يتحقق مما إذا كانت هناك جلسة مفعلة
    if (isset($_SESSION['id'])) {
        // يتحقق من نوع الحساب
        if ($_SESSION['account_type'] == '1') {
            // يتحقق مما إذا كانت هناك بيانات مرسلة عبر الطلب
            if (isset($_GET['session_id'], $_GET['patient_id'], $_GET['date'], $_GET['doctor_id'])) {
                // استقبال البيانات المرسلة عبر الطلب
                $session_id = $_GET['session_id'];
                $patient_id = $_GET['patient_id'];
                $date = $_GET['date'];
                $doctor_id = $_GET['doctor_id'];

                // إجراء استعلام SQL لإدخال بيانات الموعد في قاعدة البيانات
                $sql = "INSERT INTO appointments (session_date_id, patient_id, date, doctor_id) VALUES ('$session_id', '$patient_id', '$date', '$doctor_id')";
                // التحقق من نجاح التحديث في قاعدة البيانات
                if ($conn->query($sql) !== TRUE) {
                    $error = '2';
                    // توجيه المستخدم إلى صفحة الحجز مع رمز الخطأ
                    header("location: booking.php?error=" . $error . "&id=" . $session_id);
                    exit();
                }
            }
            ?>
            <!-- عرض صفحة الدفع الناجح -->
            <div class="container">
                <h1>Payment Successful</h1>
                <p>Your payment has been successfully processed. You now have access to the content.</p>
                <!-- زر للمتابعة -->
                <a href="appointment.php">
                    <button id="continueButton">Continue</button>
                </a>
            </div>
            <?php
        } else {
            // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول في حالة عدم وجود حساب صالح
            header("location: ../login.php");
        }
    } else {
        // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول في حالة عدم وجود جلسة نشطة
        header("location: ../login.php");
    }
    ?>
</body>

</html>