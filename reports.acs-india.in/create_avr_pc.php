<?php
// ============================================ TO SAVE ALL THE PARAMETER ============================================ 
ob_start();

require('web_acsdb.php');
if (isset($_GET["ahu_seq_no"])) {
    $sel_ahu_seq_no = $_GET['ahu_seq_no'];
}

$ahu_result = $mysqli->query("SELECT * FROM reports_ahu_details where ahu_seq_no = $sel_ahu_seq_no") or die($mysqli);
$row = $ahu_result->fetch_assoc();

$av_filter_qty = $row['av_qty'];
$pc_location = $row['pc_loc_nos'];
$filter_integrity_qty = $row['filter_integrity_qty'];

$dp_quty = $row['dp_quty'];
$lg_qty = $row['lg_qty'];
$sl_qty = $row['sl_qty'];
$temp_rh_qty = $row['temp_rh_qty'];
$rc_qty = $row['rc_qty'];

$report_id = $row['report_id'];
$room_id = $row['room_id'];
$testing_eng = $row['testing_engg'];

// ============================================ Air velocity report print the number of quantity ============================================ 

$av_result = $mysqli->query("SELECT count(*) as avcount FROM air_velocity where ahu_seq_no = $sel_ahu_seq_no") or die($mysqli);
$row1 = $av_result->fetch_assoc();
//echo 'av count '.$row1['avcount'];
$av_filter_qty = $av_filter_qty - $row1['avcount'];
//echo ' difference is '.$av_filter_qty;
if ($av_filter_qty > 0) {

    for ($x = 1; $x <= $av_filter_qty; $x++) {
        //        echo "The av number is: $x <br>";
        $tmp_fc_code = "TMP-FC-" . $x;

        $mysqli->query("INSERT INTO air_velocity (ahu_seq_no, filter_code, report_id)
        VALUES ($sel_ahu_seq_no, '$tmp_fc_code', '$report_id')") or die($mysqli);
    }
    $message = "AV records successfully updated...";
}

// ============================================ Particle count report print the number of location ============================================ 

$pc_result = $mysqli->query("SELECT count(*) as pccount FROM reports_pc_data where ahu_seq_no = $sel_ahu_seq_no") or die($mysqli);
$row2 = $pc_result->fetch_assoc();
//echo 'row count '.$row2['pccount'];
$pc_location = $pc_location - $row2['pccount'];
// echo $pc_location;
if ($pc_location > 0) {
    $pc_loc_seq_no = 0;
    for ($x = 1; $x <= $pc_location; $x++) {
        //        echo "The pc location number is: $x <br>";
        // $pc_loc_seq_no = "Loc-" . $x;

        $mysqli->query("INSERT INTO reports_pc_data (ahu_seq_no, report_id, room_id, pc_loc_seq_no)
        VALUES ($sel_ahu_seq_no, '$report_id', '$room_id','$x' )") or die($mysqli);
    }
    $message = " PC  records successfully updated...";
}
//echo $message;

// ============================================ Filter integrity report print the number of quantity ============================================ 

$fi_result = $mysqli->query("SELECT count(*) as ficount FROM reports_filter_integrity where ahu_seq_no = $sel_ahu_seq_no") or die($mysqli);
$row3 = $fi_result->fetch_assoc();
//echo 'row count '.$row2['pccount'];
$fi_count = $filter_integrity_qty - $row3['ficount'];

if ($fi_count > 0) {
    //    $fi_code_id = 0;
    $tmp_fi_code = 0;
    for ($x = 1; $x <= $fi_count; $x++) {
        //        echo "The Fi location number is: $x <br>";
        $tmp_fi_code = "HF-" . $x;

        $mysqli->query("INSERT INTO reports_filter_integrity (ahu_seq_no, report_id ,room_id, filter_name)
        VALUES ($sel_ahu_seq_no, '$report_id', '$room_id', '$tmp_fi_code')") or die($mysqli);
    }
    $message = "FI records successfully updated...";
}

// ============================================ Differential Pressure report print the number of quanity ============================================ 

$dp_result = $mysqli->query("SELECT count(*) as dpcount FROM reports_differ_pressure where ahu_seq_no = $sel_ahu_seq_no") or die($mysqli);
$row4 = $dp_result->fetch_assoc();

$dp_quty = $dp_quty - $row4['dpcount'];
// echo $dp_quty;
if ($dp_quty > 0) {
    $dp_code = 0;
    for ($x = 1; $x <= $dp_quty; $x++) {
        //        echo "The Dp location number is: $x <br>";
        $dp_code = "Dp -" . $x;

        $mysqli->query("INSERT INTO reports_differ_pressure (ahu_seq_no, report_id, room_id, differ_name)
        VALUES ($sel_ahu_seq_no, '$report_id', '$room_id', '$dp_code')") or die($mysqli);
    }
    $message = "DP records successfully updated...";
}

// ============================================ Light intensity report print the number of location ============================================ 

$lux_result = $mysqli->query("SELECT count(*) as luxcount FROM reports_lux_data where ahu_seq_no = $sel_ahu_seq_no") or die($mysqli);
$row5 = $lux_result->fetch_assoc();

$lg_qty = $lg_qty - $row5['luxcount'];
// echo $lg_qty;
if ($lg_qty > 0) {
    $lux_code = 0;
    for ($x = 1; $x <= $lg_qty; $x++) {
        //    echo "The lux location number is: $x <br>";
        $lux_code = " ";

        $mysqli->query("INSERT INTO reports_lux_data (ahu_seq_no, report_id, room_id, status)
        VALUES ($sel_ahu_seq_no, '$report_id', '$room_id', '$lux_code')") or die($mysqli);
    }
    $message = "LUX records successfully updated...";
}

// ============================================ Sound level report print the number of location ============================================ 

$sl_result = $mysqli->query("SELECT count(*) as slcount FROM reports_sl_data where ahu_seq_no = $sel_ahu_seq_no") or die($mysqli);
$row6 = $sl_result->fetch_assoc();

$sl_qty = $sl_qty - $row6['slcount'];
// echo $sl_qty;
if ($sl_qty > 0) {
    $sl_code = 0;
    for ($x = 1; $x <= $sl_qty; $x++) {
        //    echo "The SL location number is: $x <br>";
        $sl_code = "SL-" . $x;

        $mysqli->query("INSERT INTO reports_sl_data (ahu_seq_no, report_id, room_id, sl_name)
        VALUES ($sel_ahu_seq_no, '$report_id', '$room_id', '$sl_code')") or die($mysqli);
    }
    $message = "SL records successfully updated...";
}

// ============================================ Temperature RH report print the number of location ============================================ 

$temp_rh_result = $mysqli->query("SELECT count(*) as temprhcount FROM reports_temp_rh_data where ahu_seq_no = $sel_ahu_seq_no") or die($mysqli);
$row7 = $temp_rh_result->fetch_assoc();

$temp_rh_qty = $temp_rh_qty - $row7['temprhcount'];
// echo $temp_rh_qty;
if ($temp_rh_qty > 0) {
    $temprh_code = 0;
    for ($x = 1; $x <= $temp_rh_qty; $x++) {
        //    echo "The TEMP RH location number is: $x <br>";
        $temprh_code = "TEMP-RH" . $x;

        $mysqli->query("INSERT INTO reports_temp_rh_data (ahu_seq_no, report_id, room_id, temp_rh_name)
        VALUES ($sel_ahu_seq_no, '$report_id', '$room_id', '$temprh_code')") or die($mysqli);
    }
    $message = "TEMP-RH records successfully updated...";
}

// ============================================ Recovery Count Test report print the number of location ============================================ 

$rc_result = $mysqli->query("SELECT count(*) as rccount FROM reports_rc_data where ahu_seq_no = $sel_ahu_seq_no") or die($mysqli);
$row8 = $rc_result->fetch_assoc();

$rc_qty = $rc_qty - $row8['rccount'];
// echo $rc_qty;
if ($rc_qty > 0) {
    $rc_code = 0;
    for ($x = 1; $x <= $rc_qty; $x++) {
        //    echo "The RC location number is: $x <br>";
        //    $rc_code = "RC" . $x;

        $mysqli->query("INSERT INTO reports_rc_data (ahu_seq_no, report_id, room_id, status )
        VALUES ($sel_ahu_seq_no, '$report_id', '$room_id', '$x')") or die($mysqli);
    }
    $message = "RC records successfully updated...";
}

header("Location: reports_ahu_details.php?edit=1&ahu_seq_no=" . $sel_ahu_seq_no . "&message=" . $message);

ob_end_flush();
?> 