<!-- ######## Database connection ######## -->
<?php require 'web_acsdb.php'; ?>

<!--============================== TO GET THE REPORT_ID ============================== -->
<?php
$selectedReportId = "";
if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET["report_id"];
    echo '<script>alert("Viewing for Report Id: ' . $selectedReportId . '")</script>';
}
$total_count = "";
if(isset($_GET['total_count'])){
    $total_count = $_GET['total_count'];
}

$testing_date = "";
if(isset($_GET['testing_date'])){
    $testing_date = $_GET['testing_date'];
}
// ============================== TO GET JOB CARD NUM AND LOCATION ==============================
$query = ("SELECT crm.job_card_no, cust.customer_id, cust.customer_name,  cust.area, cust.city, cust.address1, cust.address2, crm.report_id
                FROM customer_reports_master crm, customer cust
                WHERE crm.report_id='$selectedReportId' AND crm.customer_id = cust.customer_id;") or die($mysqli);
$result = $mysqli->query($query);
$row = $result->fetch_assoc();

$comma = ',';
$area = $row['area'];
$city = $row['city'];
$address1 = $row['address1'];
$address2 = $row['address2'];
$cust_name = $row['customer_name'];
$job_card = $row['job_card_no'];
$address = $address1 . $comma . $address2 . $comma . $area . $comma . $city;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JC-<?php echo ltrim($selectedReportId, 'REP_'); ?> PC REPORT</title>

    <!-- Bootstrap CSS and JS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>

    <!-- Css File link -->
    <link rel="stylesheet" href="reports_file.css">
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
</head>

<body>
    <!-- ============================== AHU ROOM DETAILS ============================= -->

    <?php
    // AHU Details 
    $query2 = ("SELECT * FROM reports_ahu_details WHERE report_id='$selectedReportId' AND pc_loc_nos > 0 AND testing_date = '$testing_date'") or die($mysqli);
    $result2 = $mysqli->query($query2);
    if (mysqli_num_rows($result2) > 0) {
        while ($row2 = $result2->fetch_assoc()):

            $num_page = 1;
            $ahu_name = $row2['ahu_name'];
            $test_condition = $row2['test_condition'];
            $witness = $row2['witness_by'];
            $test_ref = $row2['test_reference'];
            $iso_class = $row2['room_iso_class'];
            $test_area = $row2['tested_area'];

            $testing = $row2['testing_date'];
            $testing_due = $row2['testing_due_date'];
            $instrument = $row2['pc_instrument_used'];
            $ahu_seq = $row2['ahu_seq_no'];
            $room_detai = $row2['room_details'];
            $numof_ahu_rooms = $row2['room_id'];
            // $num_of_ahu = substr($numof_ahu_rooms, -2);
            $num_of_ahu = 1;


            //Updated Customer name from Service report
            $updated_customer_query = $mysqli->query("SELECT customer_name, cust_address FROM employee_job_list WHERE report_id = '$selectedReportId' AND job_date ='$testing'");
            while ($updated_customer_result_row = $updated_customer_query->fetch_assoc()) {
                $cust_name = $updated_customer_result_row["customer_name"];
                $address = $updated_customer_result_row["cust_address"];
            }


            $pc_location = $row2['pc_loc_nos'];
            $pc_area = $row2['pc_room_area'];
            $pc_cycle = $row2['pc_cycle'];
            $pc_volume = $row2['pc_volume'];
            $pc_time = $row2['pc_time'];

            $tested_name = $row2['testing_engg'];
            // echo $tested_name;
            $pc_txtarea = $row2['pc_txtarea'];

            $empArray = explode(",", $tested_name);
            // // echo $empArray;
            $inclause = "";
            foreach ($empArray as $arrValue) {
                $inclause = $inclause . "'" . $arrValue . "',";
            }
            // echo $inclause; EXIT;
            $instr = "(" . substr($inclause, 0, -1) . ")";
            // echo $instr;
    
            $wit_result = $mysqli->query("SELECT employee_id, employee_name FROM employee where employee_id in $instr");
            $testedBy = "";
            while ($wit_row = $wit_result->fetch_assoc()) {
                $testedBy = $testedBy . $wit_row["employee_name"] . ",";
            }
            $tested_by = substr($testedBy, 0, -1);

            //============================== EQUIPMENT DETAILS ==============================
    
            $query3 = ("SELECT * FROM instrument_details WHERE serial_no ='$instrument'") or die($mysqli);

            $result3 = $mysqli->query($query3);
            $row3 = $result3->fetch_assoc();

            $inst_name = $row3['instrument_used'];
            $inst_make = $row3['instrument_make'];
            $inst_model = $row3['instrument_model'];
            $inst_id = $row3['instrument_sno'];
            $cal_date = $row3['calibration_date'];
            $cal_due_date = $row3['calibration_due_date'];
            $instrument_flow = $row3['instrument_flow'];

            // ============================== PARTICLE COUNT ==============================
    
            $querypc = ("SELECT * FROM reports_pc_data WHERE ahu_seq_no ='$ahu_seq'") or die($mysqli);
            $resultpc = $mysqli->query($querypc);
            $rowcount = mysqli_num_rows($resultpc);
            // echo $rowcount;
    
            $sum_a = 0;
            $sum_b = 0;
            $sum_c = 0;
            $sum_d = 0;
            $sum_e = 0;
            $sum_f = 0;
            $sum_g = 0;
            $sum_h = 0;
            while ($pcmean = $resultpc->fetch_assoc()) {
                $pc_a = $pcmean['point3_r1'];
                $pc_b = $pcmean['point5_r1'];
                $sum_a += $pc_a;
                $sum_b += $pc_b;
                $t_1 = round($sum_a / $pc_location, 1);
                $t_2 = round($sum_b / $pc_location, 1);

                $total_1 = number_format((float) $t_1, 1, '.', '');
                $total_2 = number_format((float) $t_2, 1, '.', '');

                $pc_c = $pcmean['point5_r1'];
                $pc_d = $pcmean['5point_r1'];
                $sum_c += $pc_c;
                $sum_d += $pc_d;

                $t_3 = round($sum_c / $pc_location, 1);
                $t_4 = round($sum_d / $pc_location, 1);

                $total_3 = number_format((float) $t_3, 1, '.', '');
                $total_4 = number_format((float) $t_4, 1, '.', '');

                $pc_p3_cy3_r1 = $pcmean['point3_r1'];
                $pc_p3_cy3_r2 = $pcmean['point3_r2'];
                $pc_p3_cy3_r3 = $pcmean['point3_r3'];
                $sum_avg_cy3_1 = $pc_p3_cy3_r1 + $pc_p3_cy3_r2 + $pc_p3_cy3_r3;
                $std_cycle = 3;
                $avg_point_cy3_1 = round($sum_avg_cy3_1 / $std_cycle, 1);

                $pc_p5_cy3_r1 = $pcmean['point5_r1'];
                $pc_p5_cy3_r2 = $pcmean['point5_r2'];
                $pc_p5_cy3_r3 = $pcmean['point5_r3'];
                $sum_avg_cy3_2 = $pc_p5_cy3_r1 + $pc_p5_cy3_r2 + $pc_p5_cy3_r3;
                $avg_point_cy3_2 = round($sum_avg_cy3_2 / $std_cycle, 1);

                $pc_5p_cy3_r1 = $pcmean['5point_r1'];
                $pc_5p_cy3_r2 = $pcmean['5point_r2'];
                $pc_5p_cy3_r3 = $pcmean['5point_r3'];
                $sum_avg_cy3_3 = $pc_5p_cy3_r1 + $pc_5p_cy3_r2 + $pc_5p_cy3_r3;
                $avg_point_cy3_3 = round($sum_avg_cy3_3 / $std_cycle, 1);

                $sum_e += $avg_point_cy3_1;
                $sum_f += $avg_point_cy3_2;

                $t_5 = round($sum_e / $pc_location, 1);
                $t_6 = round($sum_f / $pc_location, 1);

                $total_5 = number_format((float) $t_5, 1, '.', '');
                $total_6 = number_format((float) $t_6, 1, '.', '');

                $sum_g += $avg_point_cy3_2;
                $sum_h += $avg_point_cy3_3;

                $t_7 = round($sum_g / $pc_location, 1);
                $t_8 = round($sum_h / $pc_location, 1);

                $total_7 = number_format((float) $t_7, 1, '.', '');
                $total_8 = number_format((float) $t_8, 1, '.', '');

            }
            // $class_name = $iso_class;
    
            if ($pc_cycle == 1) {
                $pc_cycle;
            } else {
                $pc_cycle += 1;
                $pc_cycle;
            }
            // echo $iso_class; 
            $class = $iso_class[-1];
            // echo $class;
    
            //============================== DIVIDE THE NUMBER OF PAGES AND PRINT IT ==============================
    
            $pages = $rowcount / 10;
            // echo $pages;
            $pr = ceil($pages);
            // echo $pr;
            $perpage = 10;

            for ($i = 1; $i <= $pr; $i++):

                if ($pr > 0) {
                    $print_data = $i - 1;
                } else {
                    $print_data = 0;
                }
                // echo $print_data;
                $data_per = $print_data * 10;
                // echo $data_per;
                //============================== PC TESTING VALUES LIMIT PER PAGES ==============================
                $pcquery4 = ("SELECT * FROM reports_pc_data WHERE ahu_seq_no ='$ahu_seq' limit $data_per,$perpage") or die($mysqli);
                $pcresult4 = $mysqli->query($pcquery4);
                $pcrow_count = mysqli_num_rows($pcresult4);
                ?>

                <br><br><br><br><br><br><br><br><br>

                <table id="Equip" style="margin-bottom: 10px;">
                    <tr>
                        <th>CUSTOMER </th>
                    </tr>
                    <tr id="cust_details">
                        <td> <span style="font-weight: bold;"><?php echo $cust_name; ?></span><?php echo '<br>'; echo $address;?></td>
                    </tr>
                    
                </table>
                <!-- ============================== PC CUSTOMER DETAILS ============================== -->
                <table id="customer">
                    <tr>
                        <th colspan="4">PARTICLE COUNT&nbsp; TEST REPORT</th>
                    </tr>
                    
                    <tr>
                        <td class="cust_first"> <span style="font-weight: bold;"> Certificate No.</span>  </td>
                        <td class="cust_sec">
                            <span style="font-weight: bold;">
                                <?php echo $job_card; ?> &#47; PC - <?php 
                                    if($print_data != 0) {
                                        echo str_pad($total_count, 4, "0", STR_PAD_LEFT); echo '&#47;'; echo $num_page++; echo '&nbsp;'; echo 'of'; echo '&nbsp;'; echo $pr;
                                    } else {
                                        echo str_pad($total_count++ + 1, 4, "0", STR_PAD_LEFT); echo '&#47;'; echo $num_page++; echo '&nbsp;'; echo 'of'; echo '&nbsp;'; echo $pr;
                                    } ?>
                            </span>
                        </td>
                        <td class="cust_first"> Test Condition </td>
                        <td class="cust_sec">
                            <?php echo $test_condition; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="cust_first">Witnessed by</td>
                        <td class="cust_sec"><span>
                                <?php echo $witness; ?>
                            </span></td>
                        <td class="cust_first"> Test Reference </td>
                        <td class="cust_sec">
                            <?php echo $test_ref; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="cust_first">Tested by </td>
                        <td class="cust_sec">
                            <?php echo $tested_by; ?>
                        </td>
                        <td class="cust_first"> Classification of Area </td>
                        <td class="cust_sec">
                            <?php echo $iso_class; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="cust_first">Date of Testing </td>
                        <td class="cust_sec">
                            <?php echo date('d F Y', strtotime($testing)); ?>
                        </td>
                        <td class="cust_first"> Test of AHU No / Eqp ID </td>
                        <td class="cust_sec">
                            <?php echo $ahu_name; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="cust_first">Next Due Date </td>
                        <td class="cust_sec">
                                <?php 
                                if ($testing_due != "0000-00-00") {
                                    echo date("d F Y", strtotime($testing_due));
                                } else {
                                    echo 'Not Applicable';
                                }
                                ?></td>
                        <td class="cust_first">Area of Test</td>
                        <td class="cust_sec">
                            <?php echo $test_area; ?>
                        </td>
                    </tr>
                </table>
                <!--============================== INSTRUMENT DETAILS ==============================-->

                <table id="Equip">
                    <tr>
                        <th colspan="6">EQUIPMENT &nbsp; DETAILS</th>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;">Name</span></td>
                        <td><span style="font-weight: bold;">Make</span></td>
                        <td><span style="font-weight: bold;">Model</span></td>
                        <td><span style="font-weight: bold;">Serial No / ID</span></td>
                        <td><span style="font-weight: bold;">Calibration On</span></td>
                        <td><span style="font-weight: bold;">Calibration Due On</span></td>
                    </tr>

                    <tr>
                        <td>
                            <?php echo $inst_name; ?>
                        </td>
                        <td>
                            <?php echo $inst_make; ?>
                        </td>
                        <td>
                            <?php echo $inst_model; ?>
                        </td>
                        <td>
                            <?php echo $inst_id; ?>
                        </td>
                        <td>
                            <?php echo date('d F Y', strtotime($cal_date)); ?>
                        </td>
                        <td>
                            <?php echo date('d F Y', strtotime($cal_due_date)); ?>
                        </td>
                    </tr>

                </table>
                <!-- ============================== PC TEST RESULTS IN DIFFERENT FORMAT ============================== -->

                <table id="velo">
                    <tr>
                        <th colspan="13">OBTAINED TEST RESULTS</th>
                    </tr>
                    <tr>
                        <td colspan="2"><span style="font-weight: bold;">Sampling <br>Protocol</span></td>
                        <td><span style="font-weight: bold;">Room Area(Sq.Mt)</span> <br>
                            <?php echo $pc_area; ?>
                        </td>
                        <td colspan="2"><span style="font-weight: bold;">Location</span><br>
                            <?php echo $pc_location; ?>
                        </td>
                        <td><span style="font-weight: bold;">Cycle</span><br>
                            <?php echo $pc_cycle; ?>
                        </td>
                        <td colspan="2"><span style="font-weight: bold;">Volume</span><br>
                            <?php echo $pc_volume; ?> liters
                        </td>
                        <td colspan="2"><span style="font-weight: bold;">Time</span><br>
                            <?php echo $pc_time; ?>
                        </td>
                        <td colspan="3"><span style="font-weight: bold;">Instrument Flow Rate</span><br>
                            <?php echo $instrument_flow; ?>LPM ( &#177; 5% )
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2"><span style="font-weight: bold;">Sr.No</span></td>
                        <td colspan="2" rowspan="2"><span style="font-weight: bold;">Description </span></td>

                        <!-- ============================== TABLE HEADER AUTOMATICALLY CHANGE DEPENDTS ON ISO CLASS ============================== -->

                        <?php

                        //============================== IF ISO CLASS 05 ============================== -->
                        if ($iso_class == "ISO-05") { ?>

                            <td rowspan="2"><span style="font-weight: bold;">Location</span></td>
                            <td colspan="4"><span style="font-weight: bold;">No.of Particles <br> &#8805; 0.3 &#181;m/m&#179;</span>
                            </td>
                            <td colspan="4"><span style="font-weight: bold;">No.of Particles <br> &#8805; 0.5 &#181;m/m&#179;</span>
                            </td>
                            <td rowspan="2"><span style="font-weight: bold;">Result</span></td>

                        <?php } else { ?>

                            <td rowspan="2"><span style="font-weight: bold;">Location</span></td>
                            <td colspan="4"><span style="font-weight: bold;">No.of Particles <br> &#8805; 0.5 &#181;m/m&#179;</span>
                            <td colspan="4"><span style="font-weight: bold;">No.of Particles <br> &#8805; 5.0 &#181;m/m&#179;</span>
                            </td>
                            <td rowspan="2"><span style="font-weight: bold;">Result</span></td>

                        <?php } ?>

                    <tr>
                        <?php if ($pc_cycle == 3) { ?>
                            <td>R1</td>
                            <td>R2</td>
                            <td>R3</td>
                            <td style="font-weight: bold;">Avg</td>
                            <td>R1</td>
                            <td>R2</td>
                            <td>R3</td>
                            <td style="font-weight: bold; border-bottom: 1.5px dotted;">Avg</td>
                        <?php } ?>
                    </tr>

                    </tr>

                    <?PHP // $SEC_NUMBER_PC = 1;   ?>
                    <tr>
                        <td rowspan="<?php echo $pc_location + 1; ?>">
                            <?php echo $num_of_ahu++; ?>
                        </td>
                        <td colspan="2" rowspan="<?php echo $pc_location + 1; ?>">
                            <?php echo $room_detai; ?>
                        </td>
                        <?php
                        //============================== IF PC CYCLE - 01 ============================== -->
                        if ($pc_cycle == 1):

                            // We need to assign the variable zero purpose of pc average value
                            $sum_a = 0;
                            $sum_b = 0;
                            $sum_c = 0;
                            $sum_d = 0;
                            while ($pc_data = $pcresult4->fetch_assoc()):
                                ?>
                                <!-- we get ISO-05 below section to print -->
                                <?php if ($iso_class == "ISO-05") { ?>
                                    <td>
                                        <?php echo $pc_data['pc_loc_seq_no']; ?>
                                    </td>
                                    <?php if ($inst_model == 'APEX Z50') { ?>
                                        <td colspan="4">
                                            <?php echo $pc_cy1_p3_r1 = number_format($pc_data['point3_r1'], 1, '.', ''); ?>
                                        </td>
                                        <td colspan="4">
                                            <?php echo $pc_cy1_p5_r1 = number_format($pc_data['point5_r1'], 1, '.', ''); ?>
                                        </td>
                                    <?php } else { ?>

                                        <td colspan="4">
                                            <?php echo $pc_cy1_p3_r1 = $pc_data['point3_r1']; ?>
                                        </td>
                                        <td colspan="4">
                                            <?php echo $pc_cy1_p5_r1 = $pc_data['point5_r1']; ?>
                                        </td>
                                    <?php } ?>

                                    <!-- ISO Result section  -->
                                    <?php

                                    if ($test_condition == "AT-REST" || $test_condition == "AS-BUILD") {
                                        switch (true) {
                                            case $iso_class == "ISO-05" && $pc_cy1_p3_r1 <= 10200 && $pc_cy1_p5_r1 <= 3520:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && 0 < $pc_cy1_p3_r1 && $pc_cy1_p5_r1 <= 35200:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && 0 < $pc_cy1_p3_r1 && $pc_cy1_p5_r1 <= 352000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && 0 < $pc_cy1_p3_r1 && $pc_cy1_p5_r1 <= 3520000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && 0 < $pc_cy1_p3_r1 && $pc_cy1_p5_r1 <= 35200000:
                                                echo '<td> PASS </td>';
                                                break;
                                            default:
                                                echo '<td contenteditable="true"> FAIL </td>';
                                        }
                                    } else {
                                        switch (true) {
                                            case $iso_class == "ISO-05" && $pc_cy1_p3_r1 <= 10200 && $pc_cy1_p5_r1 <= 3520:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && 0 < $pc_cy1_p3_r1 && $pc_cy1_p5_r1 <= 352000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && 0 < $pc_cy1_p3_r1 && $pc_cy1_p5_r1 <= 3520000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && 0 < $pc_cy1_p3_r1 && 0 < $pc_cy1_p5_r1:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && 0 < $pc_cy1_p3_r1 && 0 < $pc_cy1_p5_r1:
                                                echo '<td> PASS </td>';
                                                break;
                                            default:
                                                echo '<td contenteditable="true"> FAIL </td>';
                                        }

                                    }

                                    ?>
                                    <!-- Incase we get the ISO-06,07,08 -->
                                <?php } else { ?>
                                    <td>
                                        <?php echo $pc_data['pc_loc_seq_no']; ?>
                                    </td>
                                    <?php if ($inst_model == 'APEX Z50') { ?>
                                        <td colspan="4">
                                            <?php echo $pc_cy1_p5_r1 = number_format($pc_data['point5_r1'], 1, '.', ''); ?>
                                        </td>
                                        <td colspan="4">
                                            <?php echo $pc_cy1_5p_r1 = number_format($pc_data['5point_r1'], 1, '.', ''); ?>
                                        </td>
                                    <?php } else { ?>
                                        <td colspan="4">
                                            <?php echo $pc_cy1_p5_r1 = $pc_data['point5_r1']; ?>
                                        </td>
                                        <td colspan="4">
                                            <?php echo $pc_cy1_5p_r1 = $pc_data['5point_r1']; ?>
                                        </td>
                                    <?php } ?>
                                    <!-- ISO Result section assign -->
                                    <?php
                                    if ($test_condition == "AT-REST" || $test_condition == "AS-BUILD") {
                                        switch (true) {
                                            case $iso_class == "ISO-5" && $pc_cy1_p5_r1 <= 3520 && $pc_cy1_5p_r1 <= 29:
                                                echo '<td > PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && $pc_cy1_p5_r1 <= 35200 && $pc_cy1_5p_r1 <= 293:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && $pc_cy1_p5_r1 <= 352000 && $pc_cy1_5p_r1 <= 2930:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && $pc_cy1_p5_r1 <= 3520000 && $pc_cy1_5p_r1 <= 29300:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && $pc_cy1_p5_r1 <= 35200000 && $pc_cy1_5p_r1 <= 293000:
                                                echo '<td> PASS </td>';
                                                break;

                                            // GRADE System AT REST and AT BUID
                                            case $iso_class == "GRADE-A" && $pc_cy1_p5_r1 <= 3520 && $pc_cy1_5p_r1 <= 20:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-B" && $pc_cy1_p5_r1 <= 3520 && $pc_cy1_5p_r1 <= 29:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-C" && $pc_cy1_p5_r1 <= 352000 && $pc_cy1_5p_r1 <= 2900:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-D" && $pc_cy1_p5_r1 <= 3520000 && $pc_cy1_5p_r1 <= 29000:
                                                echo '<td> PASS </td>';
                                                break;
                                            default:
                                                echo '<td> FAIL </td>';
                                        }
                                    } else {
                                        switch (true) {
                                            case $iso_class == "ISO-5" && $pc_cy1_p5_r1 <= 3520 && $pc_cy1_5p_r1 <= 29:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && $pc_cy1_p5_r1 <= 352000 && $pc_cy1_5p_r1 <= 2930:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && $pc_cy1_p5_r1 <= 3520000 && $pc_cy1_5p_r1 <= 29300:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && 0 < $pc_cy1_p5_r1 && 0 < $pc_cy1_5p_r1:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && 0 < $pc_cy1_p5_r1 && 0 < $pc_cy1_5p_r1:
                                                echo '<td> PASS </td>';
                                                break;

                                            // GRADE System IN OPERATION 
                                            case $iso_class == "GRADE-A" && $pc_cy1_p5_r1 <= 3520 && $pc_cy1_5p_r1 <= 20:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-B" && $pc_cy1_p5_r1 <= 352000 && $pc_cy1_5p_r1 <= 2900:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-C" && $pc_cy1_p5_r1 <= 3520000 && $pc_cy1_5p_r1 <= 29000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-D" && 0 < $pc_cy1_p5_r1 && 0 < $pc_cy1_5p_r1:
                                                echo '<td> PASS </td>';
                                                break;
                                            default:
                                                echo '<td contenteditable="true"> FAIL </td>';
                                        }
                                    }
                                    ?>
                                <?php }

                                ?>
                            </tr>
                        <?php endwhile; ?>
                        <!-- To print the mean value in final execution of cycle one -->
                        <?php if ($pr == $i): ?>

                            <?php if ($iso_class == "ISO-05") { ?>
                                <tr>
                                    <td colspan="2" style="font-weight: bold;">Mean Avg.</td>
                                    <td colspan="3">
                                        <?php echo $total_1; ?>
                                    </td>
                                    <td colspan="4">
                                        <?php echo $total_2; ?>
                                    </td>
                                    <?php
                                    if ($test_condition == "AT-REST" || $test_condition == "AS-BUILD") {
                                        switch (true) {
                                            case $iso_class == "ISO-05" && $total_1 <= 10200 && $total_2 <= 3520:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && 0 < $total_1 && $total_2 <= 35200:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && 0 < $total_1 && $total_2 <= 352000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && 0 < $total_1 && $total_2 <= 3520000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && 0 < $total_1 && $total_2 <= 35200000:
                                                echo '<td> PASS </td>';
                                                break;
                                            default:
                                                echo '<td> FAIL </td>';
                                        }
                                    } else {
                                        switch (true) {
                                            case $iso_class == "ISO-05" && $total_1 <= 10200 && $total_2 <= 3520:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && 0 < $total_1 && $total_2 <= 352000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && 0 < $total_1 && $total_2 <= 3520000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && 0 < $total_1 && 0 < $total_2:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && 0 < $total_1 && 0 < $total_2:
                                                echo '<td> PASS </td>';
                                                break;
                                            default:
                                                echo '<td> FAIL </td>';
                                        }

                                    }
                                    ?>

                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2" style="font-weight: bold;">Mean Avg.</td>
                                    <td colspan="3">
                                        <?php echo $total_3; ?>
                                    </td>
                                    <td colspan="4">
                                        <?php echo $total_4; ?>
                                    </td>
                                    <?php
                                    if ($test_condition == "AT-REST" || $test_condition == "AS-BUILD") {
                                        switch (true) {
                                            case $iso_class == "ISO-5" && $total_3 <= 3520 && $total_4 <= 29:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && $total_3 <= 35200 && $total_4 <= 293:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && $total_3 <= 352000 && $total_4 <= 2930:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && $total_3 <= 3520000 && $total_4 <= 29300:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && $total_3 <= 35200000 && $total_4 <= 293000:
                                                echo '<td> PASS </td>';
                                                break;

                                            // GRADE System
                                            case $iso_class == "GRADE-A" && $total_3 <= 3520 && $total_4 <= 20:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-B" && $total_3 <= 3520 && $total_4 <= 29:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-C" && $total_3 <= 352000 && $total_4 <= 2900:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-D" && $total_3 <= 3520000 && $total_4 <= 29000:
                                                echo '<td> PASS </td>';
                                                break;

                                            default:
                                                echo '<td> FAIL </td>';
                                        }
                                    } else {
                                        switch (true) {
                                            case $iso_class == "ISO-5" && $total_3 <= 3520 && $total_4 <= 29:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && $total_3 <= 352000 && $total_4 <= 2930:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && $total_3 <= 3520000 && $total_4 <= 29300:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && 0 < $total_3 && 0 < $total_4:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && 0 < $total_3 && 0 < $total_4:
                                                echo '<td> PASS </td>';
                                                break;

                                            // GRADE system
                                            case $iso_class == "GRADE-A" && $total_3 <= 3520 && $total_4 <= 20:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-B" && $total_3 <= 352000 && $total_4 <= 2900:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-C" && $total_3 <= 3520000 && $total_4 <= 29000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-D" && 0 < $total_3 && 0 < $total_4:
                                                echo '<td> PASS </td>';
                                                break;
                                            default:
                                                echo '<td> FAIL </td>';
                                        }

                                    }
                                    ?>

                                </tr>


                            <?php }endif; endif; ?>


                    <!-- ============================== IF PC CYCLE - 03 ============================== -->
                    <?php
                    if ($pc_cycle == 3):

                        // We need to assign the variable zero purpose of pc average value
        
                        foreach ($pcresult4 as $pcdata):

                            $sum_avg_1 = 0;
                            $sum_avg_2 = 0;
                            $sum_avg_3 = 0;
                            $sum_avg_4 = 0;
                            ?>
                            <!-- we get ISO-05 below section to print -->
                            <?php if ($iso_class == 'ISO-05') { ?>
                                <td>
                                    <?php echo $pcdata['pc_loc_seq_no']; ?>
                                </td>
                                <?php if ($inst_model == 'APEX Z50') { ?>
                                    <td>
                                        <?php echo number_format($pcdata['point3_r1'], 1, '.', ''); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($pcdata['point3_r2'], 1, '.', ''); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($pcdata['point3_r3'], 1, '.', ''); ?>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <?php echo $pcdata['point3_r1']; ?>
                                    </td>
                                    <td>
                                        <?php echo $pcdata['point3_r2']; ?>
                                    </td>
                                    <td>
                                        <?php echo $pcdata['point3_r3']; ?>
                                    </td>
                                <?php } ?>
                                <?php
                                $pc_p3_r1 = $pcdata['point3_r1'];
                                $pc_p3_r2 = $pcdata['point3_r2'];
                                $pc_p3_r3 = $pcdata['point3_r3'];
                                $sum_avg_3 = $pc_p3_r1 + $pc_p3_r2 + $pc_p3_r3;
                                $std_cycle = 3;
                                $av_1 = round($sum_avg_3 / $std_cycle, 1);
                                $avg_point1 = number_format((float) $av_1, 1, '.', '');
                                ?>
                                <td>
                                    <?php echo $avg_point1; ?>
                                </td>
                                <?php if ($inst_model == 'APEX Z50') { ?>
                                    <td>
                                        <?php echo number_format($pcdata['point5_r1'], 1, '.', ''); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($pcdata['point5_r2'], 1, '.', ''); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($pcdata['point5_r3'], 1, '.', ''); ?>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <?php echo $pcdata['point5_r1']; ?>
                                    </td>
                                    <td>
                                        <?php echo $pcdata['point5_r2']; ?>
                                    </td>
                                    <td>
                                        <?php echo $pcdata['point5_r3']; ?>
                                    </td>
                                <?php } ?>
                                <?php
                                $pc_p5_r1 = $pcdata['point5_r1'];
                                $pc_p5_r2 = $pcdata['point5_r2'];
                                $pc_p5_r3 = $pcdata['point5_r3'];
                                $sum_avg_4 = $pc_p5_r1 + $pc_p5_r2 + $pc_p5_r3;
                                $av_2 = round($sum_avg_4 / $std_cycle, 1);
                                $avg_point2 = number_format((float) $av_2, 1, '.', '');
                                ?>
                                <td>
                                    <?php echo $avg_point2; ?>
                                </td>
                                <!-- Cycle 3 result section -->
                                <?php
                                if ($test_condition == "AT-REST" || $test_condition == "AS-BUILD") {
                                    switch (true) {
                                        case $iso_class == "ISO-05" && $pc_p5_r1 <= 3520 && $pc_p5_r2 <= 3520 && $pc_p5_r3 <= 3520
                                        && $pc_p3_r1 <= 10200 && $pc_p3_r2 <= 10200 && $pc_p3_r3 <= 10200:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-06" && $pc_p5_r1 <= 35200 && $pc_p5_r2 <= 35200 && $pc_p5_r3 <= 35200
                                        && 0 < $pc_p3_r1 && 0 < $pc_p3_r2 && 0 < $pc_p3_r3:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-07" && $pc_p5_r1 <= 352000 && $pc_p5_r2 <= 352000 && $pc_p5_r3 <= 352000
                                        && 0 < $pc_p3_r1 && 0 < $pc_p3_r2 && 0 < $pc_p3_r3:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-08" && $pc_p5_r1 <= 3520000 && $pc_p5_r2 <= 3520000 && $pc_p5_r3 <= 3520000
                                        && 0 < $pc_p3_r1 && 0 < $pc_p3_r2 && 0 < $pc_p3_r3:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-09" && $pc_p5_r1 <= 35200000 && $pc_p5_r2 <= 35200000 && $pc_p5_r3 <= 35200000
                                        && 0 < $pc_p3_r1 && 0 < $pc_p3_r2 && 0 < $pc_p3_r3:
                                            echo '<td> PASS </td>';
                                            break;
                                        default:
                                            echo '<td> FAIL </td>';
                                    }
                                } else {
                                    switch (true) {
                                        case $iso_class == "ISO-05" && $pc_p5_r1 <= 3520 && $pc_p5_r2 <= 3520 && $pc_p5_r3 <= 3520
                                        && $pc_p3_r1 <= 10200 && $pc_p3_r2 <= 10200 && $pc_p3_r3 <= 10200:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-06" && $pc_p5_r1 <= 352000 && $pc_p5_r2 <= 352000 && $pc_p5_r3 <= 352000
                                        && 0 < $pc_p3_r1 && 0 < $pc_p3_r2 && 0 < $pc_p3_r3:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-07" && $pc_p5_r1 <= 3520000 && $pc_p5_r2 <= 3520000 && $pc_p5_r3 <= 3520000
                                        && 0 < $pc_p3_r1 && 0 < $pc_p3_r2 && 0 < $pc_p3_r3:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-08" && 0 < $pc_p5_r1 && 0 < $pc_p5_r2 && 0 < $pc_p5_r3
                                        && 0 < $pc_p3_r1 && 0 < $pc_p3_r2 && 0 < $pc_p3_r3:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-09" && 0 < $pc_p5_r1 && 0 < $pc_p5_r2 && 0 < $pc_p5_r3
                                        && 0 < $pc_p3_r1 && 0 < $pc_p3_r2 && 0 < $pc_p3_r3:
                                            echo '<td> PASS </td>';
                                            break;
                                        default:
                                            echo '<td> FAIL </td>';
                                    }

                                }
                                ?>
                                <!-- Incase we get the ISO-06,07,08 -->
                            <?php } else { ?>
                                <td>
                                    <?php echo $pcdata['pc_loc_seq_no']; ?>
                                </td>
                                <?php if ($inst_model == 'APEX Z50') { ?>
                                    <td>
                                        <?php echo number_format($pcdata['point5_r1'], 1, '.', ''); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($pcdata['point5_r2'], 1, '.', ''); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($pcdata['point5_r3'], 1, '.', ''); ?>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <?php echo $pcdata['point5_r1']; ?>
                                    </td>
                                    <td>
                                        <?php echo $pcdata['point5_r2']; ?>
                                    </td>
                                    <td>
                                        <?php echo $pcdata['point5_r3']; ?>
                                    </td>
                                <?php } ?>
                                <?php
                                $pc_p5_r1 = $pcdata['point5_r1'];
                                $pc_p5_r2 = $pcdata['point5_r2'];
                                $pc_p5_r3 = $pcdata['point5_r3'];
                                $sum_avg_1 = $pc_p5_r1 + $pc_p5_r2 + $pc_p5_r3;
                                $std_cycle = 3;
                                $av_3 = round($sum_avg_1 / $std_cycle, 1);
                                $avg_point3 = number_format((float) $av_3, 1, '.', '');
                                ?>
                                <td>
                                    <?php echo $avg_point3; ?>
                                </td>

                                <?php if ($inst_model == 'APEX Z50') { ?>
                                    <td>
                                        <?php echo number_format($pcdata['5point_r1'], 1, '.', ''); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($pcdata['5point_r2'], 1, '.', ''); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($pcdata['5point_r3'], 1, '.', ''); ?>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <?php echo $pcdata['5point_r1']; ?>
                                    </td>
                                    <td>
                                        <?php echo $pcdata['5point_r2']; ?>
                                    </td>
                                    <td>
                                        <?php echo $pcdata['5point_r3']; ?>
                                    </td>
                                <?php } ?>
                                <?php
                                $pc_5p_r1 = $pcdata['5point_r1'];
                                $pc_5p_r2 = $pcdata['5point_r2'];
                                $pc_5p_r3 = $pcdata['5point_r3'];
                                $sum_avg_2 = $pc_5p_r1 + $pc_5p_r2 + $pc_5p_r3;
                                $av_4 = round($sum_avg_2 / $std_cycle, 1);
                                $avg_point4 = number_format((float) $av_4, 1, '.', '');

                                ?>
                                <td>
                                    <?php echo $avg_point4; ?>
                                </td>
                                <?php
                                if ($test_condition == "AT-REST" || $test_condition == "AS-BUILD") {
                                    switch (true) {
                                        case $iso_class == "ISO-5" && $pc_p5_r1 <= 3520 && $pc_p5_r2 <= 3520 && $pc_p5_r3 <= 3520
                                        && $pc_5p_r1 <= 29 && $pc_5p_r2 <= 29 && $pc_5p_r3 <= 29:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-06" && $pc_p5_r1 <= 35200 && $pc_p5_r2 <= 35200 && $pc_p5_r3 <= 35200
                                        && $pc_5p_r1 <= 293 && $pc_5p_r2 <= 293 && $pc_5p_r3 <= 293:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-07" && $pc_p5_r1 <= 352000 && $pc_p5_r2 <= 352000 && $pc_p5_r3 <= 352000
                                        && $pc_5p_r1 <= 2930 && $pc_5p_r2 <= 2930 && $pc_5p_r3 <= 2930:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-08" && $pc_p5_r1 <= 3520000 && $pc_p5_r2 <= 3520000 && $pc_p5_r3 <= 3520000
                                        && $pc_5p_r1 <= 29300 && $pc_5p_r2 <= 29300 && $pc_5p_r3 <= 29300:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-09" && $pc_p5_r1 <= 35200000 && $pc_p5_r2 <= 3520000 && $pc_p5_r3 <= 3520000
                                        && $pc_5p_r1 <= 293000 && $pc_5p_r2 <= 293000 && $pc_5p_r3 <= 293000:
                                            echo '<td> PASS </td>';
                                            break;

                                        // GRADE System AT BUILD and AT REST
                                        case $iso_class == "GRADE-A" && $pc_p5_r1 <= 3520 && $pc_p5_r2 <= 3520 && $pc_p5_r3 <= 3520
                                        && $pc_5p_r1 <= 20 && $pc_5p_r2 <= 20 && $pc_5p_r3 <= 20:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "GRADE-B" && $pc_p5_r1 <= 3520 && $pc_p5_r2 <= 3520 && $pc_p5_r3 <= 3520
                                        && $pc_5p_r1 <= 29 && $pc_5p_r2 <= 29 && $pc_5p_r3 <= 29:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "GRADE-C" && $pc_p5_r1 <= 352000 && $pc_p5_r2 <= 352000 && $pc_p5_r3 <= 352000
                                        && $pc_5p_r1 <= 2900 && $pc_5p_r2 <= 2900 && $pc_5p_r3 <= 2900:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "GRADE-D" && $pc_p5_r1 <= 3520000 && $pc_p5_r2 <= 352000 && $pc_p5_r3 <= 352000
                                        && $pc_5p_r1 <= 29000 && $pc_5p_r2 <= 29000 && $pc_5p_r3 <= 29000:
                                            echo '<td> PASS </td>';
                                            break;

                                        default:
                                            echo '<td> FAIL </td>';
                                    }
                                } else {
                                    switch (true) {
                                        case $iso_class == "ISO-5" && $pc_p5_r1 <= 3520 && $pc_p5_r2 <= 3520 && $pc_p5_r3 <= 3520
                                        && $pc_5p_r1 <= 29 && $pc_5p_r2 <= 29 && $pc_5p_r3 <= 29:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-06" && $pc_p5_r1 <= 352000 && $pc_p5_r2 <= 352000 && $pc_p5_r3 <= 352000
                                        && $pc_5p_r1 <= 2930 && $pc_5p_r2 <= 2930 && $pc_5p_r3 <= 2930:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-07" && $pc_p5_r1 <= 3520000 && $pc_p5_r2 <= 3520000 && $pc_p5_r3 <= 3520000
                                        && $pc_5p_r1 <= 29300 && $pc_5p_r2 <= 29300 && $pc_5p_r3 <= 29300:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-08" && 0 < $pc_p5_r1 && 0 < $pc_p5_r2 && 0 < $pc_p5_r3
                                        && 0 < $pc_5p_r1 && 0 < $pc_5p_r2 && 0 < $pc_5p_r3:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "ISO-09" && 0 < $pc_p5_r1 && 0 < $pc_p5_r2 && 0 < $pc_p5_r3
                                        && 0 < $pc_5p_r1 && 0 < $pc_5p_r2 && 0 < $pc_5p_r3:
                                            echo '<td> PASS </td>';
                                            break;

                                        // GRADE System IN OPERATION
                                        case $iso_class == "GRADE-A" && $pc_p5_r1 <= 3520 && $pc_p5_r2 <= 3520 && $pc_p5_r3 <= 3520
                                        && $pc_5p_r1 <= 20 && $pc_5p_r2 <= 20 && $pc_5p_r3 <= 20:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "GRADE-B" && $pc_p5_r1 <= 352000 && $pc_p5_r2 <= 352000 && $pc_p5_r3 <= 352000
                                        && $pc_5p_r1 <= 2900 && $pc_5p_r2 <= 2900 && $pc_5p_r3 <= 2900:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "GRADE-C" && $pc_p5_r1 <= 3520000 && $pc_p5_r2 <= 3520000 && $pc_p5_r3 <= 3520000
                                        && $pc_5p_r1 <= 29000 && $pc_5p_r2 <= 29000 && $pc_5p_r3 <= 29000:
                                            echo '<td> PASS </td>';
                                            break;
                                        case $iso_class == "GRADE-D" && 0 < $pc_p5_r1 && 0 < $pc_p5_r2 && 0 < $pc_p5_r3
                                        && 0 < $pc_5p_r1 && 0 < $pc_5p_r2 && 0 < $pc_5p_r3:
                                            echo '<td> PASS </td>';
                                            break;
                                        default:
                                            echo '<td> FAIL </td>';
                                    }

                                }
                            } ?>
                            </tr>
                        <?php endforeach; ?>
                        <!-- To print the mean value in final execution of cycle three -->
                        <?php if ($pr == $i): ?>
                            <?php if ($iso_class == 'ISO-05') { ?>
                                <tr>
                                    <td style="font-weight: bold;">Mean</td>
                                    <td colspan="3"></td>
                                    <td>
                                        <?php echo $total_5; ?>
                                    </td>
                                    <td colspan="3"></td>
                                    <td>
                                        <?php echo $total_6; ?>
                                    </td>
                                    <?php
                                    if ($test_condition == "AT-REST" || $test_condition == "AS-BUILD") {
                                        switch (true) {
                                            case $iso_class == "ISO-05" && $total_5 <= 10200 && $total_6 <= 3520:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && 0 < $total_5 && $total_6 <= 35200:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && 0 < $total_5 && $total_6 <= 352000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && 0 < $total_5 && $total_6 <= 3520000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && 0 < $total_5 && $total_6 <= 35200000:
                                                echo '<td> PASS </td>';
                                                break;
                                            default:
                                                echo '<td> FAIL </td>';
                                        }
                                    } else {
                                        switch (true) {
                                            case $iso_class == "ISO-05" && $total_5 <= 10200 && $total_6 <= 3520:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && 0 < $total_5 && $total_6 <= 352000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && 0 < $total_5 && $total_6 <= 3520000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && 0 < $total_5 && 0 < $total_6:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && 0 < $total_5 && 0 < $total_6:
                                                echo '<td> PASS </td>';
                                                break;
                                            default:
                                                echo '<td> FAIL </td>';
                                        }

                                    }
                                    ?>
                                </tr>

                            <?php } else { ?>
                                <tr>
                                    <td style="font-weight: bold;">Mean</td>
                                    <td colspan="3"></td>
                                    <td>
                                        <?php echo $total_7; ?>
                                    </td>
                                    <td colspan="3"></td>
                                    <td>
                                        <?php echo $total_8; ?>
                                    </td>
                                    <?php
                                    if ($test_condition == "AT-REST" || $test_condition == "AS-BUILD") {
                                        switch (true) {
                                            case $iso_class == "ISO-5" && $total_7 <= 3520 && $total_8 <= 29:
                                                echo '<td> PASS  </td>';
                                                break;
                                            case $iso_class == "ISO-06" && $total_7 <= 35200 && $total_8 <= 293:
                                                echo '<td> PASS  </td>';
                                                break;
                                            case $iso_class == "ISO-07" && $total_7 <= 352000 && $total_8 <= 2930:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && $total_7 <= 3520000 && $total_8 <= 29300:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && $total_7 <= 35200000 && $total_8 <= 293000:
                                                echo '<td> PASS </td>';
                                                break;

                                            // GRADE System AT REST and AT BUILD
                                            case $iso_class == "GRADE-A" && $total_7 <= 3520 && $total_8 <= 20:
                                                echo '<td> PASS  </td>';
                                                break;
                                            case $iso_class == "GRADE-B" && $total_7 <= 3520 && $total_8 <= 29:
                                                echo '<td> PASS  </td>';
                                                break;
                                            case $iso_class == "GRADE-C" && $total_7 <= 352000 && $total_8 <= 2900:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-D" && $total_7 <= 3520000 && $total_8 <= 29000:
                                                echo '<td> PASS </td>';
                                                break;

                                            default:
                                                echo '<td> FAIL </td>';
                                        }
                                    } else {
                                        switch (true) {
                                            case $iso_class == "ISO-5" && $total_7 <= 3520 && $total_8 <= 29:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-06" && $total_7 <= 352000 && $total_8 <= 2930:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-07" && $total_7 <= 3520000 && $total_8 <= 29300:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-08" && 0 < $total_7 && 0 < $total_8:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "ISO-09" && 0 < $total_7 && 0 < $total_8:
                                                echo '<td> PASS </td>';
                                                break;

                                            // GRADE System IN Operation
                                            case $iso_class == "GRADE-A" && $total_7 <= 3520 && $total_8 <= 20:
                                                echo '<td> PASS  </td>';
                                                break;
                                            case $iso_class == "GRADE-B" && $total_7 <= 352000 && $total_8 <= 2900:
                                                echo '<td> PASS  </td>';
                                                break;
                                            case $iso_class == "GRADE-C" && $total_7 <= 3520000 && $total_8 <= 29000:
                                                echo '<td> PASS </td>';
                                                break;
                                            case $iso_class == "GRADE-D" && 0 < $total_7 && 0 < $total_8:
                                                echo '<td> PASS </td>';
                                                break;
                                            default:
                                                echo '<td> FAIL </td>';
                                        }

                                    }
                                    ?>
                                </tr>

                            <?php }endif; endif; ?>
                </table>

                <?php
                // echo $pcrow_count;
                // ============================== TO ADJUST THE PAGE HEIGHT ==============================
    
                switch ($pcrow_count) {
                    case 1:
                        echo '<div id="empty" style="height:180px; "></div>';
                        break;
                    case 2:
                        echo '<div id="empty" style="height:160px; "></div>';
                        break;
                    case 3:
                        echo '<div id="empty" style="height:140px; "></div>';
                        break;
                    case 4:
                        echo '<div id="empty" style="height:120px; "></div>';
                        break;
                    case 5:
                        echo '<div id="empty" style="height:100px; "></div>';
                        break;
                    case 6:
                        echo '<div id="empty" style="height:80px; "></div>';
                        break;
                    case 7:
                        echo '<div id="empty" style="height:60px; "></div>';
                        break;
                    case 8:
                        echo '<div id="empty" style="height:40px; "></div>';
                        break;
                    case 9:
                        echo '<div id="empty" style="height:20px; "></div>';
                        break;
                    default:
                        echo '<div id="empty" style="height:0px; "></div>';
                        break;
                }

                $test_bottom_table = $test_ref[1];
                // echo $test_bottom_table;
                if ($test_bottom_table == 'S') {
                    ?>
                    <!-- ============================== FOOTER PC CLASSIFICATION TABLE ============================== -->

                    <table id="velo">

                        <tr style="border-top: 1px dotted; text-align:center;">
                            <th>ISO CLASS</th>
                            <th colspan="3">AT REST</th>
                            <th colspan="3">IN OPERATION</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td>0.3</td>
                            <td>0.5</td>
                            <td>5.0</td>
                            <td>0.3</td>
                            <td>0.5</td>
                            <td>5.0</td>
                        </tr>
                        <?php
                        switch ($iso_class) {

                            case $iso_class == 'ISO-05': ?>
                                <tr id="5">
                                    <td>CLASS - 05</td>
                                    <td>10200</td>
                                    <td class="point5">3520</td>
                                    <td>NA</td>
                                    <td>10200</td>
                                    <td>3520</td>
                                    <td>NA</td>
                                </tr>
                                <?php break;
                            case $iso_class == 'ISO-5': ?>
                                <tr id="5">
                                    <td>CLASS - 5</td>
                                    <td>10200</td>
                                    <td class="point5">3520</td>
                                    <td>29</td>
                                    <td>10200</td>
                                    <td>3520</td>
                                    <td>29</td>
                                </tr>
                                <?php
                                break;
                            case $iso_class == 'ISO-06': ?>
                                <tr id="6">
                                    <td>CLASS - 6</td>
                                    <td>-</td>
                                    <td class="point5">35200</td>
                                    <td>293</td>
                                    <td>-</td>
                                    <td>352000</td>
                                    <td>2930</td>
                                </tr>
                                <?php
                                break;
                            case $iso_class == 'ISO-07': ?>
                                <tr id="7">
                                    <td>CLASS - 7</td>
                                    <td>-</td>
                                    <td class="point5">352000</td>
                                    <td>2930</td>
                                    <td>-</td>
                                    <td>3520000</td>
                                    <td>29300</td>
                                </tr>
                                <?php
                                break;
                            case $iso_class == 'ISO-08': ?>
                                <tr id="8">
                                    <td>CLASS - 8</td>
                                    <td>-</td>
                                    <td class="point5">3520000</td>
                                    <td>29300</td>
                                    <td>-</td>
                                    <td colspan="2">Not defined</td>
                                </tr>
                                <?php
                                break;
                            case $iso_class == 'ISO-09': ?>
                                <tr id="9">
                                    <td>CLASS - 9</td>
                                    <td>-</td>
                                    <td class="point5">35200000</td>
                                    <td>293000</td>
                                    <td>-</td>
                                    <td colspan="2">Not Applicable</td>
                                </tr>
                        <?php } ?>
                    </table>

                    <!-- ============================== AHU ROOM NOTE POINTS ============================= -->
                    <?php if ($pc_txtarea = true) { ?>
                        <div id="bottomnote">
                            <td colspan="7"> <span style="font-weight: bold; ">INFERENCE :</span>The above test results meets the
                                acceptance criteria. <br><span style="font-weight: bold;">NOTE :</span> 1.Sq.Mt-Square Meter
                                2.LPM-Litre Per Minute 3.Avg-Average <br>
                                <?php echo $row2['pc_txtarea']; ?>
                            </td>
                        </div>
                    <?php } else { ?>
                        <div id="bottomnote">
                            <td colspan="7"> <span style="font-weight: bold; ">INFERENCE :</span>The above test results meets the
                                acceptance criteria. <br><span style="font-weight: bold;">NOTE :</span> 1.Sq.Mt-Square Meter
                                2.LPM-Litre Per Minute 3.Avg-Average
                            </td>
                        </div>
                    <?php } ?>




                    <!-- GRADE system table -->
                <?php } else { ?>
                    <table id="velo">
                        <tr style="text-align:center;">
                            <th></th>
                            <th colspan="2">AT REST</th>
                            <th colspan="2">AT OPERATION</th>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">GRADE</td>
                            <td>0.5</td>
                            <td>5.0</td>
                            <td>0.5</td>
                            <td>5.0</td>
                        </tr>
                        <tr>
                            <td>A</td>
                            <td>3520</td>
                            <td>20</td>
                            <td>3520</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>B</td>
                            <td>3520</td>
                            <td>29</td>
                            <td>352000</td>
                            <td>2900</td>
                        </tr>
                        <tr>
                            <td>C</td>
                            <td>352000</td>
                            <td>2900</td>
                            <td>3520000</td>
                            <td>29000</td>
                        </tr>
                        <tr>
                            <td>D</td>
                            <td>3520000</td>
                            <td>29000</td>
                            <td colspan="2">Not defined</td>
                        </tr>
                    </table>
                    <?php if ($pc_txtarea = true) { ?>
                        <div id="bottomnote">
                            <td colspan="7"> <span style="font-weight: bold;">INFERENCE : </span>The above test results meets the
                                acceptance criteria.
                                <br><span style="font-weight: bold;">NOTE :</span> 1.Sq.Mt-Square Meter
                                2.LPM-Litre Per Minute 3.Avg-Average <br>
                                <?php echo $row2['pc_txtarea']; ?>
                            </td>
                        </div>
                    <?php } else { ?>
                        <div id="bottomnote">
                            <td colspan="7"> <span style="font-weight: bold;">INFERENCE :</span>The above test results meets the
                                acceptance criteria.
                                <br><span style="font-weight: bold;">NOTE :</span> 1.Sq.Mt-Square Meter
                                2.LPM-Litre Per Minute 3.Avg-Average
                            </td>
                        </div>
                    <?php } ?>

                <?php } ?>
                <?php include('footer.php');
            endfor;
        endwhile;
    } else {
        // ============================== EMPTY ROOM ============================= -->
        echo '<div style="page-break-after:always" align="center"><strong>No records found</strong></div>';
    } ?>
</body>

</html>