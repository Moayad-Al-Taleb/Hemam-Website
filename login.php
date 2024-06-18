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
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>

<body>

    <?php
    $error = '';

    if (isset($_POST['submit'])) {
        require ('connection.php');
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql_patients = "SELECT * FROM patients WHERE email='$email' AND password='$password'";
        $result_patients = $conn->query($sql_patients);

        $sql_doctors = "SELECT * FROM doctors WHERE email='$email' AND password='$password'";
        $result_doctors = $conn->query($sql_doctors);

        $sql_admins = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
        $result_admins = $conn->query($sql_admins);

        if ($result_patients->num_rows == 1) {
            $row = $result_patients->fetch_assoc();
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['address'] = $row['address'];
            $_SESSION['mobile_number'] = $row['mobile_number'];
            $_SESSION['account_type'] = $row['account_type'];
            $_SESSION['account_status'] = $row['account_status'];

            header("Location: patient/index.php");
            exit();
        } elseif ($result_doctors->num_rows == 1) {
            $row = $result_doctors->fetch_assoc();
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['address'] = $row['address'];
            $_SESSION['mobile_number'] = $row['mobile_number'];
            $_SESSION['account_type'] = $row['account_type'];
            $_SESSION['account_status'] = $row['account_status'];

            header("Location: doctor/index.php");
            exit();
        } elseif ($result_admins->num_rows == 1) {
            $row = $result_admins->fetch_assoc();
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['account_type'] = $row['account_type'];

            header("Location: admin/index.php");
            exit();
        } else {
            $error = 'Invalid email or password';
        }

        $conn->close();

    }
    ?>

    <center>
        <div class="container">
            <table border="0" style="margin: 0;padding: 0;width: 60%;">
                <tr>
                    <td>
                        <p class="header-text">Welcome Back!</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="sub-text">Login with your details to continue</p>
                    </td>
                </tr>
                <tr>
                    <form action="" method="POST">
                        <td class="label-td">
                            <label for="useremail" class="form-label">Email: </label>
                        </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="email" name="email" class="input-text" placeholder="Email Address" required>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <label for="userpassword" class="form-label">Password: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="Password" name="password" class="input-text" placeholder="Password" required>
                    </td>
                </tr>
                <tr>
                    <td><br>
                        <?php echo $error ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Login" class="login-btn btn-primary btn" name="submit">
                    </td>
                </tr>
                <tr>
                    <td>
                        <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Don't have an account&#63; </label>
                        <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                        <br><br><br>
                    </td>
                </tr>
                </form>
            </table>
        </div>
    </center>
</body>

</html>