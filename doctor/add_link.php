<?php
// بدء الجلسة لتخزين البيانات عبر الصفحات
session_start();

// تضمين ملف الاتصال بقاعدة البيانات
include ("../connection.php");

// التحقق مما إذا كانت هناك جلسة مفتوحة
if (isset($_SESSION['id'])) {
    // التحقق من نوع الحساب وحالة الحساب للمستخدم
    if ($_SESSION['account_type'] == '2' && $_SESSION['account_status'] == '1') {

        // التحقق من وجود بيانات مرسلة عبر الطلب بواسطة POST
        if ($_POST) {

            // تهيئة المتغيرات
            $meeting_link = '';

            // التحقق من الضغط على زر الإرسال
            if (isset($_POST['submit'])) {
                // استقبال قيمة معرف الاجتماع ورابط الاجتماع
                $id = !empty($_POST['id']) ? $_POST['id'] : null;
                $meeting_link = !empty($_POST['meeting_link']) ? $_POST['meeting_link'] : null;
            }

            // التحقق من وجود رابط الاجتماع
            if (!empty($meeting_link)) {
                // تضمين ملف الاتصال بقاعدة البيانات
                require ('../connection.php');

                // التحقق من صحة رابط الاجتماع
                if (!preg_match('/^https:\/\/meet\.google\.com\/[a-z0-9-]+$/i', $meeting_link)) {
                    // إعداد رمز الخطأ للتنبيه
                    $error = '1';
                } else {
                    // تحديث رابط الاجتماع في قاعدة البيانات
                    $sql = "UPDATE appointments SET appointment_status=2, meeting_link='$meeting_link' WHERE id='$id'";
                    if ($conn->query($sql) === TRUE) {
                        // إعداد رمز الخطأ للنجاح
                        $error = '0';
                    } else {
                        // إعداد رمز الخطأ لخطأ في قاعدة البيانات
                        $error = '3';
                    }
                }
            } else {
                // إعداد رمز الخطأ لعدم وجود رابط الاجتماع
                $error = '2';
            }
            // إغلاق الاتصال بقاعدة البيانات
            $conn->close();
        }

        // إعادة توجيه المستخدم بعد إنهاء العملية
        header("location: appointment.php?action=add_link&id=" . $id . "&error=" . $error);
        exit();

    }
}

// في حالة عدم توفر جلسة، إعادة توجيه المستخدم لصفحة تسجيل الدخول
header("location: ../login.php");

?>