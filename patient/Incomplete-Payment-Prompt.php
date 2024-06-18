<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incomplete Payment Prompt</title>
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

    <script>
        document.getElementById("paymentButton").addEventListener("click", function () {
            // You can redirect the user to your payment page or perform any other action here
            // For demonstration, let's just show an alert
            alert("Redirecting you to the payment page...");
            // Replace the alert with your actual payment redirection logic
        });
    </script>
</head>

<body>
    <?php
    if (isset($_SESSION['id'])) {
        if ($_SESSION['account_type'] == '1') {
            ?>
            <div class="container">
                <h1>Complete Your Payment</h1>
                <p>It seems like your payment process was not completed. Please proceed with the payment to access the content.
                </p>
                <a href="session-dates.php">
                    <button id="paymentButton">Proceed to Payment</button>
                </a>
            </div>
            <?php
        } else {
            header("location: ../login.php");
        }
    } else {
        header("location: ../login.php");
    }

    ?>
</body>

</html>