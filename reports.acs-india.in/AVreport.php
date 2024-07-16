<!-- ######## DATABASE CONNECTION ######## -->
<?php require 'web_acsdb.php';

// ============================== TO GET THE REPORT_ID ==============================
$total_count = "";
if(isset($_GET['total_count'])){
    $total_count = $_GET['total_count'];
}

$selectedReportId = "";
if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET["report_id"];
    echo '<script>alert("Viewing for Report Id: ' . $selectedReportId . '")</script>';
}
$testing_date = "";
if(isset($_GET['testing_date'])){
    $testing_date = $_GET['testing_date'];
}
// ============================== TO GET JOB CARD NUM AND LOCATION ==============================
$query = ("SELECT crm.job_card_no, cust.customer_id, cust.customer_name,  cust.area, cust.city, cust.address1, cust.address2, cust.pincode, crm.report_id
                FROM customer_reports_master crm, customer cust
                WHERE crm.report_id='$selectedReportId' AND crm.customer_id = cust.customer_id;") or die($mysqli);
$result = $mysqli->query($query);
$row = $result->fetch_assoc();

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
    <title>JC-<?php echo ltrim($selectedReportId, 'REP_'); ?> AV-REPORT</title>

    <!-- Bootstrap CSS and JS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <!-- Css File link -->
    <link rel="stylesheet" href="reports_file.css">
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
</head>

<body>
    <!-- ============================== AHU ROOM DETAILS ============================= -->
    <?php
   
    // AHU Details 
    $query2 = ("SELECT * FROM reports_ahu_details WHERE report_id='$selectedReportId' AND av_qty > 0 AND testing_date = '$testing_date'") or die($mysqli);
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
            $instrument = $row2['av_instrument_used'];
            $ahu_seq = $row2['ahu_seq_no'];
            $room_detai = $row2['room_details'];
            $av_room_vo = $row2['av_room_volume'];
            $av_room_area = $row2['av_area'];
            $numof_ahu_rooms = $row2['room_id'];
            // $num_of_ahu = substr($numof_ahu_rooms, -2);
            $num_of_ahu = 1;

            //Updated Customer name from Service report
            $updated_customer_query = $mysqli->query("SELECT customer_name, cust_address FROM employee_job_list WHERE report_id = '$selectedReportId' AND job_date ='$testing'");
            while ($updated_customer_result_row = $updated_customer_query->fetch_assoc()) {
                $cust_name = $updated_customer_result_row["customer_name"];
                $address = $updated_customer_result_row["cust_address"];
            }
            $category = $row2['category'];
            $avqu = $row2['av_qty'];
            $tested_name = $row2['testing_engg'];

            $av_txtarea = $row2['av_txtarea'];

            $empArray = explode(",", $tested_name);
            $inclause = "";
            foreach ($empArray as $arrValue) {
                $inclause = $inclause . "'" . $arrValue . "',";
            }
            $instr = "(" . substr($inclause, 0, -1) . ")";

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

            // Obtained results details
            $query4 = ("SELECT * FROM air_velocity WHERE ahu_seq_no ='$ahu_seq' ") or die($mysqli);
            $result4 = $mysqli->query($query4);
            $rowcount = mysqli_num_rows($result4);

            //============================== DIVIDE THE NUMBER OF PAGES AND PRINT IT ==============================
            $pages = $rowcount / 12;
            $pr = ceil($pages);
            // echo $pr;
            $perpage = 12;

            for ($i = 1; $i <= $pr; $i++):

                if ($pr > 0) {
                    $print_data = $i - 1;
                } else {
                    $print_data = 0;
                }
                // echo $print_data;
                $data_per = $print_data * 12;
                // echo $data_per;

                $avquery4 = ("SELECT * FROM air_velocity WHERE ahu_seq_no ='$ahu_seq' limit $data_per,$perpage") or die($mysqli);
                $avresult4 = $mysqli->query($avquery4);
                $avrow_count = mysqli_num_rows($avresult4);

                
                // ============================== TO GET THE AIR VELOCITY ( Equipment ) ==============================
                if ($category == 1) { ?>
                                    <br><br><br><br><br><br><br><br><br>
                                    
                                    <table id="Equip" style="margin-bottom: 10px;">
                                        <tr>
                                            <th>CUSTOMER </th>
                                        </tr>
                                        <tr id="cust_details">
                                            <td> <span style="font-weight: bold;"><?php echo $cust_name; ?></span><?php echo '<br>'; echo $address;?></td>
                                        </tr>
                                    </table>
                                    <table id="customer">
                                        <tr>
                                            <th colspan="4">AIR VELOCITY &nbsp; TEST REPORT</th>
                                        </tr>
                                       
                                        <tr>
                                            <td class="cust_first"><span style="font-weight: bold;"> Certificate No.</span></td>
                                            <td class="cust_sec">
                                                <span style="font-weight: bold;">
                                                    <?php echo $job_card; ?> &#47; AV - 
                                                    <?php 
                                                    if($print_data != 0) {
                                                        echo str_pad($total_count, 4, "0", STR_PAD_LEFT); echo '&#47;'; echo $num_page++; echo '&nbsp;'; echo 'of'; echo '&nbsp;'; echo $pr;
                                                    } else {
                                                        echo str_pad($total_count++ + 1, 4, "0", STR_PAD_LEFT); echo '&#47;'; echo $num_page++; echo '&nbsp;'; echo 'of'; echo '&nbsp;'; echo $pr;
                                                    }
                                                    ?>
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
                                                <?php echo date("d F Y", strtotime($testing)); ?>
                                            </td>
                                            <td class="cust_first"> Test of AHU No / Eqpt ID </td>
                                            <td class="cust_sec">
                                                <?php echo $ahu_name; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="cust_first"><span>Next Due Date</span> </td>
                                            <td class="cust_sec"><span>
                                            <?php 
                                              if ($testing_due != "0000-00-00") {
                                                echo date("d F Y", strtotime($testing_due));
                                            } else {
                                                echo 'Not Applicable';
                                            }
                                             ?>
                                                </span></td>
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
                                            <td><span style="font-weight: bold;">Name </span></td>
                                            <td><span style="font-weight: bold;">Make</span></td>
                                            <td><span style="font-weight: bold;">Model</span></td>
                                            <td><span style="font-weight: bold;">Serial / ID</span></td>
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
                                    <!-- ============================== TO GET AHU EQUIPMENT ROOM DETAILS ============================== -->
                                    <table id="velo">
                                        <tr>
                                            <th colspan="18">OBTAINED &nbsp; TEST RESULTS</th>
                                        </tr>
                                        <tr>
                                            <td rowspan="2"><span style="font-weight: bold;">Sr.No</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;">Description</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;">Filter Code</span></td>
                                            <td colspan="8"><span style="font-weight: bold;">Measured Air Velocity in FPM</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;">Average <br>Velocity(FPM)</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;">Limit In FPM</span></td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-weight: bold;">V1</span></td>
                                            <td><span style="font-weight: bold;">V2</span></td>
                                            <td><span style="font-weight: bold;">V3</span></td>
                                            <td colspan="1"><span style="font-weight: bold;">V4</span></td>
                                            <td colspan="1"><span style="font-weight: bold;">V5</span></td>
                                        </tr>

                                        <tr>
                                            <td rowspan="<?php echo $avqu; ?>" ><?php echo $num_of_ahu++; ?></td>
                                            <td rowspan="<?php echo $avqu; ?>"><?php echo $room_detai; ?></td>
                                            <?php while ($avrow4 = $avresult4->fetch_assoc()): ?>

                                                    <td>
                                                        <?php echo $filter_code = $avrow4['filter_code']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vel1 = $avrow4['v1']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vel2 = $avrow4['v2']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vel3 = $avrow4['v3']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vel4 = $avrow4['v4']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vel5 = $avrow4['v5']; ?>
                                                    </td>
                                                    <?php
                                                    $sum = $vel1 + $vel2 + $vel3 + $vel4 + $vel5;
                                                    $total = 5;
                                                    $average = $sum / $total;
                                                    ?>
                                                    <td colspan="4">
                                                        <?php echo $average; ?>
                                                    </td>
                                                    <td colspan="2">
                                                        <?php echo $avrow4['av_limit']; ?>
                                                    </td>
                                                </tr>
                                        <?php endwhile; ?>
                                    </table>
                                    <?php
                                    // ============================== TO ADJUST THE PAGE HEIGHT ==============================
                                    switch ($avrow_count) {
                                        case 1:
                                            echo '<div id="empty" style="height:280px; "></div>';
                                            break;
                                        case 2:
                                            echo '<div id="empty" style="height:260px; "></div>';
                                            break;
                                        case 3:
                                            echo '<div id="empty" style="height:240px; "></div>';
                                            break;
                                        case 4:
                                            echo '<div id="empty" style="height:220px; "></div>';
                                            break;
                                        case 5:
                                            echo '<div id="empty" style="height:200px; "></div>';
                                            break;
                                        case 6:
                                            echo '<div id="empty" style="height:180px; "></div>';
                                            break;
                                        case 7:
                                            echo '<div id="empty" style="height:160px; "></div>';
                                            break;
                                        case 8:
                                            echo '<div id="empty" style="height:140px; "></div>';
                                            break;
                                        case 9:
                                            echo '<div id="empty" style="height:120px; "></div>';
                                            break;
                                        case 10:
                                            echo '<div id="empty" style="height:100px; "></div>';
                                            break;
                                        case 11:
                                            echo '<div id="empty" style="height:80px; "></div>';
                                            break;
                                        default:
                                            echo '<div id="empty" style="height:60px; "></div>';
                                            break;
                                    }
                                    ;
                                    ?>

                                    <!-- ============================== AHU ROOM NOTE POINTS ============================= -->
                    
                                    <table id="note">
                                        <td style="font-weight: bold;">
                                            &nbsp;Inference : The above number of Air Velocity meets the specified requirement.
                                        </td>
                                    </table>
                                    <?php if ($av_txtarea = true) { ?>
                                            <table id="note">
                                                <td><span style="font-weight: bold;">&nbsp;Note :</span> The readings have to be taken at "150mm to 300mm".<br>
                                                    ABB : 1. FPM-Feet Per Minute &nbsp; <br>
                                                    <?php echo $row2['av_txtarea']; ?>
                                                </td>
                                            </table>
                                    <?php } else { ?>
                                            <table id="note">
                                                <td><span style="font-weight: bold;">&nbsp;Note :</span> The readings have to be taken at "150mm to 300mm".<br>
                                                    ABB : 1. FPM-Feet Per Minute &nbsp; 
                                                </td>
                                            </table>
                                    <?php } ?>
                                    <?php include("footer.php"); ?>
                            <?php } elseif ($category == 2) { ?>
                                    <!-- ============================== TO GET THE AIR QUANTITY (Area) ============================== -->
                                    <br><br><br><br><br><br><br><br><br>
                                    <table id="Equip" style="margin-bottom: 10px;">
                                        <tr>
                                            <th>CUSTOMER </th>
                                        </tr>
                                        <tr id="cust_details">
                                            <td> <span style="font-weight: bold;"><?php echo $cust_name; ?></span><?php echo '<br>'; echo $address;?></td>
                                        </tr>
                                        
                                    </table>
                                    <table id="customer">
                                        <tr>
                                            <th colspan="4">AIR QUANTITY &nbsp; TEST REPORT</th>
                                        </tr>
                                        <tr>
                                            <td class="cust_first"><span style="font-weight: bold;"> Certificate No.</span></td>
                                            <td class="cust_sec">
                                                <span style="font-weight: bold;">
                                                    <?php echo $job_card; ?> &#47; AV - 
                                                    <?php 
                                                    if($print_data != 0) {
                                                        echo str_pad($total_count, 4, "0", STR_PAD_LEFT); echo '&#47;'; echo $num_page++; echo '&nbsp;'; echo 'of'; echo '&nbsp;'; echo $pr;
                                                    } else {
                                                        echo str_pad($total_count++ + 1, 4, "0", STR_PAD_LEFT); echo '&#47;'; echo $num_page++; echo '&nbsp;'; echo 'of'; echo '&nbsp;'; echo $pr;
                                                    }
                                                    ?>
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
                                                <?php echo date("d F Y", strtotime($testing)); ?>
                                            </td>
                                            <td class="cust_first"> Test of AHU No / Eqpt ID </td>
                                            <td class="cust_sec">
                                                <?php echo $ahu_name; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="cust_first"><span>Next Due Date</span> </td>
                                            <td class="cust_sec"><span>
                                                    <?php 
                                                      if ($testing_due != "0000-00-00") {
                                                        echo date("d F Y", strtotime($testing_due));
                                                    } else {
                                                        echo 'Not Applicable';
                                                    }
                                                    ?>
                                                </span></td>
                                            <td class="cust_first">Area of Test</td>
                                            <td class="cust_sec">
                                                <?php echo $test_area; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- ============================== To get the Instrument Details ============================== -->
                                    <table id="Equip">
                                        <tr>
                                            <th colspan="6">EQUIPMENT &nbsp; DETAILS</th>
                                        </tr>
                                        <tr>
                                            <td><span style="font-weight: bold;">Name </span></td>
                                            <td><span style="font-weight: bold;">Make</span></td>
                                            <td><span style="font-weight: bold;">Model</span></td>
                                            <td><span style="font-weight: bold;">Serial / ID</span></td>
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
                                    <table id="velo">
                                        <tr>
                                            <th colspan="8">OBTAINED &nbsp; TEST RESULTS</th>
                                        </tr>
                                        <tr>
                                            <td rowspan="2"><span style="font-weight: bold;">S.No</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;"> Description </span></td>
                                            <td rowspan="2"><span style="font-weight: bold;"> Grill Code </span></td>
                                            <td rowspan="2"><span style="font-weight: bold;"> Grill<br>CFM</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;">TAQ<br>(CFM)</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;">Room Volume<br>(ft&sup3;)</span></td>
                                            <td colspan="2"><span style="font-weight: bold;">ACPH</span></td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-weight: bold;">Achieved</span></td>
                                            <td><span style="font-weight: bold;">Designed</span></td>
                                        </tr>

                                        <!-- ============================== AHU AQ ROOM DATA ============================== -->
                                        <tr>
                                            <td>
                                                <?php echo $num_of_ahu++; ?>
                                            </td>
                                            <td>
                                                <?php echo $room_detai; ?>
                                            </td>

                                            <!-- To get the filter codes -->
                                            <td <?php $sum = 0;
                                            while ($area_av = $avresult4->fetch_assoc()): ?>>
                                                        <?php
                                                        echo $area_av['filter_code'];
                                                      
                                                        ?>
                                                        <br <?php endwhile; ?>>
                                            </td>
                                            <!-- ============================== TO GET THE CFM VALUES ============================== -->
                                            <td <?php foreach ($avresult4 as $avarea): ?>>
                                                        <?php
                                                        echo $avarea['fcfm'];
                                                        ?>
                                                        <br <?php endforeach; ?>>
                                            </td>
                                            <?php while ($av_value_area = $result4->fetch_assoc()){
                                                $add = $av_value_area['fcfm'];
                                                $sum += $add;
                                                $total = $sum;
                                                $nlt_value = $av_value_area['Designed'];
                                                $arch = $sum * 60 / $av_room_vo;
                                            } ?>
                                            <td>
                                                <?php echo $total; ?>
                                            </td>
                                            <td>
                                                <?php echo $av_room_vo; ?>
                                            </td>
                                            <td>
                                                <?php echo round($arch); ?>
                                            </td>
                                            <td style="font-weight: bolder;"> NLT
                                                <?php echo $nlt_value; ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <?php
                                    // ============================== TO ADJUST THE PAGE HEIGHT ==============================
                                    switch ($avrow_count) {
                                        case 1:
                                            echo '<div id="empty" style="height:280px; "></div>';
                                            break;
                                        case 2:
                                            echo '<div id="empty" style="height:260px; "></div>';
                                            break;
                                        case 3:
                                            echo '<div id="empty" style="height:240px; "></div>';
                                            break;
                                        case 4:
                                            echo '<div id="empty" style="height:220px; "></div>';
                                            break;
                                        case 5:
                                            echo '<div id="empty" style="height:200px; "></div>';
                                            break;
                                        case 6:
                                            echo '<div id="empty" style="height:180px; "></div>';
                                            break;
                                        case 7:
                                            echo '<div id="empty" style="height:160px; "></div>';
                                            break;
                                        case 8:
                                            echo '<div id="empty" style="height:140px; "></div>';
                                            break;
                                        case 9:
                                            echo '<div id="empty" style="height:120px; "></div>';
                                            break;
                                        case 10:
                                            echo '<div id="empty" style="height:100px; "></div>';
                                            break;
                                        case 11:
                                            echo '<div id="empty" style="height:80px; "></div>';
                                            break;
                                        default:
                                            echo '<div id="empty" style="height:60px; "></div>';
                                            break;
                                    }
                                    ;
                                    ?>

                                    <!-- ============================== AHU ROOM NOTE POINTS ============================= -->

                                    <table id="note">
                                        <td style="font-weight: bold;">
                                            &nbsp;Inference : The above number of Air Changes Per Hour meets the specified requirement.
                                        </td>
                                    </table>
                                    <?php if ($av_txtarea = true) { ?>
                                            <table id="note">
                                                <td><span style="font-weight: bold;">&nbsp; Note :</span> ACPH = Total Air Quantity (CFM) x 60 / Room Volume
                                                    (ft&sup3;) <br>
                                                    ABB : &nbsp;1.TAQ-Total Air Quantity &nbsp; 2.ACPH-Air Changes Per Hour &nbsp; 3.CFM-Cubic Feet Per Minute <br>
                                                    <?php echo $row2['av_txtarea']; ?>
                                                </td>
                                            </table>
                                    <?php } else { ?>
                                            <table id="note">
                                                <td><span style="font-weight: bold;">&nbsp; Note :</span> ACPH = Total Air Quantity (CFM) x 60 / Room Volume
                                                    (ft&sup3;) <br>
                                                    ABB : &nbsp;1.TAQ-Total Air Quantity &nbsp; 2.ACPH-Air Changes Per Hour &nbsp; 3.CFM-Cubic Feet Per Minute
                                                </td>
                                            </table>
                                    <?php } ?>
                                    <?php include("footer.php");
                } else { ?>
                                    <!-- ============================== TO GET THE AIR Velocity (Master) ============================== -->
                                    <br><br><br><br><br><br><br><br><br>
                                    <table id="Equip" style="margin-bottom: 10px;">
                                        <tr>
                                            <th>CUSTOMER </th>
                                        </tr>
                                        <tr id="cust_details">
                                            <td> <span style="font-weight: bold;"><?php echo $cust_name; ?></span><?php echo '<br>'; echo $address;?></td>
                                        </tr>
                                        
                                    </table>
                                    <table id="customer">
                                        <tr>
                                            <th colspan="4">AIR VELOCITY &nbsp; TEST REPORT</th>
                                        </tr>
                                        <tr>
                                            <td class="cust_first"><span style="font-weight: bold;"> Certificate No.</span> </td>
                                            <td class="cust_sec">
                                                <span style="font-weight: bold;">
                                                    <?php echo $job_card; ?> &#47; AV - <?php 
                                                        if($print_data != 0) {
                                                            echo str_pad($total_count, 4, "0", STR_PAD_LEFT); echo '&#47;'; echo $num_page++; echo '&nbsp;'; echo 'of'; echo '&nbsp;'; echo $pr;
                                                        } else {
                                                            echo str_pad($total_count++ + 1, 4, "0", STR_PAD_LEFT); echo '&#47;'; echo $num_page++; echo '&nbsp;'; echo 'of'; echo '&nbsp;'; echo $pr;
                                                        }
                                                    ?>
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
                                                <?php echo date("d F Y", strtotime($testing)); ?>
                                            </td>
                                            <td class="cust_first"> Test of AHU No / Eqpt ID </td>
                                            <td class="cust_sec">
                                                <?php echo $ahu_name; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="cust_first"><span>Next Due Date</span> </td>
                                            <td class="cust_sec"><span>
                                                    <?php 
                                                      if ($testing_due != "0000-00-00") {
                                                        echo date("d F Y", strtotime($testing_due));
                                                    } else {
                                                        echo 'Not Applicable';
                                                    }
                                                    ?>
                                                </span></td>
                                            <td class="cust_first">Area of Test</td>
                                            <td class="cust_sec">
                                                <?php echo $test_area; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- ============================== To get the Instrument Details ============================== -->
                                    <table id="Equip">
                                        <tr>
                                            <th colspan="6">EQUIPMENT &nbsp; DETAILS</th>
                                        </tr>
                                        <tr>
                                            <td><span style="font-weight: bold;">Name </span></td>
                                            <td><span style="font-weight: bold;">Make</span></td>
                                            <td><span style="font-weight: bold;">Model</span></td>
                                            <td><span style="font-weight: bold;">Serial / ID</span></td>
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
                                    <table id="velo">
                                        <tr>
                                            <th colspan="50">OBTAINED &nbsp; TEST RESULTS</th>
                                        </tr>
                                        <tr>
                                            <td rowspan="2"><span style="font-weight: bold;">S.No</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;"> Description </span></td>
                                            <td rowspan="2"><span style="font-weight: bold;"> Grill / filter Code</span></td>
                                            <td colspan="5"><span style="font-weight: bold;">Measured Air Velocity in FPM</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;">Avg <br>Velocity <br>(FPM)</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;"> Area (ft&sup2;)</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;"> Individual <br> CFM</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;">TAQ<br>(CFM)</span></td>
                                            <td rowspan="2"><span style="font-weight: bold;">Room Volume<br>(ft&sup3;)</span></td>
                                            <td colspan="2"><span style="font-weight: bold;">ACPH</span></td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-weight: bold;">V1</span></td>
                                            <td><span style="font-weight: bold;">V2</span></td>
                                            <td><span style="font-weight: bold;">V3</span></td>
                                            <td colspan="1"><span style="font-weight: bold;">V4</span></td>
                                            <td colspan="1"><span style="font-weight: bold;">V5</span></td>
                                            <td><span style="font-weight: bold;">Achieved</span></td>
                                            <td><span style="font-weight: bold;">Designed</span></td>
                                        </tr>

                                     
                                <!-- ============================== AHU AQ ROOM DATA ============================== -->
                                <?php
                                $master_cfm_in_value = 0;
                                $sum_master_cfm = 0;
                                $achieved = 0;
                                foreach ($result4 as $av_master_data):
                                    $av_designed = $av_master_data['Designed'];

                                    $master_v_1 = $av_master_data['v1'];
                                    $master_v_2 = $av_master_data['v2'];
                                    $master_v_3 = $av_master_data['v3'];
                                    $master_v_4 = $av_master_data['v4'];
                                    $master_v_5 = $av_master_data['v5'];
                                    $master_sum_value = $master_v_1 + $master_v_2 + $master_v_3 + $master_v_4 + $master_v_5;
                                    $divided_val = 5;
                                    $master_total_value =  $master_sum_value / $divided_val ;
                                    $av_room_area;

                                    $master_cfm_in_value = $master_total_value * $av_room_area;

                                    $sum_master_cfm += $master_cfm_in_value;
                                    

                                    $achieved = $sum_master_cfm * 60 / $av_room_vo;
                                endforeach;
                                
                                $count = 1;
                                foreach ($avresult4 as $av_master):
                                    $master_cfm = $av_master['fcfm'];
                                    // $sum_master_cfm += $master_cfm;
                                    ?>
                                            <tr>
                                                <?php if ($count == 1) { ?>
                                                                <td rowspan="<?php echo $avqu; ?>">
                                                                    <?php echo $num_of_ahu++; ?>
                                                                </td>
                                                                <td rowspan="<?php echo $avqu; ?>">
                                                                    <?php echo $room_detai; ?>
                                                                </td>
                                                <?php } ?>
                                                <!-- To get the filter codes -->
                                                <td>
                                                    <?php echo $filter_code = $av_master['filter_code']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $vel1_master = $av_master['v1']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $vel2_master = $av_master['v2']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $vel3_master = $av_master['v3']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $vel4_master = $av_master['v4']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $vel5_master = $av_master['v5']; ?>
                                                </td>
                                                <?php
                                                $sum_master = $vel1_master + $vel2_master + $vel3_master + $vel4_master + $vel5_master;
                                                $total_master = 5;
                                                $average_master = $sum_master / $total_master;
                                                ?>
                                                <td>
                                                    <?php echo $avg_vel_value = number_format((float) $average_master, 1, '.', ''); ?>
                                                </td>

                                                <td>
                                                    <?php echo $av_room_area; ?>
                                                </td>
                                                <td>
                                                    <?php echo $master_cfm_val = number_format((float) ($avg_vel_value * $av_room_area), 1, '.', ''); ?>
                                                </td>
                                                <?php if ($count == 1) { ?>

                                                            <td rowspan="<?php echo $avqu; ?>"><?php echo number_format((float) $sum_master_cfm, 1, '.', ''); ?></td>
                                                            <td rowspan="<?php echo $avqu; ?>"><?php echo $av_room_vo; ?></td>
                                                            <td rowspan="<?php echo $avqu; ?>"><?php echo round($achieved); ?> </td>
                                                            <td rowspan="<?php echo $avqu; ?>">NLT <?php echo $av_designed; ?></td>

                                                <?php }
                                                $count++; ?>
                                                <!-- ============================== TO GET THE CFM VALUES ============================== -->

                                                </tr>
                                    <?php endforeach; ?>
                                       </table>

                                    <?php
                                    // ============================== TO ADJUST THE PAGE HEIGHT ==============================
                                    switch ($avrow_count) {
                                        case 1:
                                            echo '<div id="empty" style="height:280px; "></div>';
                                            break;
                                        case 2:
                                            echo '<div id="empty" style="height:260px; "></div>';
                                            break;
                                        case 3:
                                            echo '<div id="empty" style="height:240px; "></div>';
                                            break;
                                        case 4:
                                            echo '<div id="empty" style="height:220px; "></div>';
                                            break;
                                        case 5:
                                            echo '<div id="empty" style="height:200px; "></div>';
                                            break;
                                        case 6:
                                            echo '<div id="empty" style="height:180px; "></div>';
                                            break;
                                        case 7:
                                            echo '<div id="empty" style="height:160px; "></div>';
                                            break;
                                        case 8:
                                            echo '<div id="empty" style="height:140px; "></div>';
                                            break;
                                        case 9:
                                            echo '<div id="empty" style="height:120px; "></div>';
                                            break;
                                        case 10:
                                            echo '<div id="empty" style="height:100px; "></div>';
                                            break;
                                        case 11:
                                            echo '<div id="empty" style="height:80px; "></div>';
                                            break;
                                        default:
                                            echo '<div id="empty" style="height:60px; "></div>';
                                            break;
                                    }
                                    ;
                                    ?>

                                    <!-- ============================== AHU ROOM NOTE POINTS ============================= -->

                                    <table id="note">
                                        <td style="font-weight: bold;">
                                            &nbsp;Inference : The above number of Air Changes Per Hour meets the specified requirement.
                                        </td>
                                    </table>
                                    <?php if ($av_txtarea = True) { ?>
                                            <table id="note"> 
                                                <td><span style="font-weight: bold;">&nbsp; Note :</span> ACPH = Total Air Quantity (CFM) x 60 / Room Volume
                                                    (ft&sup3;) <br>
                                                    ABB : &nbsp;1.TAQ-Total Air Quantity &nbsp; 2.ACPH-Air Changes Per Hour &nbsp; 3.CFM-Cubic Feet Per Minute <br>
                                                    <?php echo $row2['av_txtarea']; ?>
                                                </td>
                                            </table>
                                    <?php } else { ?>
                                            <table id="note"> 
                                                <td><span style="font-weight: bold;">&nbsp; Note :</span> ACPH = Total Air Quantity (CFM) x 60 / Room Volume
                                                    (ft&sup3;) <br>
                                                    ABB : &nbsp;1.TAQ-Total Air Quantity &nbsp; 2.ACPH-Air Changes Per Hour &nbsp; 3.CFM-Cubic Feet Per Minute
                                                </td>
                                            </table>
                                    <?php } ?>

                                    <?php include("footer.php");
                }
            endfor;
        endwhile;
    } else {
        // ============================== EMPTY ROOM ============================= -->
        echo '<div style="page-break-after:always" align="center"><strong> No records Found </strong></div>';
    } ?>
</body>

</html>