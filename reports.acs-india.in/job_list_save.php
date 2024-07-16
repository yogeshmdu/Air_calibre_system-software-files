<?php

require 'web_acsdb.php';

if (isset($_GET["updateIndicator"])) {
    $updateInd = $_GET["updateIndicator"];
}

// Retrieve other form fields
$job_card_no = $mysqli->real_escape_string($_POST['job_card_no']);
$report_id = $mysqli->real_escape_string($_POST['report_id']);
$contract_cust_name = $mysqli->real_escape_string($_POST['customer_name']);
$cust_address = $mysqli->real_escape_string($_POST['cust_address']);

$emp_in_time = $mysqli->real_escape_string($_POST['emp_in_time']);
$emp_out_time = $mysqli->real_escape_string($_POST['emp_out_time']);
$job_date = $mysqli->real_escape_string($_POST['job_date']);
$selectedInstruments = isset($_POST['instr_used'])? $_POST['instr_used'] : [];
$instr_used = implode(',', $selectedInstruments);

$selectedemp = isset($_POST['testing_engg'])? $_POST['testing_engg'] : [];
$testing_engg = implode(',', $selectedemp);

$shift_status = $mysqli->real_escape_string($_POST['shift_status']);

// Handle the selected instruments
$carried_Instruments = isset($_POST['carried_out']) ? $_POST['carried_out'] : [];
$carried_out_instr = implode(',', $carried_Instruments);

$job_closing_remark = $mysqli->real_escape_string($_POST['job_closing_remarks']);

if ($updateInd == 1) {
    $updateQuery = "UPDATE employee_job_list SET 
                        customer_name='$contract_cust_name', 
                        cust_address='$cust_address',
                        emp_in_time='$emp_in_time',
                        emp_out_time='$emp_out_time',
                        shift_status='$shift_status',
                        instr_used='$instr_used',
                        carried_out='$carried_out_instr',
                        testing_engg='$testing_engg',
                        job_closing_remarks= '$job_closing_remark'
                    WHERE report_id='$report_id' AND job_date='$job_date'";

    if ($mysqli->query($updateQuery) === TRUE) {
        $message = " Id: " . $job_card_no . "Room details successfully updated...";
    } else {
        $message = "Error: " . $mysqli->error;
    }
} else {
    // Insert into the database
    $query = "INSERT INTO employee_job_list(job_card_no, report_id, customer_name, cust_address, emp_in_time, emp_out_time, job_date, instr_used, shift_status, carried_out, testing_engg, job_closing_remarks) 
                    VALUES ('$job_card_no', '$report_id', '$contract_cust_name', '$cust_address', '$emp_in_time', '$emp_out_time', '$job_date', '$instr_used', '$shift_status', '$carried_out_instr', '$testing_engg', '$job_closing_remark')";

    if ($mysqli->query($query)) {
        $job_card_no = $mysqli->insert_id;
        $message = "Record Id: " . $job_card_no . "Room details successfully created...";
    } else {
        $message = "Error: " . $mysqli->error;
    }
}

echo "Connection established";
header("location: job_service_sheet.php?report_id=$report_id&test_date=$job_date&message=$message");
exit;

?>