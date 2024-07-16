<?php

ob_start();
// if (!isset($_SESSION['username'])) {
//     header("location: errorpage.php");
// }

// echo ' report save php';
require('web_acsdb.php');
$created_by = $_SESSION['username'];

// echo isset($_GET['save']);

// ==================================================== AHU MASTER DATA ====================================================
// if ($_GET['save'] == 'DS') {

//     $report_id = trim($_POST['report_id']);
//     $witness_by = $_POST['witness_by'];
//     $test_area = $_POST['tested_area'];
//     $testing_date = $_POST['testing_date'];
//     $testing_due_date = $_POST['testing_due_date'];
//     $department = $_POST['department'];
//     $test_reference = $_POST['test_reference'];
//     $test_condition = $_POST['test_condition'];
//     $iso_class = $_POST['iso_class'];
//     $testing_eng = $_POST['testing_engg'];
//     $testing_date = date("Y-m-d", strtotime(str_replace('/', '-', $testing_date)));
//     // echo "sdsd ".$testing_date;
//     $testing_due_date = date("Y-m-d", strtotime(str_replace('/', '-', $testing_due_date)));
//     // echo $testing_date;
//     $result = $mysqli->query("UPDATE customer_reports_master SET 
//             witness_by = '$witness_by',
//             test_area = '$test_area',
//             testing_date = '$testing_date',
//             testing_due_date = '$testing_due_date',
//             department = '$department',
//             test_reference = '$test_reference',
//             test_condition = '$test_condition',
//             iso_class = '$iso_class',
//             testing_engg = '$testing_eng',
//             created_by = '$created_by'
//             WHERE report_id = '$report_id'");

//     $message = "CRM successfully updated";
//     header("location: rep_datasheet.php?report_id=$report_id" . "&message=" . $message);
// }

//if($_GET['delete'] == 'ahu_det') {
//
//    $message="AHU successfully deleted";
//    header("location: rep_datasheet.php?report_id=$report_id"."&message=".$message);
//
//}

// ==================================================== EDIT THE VALUES AND SAVE ====================================================

