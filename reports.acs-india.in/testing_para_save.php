<?php
ob_start();

require('web_acsdb.php');

$test_description = $_POST['test_description'];

$mysqli->query("INSERT INTO test_carried_details (test_description)
                    VALUES ('$test_description')") or die($mysqli);

$report_id = $mysqli->insert_id;

$message = "Record Id: " . $report_id . " Room details successfully created...";


// echo $message;
header("location: test_carried_parameter.php?&message=.$message");

ob_end_flush();

?>