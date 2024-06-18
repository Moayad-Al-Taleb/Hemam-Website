<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/signup.css">

    <title>Sign Up</title>

</head>

<body>

    <?php

    $error = '';

    $name = $email = $password = $address = $mobile_number = '';
    $name_error = $email_error = $password_error = $address_error = $mobile_number_error = '';

    if (isset($_POST['submit'])) {
        if (empty($_POST['fname']) && empty($_POST['lname'])) {
            $name_error = 'Required field *';
        } else {
            $name = $_POST['fname'] . " " . $_POST['lname'];
        }

        if (empty($_POST['email'])) {
            $email_error = 'Required field *';
        } else {
            $email = $_POST['email'];
        }

        if (empty($_POST['password'])) {
            $password_error = 'Required field *';
        } else {
            $password = $_POST['password'];
        }

        if (empty($_POST['address'])) {
            $address_error = 'Required field *';
        } else {
            $address = $_POST['address'];
        }

        if (empty($_POST['mobile_number'])) {
            $mobile_number_error = 'Required field *';
        } else {
            $mobile_number = $_POST['mobile_number'];
        }

        if (!empty($name) && !empty($email) && !empty($password) && !empty($address) && !empty($mobile_number)) {
            // (?=.*[a-z]) يفحص ما إذا كانت هناك حرف صغير على الأقل.
            // (?=.*[A-Z]) يفحص ما إذا كانت هناك حرف كبير على الأقل.
            // (?=.*\d) يفحص ما إذا كان هناك رقم واحد على الأقل.
            // (?=.*[@$!%*?&]) يفحص ما إذا كانت هناك علامة مميزة واحدة على الأقل.
            // [A-Za-z\d@$!%*?&]{8,} يفحص ما إذا كانت الباسوورد تحتوي على أحرف كبيرة وصغيرة وأرقام وعلامات مميزة فقط، وأن يكون طولها على الأقل 8 أحرف.
            // إذا لم تتطابق الباسوورد مع هذا الشرط، ستعرض رسالة خطأ مناسبة.
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                $error = 'Password should contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long';
            } elseif (!preg_match('/^05/', $mobile_number)) {
                $error = 'Mobile number should start with "05"';
            } else {
                require ('connection.php');

                $email_check_query_patients = "SELECT * FROM patients WHERE email='$email' LIMIT 1";
                $email_result_patients = $conn->query($email_check_query_patients);

                $email_check_query_doctors = "SELECT * FROM doctors WHERE email='$email' LIMIT 1";
                $email_result_doctors = $conn->query($email_check_query_doctors);

                $email_check_query_admins = "SELECT * FROM admins WHERE email='$email' LIMIT 1";
                $email_result_admins = $conn->query($email_check_query_admins);

                if (($email_result_patients->num_rows > 0) || ($email_result_doctors->num_rows > 0) || ($email_result_admins->num_rows > 0)) {
                    $error = 'Sorry, the email is already in the database';
                } else {
                    $sql = "INSERT INTO patients (name, email, password, address, mobile_number) VALUES ('$name', '$email', '$password', '$address', '$mobile_number')";
                    if ($conn->query($sql) === TRUE) {
                        header("Location: login.php");
                        exit();
                    } else {
                        $error = 'An error occurred while adding data' . $conn->error;
                    }
                }
                $conn->close();

            }
        }
    }
    ?>

    <center>
        <div class="container">
            <table border="0">
                <tr>
                    <td colspan="2">
                        <p class="header-text">Let's Get Started</p>
                        <p class="sub-text">Add Your Personal Details to Continue</p>
                    </td>
                </tr>
                <tr>
                    <form action="" method="POST">
                        <table>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name:</label>
                                    <span>
                                        <?php echo $name_error; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td">
                                    <input type="text" name="fname" class="input-text" placeholder="First Name">
                                </td>
                                <td class="label-td">
                                    <input type="text" name="lname" class="input-text" placeholder="Last Name">
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="email" class="form-label">Email:</label>
                                    <span>
                                        <?php echo $email_error; ?>
                                    </span>

                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="email" name="email" class="input-text" placeholder="Email">
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="password" class="form-label">Password:</label>
                                    <span>
                                        <?php echo $password_error; ?>
                                    </span>

                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="password" name="password" class="input-text"
                                        placeholder="Password (Pa$$w0rd!)">
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="address" class="form-label">Address:</label>
                                    <span>
                                        <?php echo $address_error; ?>
                                    </span>

                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="address" class="input-text" placeholder="Address">
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="mobile_number" class="form-label">Mobile Number:</label>
                                    <span>
                                        <?php echo $mobile_number_error; ?>
                                    </span>

                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="tel" name="mobile_number" class="input-text"
                                        placeholder="Mobile Number">
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2"></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
                                </td>
                                <td>
                                    <input type="submit" value="Next" class="login-btn btn-primary btn" name="submit">
                                </td>
                            </tr>
                            <tr>
                                <td><br>
                                    <?php echo $error ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br>
                                    <label for="" class="sub-text" style="font-weight: 280;">Already have an
                                        account?</label>
                                    <a href="login.php" class="hover-link1 non-style-link">Login</a>
                                    <br><br><br>
                                </td>
                            </tr>
                        </table>
                    </form>
            </table>

        </div>
    </center>
</body>

</html>