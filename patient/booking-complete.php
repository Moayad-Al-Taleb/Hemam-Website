<?php
session_start(); // بدء الجلسة

include ("../connection.php"); // تضمين ملف الاتصال بقاعدة البيانات

if (isset($_SESSION['id'])) { // التحقق مما إذا كان هناك معرف للجلسة موجودًا
    if ($_SESSION['account_type'] == '1') { // التحقق من نوع الحساب للمستخدم

        if ($_POST) { // التحقق مما إذا كانت الطلبية نوع POST

            $error = ''; // متغير لتخزين الأخطاء
            $session_id = $doctor_id = $title = $time = $name = $email = $session_fee = $date = ''; // تعريف المتغيرات لتخزين البيانات

            if (isset($_POST['booknow'])) { // التحقق مما إذا كان زر "احجز الآن" مضغوطًا
                // جلب بيانات النموذج من المتغيرات المرسلة عبر POST
                $session_id = !empty($_POST['session_id']) ? $_POST['session_id'] : null;
                $doctor_id = !empty($_POST['doctor_id']) ? $_POST['doctor_id'] : null;
                $title = !empty($_POST['title']) ? $_POST['title'] : null;
                $time = !empty($_POST['time']) ? $_POST['time'] : null;
                $name = !empty($_POST['name']) ? $_POST['name'] : null;
                $email = !empty($_POST['email']) ? $_POST['email'] : null;
                $session_fee = !empty($_POST['session_fee']) ? $_POST['session_fee'] : null;
                $date = !empty($_POST['date']) ? $_POST['date'] : null;

                $patient_id = !empty($_SESSION['id']) ? $_SESSION['id'] : null; // جلب معرف المريض من الجلسة
            }

            // التحقق من أن جميع الحقول مملوءة
            if (!empty($session_id) && !empty($doctor_id) && !empty($title) && !empty($time) && !empty($name) && !empty($email) && !empty($session_fee) && !empty($date) && !empty($patient_id)) {
                require ('../connection.php'); // تضمين ملف الاتصال مرة أخرى لضمان وجود الاتصال

                // استعلام للتحقق مما إذا كان هناك بالفعل موعد مسجل لنفس الجلسة والتاريخ
                $sql_check_session = "SELECT * FROM appointments WHERE session_date_id = '$session_id' AND date = '$date'";
                $result_check_session = $conn->query($sql_check_session);

                // تحديد التوقيت الزمني لمدينة الرياض
                date_default_timezone_set('Asia/Riyadh');
                $today = date('Y-m-d');

                if ($result_check_session->num_rows > 0) { // إذا كان هناك موعد بالفعل
                    $error = '1'; // تعيين رمز للخطأ
                } elseif (strtotime($date) < strtotime($today)) {
                    $error = '4'; // تعيين رمز للخطأ
                } else {

                    // جلب بيانات البريد الإلكتروني للمريض من قاعدة البيانات
                    $result_patient = $conn->query("SELECT email FROM patients WHERE id='$patient_id'");
                    $row_patient = $result_patient->fetch_assoc();
                    $patient_email = $row_patient['email'];

                    // تهيئة بيانات الدفع وإنشاء جلسة دفع باستخدام Stripe API
                    require_once ('vendor/autoload.php');
                    \Stripe\Stripe::setApiKey('sk_test_51PDwMkRpPumUKsNgk43f89UcWxXBXrwietlg9R2ET0YRhX80ZuTeuv9gnwqM4nFjlrwL0dV3gwaM1bnjTo3RTUst00lMmTwVvD');

                    $mode = "payment"; // نوع الجلسة (دفع)

                    // إنشاء بيانات لإدراجها في جلسة الدفع وترميزها لاستخدامها في عنوان URL لصفحة النجاح
                    $dataInsert = [
                        "session_id" => $session_id,
                        "patient_id" => $patient_id,
                        "date" => $date,
                        "doctor_id" => $doctor_id,
                    ];

                    $encodedDataInsert = http_build_query($dataInsert);
                    $success_url = "http://localhost/Hemam_website/patient/Successful-Payment-Page.php?" . $encodedDataInsert;
                    $cancel_url = "http://localhost/Hemam_website/patient/Incomplete-Payment-Prompt.php"; // رابط العودة في حالة عدم إكمال الدفع
                    $product_name = "Pay for an interview with a doctor"; // اسم المنتج
                    $product_description = "Upon payment, the request to book an interview with a doctor will be converted to activation, and the meeting link will be sent when the time comes."; // وصف المنتج
                    $currency = "usd"; // العملة
                    $quantity = 1; // الكمية
                    $unit_amount = doubleval($session_fee) * 100; // مبلغ الدفع بالسنتات

                    // إنشاء جلسة دفع باستخدام Stripe API
                    $checkout_session = \Stripe\Checkout\Session::create([
                        "mode" => $mode,
                        "success_url" => $success_url,
                        "cancel_url" => $cancel_url,
                        "client_reference_id" => $patient_id,
                        "customer_email" => $patient_email,
                        "line_items" => [
                            [
                                'quantity' => $quantity,
                                "price_data" => [
                                    "currency" => $currency,
                                    'unit_amount' => $unit_amount,
                                    "product_data" => [
                                        "name" => $product_name,
                                        "description" => $product_description,
                                    ],
                                ]
                            ],
                        ],
                    ]);

                    // إعادة توجيه المستخدم لجلسة الدفع المنشأة
                    http_response_code(303);
                    header("location: " . $checkout_session->url);
                    exit(); // الخروج من السكربت بعد إتمام العمليات المطلوبة
                }

                $conn->close(); // إغلاق الاتصال بقاعدة البيانات
            } else {
                $error = '3'; // في حالة عدم تعبئة جميع الحقول
            }
        }

        // إعادة توجيه المستخدم لصفحة الحجز بعد انتهاء عملية الحجز
        header("location: booking.php?error=" . $error . "&id=" . $session_id);
        exit(); // الخروج من السكربت بعد إتمام العمليات المطلوبة
    }
}

// إعادة توجيه المستخدم لصفحة تسجيل الدخول في حالة عدم تسجيل الدخول أو انتهاء الجلسة
header("location: ../login.php");
?>