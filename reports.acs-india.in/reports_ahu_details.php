<?php
session_start();
require('web_acsdb.php');
if (strlen(isset($_GET["message"])) > 0) {
    echo '<script>alert("' . $_GET["message"] . '")</script>';
}
$selectedReportId = "";

if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET["report_id"];

}

$editahu = "";
if (isset($_GET["edit"])) {
    $editahu = "&edit=yes";
}

$ahu_name = "";
$room_details = "";
$av_qty = 0;
$av_area = 0;
$av_room_volume = 0;
$recovery_test = "";
$filter_integrity_qty = 0;
$pc_loc_nos = 0;
$pc_room_area = 0;
$category = 0;

$pc_volume = "";
$pc_time = "";
$dp_quty = 0;
$lg_qty = 0;
$sl_qty = 0;
$temp_rh_qty = 0;
$rc_qty = 0;

$edit = "";
$room_id = "";
$customer_name = "";
$selected_ahu_seqno = 0;
$room_iso_class = "";
$pc_cycle = 1;

$tested_area = "";
$test_condition = "";
$department = "";
$test_reference = "";
$witness_by = "";
$testing_date = "";
$testing_due_date = "";

$testing_array = "";

$av_txtarea = "";
$fi_txtarea = "";
$pc_txtarea = "";
$rc_txtarea = "";
$temp_txtarea = "";
$dp_txtarea = "";
$sl_txtarea = "";
$lux_txtarea = "";

$av_array = "";
$fi_array = "";
$pc_array = "";
$rc_array = "";
$temp_rh_array = "";
$dp_array = "";
$sl_array = "";
$lg_array = "";

//pc_loc_nos, pc_room_area, filter_integrity_qty, afp_supply_filter, afp_return_filter, afp_doors, cav_point_id, cav_test_method, cav_units, recovery_test, air_purity_test, noise_level_test, light_intensity_test, last_updated_date, entered_by_user
if (isset($_GET["ahu_seq_no"])) {
    $new = false;
    $selected_ahu_seqno = trim($_GET["ahu_seq_no"]);
    $editahu = $editahu . "&ahu_seq_no=$selected_ahu_seqno";

    $ahuresult = $mysqli->query("SELECT * FROM reports_ahu_details where ahu_seq_no = '$selected_ahu_seqno' ")
        or die($mysqli);
    $ahurow = $ahuresult->fetch_assoc();

    $selectedReportId = $ahurow['report_id'];
    $room_id = $ahurow['room_id'];
    $ahu_name = $ahurow['ahu_name'];
    $room_details = $ahurow['room_details'];
    $av_qty = $ahurow['av_qty'];
    $av_area = $ahurow['av_area'];
    $av_room_volume = $ahurow['av_room_volume'];
    $recovery_test = $ahurow['recovery_test'];
    $air_purity_test = $ahurow['air_purity_test'];
    $noise_level_test = $ahurow['noise_level_test'];
    $light_intensity_test = $ahurow['light_intensity_test'];
    $filter_integrity_qty = $ahurow['filter_integrity_qty'];
    $pc_loc_nos = $ahurow['pc_loc_nos'];
    $pc_cycle = $ahurow['pc_cycle'];
    $room_iso_class = $ahurow['room_iso_class'];

    $pc_room_area = $ahurow['pc_room_area'];

    $dp_quty = $ahurow['dp_quty'];
    $lg_qty = $ahurow['lg_qty'];
    $sl_qty = $ahurow['sl_qty'];
    $temp_rh_qty = $ahurow['temp_rh_qty'];
    $rc_qty = $ahurow['rc_qty'];

    $pc_volume = $ahurow['pc_volume'];
    $pc_time = $ahurow['pc_time'];
    $category = $ahurow['category'];
    $editParam = "&edit=yes&ahu_seq_no=" . trim($selected_ahu_seqno);

    $testing_array = explode(",", $ahurow['testing_engg']);

    $tested_area = $ahurow['tested_area'];
    $test_condition = $ahurow['test_condition'];
    $department = $ahurow['department'];
    $test_reference = $ahurow['test_reference'];
    $witness_by = $ahurow['witness_by'];
    $testing_date = $ahurow['testing_date'];
    $testing_due_date = $ahurow['testing_due_date'];
    $av_array = explode(",", $ahurow['av_instrument_used']);
    $pc_array = explode(",", $ahurow['pc_instrument_used']);
    $fi_array = explode(",", $ahurow['fi_instrument_used']);
    $dp_array = explode(",", $ahurow['dp_instrument_used']);
    $lg_array = explode(",", $ahurow['lg_instrument_used']);
    $sl_array = explode(",", $ahurow['sl_instrument_used']);
    $rc_array = explode(",", $ahurow['rc_instrument_used']);
    $temp_rh_array = explode(",", $ahurow['temp_rh_instrument_used']);

    $av_txtarea = $ahurow['av_txtarea'];
    $fi_txtarea = $ahurow['fi_txtarea'];
    $pc_txtarea = $ahurow['pc_txtarea'];
    $rc_txtarea = $ahurow['rc_txtarea'];
    $temp_txtarea = $ahurow['temp_txtarea'];
    $sl_txtarea = $ahurow['sl_txtarea'];
    $dp_txtarea = $ahurow['dp_txtarea'];
    $lux_txtarea = $ahurow['lux_txtarea'];

} else {
    $new = true;
    $count_result = $mysqli->query("SELECT count(room_id)+1 as room_cnt FROM reports_ahu_details where report_id = '$selectedReportId' ")
        or die($mysqli);
    $count_row = $count_result->fetch_assoc();
    $room_cnt = $count_row['room_cnt'];

    $room_id = "AR" . substr(str_repeat(0, 5) . $room_cnt, -5);
}

$cust_result = $mysqli->query("SELECT cust.customer_name 
FROM customer cust, customer_reports_master  crm
where crm.report_id = '$selectedReportId' and trim(crm.customer_id) = trim(cust.customer_id)")
    or die($mysqli);
$custrow = $cust_result->fetch_assoc();

if (mysqli_num_rows($cust_result) > 0) {
    $customer_name = $custrow['customer_name'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>AHU Details Form</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="ACS, 'Air Calibre Systems','clean rooms and validation'" name="keywords">

    <!-- Bootstrap CSS and JS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Dropdown -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />

        <!-- DataTable CSS  -->
        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0-alpha3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

    <link rel="stylesheet" href="style.css" />
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpg">

    <style>
        .note_font {
            font-weight: 500;
        }

        #text_area_col_div {
            padding-left: 10px;
        }

        @media only screen and (max-width: 600px) {
            body {   
                /* background-color: yellow; */
                /* width: 80%; */
                display: inline;
            }
            #av_table{
                overflow: hidden;
            }
            #fi_table{
                overflow: hidden;
            }
            #pc_table{
                overflow: hidden;
            }
            #dp_table{
                overflow: hidden;
            }
            #temp_th_table{
                overflow: hidden;
            }
            #rc_table{
                overflow: hidden;
            }
            #av_list{
                /* width: 100%; */
                /* background-color: #007bff;
                display: block; */
            }
            .one{
			display: none;
		   }
            /* td.collapsable {display:none;} */

            /* #data td,th:nth-child(6),
            #data td,th:nth-child(7),
            #data td,th:nth-child(7),
            #data td,th:nth-child(8),
            #data td,th:nth-child(9),
            #data td,th:nth-child(10) {
             display: none;
            } */
            #av_table{
                /* background-color: red; */
                /* width: 48%; */
            }
            .table-text{
                /* color: black; */
            }
            .box-css{
                display: flex;
            }
        }
    </style>
