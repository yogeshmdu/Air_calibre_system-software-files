<?php

require 'web_acsdb.php';

$user_id = $_GET['user_id']; // Corrected variable name
$user_password = $_GET['user_password']; // Corrected variable name

if ($mysqli->connect_errno) {
    die("Connection error! " . $mysqli->connect_error);
} else {
    $statement = $mysqli->prepare("SELECT * FROM employee WHERE user_id = ? and employee_status = 'Active'");
    $statement->bind_param("s", $user_id); // Specify the data type for the parameter
    $statement->execute();
    $statement_result = $statement->get_result();
    if ($statement_result->num_rows > 0) {
        $stored_data = $statement_result->fetch_assoc();
        if ($stored_data["user_password"] === $user_password) {
            // echo "<h1>Log in Thaliava</h1>";
            include('index.php');
        } else {
            echo "<h1>Please re-correct your password</h1>";
        }
    } else {
        echo "<h1 style='text-align: center;'>Invalid username & Please contact ACS Admin Department..,</h1>";
    }
    $statement->close();
}
?>