if ($_GET['save'] == 'ahu_det') {
    // echo "Inside ahu_det save as well";
    $report_id = $_POST['report_id'];
    $ahu_name = strtoupper(trim($_POST['ahu_name']));
    $room_id = $_POST['ahu_room_id'];
    // echo $room_id;
    $room_details = $_POST['room_details'];
    // if ($room_details == true){
    //     $updated_date = date_timestamp_get()
    // }
    $category = $_POST['category'];
    $av_qty = $_POST['av_qty'];
    $av_area = $_POST['av_area'];
    $pc_loc_nos = $_POST['pc_loc'];
    $pc_cycle = $_POST['pc_cycle'];
    $room_iso_class = $_POST['room_iso_class'];
    // echo 'ISO class '.$room_iso_class;
    $av_room_volume = $_POST['av_room_volume'];
    $filter_integrity_qty = $_POST['filter_integrity_qty'];

    $last_updated_date = date('Y-m-d');
    $entered_by_user = $created_by;

    $pc_room_area = $_POST['pc_room_area'];
    $pc_volume = $_POST['pc_volume'];
    $pc_time = $_POST['pc_time'];

    $dp_quty = $_POST['dp_quty'];
    $lg_qty = $_POST['lg_qty'];
    $sl_qty = $_POST['sl_qty'];
    $temp_rh_qty = $_POST['temp_rh_qty'];
    $rc_qty = $_POST['rc_qty'];

    $witness_by = $_POST['witness_by'];
    $test_area = $_POST['tested_area'];
    $testing_date = $_POST['testing_date'];


    $testing_due_date = $_POST['testing_due_date'];
    if($testing_due_date = false) {
        $testing_due_date = NULL;
    } else {
        $testing_due_date = $_POST['testing_due_date'];
    }
    $department = $_POST['department'];
    $test_reference = $_POST['test_reference'];
    $test_condition = $_POST['test_condition'];

    $testing_eng = $_POST['testing_engg'];
    $testing_eng = implode(',', $_POST['testing_engg']);
    
    $av_instrument_used = $_POST['av_instrument_used'];
    if($av_instrument_used != NULL) {
        $av_instrument_used = implode(',', $_POST['av_instrument_used']);
    }

    $pc_instrument_used = $_POST['pc_instrument_used']; //implode(', ', $_POST['pc_instrument_used']);
    if($pc_instrument_used != NULL) {
        $pc_instrument_used = implode(',', $_POST['pc_instrument_used']);
    }

    $fi_instrument_used = $_POST['fi_instrument_used']; //$_POST['fi_instrument_used'];
    if($fi_instrument_used != NULL) {
        $fi_instrument_used = implode(',', $_POST['fi_instrument_used']);
    }

    $dp_instrument_used = $_POST['dp_instrument_used'];
    if($dp_instrument_used != NULL) {
        $dp_instrument_used = implode(',', $_POST['dp_instrument_used']);
    }
    
    $lg_instrument_used = $_POST['lg_instrument_used'];
    if($lg_instrument_used != NULL) {
        $lg_instrument_used = implode(',', $_POST['lg_instrument_used']);
    }
   
    $sl_instrument_used = $_POST['sl_instrument_used'];
    if($sl_instrument_used != NULL) {
        $sl_instrument_used = implode(',', $_POST['sl_instrument_used']);
    }

    $temp_rh_instrument_used = $_POST['temp_rh_instrument_used'];
    if($temp_rh_instrument_used != NULL) {
        $temp_rh_instrument_used = implode(',', $_POST['temp_rh_instrument_used']);
    }

    $rc_instrument_used = $_POST['rc_instrument_used'];
    if($rc_instrument_used != NULL) {
        $rc_instrument_used = implode(',', $_POST['rc_instrument_used']);
    }
    $av_txtarea = $_POST['av_txtarea'];
    $fi_txtarea = $_POST['fi_txtarea'];
    $pc_txtarea = $_POST['pc_txtarea'];
    $dp_txtarea = $_POST['dp_txtarea'];
    $temp_txtarea = $_POST['temp_txtarea'];
    $sl_txtarea = $_POST['sl_txtarea'];
    $rc_txtarea = $_POST['rc_txtarea'];
    $lux_txtarea = $_POST['lux_txtarea'];

    // report_id = '$report_id',
    $new_record_id = 0;
    $ahu_seq_no = 0;
    if (isset($_GET["ahu_seq_no"])) {
        $ahu_seq_no = $_GET["ahu_seq_no"];
    }
    //  echo "ahu  ".$ahu_seq_no;
    // ==================================================== AHU UPDATE SECTION ====================================================
    if (isset($_GET['edit']) == 'yes') {

        $ahu_upd_query = "UPDATE reports_ahu_details SET  
                ahu_name = '$ahu_name', 
                room_details = '$room_details', 
                category = $category,
                av_qty = '$av_qty',  
                av_area = '$av_area',  
                pc_loc_nos = '$pc_loc_nos',  
                pc_room_area = '$pc_room_area', 
                av_room_volume = '$av_room_volume', 
                filter_integrity_qty = '$filter_integrity_qty', 
                pc_volume = '$pc_volume',
                pc_time = '$pc_time',
                pc_cycle= '$pc_cycle', 

                dp_quty = '$dp_quty',
                lg_qty = '$lg_qty',
                sl_qty = '$sl_qty',
                temp_rh_qty = '$temp_rh_qty',
                rc_qty = '$rc_qty',

                room_iso_class= '$room_iso_class',
                witness_by = '$witness_by',
                tested_area = '$test_area',
                testing_date = '$testing_date',
                testing_due_date = '$testing_due_date',
                department = '$department',
                test_reference = '$test_reference',
                test_condition = '$test_condition',
                testing_engg = '$testing_eng',
                av_instrument_used = '$av_instrument_used',
                pc_instrument_used = '$pc_instrument_used',
                fi_instrument_used = '$fi_instrument_used',
                dp_instrument_used = '$dp_instrument_used',
                lg_instrument_used = '$lg_instrument_used',
                sl_instrument_used = '$sl_instrument_used',
                temp_rh_instrument_used = '$temp_rh_instrument_used',
                rc_instrument_used = '$rc_instrument_used',

                av_txtarea = '$av_txtarea',
                fi_txtarea = '$fi_txtarea',
                pc_txtarea = '$pc_txtarea',
                rc_txtarea = '$rc_txtarea',
                dp_txtarea = '$dp_txtarea',
                temp_txtarea = '$temp_txtarea',
                lux_txtarea = '$lux_txtarea',
                sl_txtarea = '$sl_txtarea',
                
                last_updated_date = '$last_updated_date', 
                entered_by_user = '$entered_by_user' where report_id = '$report_id' and room_id = '$room_id' ";
        //  echo $ahu_upd_query;
        $mysqli->query($ahu_upd_query) or die($mysqli);
        $message = "AHU/Equipment : " . $ahu_name . " Room Id: " . $room_id . " details successfully updated...";
    }
    // ==================================================== AHU INSERTING NEW ROOM ====================================================
    else {
        $mysqli->query("INSERT INTO reports_ahu_details (report_id, ahu_name, room_id, room_details, category, av_qty, av_area, pc_loc_nos, pc_room_area, av_room_volume, filter_integrity_qty, rc_qty, pc_volume, pc_time, last_updated_date, entered_by_user, pc_cycle, room_iso_class, tested_area, department, test_condition, test_reference, witness_by, testing_date, testing_due_date, av_instrument_used, pc_instrument_used, fi_instrument_used, dp_instrument_used, dp_quty, lg_instrument_used, lg_qty ,sl_qty, temp_rh_qty, sl_instrument_used, temp_rh_instrument_used, rc_instrument_used, testing_engg, av_txtarea, fi_txtarea, pc_txtarea, rc_txtarea, dp_txtarea, lux_txtarea, sl_txtarea, temp_txtarea)
            VALUES ('$report_id', '$ahu_name', '$room_id', '$room_details', $category, '$av_qty', '$av_area', '$pc_loc_nos', '$pc_room_area', '$av_room_volume', '$filter_integrity_qty', '$rc_qty', '$pc_volume', '$pc_time', '$last_updated_date', '$entered_by_user', '$pc_cycle', '$room_iso_class','$test_area','$department','$test_condition','$test_reference','$witness_by','$testing_date', '$testing_due_date','$av_instrument_used', '$pc_instrument_used', '$fi_instrument_used', '$dp_instrument_used', '$dp_quty', '$lg_instrument_used', '$lg_qty', '$sl_qty', '$temp_rh_qty', '$sl_instrument_used', '$temp_rh_instrument_used', '$rc_instrument_used', '$testing_eng', '$av_txtarea', '$fi_txtarea', '$pc_txtarea', '$rc_txtarea', '$dp_txtarea', '$lux_txtarea', '$sl_txtarea', '$temp_txtarea')") or die($mysqli);

        $ahu_seq_no = $mysqli->insert_id;

        $message = "Record Id: " . $ahu_seq_no . " Room details successfully created...";

    }
    // echo $message;
    header("location: rep_datasheet.php?report_id=$report_id"."&message=".$message);

    ob_end_flush();
}