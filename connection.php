<?php

$conn = new mysqli("localhost", "root", "", "db_hemam");
if ($conn->connect_error) {
    die("Connection failed:  " . $conn->connect_error);
}

?>