<?php

// ini_set('error_log', 'test.log');
ob_start();
include('web_acsdb.php');
$selectedIQC_date = date("d-m-Y");
if (isset($_GET["iqc_date"])) {
    $selectedIQC_date = $_GET[date("d-m-Y")];
}

$selectedReportId = "";
if (isset($_GET['report_id'])) {
    $selectedReportId = $_GET['report_id'];
}

// error_log("Before Save");

if ($_GET['save'] == 'iqc_data') {

    $iqc_date = $_POST['iqc_date'];

    // error_log('Inside  Save...');
    $data = $_POST['data'];

    // error_log(print_r($data));
    // Process the received data as needed
    foreach ($data as $rowIndex => $rowData) {
        $iqc_seq_no = $rowIndex;
        $iqc_inst = $rowData['instr_name'];
        $report_id = $selectedReportId;
        $iqc_instid = $rowData['instr_id'];
        $iqc_parameter = $rowData['iqc_parameter'];
        $iqc_accp_var = $rowData['iqc_accp_var'];
        $iqc_var_obs = $rowData['iqc_var_obs'];
        $iqc_result = $rowData['iqc_result'];
        $iqc_prepared = $rowData['iqc_prepared'];
        $iqc_verified = $rowData['iqc_verified'];
        $iqc_remarks = $rowData['iqc_remarks'];
     

    
    //$new_row = 0;
  
    
        $mysqli->query("INSERT INTO internal_qty_ctrl (iqc_seq_no, report_id, iqc_date, inst_name, inst_id, parameter_check, acceptable_variation, variation_obs, result, prepared_by, verified, iqc_remarks) 
            VALUES ('$iqc_seq_no', '$selectedReportId', '$iqc_date', '$iqc_inst', '$iqc_instid', '$iqc_parameter', '$iqc_accp_var', '$iqc_var_obs', '$iqc_result', '$iqc_prepared', '$iqc_verified', '$iqc_remarks')") or die($mysqli);

        $new_row = $mysqli->insert_id;

        $message = "ACS: " . $selectedReportId . " IQC details successfully created...";
    
    }
    header("location: iqc_view_data.php?report_id=$selectedReportId"."&message=".$message);

    ob_end_flush();
}