</head>

<body>
    <!-- ############################################## AHU DETAILS FORM ################################################## -->
    <main id="main">
        <div class="container-fluid" id="container_add_ahu_details">
            <form id="ahu_details_form" class="form-horizontal" method="POST"
                action="report_save.php?save=ahu_det<?php echo $editahu; ?>">
                <input type="hidden" id="report_id" name="report_id" value="<?php echo $selectedReportId; ?>">

                <div class="form-group row">
                    <div class="col-md-2">
                        <a href="rep_datasheet.php?report_id=<?php echo $selectedReportId; ?>" class="btn btn-primary"
                            role="button">
                            << Back</a>
                    </div>
                    <div class="col-md-7 text-center">
                        <h2>AHU Details Form</h2>
                    </div>
                    <div class="col-md-3">
                        <a>
                            <button type="submit" class="btn btn-primary float-end">Save Room Details</button>
                        </a>
                    </div>
                </div>
                <br>

                <div class="form-group row font-weight-bold">
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="rep_id" class="col-md-2 col-form-label text-nowrap">Report Id</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="rep_id" name="report_id"
                                    value="<?php echo $selectedReportId; ?>" placeholder="Report Id" readonly>
                            </div>

                            <label for="customer_name" class="col-md-2 col-form-label text-nowrap">Customer Name</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="customer_name"
                                    name="customer_name" value="<?php echo $customer_name; ?>"
                                    placeholder="Customer Name" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="row">
                            <label for="ahu_room_id" class="col-md-2 col-form-label text-nowrap">Room Id</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="ahu_room_id"
                                    name="ahu_room_id" value="<?php echo $room_id; ?>" placeholder="Room Id" readonly>
                            </div>

                            <label for="room_details" class="col-md-2 col-form-label text-nowrap">Room Name</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="room_details"
                                    name="room_details" value="<?php echo $room_details; ?>" placeholder="Room Name">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="ahu_name" class="col-md-2 col-form-label text-nowrap">AHU Name</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm text-uppercase" id="ahu_name"
                                    name="ahu_name" required value="<?php echo $ahu_name; ?>" placeholder="AHU Name">
                            </div>

                            <label for="eqp_name" class="col-md-2 col-form-label text-nowrap">Equipment Name</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm text-uppercase" id="eqp_name"
                                    name="eqp_name" required value="<?php echo $ahu_name; ?>"
                                    placeholder="Equipment Name">
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="row">
                            <label for="category" class="col-md-2 col-form-label text-nowrap">Category</label>
                            <div class="col-md-4">
                                <select id="category" name="category" class="form-select form-select-sm">
                                    <option <?php echo ($category == 1) ? ' selected ' : ''; ?> value="1">AV - Equipment
                                    </option>
                                    <option <?php echo ($category == 2) ? ' selected ' : ''; ?> value="2">AV - AHU,AQ
                                    </option>
                                    <option <?php echo ($category == 3) ? ' selected ' : ''; ?> value="3">AV - Master
                                    </option>
                                </select>
                            </div>

                            <label for="pc_cycle" class="col-md-2 col-form-label text-nowrap">PC Cycle</label>
                            <div class="col-md-4">
                                <select id="pc_cycle" name="pc_cycle" class="form-select form-select-sm">
                                    <option <?php echo ($pc_cycle == 1) ? ' selected ' : ''; ?> value="1">Cycle 1</option>
                                    <option <?php echo ($pc_cycle == 2) ? ' selected ' : ''; ?> value="2">Cycle 3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="av_qty" class="col-md-2 col-form-label text-nowrap">Grill/Filter Qty</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="av_qty" name="av_qty"
                                    value="<?php echo $av_qty; ?>" placeholder="No. of AV units">
                            </div>

                            <label for="av_area" class="col-md-2 col-form-label text-nowrap">Grill Area (ft
                                &#8322)</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="av_area" name="av_area"
                                    value="<?php echo $av_area; ?>" placeholder="AV Area">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="av_room_volume" class="col-md-2 col-form-label text-nowrap">AV Room
                                Volume</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="av_room_volume"
                                    name="av_room_volume" value="<?php echo $av_room_volume; ?>"
                                    placeholder="Room Details">
                            </div>

                            <label for="av_instrument_used" class="col-md-2 col-form-label text-nowrap">AV
                                Instrument</label>
                            <div class="col-md-4">
                                <select name="av_instrument_used[]" class="selectpicker" id="av_instrument_used" multiple
                                    data-live-search="false">
                                    <?php
                                    $eqpresult = $mysqli->query("SELECT * FROM instrument_details WHERE instr_old = 1") or die($mysqli);
                                    //   where instrument_category = 'AV' 
                                    while ($eqpdetail = $eqpresult->fetch_assoc()):
                                        $serial_no = $eqpdetail['serial_no'];
                                        $inst_model = $eqpdetail['instrument_model'];
                                        $inst_name = $eqpdetail['instrument_used'];

                                        if ($new) { ?>
                                            <option value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option <?php echo (in_array($serial_no, $av_array)) ? ' selected ' : ''; ?>
                                                value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name;
                                                echo "--";
                                                echo $inst_model; ?>
                                            </option>
                                        <?php }
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="pc_loc" class="col-md-2 col-form-label text-nowrap">PC Location Qty.</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="pc_loc" name="pc_loc"
                                    value="<?php echo $pc_loc_nos; ?>" placeholder="Particle Count Location">
                            </div>

                            <label for="pc_room_area" class="col-md-2 col-form-label text-nowrap">Room
                                Area(Sq.mt)</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="pc_room_area"
                                    name="pc_room_area" value="<?php echo $pc_room_area; ?>"
                                    placeholder="ISO Classification">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="pc_instrument_used" class="col-md-2 col-form-label text-nowrap">PC
                                Instrument</label>
                            <div class="col-md-4">
                                <select class="selectpicker" name="pc_instrument_used[]" id="pc_instrument_used" multiple
                                    data-live-search="false">
                                    <?php
                                    $eqpresult = $mysqli->query("SELECT * FROM instrument_details WHERE instr_old = 1") or die($mysqli);
                                    while ($eqpdetail = $eqpresult->fetch_assoc()):
                                        $serial_no = $eqpdetail['serial_no'];
                                        $inst_model = $eqpdetail['instrument_model'];
                                        $inst_name = $eqpdetail['instrument_used'];

                                        if ($new) { ?>
                                            <option value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option <?php echo (in_array($serial_no, $pc_array)) ? ' selected ' : ''; ?>
                                                value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name;
                                                echo "--";
                                                echo $inst_model; ?>
                                            </option>
                                        <?php }
                                    endwhile;
                                    ?>
                                </select>
                            </div>

                            <label for="test_condition" class="col-md-2 col-form-label text-nowrap">Test
                                Condition</label>
                            <div class="col-md-4">
                                <select id="test_condition" name="test_condition" class="form-select form-select-sm">
                                    <option <?php echo ($test_condition == "AT-REST") ? ' selected ' : ''; ?>
                                        value="AT-REST">At Rest</option>
                                    <option <?php echo ($test_condition == "AS-BUILD") ? ' selected ' : ''; ?>
                                        value="AS-BUILD">As Build</option>
                                    <option <?php echo ($test_condition == "IN-OP") ? ' selected ' : ''; ?>
                                        value="In Operation">In OP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xl-6">
                        <div class="row">
                            <!-- To add the PC_Volume of Master data -->
                            <label for="pc_volume" class="col-md-2 col-form-label text-nowrap">PC Volume</label>
                            <div class="col-md-4">
                                <select id="pc_volume" name="pc_volume" class="form-select form-select-sm">
                                    <option value=<?php echo floatval($pc_volume) ?>>select pc_volume</option>
                                    <option <?php echo ($pc_volume == "0.683") ? ' selected ' : ''; ?> value="0.683">0.683
                                    </option>
                                    <option <?php echo ($pc_volume == "1000") ? ' selected ' : ''; ?> value="1000">1000
                                    </option>
                                    <option <?php echo ($pc_volume == "5.682") ? ' selected ' : ''; ?> value="5.682">5.682
                                    </option>
                                    <option <?php echo ($pc_volume == "6.826") ? ' selected ' : ''; ?> value="6.826">6.826
                                    </option>
                                    <option <?php echo ($pc_volume == "68.26") ? ' selected ' : ''; ?> value="68.26">68.26
                                    </option>
                                    <option <?php echo ($pc_volume == "690.0") ? ' selected ' : ''; ?> value="690.0">690.0
                                    </option>
                                </select>
                            </div>
                            <!-- To add the PC_Time of Master data  -->
                            <label class="col-md-2 col-form-label" for="pc_time">Pc time </label>
                            <div class="col-md-4">
                                <input type="time" name="pc_time" step="2" id="pc_time"
                                    class="form-control form-control-sm" value="<?php echo $pc_time; ?>"
                                    placeholder="Tested Date" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="dp_quty" class="col-md-2 col-form-label text-nowrap">Differential Quty</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="dp_quty" name="dp_quty"
                                    value="<?php echo $dp_quty; ?>" placeholder="Differential_quty">
                            </div>

                            <label for="dp_instrument_used" class="col-md-2 col-form-label text-nowrap">Dp
                                Instrument</label>
                            <div class="col-md-4">
                                <select class="selectpicker" name="dp_instrument_used[]" id="dp_instrument_used" multiple
                                    data-live-search="false">
                                    <?php
                                    $eqpresult = $mysqli->query("SELECT * FROM instrument_details WHERE instr_old = 1") or die($mysqli);
                                    while ($eqpdetail = $eqpresult->fetch_assoc()):
                                        $serial_no = $eqpdetail['serial_no'];
                                        $inst_model = $eqpdetail['instrument_model'];
                                        $inst_name = $eqpdetail['instrument_used'];

                                        if ($new) { ?>
                                            <option value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option <?php echo (in_array($serial_no, $dp_array)) ? ' selected ' : ''; ?>
                                                value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name;
                                                echo "--";
                                                echo $inst_model; ?>
                                            </option>
                                        <?php }
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="lg_qty" class="col-md-2 col-form-label text-nowrap">LUX Quty</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="lg_qty" name="lg_qty"
                                    value="<?php echo $lg_qty; ?>" placeholder="Light intensity Quty">
                            </div>

                            <label for="lg_instrument_used" class="col-md-2 col-form-label text-nowrap">LUX
                                Instrument</label>
                            <div class="col-md-4">
                                <select class="selectpicker dropleft" name="lg_instrument_used[]" id="lg_instrument_used"
                                    multiple data-live-search="false">
                                    <?php
                                    $eqpresult = $mysqli->query("SELECT * FROM instrument_details WHERE instr_old = 1") or die($mysqli);
                                    while ($eqpdetail = $eqpresult->fetch_assoc()):
                                        $serial_no = $eqpdetail['serial_no'];
                                        $inst_model = $eqpdetail['instrument_model'];
                                        $inst_name = $eqpdetail['instrument_used'];

                                        if ($new) { ?>
                                            <option value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option <?php echo (in_array($serial_no, $lg_array)) ? ' selected ' : ''; ?>
                                                value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name;
                                                echo "--";
                                                echo $inst_model; ?>
                                            </option>
                                        <?php }
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="sl_qty" class="col-md-2 col-form-label text-nowrap">Sound Level Quty</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="sl_qty" name="sl_qty"
                                    value="<?php echo $sl_qty; ?>" placeholder="Sound level_quty">
                            </div>

                            <label for="sl_instrument_used" class="col-md-2 col-form-label text-nowrap">SL
                                Instrument</label>
                            <div class="col-md-4">
                                <select class="selectpicker" name="sl_instrument_used[]" id="sl_instrument_used" multiple
                                    data-live-search="false">
                                    <?php
                                    $eqpresult = $mysqli->query("SELECT * FROM instrument_details WHERE instr_old = 1") or die($mysqli);
                                    while ($eqpdetail = $eqpresult->fetch_assoc()):
                                        $serial_no = $eqpdetail['serial_no'];
                                        $inst_model = $eqpdetail['instrument_model'];
                                        $inst_name = $eqpdetail['instrument_used'];

                                        if ($new) { ?>
                                            <option value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option <?php echo (in_array($serial_no, $sl_array)) ? ' selected ' : ''; ?>
                                                value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name;
                                                echo "--";
                                                echo $inst_model; ?>
                                            </option>
                                        <?php }
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="row">
                            <label for="temp_rh_qty" class="col-md-2 col-form-label text-nowrap">Temp RH Quty</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="temp_rh_qty"
                                    name="temp_rh_qty" value="<?php echo $temp_rh_qty; ?>"
                                    placeholder="Temp RH Qauantity">
                            </div>

                            <label for="temp_rh_instrument_used" class="col-md-2 col-form-label text-nowrap">Temp RH
                                Instrument</label>
                            <div class="col-md-4">
                                <select class="selectpicker dropleft" name="temp_rh_instrument_used[]"
                                    id="temp_rh_instrument_used" multiple data-live-search="false">
                                    <?php
                                    $eqpresult = $mysqli->query("SELECT * FROM instrument_details WHERE instr_old = 1") or die($mysqli);
                                    while ($eqpdetail = $eqpresult->fetch_assoc()):
                                        $serial_no = $eqpdetail['serial_no'];
                                        $inst_model = $eqpdetail['instrument_model'];
                                        $inst_name = $eqpdetail['instrument_used'];

                                        if ($new) { ?>
                                            <option value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option <?php echo (in_array($serial_no, $temp_rh_array)) ? ' selected ' : ''; ?>
                                                value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name;
                                                echo "--";
                                                echo $inst_model; ?>
                                            </option>
                                        <?php }
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="rc_qty" class="col-md-2 col-form-label text-nowrap">RC Quty</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="rc_qty" name="rc_qty"
                                    value="<?php echo $rc_qty; ?>" placeholder="Recovery count Quty">
                            </div>

                            <label for="rc_instrument_used" class="col-md-2 col-form-label text-nowrap">RC
                                Instrument</label>
                            <div class="col-md-4">
                                <select class="selectpicker" name="rc_instrument_used[]" id="rc_instrument_used" multiple
                                    data-live-search="false">
                                    <?php
                                    $eqpresult = $mysqli->query("SELECT * FROM instrument_details WHERE instr_old = 1") or die($mysqli);
                                    while ($eqpdetail = $eqpresult->fetch_assoc()):
                                        $serial_no = $eqpdetail['serial_no'];
                                        $inst_model = $eqpdetail['instrument_model'];
                                        $inst_name = $eqpdetail['instrument_used'];

                                        if ($new) { ?>
                                            <option value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option <?php echo (in_array($serial_no, $rc_array)) ? ' selected ' : ''; ?>
                                                value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name;
                                                echo "--";
                                                echo $inst_model; ?>
                                            </option>
                                        <?php }
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="row">
                            <label for="filter_integrity_qty" class="col-md-2 col-form-label text-nowrap">Filter
                                Integrity</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="filter_integrity_qty"
                                    name="filter_integrity_qty" value="<?php echo $filter_integrity_qty; ?>"
                                    placeholder="Filter Integrity Qty">
                            </div>

                            <label for="fi_instrument_used" class="col-md-2 col-form-label text-nowrap">FI
                                Instrument</label>
                            <div class="col-md-4">
                                <select class="selectpicker dropleft" name="fi_instrument_used[]" id="fi_instrument_used"
                                    multiple data-live-search="false">
                                    <?php
                                    $eqpresult = $mysqli->query("SELECT * FROM instrument_details WHERE instr_old = 1") or die($mysqli);
                                    while ($eqpdetail = $eqpresult->fetch_assoc()):
                                        $serial_no = $eqpdetail['serial_no'];
                                        $inst_model = $eqpdetail['instrument_model'];
                                        $inst_name = $eqpdetail['instrument_used'];

                                        if ($new) { ?>
                                            <option value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option <?php echo (in_array($serial_no, $fi_array)) ? ' selected ' : ''; ?>
                                                value="<?php echo $serial_no; ?>">
                                                <?php echo $inst_name;
                                                echo "--";
                                                echo $inst_model; ?>
                                            </option>
                                        <?php }
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="tested_area" class="col-md-2 col-form-label">Tested Area</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" name="tested_area"
                                    id="tested_area" value="<?php echo $tested_area ?>" placeholder="Tested Area">
                            </div>

                            <label for="department" class="col-md-2 col-form-label">Department</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" name="department"
                                    id="department" value="<?php echo $department; ?>" placeholder="Department">
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="row">
                            <label for="testing_date" class="col-md-2 col-form-label">Testing Date </label>
                            <div class="col-md-4">
                                <input required type="date" name="testing_date" id="testing_date"
                                    class="form-control form-control-sm" value="<?php echo $testing_date; ?>"
                                    placeholder="Tested Date" />
                            </div>

                            <label for="testing_due_date" class="col-md-2 col-form-label">Testing Due Date </label>
                            <div class="col-md-4">
                                <input type="date" name="testing_due_date" id="testing_due_date"
                                    class="form-control form-control-sm" value="<?php echo $testing_due_date; ?>"
                                    placeholder="Testeing_due_date" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="testing_engg" class="col-md-2 col-form-label text-nowrap">Tested by</label>
                            <div class="col-md-4">
                                <select required name="testing_engg[]" class="selectpicker" id="testing_engg" multiple
                                    data-live-search="false">
                                    <?php
                                    $employelist = $mysqli->query("SELECT * FROM employee where employee_status = 'Active'") or die($mysqli);
                                    //   where instrument_category = 'AV' 
                                    while ($empdetail = $employelist->fetch_assoc()):
                                        $serial_no = $empdetail['serial_no'];
                                        $emp_id = $empdetail['employee_id'];
                                        $emp_name = $empdetail['employee_name'];
                                        if ($new) { ?>
                                            <option value="<?php echo $emp_id; ?>">
                                                <?php echo $emp_name; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option <?php echo (in_array($emp_id, $testing_array)) ? ' selected ' : ''; ?>
                                                value="<?php echo $emp_id; ?>">
                                                <?php echo $emp_name; ?>
                                            </option>
                                        <?php }
                                    endwhile;
                                    ?>
                                </select>
                            </div>

                            <label for="witness_by" class="col-md-2 col-form-label">Witness</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control  form-control-sm" name="witness_by"
                                    id="witness_by" value="<?php echo $witness_by; ?>"
                                    placeholder="Customer side Witness">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <label for="test_reference" class="col-md-2 col-form-label text-nowrap">Test
                                Reference</label>
                            <div class="col-md-4">
                                <select id="test_reference" name="test_reference" class="form-select form-select-sm">
                                    <option <?php echo ($test_reference == "ISO-14644") ? ' selected ' : ''; ?>
                                        value="ISO-14644">ISO-14644</option>
                                    <option <?php echo ($test_reference == "ISO-14645") ? ' selected ' : ''; ?>
                                        value="ISO-14645">ISO-14645</option>
                                    <option <?php echo ($test_reference == "EU-GMP") ? ' selected ' : ''; ?>
                                        value="EU-GMP">EU - GMP</option>
                                </select>
                            </div>

                            <label for="room_iso_class" class="col-md-2 col-form-label text-nowrap">ISO Class</label>
                            <div class="col-md-4">
                                <select id="room_iso_class" name="room_iso_class" class="form-select form-select-sm">
                                    <option <?php echo ($room_iso_class == "ISO-0.5") ? ' selected ' : ''; ?>
                                        value="ISO-5">ISO-0.5</option>
                                    <option <?php echo ($room_iso_class == "ISO-05") ? ' selected ' : ''; ?>
                                        value="ISO-05">ISO-05</option>
                                    <option <?php echo ($room_iso_class == "ISO-06") ? ' selected ' : ''; ?>
                                        value="ISO-06">ISO-06</option>
                                    <option <?php echo ($room_iso_class == "ISO-07") ? ' selected ' : ''; ?>
                                        value="ISO-07">ISO-07</option>
                                    <option <?php echo ($room_iso_class == "ISO-08") ? ' selected ' : ''; ?>
                                        value="ISO-08">ISO-08</option>
                                    <option <?php echo ($room_iso_class == "ISO-09") ? ' selected ' : ''; ?>
                                        value="ISO-09">ISO-09</option>
                                    <option <?php echo ($room_iso_class == "GRADE-A") ? ' selected ' : ''; ?>
                                        value="GRADE-A">GRADE-A</option>
                                    <option <?php echo ($room_iso_class == "GRADE-B") ? ' selected ' : ''; ?>
                                        value="GRADE-B">GRADE-B</option>
                                    <option <?php echo ($room_iso_class == "GRADE-C") ? ' selected ' : ''; ?>
                                        value="GRADE-C">GRADE-C</option>
                                    <option <?php echo ($room_iso_class == "GRADE-D") ? ' selected ' : ''; ?>
                                        value="GRADE-D">GRADE-D</option>
                                    <option <?php echo ($room_iso_class == "CNC") ? ' selected ' : ''; ?> value="CNC">CNC
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="row">
                            <div id="text_area_col_div" class="col-md-3">
                                <label class="note_font">AV Note</label> <br>
                                <textarea id="av_txtarea" name="av_txtarea"
                                    class="form-control"><?php echo $av_txtarea; ?></textarea>
                            </div>
                            <div id="text_area_col_div" class="col-md-3">
                                <label class="note_font">FI Note</label> <br>
                                <textarea id="fi_txtarea" name="fi_txtarea"
                                    class="form-control"><?php echo $fi_txtarea; ?></textarea>
                            </div>
                            <div id="text_area_col_div" class="col-md-3">
                                <label class="note_font">PC Note</label> <br>
                                <textarea id="pc_txtarea" name="pc_txtarea"
                                    class="form-control"><?php echo $pc_txtarea; ?></textarea>
                            </div>
                            <div id="text_area_col_div" class="col-md-3">
                                <label class="note_font">RC Note</label> <br>
                                <textarea id="rc_txtarea" name="rc_txtarea"
                                    class="form-control"><?php echo $rc_txtarea; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <div id="text_area_col_div" class="col-md-3">
                                <label class="note_font">DP Note</label> <br>
                                <textarea id="dp_txtarea" name="dp_txtarea"
                                    class="form-control"><?php echo $dp_txtarea; ?></textarea>
                            </div>
                            <div id="text_area_col_div" class="col-md-3">
                                <label class="note_font">SL Note</label> <br>
                                <textarea id="sl_txtarea" name="sl_txtarea"
                                    class="form-control"><?php echo $sl_txtarea; ?></textarea>
                            </div>
                            <div id="text_area_col_div" class="col-md-3">
                                <label class="note_font">LUX Note</label> <br>
                                <textarea id="lux_txtarea" name="lux_txtarea"
                                    class="form-control"><?php echo $lux_txtarea; ?></textarea>
                            </div>
                            <div id="text_area_col_div" class="col-md-3">
                                <label class="note_font">TEMP Note</label> <br>
                                <textarea id="temp_txtarea" name="temp_txtarea"
                                    class="form-control"><?php echo $temp_txtarea; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <script>
                function updateFilterData(colName, colValue) {
                    // alert("Column Name"+colName+" Column Value "+colValue);
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    }
                    xmlhttp.open("GET", "save_avfilter.php?colName=" + colName + "&colValue=" + colValue, true);
                    xmlhttp.send();
                }

                function updatePCData(colName, colValue) {
                    // alert("Column Name"+colName+" Column Value "+colValue);
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    }
                    xmlhttp.open("GET", "save_pcdata.php?colName=" + colName + "&colValue=" + colValue, true);
                    xmlhttp.send();
                }

                function updateFIData(colName, colValue) {
                    // alert("FI Column Name"+colName+" FI Column Value "+colValue);
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    }
                    xmlhttp.open("GET", "save_fi_data.php?colName=" + colName + "&colValue=" + colValue, true);
                    xmlhttp.send();
                }

                function updateDPData(colName, colValue) {
                    // alert("DP Column Name"+colName+" DP Column Value "+colValue);
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    }
                    xmlhttp.open("GET", "save_dp_data.php?colName=" + colName + "&colValue=" + colValue, true);
                    xmlhttp.send();
                }

                function updateLUXData(colName, colValue) {
                    // alert("LUX Column Name"+colName+" LUX Column Value "+colValue);
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    }
                    xmlhttp.open("GET", "save_lux_data.php?colName=" + colName + "&colValue=" + colValue, true);
                    xmlhttp.send();
                }

                function updateSLData(colName, colValue) {
                    // alert("SL Column Name"+colName+" SL Column Value "+colValue);
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    }
                    xmlhttp.open("GET", "save_sl_data.php?colName=" + colName + "&colValue=" + colValue, true);
                    xmlhttp.send();
                }

                function updateTRHData(colName, colValue) {
                    // alert("TRH Column Name"+colName+" TRH Column Value "+colValue);
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    }
                    xmlhttp.open("GET", "save_temp_rh_data.php?colName=" + colName + "&colValue=" + colValue, true);
                    xmlhttp.send();
                }

                function updateRCData(colName, colValue) {
                    // alert("RC Column Name"+colName+" RC Column Value "+colValue);
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    }
                    xmlhttp.open("GET", "save_rc_data.php?colName=" + colName + "&colValue=" + colValue, true);
                    xmlhttp.send();
                }
            </script>

            <hr>
            <!-- #############################################  ADD CHILD DATA SECTION  ############################################################### -->
            <div class="row">
                <div class="col-lg-12 text-center">
                    <a href="create_avr_pc.php?ahu_seq_no=<?php echo $selected_ahu_seqno ?>">
                        <button class="btn btn-dark">Add Child Data</button>
                    </a>
                </div>
            </div>
            <br>
            <!-- ======================================= AV SECTION ============================================ -->
            <div class="row" id="av_list">
                <table id="av_table"
                    class="table table-responsive table-sm table-striped table-hover table-bordered table-collapse">
                    <thead class="thead-light text-center">
                        <tr>
                            <th class="table-cell text-center">AV Seq#</th>
                            <th class="table-cell text-center">Ahu Seq#</th>
                            <th class="table-cell text-center">Room Id#</th>
                            <th class="table-cell text-center">Room Description</th>
                            <!-- <th style="order:4;" class="table-cell text-center"></th> -->

                            <th class="table-text text-center">Filter Code</th>
                            <th class="table-text text-center one" colspan="5">Measured Air Velocity in FPM</th>
                            <th rowspan="2" class="text-center one">CFM</th>
                            <th rowspan="2" class="text-center one">Limit</th>
                            <th rowspan="2" class="text-center one">Designed</th>
                        </tr>
                        <tr>
                            <th colspan="5"></th>
                            <th class="table-text text-center one">V1</th>
                            <th class="table-text text-center one">V2</th>
                            <th class="text-center one">V3</th>
                            <th class="text-center one">V4</th>
                            <th class="text-center one">V5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recordCount = 0;
                        $testing_serial = 1;
                        $av_result = $mysqli->query("SELECT * FROM air_velocity where ahu_seq_no= $selected_ahu_seqno ; ") or die($mysqli);
                        while ($row = $av_result->fetch_assoc()):
                            $row_id = "avf-" . $row['av_seq_no'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $testing_serial++ ?>
                                </td>
                                <td>
                                    <?php echo $row["ahu_seq_no"]; ?>
                                </td>
                                <td>
                                    <?php echo $room_id; ?>
                                </td>
                                <td>
                                    <?php echo str_pad(trim($room_details), 14, " ."); ?>
                                </td>
                                <td>
                                    <div class="col-xs-1">
                                        <input type="text" style="max-width: 400px;"
                                            name="<?php echo $row_id ?>-filter_code" id="<?php echo $row_id ?>_filter_code"
                                            value="<?php echo $row['filter_code']; ?>"
                                            onchange="updateFilterData(this.name, this.value)" />
                                    </div>
                                </td>
                                <td><input style="max-width: 100px;" type="text" class="table-text" name="<?php echo $row_id ?>-v1"
                                        id="<?php echo $row_id ?>_v1" value="<?php echo $row['v1']; ?>"
                                        onchange="updateFilterData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 100px;" type="text" name="<?php echo $row_id ?>-v2"
                                        id="<?php echo $row_id ?>_v2" value="<?php echo $row['v2']; ?>"
                                        onchange="updateFilterData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 100px;" type="text" name="<?php echo $row_id ?>-v3"
                                        id="<?php echo $row_id ?>_v3" value="<?php echo $row['v3']; ?>"
                                        onchange="updateFilterData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 100px;" type="text" name="<?php echo $row_id ?>-v4"
                                        id="<?php echo $row_id ?>_v4" value="<?php echo $row['v4']; ?>"
                                        onchange="updateFilterData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 100px;" type="text" name="<?php echo $row_id ?>-v5"
                                        id="<?php echo $row_id ?>_v5" value="<?php echo $row['v5']; ?>"
                                        onchange="updateFilterData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 100px;" type="text" name="<?php echo $row_id ?>-fcfm"
                                        id="<?php echo $row_id ?>_fcfm" value="<?php echo $row['fcfm']; ?>"
                                        onchange="updateFilterData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 100px;" type="text" name="<?php echo $row_id ?>-av_limit"
                                        id="<?php echo $row_id ?>_av_limit" value="<?php echo $row['av_limit']; ?>"
                                        onchange="updateFilterData(this.name, this.value)" placeholder="Enter limit" />
                                </td>
                                <td><input style="max-width: 100px;" type="number" name="<?php echo $row_id ?>-designed"
                                        id="<?php echo $row_id ?>_designed" value="<?php echo $row['Designed']; ?>"
                                        onchange="updateFilterData(this.name, this.value)" placeholder="Enter value" />
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <br><br><br>
            <!-- ======================================= FI SECTION ============================================ -->
            <div class="row" id="fi_list">
                <table id="fi_table"
                    class="table table-responsive table-sm table-striped table-hover table-bordered controls controls-row">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">FI Seq#</th>
                            <th class="text-center">Ahu Seq#</th>
                            <th class="text-center">Room Id#</th>
                            <th class="text-center">Room Description</th>
                            <th class="text-center">Filter Code</th>
                            <th></th>
                            <th class="text-center one" colspan="2">Upstream Concentration</th>
                            <th class="text-center one" colspan="2">Down stream Penetration (%)</th>
                            <th class="text-center one">Status</th>
                        </tr>
                        <tr>
                            <th colspan="5"></th>
                            <th class="text-center one">&micro;g/L</th>
                            <th class="text-center one">Before</th>
                            <th class="text-center one">After</th>
                            <th class="text-center one">HEPA Filter eff.</th>
                            <th class="text-center one">Fitment with <br>Frame Leakage</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recordCount = 0;
                        $testing_serial = 1;
                        $fi_result = $mysqli->query("SELECT * FROM reports_filter_integrity where ahu_seq_no= $selected_ahu_seqno ; ") or die($mysqli);
                        while ($fi_row = $fi_result->fetch_assoc()):
                            $row_id = "HF-" . $fi_row['filter_seq_id'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $testing_serial++ ?>
                                </td>
                                <td>
                                    <?php echo $fi_row["ahu_seq_no"]; ?>
                                </td>
                                <td>
                                    <?php echo $room_id; ?>
                                </td>
                                <td>
                                    <?php echo str_pad(trim($room_details), 21, " ."); ?>
                                </td>
                                <td><input style="max-width: 200px;" type="text" name="<?php echo $row_id ?>-filter_name"
                                        id="<?php echo $row_id ?>-filter_name" value="<?php echo $fi_row['filter_name']; ?>"
                                        onchange="updateFIData(this.name, this.value)" /></td>
                                <td><input style="max-width: 100px;" type="text" name="<?php echo $row_id ?>-micro_gram"
                                        id="<?php echo $row_id ?>-micro_gram" value="<?php echo $fi_row['micro_gram']; ?>"
                                        onchange="updateFIData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 160px;" type="text"
                                        name="<?php echo $row_id ?>-before_upstream"
                                        id="<?php echo $row_id ?>-before_upstream"
                                        value="<?php echo $fi_row['before_upstream']; ?>"
                                        onchange="updateFIData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 160px;" type="text" name="<?php echo $row_id ?>-after_upstream"
                                        id="<?php echo $row_id ?>-after_upstream"
                                        value="<?php echo $fi_row['after_upstream']; ?>"
                                        onchange="updateFIData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 170px;" type="text"
                                        name="<?php echo $row_id ?>-hepa_filter_efficiency"
                                        id="<?php echo $row_id ?>-hepa_filter_efficiency"
                                        value="<?php echo $fi_row['hepa_filter_efficiency']; ?>"
                                        onchange="updateFIData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 170px;" type="text"
                                        name="<?php echo $row_id ?>-fitment_leakage"
                                        id="<?php echo $row_id ?>-fitment_leakage"
                                        value="<?php echo $fi_row['fitment_leakage']; ?>"
                                        onchange="updateFIData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 150px;" type="text" name="<?php echo $row_id ?>-status"
                                        id="<?php echo $row_id ?>-status" value="<?php echo $fi_row['status']; ?>"
                                        placeholder="Pass" readonly /> </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- ======================================= PC SECTION ============================================ -->
            <div class="row" id="pc_list">
                <table id="pc_table"
                    class="table table-responsive table-sm table-striped table-hover table-bordered controls controls-row">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">PC Seq#</th>
                            <th class="text-center">Ahu Seq#</th>
                            <th class="text-center">Room Id#</th>
                            <th class="text-center">Room Description</th>
                            <th class="text-center">Location</th>
                            <th class="text-center one" colspan="3">≥0.3 μm/m³</th>
                            <th class="text-center one" colspan="3">≥0.5 μm/m³</th>
                            <th class="text-center one" colspan="3">≥5.0 μm/m³</th>
                            <th class="text-center one">Result</th>
                        </tr>
                        <tr>
                            <th colspan="5"></th>
                            <th class="text-center one">R1</th>
                            <th class="text-center one">R2</th>
                            <th class="text-center one">R3</th>
                            <th class="text-center one">R1</th>
                            <th class="text-center one">R2</th>
                            <th class="text-center one">R3</th>
                            <th class="text-center one">R1</th>
                            <th class="text-center one">R2</th>
                            <th class="text-center one">R3</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recordCount = 0;
                        $testing_serial = 1;
                        $pc_result = $mysqli->query("SELECT * FROM reports_pc_data where ahu_seq_no= $selected_ahu_seqno ;") or die($mysqli);
                        while ($row = $pc_result->fetch_assoc()):
                            $row_id = "pc-" . $row['pc_seq_no'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $testing_serial++; ?>
                                </td>
                                <td>
                                    <?php echo $row["ahu_seq_no"]; ?>
                                </td>
                                <td>
                                    <?php echo $room_id; ?>
                                </td>
                                <td>
                                    <?php echo str_pad(trim($room_details), 21, " ."); ?>
                                </td>
                                <td>
                                    <div class="col-xs-1">
                                        <input style="max-width: 70px;" type="text" name="<?php echo $row_id ?>"
                                            id="<?php echo $row_id ?>" value="<?php echo $row['pc_loc_seq_no']; ?>" />
                                    </div>
                                </td>
                                <td><input style="max-width: 70px;" type="text" name="<?php echo $row_id ?>-point3_r1"
                                        id="<?php echo $row_id ?>-point3_r1" value="<?php echo $row['point3_r1']; ?>"
                                        onchange="updatePCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 70px;" type="text" name="<?php echo $row_id ?>-point3_r2"
                                        id="<?php echo $row_id ?>-point3_r2" value="<?php echo $row['point3_r2']; ?>"
                                        onchange="updatePCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 70px;" type="text" name="<?php echo $row_id ?>-point3_r3"
                                        id="<?php echo $row_id ?>-point3_r3" value="<?php echo $row['point3_r3']; ?>"
                                        onchange="updatePCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 70px;" type="text" name="<?php echo $row_id ?>-point5_r1"
                                        id="<?php echo $row_id ?>-point5_r1" value="<?php echo $row['point5_r1']; ?>"
                                        onchange="updatePCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 70px;" type="text" name="<?php echo $row_id ?>-point5_r2"
                                        id="<?php echo $row_id ?>-point5_r2" value="<?php echo $row['point5_r2']; ?>"
                                        onchange="updatePCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 70px;" type="text" name="<?php echo $row_id ?>-point5_r3"
                                        id="<?php echo $row_id ?>-point5_r3" value="<?php echo $row['point5_r3']; ?>"
                                        onchange="updatePCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 70px;" type="text" name="<?php echo $row_id ?>-5point_r1"
                                        id="<?php echo $row_id ?>-5point_r1" value="<?php echo $row['5point_r1']; ?>"
                                        onchange="updatePCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 70px;" type="text" name="<?php echo $row_id ?>-5point_r2"
                                        id="<?php echo $row_id ?>-5point_r2" value="<?php echo $row['5point_r2']; ?>"
                                        onchange="updatePCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 70px;" type="text" name="<?php echo $row_id ?>-5point_r3"
                                        id="<?php echo $row_id ?>-5point_r3" value="<?php echo $row['5point_r3']; ?>"
                                        onchange="updatePCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 100px;" type="text" name="<?php echo $row_id ?>-result"
                                        id="<?php echo $row_id ?>-result" value="<?php echo $row['result']; ?>"
                                        placeholder="Pass" readonly /> </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <br>

            <!-- ======================================= DP SECTION ============================================ -->
            <div class="row" id="dp_list">
                <table id="dp_table"
                    class="table table-responsive table-sm table-striped table-hover table-bordered controls controls-row">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Dp Seq#</th>
                            <th class="text-center">Ahu Seq#</th>
                            <th class="text-center">Room Id#</th>
                            <th rowspan="2">Room Description</th>
                            <th class="text-center one" colspan="2">Differential Pressure in PASCAL</th>
                            <th class="text-center one" rowspan="2">Status</th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-center one">Pressure Limit</th>
                            <th class="text-center one">Pressure Observed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recordCount = 0;
                        $testing_serial = 1;
                        $dp_result = $mysqli->query("SELECT * FROM reports_differ_pressure where ahu_seq_no = $selected_ahu_seqno ;") or die($mysqli);
                        while ($dp_row = $dp_result->fetch_assoc()):
                            $row_id = "DP-" . $dp_row['differ_seq_id'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $testing_serial++; ?>
                                </td>
                                <td>
                                    <?php echo $dp_row['ahu_seq_no']; ?>
                                </td>
                                <td>
                                    <?php echo $room_id; ?>
                                </td>
                                <td><input style="max-width: 400px;" type="text" name="<?php echo $row_id ?>-differ_name"
                                        id="<?php echo $row_id ?>-differ_name" value="<?php echo $dp_row['differ_name']; ?>"
                                        onchange="updateDPData(this.name, this.value)" /></td>
                                <td><input style="max-width: 160px;" type="text" name="<?php echo $row_id ?>-pressure_limit"
                                        id="<?php echo $row_id ?>-pressure_limit"
                                        value="<?php echo $dp_row['pressure_limit']; ?>"
                                        onchange="updateDPData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 160px;" type="text"
                                        name="<?php echo $row_id ?>-Pressure_Observed"
                                        id="<?php echo $row_id ?>-pressure_observed"
                                        value="<?php echo $dp_row['pressure_observed']; ?>"
                                        onchange="updateDPData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 150px;" type="text" name="<?php echo $row_id ?>-status"
                                        id="<?php echo $row_id ?>-status" value="<?php echo $dp_row['status']; ?>"
                                        onchange="updateDPData(this.name, this.value)" /> </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- ======================================= LUX SECTION ============================================ -->
            <div class="row" id="lg_list">
                <table id="lg_table"
                    class="table table-responsive table-sm table-striped table-hover table-bordered controls controls-row">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">LUX Seq#</th>
                            <th class="text-center">Ahu Seq#</th>
                            <th class="text-center">Room Id#</th>
                            <th rowspan="2">Room Description</th>
                            <th class="text-center one" colspan="2">Light Intensity Level (LUX)</th>
                            <th class="text-center one" rowspan="2">Status</th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-center one">Acceptance Limit</th>
                            <th class="text-center one">Observed Limit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recordCount = 0;
                        $testing_serial = 1;
                        $lux_result = $mysqli->query("SELECT * FROM reports_lux_data where ahu_seq_no = $selected_ahu_seqno ;") or die($mysqli);
                        while ($lux_row = $lux_result->fetch_assoc()):
                            $row_id = "LUX-" . $lux_row['lux_seq_id'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $testing_serial++; ?>
                                </td>
                                <td>
                                    <?php echo $lux_row['ahu_seq_no']; ?>
                                </td>
                                <td>
                                    <?php echo $room_id; ?>
                                </td>
                                <td>
                                    <?php echo str_pad(trim($room_details), 21, " ."); ?>
                                </td>
                                <td><input style="max-width: 160px;" type="text"
                                        name="<?php echo $row_id ?>-acceptance_limit"
                                        id="<?php echo $row_id ?>-acceptance_limit"
                                        value="<?php echo $lux_row['acceptance_limit']; ?>"
                                        onchange="updateLUXData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 160px;" type="text" name="<?php echo $row_id ?>-observed_limit"
                                        id="<?php echo $row_id ?>-observed_limit"
                                        value="<?php echo $lux_row['observed_limit']; ?>"
                                        onchange="updateLUXData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 150px;" type="text" name="<?php echo $row_id ?>-status"
                                        id="<?php echo $row_id ?>-status" value="<?php echo $lux_row['status']; ?>"
                                        onchange="updateLUXData(this.name, this.value)" /> </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- ======================================= SL SECTION ============================================ -->
            <div class="row" id="sl_list">
                <table id="lg_table"
                    class="table table-responsive table-sm table-striped table-hover table-bordered controls controls-row">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">SL Seq#</th>
                            <th class="text-center">Ahu Seq#</th>
                            <th class="text-center">Room Id#</th>
                            <th rowspan="2">Room Description</th>
                            <th class="text-center one" colspan="2">Sound Level Test (SL)</th>
                            <th class="text-center one" rowspan="2">Status</th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-center one">Acceptance Limit</th>
                            <th class="text-center one">Observed Limit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recordCount = 0;
                        $testing_serial = 1;
                        $sl_result = $mysqli->query("SELECT * FROM reports_sl_data where ahu_seq_no = $selected_ahu_seqno ;") or die($mysqli);
                        while ($sl_row = $sl_result->fetch_assoc()):
                            $row_id = "SL-" . $sl_row['sl_seq_id'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $testing_serial++; ?>
                                </td>
                                <td>
                                    <?php echo $sl_row['ahu_seq_no']; ?>
                                </td>
                                <td>
                                    <?php echo $room_id; ?>
                                </td>
                                <td>
                                    <?php echo str_pad(trim($room_details), 21, " ."); ?>
                                </td>
                                <td><input style="max-width: 160px;" type="text"
                                        name="<?php echo $row_id ?>-acceptance_limit"
                                        id="<?php echo $row_id ?>-acceptance_limit"
                                        value="<?php echo $sl_row['acceptance_limit']; ?>"
                                        onchange="updateSLData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 160px;" type="text" name="<?php echo $row_id ?>-observed_limit"
                                        id="<?php echo $row_id ?>-observed_limit"
                                        value="<?php echo $sl_row['observed_limit']; ?>"
                                        onchange="updateSLData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 150px;" type="text" name="<?php echo $row_id ?>-status"
                                        id="<?php echo $row_id ?>-status" value="<?php echo $sl_row['status']; ?>"
                                        onchange="updateSLData(this.name, this.value)" /> </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- ======================================= TEMP RH SECTION ============================================ -->
            <div class="row" id="temp_rh_list">
                <table id="temp_th_table"
                    class="table table-responsive table-sm table-striped table-hover table-bordered controls controls-row">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">TEMP RH Seq#</th>
                            <th class="text-center">Ahu Seq#</th>
                            <th class="text-center">Room Id#</th>
                            <th rowspan="2">Room Description</th>
                            <th class="text-center one" colspan="2">Temperature and RH </th>
                            <th></th>
                            <th></th>
                            <th class="text-center one" rowspan="2">Status</th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-center one">Temperature limit</th>
                            <th class="text-center one">Temperature observed</th>
                            <th class="text-center one">Relative Humidity limit</th>
                            <th class="text-center one">Relative Humidity observed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recordCount = 0;
                        $testing_serial = 1;
                        $temp_rh_result = $mysqli->query("SELECT * FROM reports_temp_rh_data where ahu_seq_no = $selected_ahu_seqno ;") or die($mysqli);
                        while ($temp_rh_row = $temp_rh_result->fetch_assoc()):
                            $row_id = "TempRH-" . $temp_rh_row['temp_rh_seq_id'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $testing_serial++; ?>
                                </td>
                                <td>
                                    <?php echo $temp_rh_row['ahu_seq_no']; ?>
                                </td>
                                <td>
                                    <?php echo $room_id; ?>
                                </td>
                                <td>
                                    <?php echo str_pad(trim($room_details), 21, " ."); ?>
                                </td>
                                <td><input style="max-width: 160px;" type="text"
                                        name="<?php echo $row_id ?>-temperature_limit"
                                        id="<?php echo $row_id ?>-temperature_limit"
                                        value="<?php echo $temp_rh_row['temperature_limit']; ?>"
                                        onchange="updateTRHData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 160px;" type="text"
                                        name="<?php echo $row_id ?>-temperature_observed"
                                        id="<?php echo $row_id ?>-temperature_observed"
                                        value="<?php echo $temp_rh_row['temperature_observed']; ?>"
                                        onchange="updateTRHData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 160px;" type="text" name="<?php echo $row_id ?>-humidity_limit"
                                        id="<?php echo $row_id ?>-humidity_limit"
                                        value="<?php echo $temp_rh_row['humidity_limit']; ?>"
                                        onchange="updateTRHData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 160px;" type="text"
                                        name="<?php echo $row_id ?>-humidity_observed"
                                        id="<?php echo $row_id ?>-humidity_observed"
                                        value="<?php echo $temp_rh_row['humidity_observed']; ?>"
                                        onchange="updateTRHData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 150px;" type="text" name="<?php echo $row_id ?>-status"
                                        id="<?php echo $row_id ?>-status" value="<?php echo $temp_rh_row['status']; ?>"
                                        onchange="updateTRHData(this.name, this.value)" /> </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- ======================================= RC SECTION ============================================ -->
            <div class="row" id="rc_list">
                <table id="rc_table"
                    class="table table-responsive table-sm table-striped table-hover table-bordered controls controls-row">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" class="text-center">RC Seq#</th>
                            <th rowspan="2" class="text-center">Ahu Seq#</th>
                            <th rowspan="2" class="text-center">Room Id#</th>
                            <th rowspan="2">Room Description</th>
                            <th rowspan="2" class="text-center one">Status </th>
                            <th rowspan="2" class="text-center one">Hr-Min-Sec</th>
                            <th colspan="3">Concentration of Particle sizez / m&sup3;</th>
                            <th class="text-center one" rowspan="2">Status</th>
                            <th class="text-center one" rowspan="2">Print</th>
                        </tr>
                        <tr>
                            <th class="text-center one"> &ge;0.3 &micro;m/m&sup3; </th>
                            <th class="text-center one"> &ge;0.5 &micro;m/m&sup3;</th>
                            <th class="text-center one"> &ge;5.0 &micro;m/m&sup3;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recordCount = 0;
                        $testing_serial = 1;
                        $rc_result = $mysqli->query("SELECT * FROM reports_rc_data where ahu_seq_no = $selected_ahu_seqno ;") or die($mysqli);
                        while ($rc_row = $rc_result->fetch_assoc()):
                            $row_id = "RC-" . $rc_row['rc_seq_id'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $testing_serial++; ?>
                                </td>
                                <td>
                                    <?php echo $rc_row['ahu_seq_no']; ?>
                                </td>
                                <td>
                                    <?php echo $room_id; ?>
                                </td>
                                <td>
                                    <?php echo str_pad(trim($room_details), 21, " ."); ?>
                                </td>
                                <td><input style="max-width: 160px;" type="text" name="<?php echo $row_id ?>-status"
                                        id="<?php echo $row_id ?>-status" value="<?php echo $rc_row['status']; ?>"
                                        onchange="updateRCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 160px;" type="text" name="<?php echo $row_id ?>-time_duration"
                                        id="<?php echo $row_id ?>-time_duration"
                                        value="<?php echo $rc_row['time_duration']; ?>"
                                        onchange="updateRCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 160px;" type="text" name="<?php echo $row_id ?>-point3"
                                        id="<?php echo $row_id ?>-point3" value="<?php echo $rc_row['point3']; ?>"
                                        onchange="updateRCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 150px;" type="text" name="<?php echo $row_id ?>-point5"
                                        id="<?php echo $row_id ?>-point5" value="<?php echo $rc_row['point5']; ?>"
                                        onchange="updateRCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 150px;" type="text" name="<?php echo $row_id ?>-5point"
                                        id="<?php echo $row_id ?>-5point" value="<?php echo $rc_row['5point']; ?>"
                                        onchange="updateRCData(this.name, this.value)" /> </td>
                                <td><input style="max-width: 100px;" type="text" name="<?php echo $row_id ?>-recovery_time"
                                        placeholder="Pass" readonly /> </td>
                                <td style="text-align:center;"><strong>Print</strong>
                                    <input type="checkbox" name="<?php echo $row_id ?>-print_data"
                                        id="<?php echo $row_id ?>-print_data"
                                        onchange="updateRCData(this.name, this.checked ? '2' : '0')" <?php echo ($rc_row['print_data'] == 2 ? 'checked' : ''); ?> />
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
 <!-- Main Content -->
 <!-- <div class="container p-3 my-5 bg-light border border-primary">
      
        <table id="example" class="table table-striped nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                    <th>Extn.</th>
                    <th>E-mail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tiger</td>
                    <td>Nixon</td>
                    <td>System Architect</td>
                    <td>Edinburgh</td>
                    <td>61</td>
                    <td>2011-04-25</td>
                    <td>$320,800</td>
                    <td>5421</td>
                    <td>t.nixon@datatables.net</td>
                </tr>
                
                
            </tbody>
        </table>
    </div> -->
    
    <!-- DataTable JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    <script>
        // $("#av_table").DataTable({
        //     responsive: true,
        //     searching: false,
        //     paging: false,
        //     info: false,
        // });

        // var smallBreak = 800; // Your small screen breakpoint in pixels
        // var columns = $('.table tr').length;
        // var rows = $('.table .dataHead th').length;

        // $("#fi_table, #pc_table, #rc_table, #dp_table, #lg_table, #sl_table, #temp_th_table").DataTable({
        //     responsive: true,
        //     searching: false,
        //     paging: false,
        //     info: false,
        //     sort: false,
        // });

        // new DataTable('#av_table', {
        $("#av_table, #fi_table, #pc_table, #rc_table, #dp_table, #lg_table, #sl_table, #temp_th_table").DataTable({ 
        sort: false,
        searching: false,
        paging: false,
        info: false,
        responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
              header: function (row) {
                  var data = row.data();
                  return '<h2 class="text-center">Air Velocity</h2>';
              }
          }),
          
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
                return '<tr data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td class="fw-bold">' +
                            col.title +
                            ': &emsp;' +
                            '</td> ' +
                            '<td>' +
                            col.data +
                            '</td>' +
                            '</tr>'
                    ;
            }).join('');

            return data ? $('<table/>').append(data) : false;
        }
      }
  }
});
    </script>
</body>

</html>