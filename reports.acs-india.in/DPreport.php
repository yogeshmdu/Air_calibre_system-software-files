<!-- ######## Database connection ######## -->
<?php require 'web_acsdb.php'; ?>

<!--============================== TO GET THE REPORT_ID ==============================-->
<?php
$selectedReportId = "";
if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET["report_id"];
    echo '<script>alert("Viewing for Report Id: ' . $selectedReportId . '")</script>';
}
// echo $selectedReportId;

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
    <title>JC-<?php echo ltrim($selectedReportId, 'REP_'); ?> DP REPORT</title>

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
    $query2 = ("SELECT * FROM reports_ahu_details WHERE report_id='$selectedReportId' AND dp_quty > 0 AND testing_date = '$testing_date'") or die($mysqli);
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
            $instrument = $row2['dp_instrument_used'];
            $ahu_seq = $row2['ahu_seq_no'];
            $room_detai = $row2['room_details'];
            $numof_ahu_rooms = $row2['room_id'];
            $num_of_ahu = 1;


            //Updated Customer name from Service report
            $updated_customer_query = $mysqli->query("SELECT customer_name, cust_address FROM employee_job_list WHERE report_id = '$selectedReportId' AND job_date ='$testing'");
            while ($updated_customer_result_row = $updated_customer_query->fetch_assoc()) {
                $cust_name = $updated_customer_result_row["customer_name"];
                $address = $updated_customer_result_row["cust_address"];
            }

            $dp_quaty = $row2['dp_quty'];
            // echo $category;
    
            $tested_name = $row2['testing_engg'];
            // echo $tested_name;
            $dp_txtarea = $row2['dp_txtarea'];

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

            // Obtained results for Filter integrity
            $query4 = ("SELECT * FROM reports_differ_pressure WHERE ahu_seq_no ='$ahu_seq'") or die($mysqli);
            $result4 = $mysqli->query($query4);
            $rowcount = mysqli_num_rows($result4);
            // echo $rowcount;
    
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

                $dpquery4 = ("SELECT * FROM reports_differ_pressure WHERE ahu_seq_no ='$ahu_seq' limit $data_per,$perpage") or die($mysqli);
                $dpresult4 = $mysqli->query($dpquery4);
                $dprowcount = mysqli_num_rows($dpresult4);
                ?>

                <!-- ============================== TO GET ROOM DETAILS ============================== -->
                <br><br><br><br><br><br><br><br><br>

                <!-- To get the customer details -->
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
                        <th colspan="4">PRESSURE &nbsp; TEST REPORT</th>
                    </tr>
                    
                    <tr>
                        <td class="cust_first"><span style="font-weight: bold;"> Certificate No.</span> </td>
                        <td class="cust_sec">
                            <span style="font-weight: bold;">
                                <?php echo $job_card;  ?> &#47; DP - <?php 
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
                            <?php echo date("d F Y", strtotime($testing)); ?>
                        </td>
                        <td class="cust_first"> Test of AHU No./ID </td>
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
                            ?>
                            </td>
                        <td class="cust_first">Area of Test</td>
                        <td class="cust_sec">
                            <?php echo $test_area; ?>
                        </td>
                    </tr>
                </table>

                <!-- ============================== INSTRUMENT DETAILS ============================== -->
                <table id="Equip">
                    <tr>
                        <th colspan="6">EQUIPMENT &nbsp; DETAILS</th>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;"> Name</span></td>
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

                <table id="velo">
                    <tr>
                        <th colspan="5">OBTAINED &nbsp; TEST RESULTS</th>
                    </tr>
                    <tr>
                        <td rowspan="2"><span style="font-weight: bold;">S.No</span></td>
                        <td rowspan="2"><span style="font-weight: bold;">Description</span></td>
                        <td colspan="2"><span style="font-weight: bold;">Pressure in PASCAL/MMWC</span></td>
                        <td rowspan="2"><span style="font-weight: bold;">status</span></td>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;">Pressure Limit<sapn>
                        </td>
                        <td>
                            <sapn style="font-weight: bold;">Pressure Observed</sapn>
                        </td>
                    </tr>
                    <tr>
                        <?php
                        while ($dpdata = $dpresult4->fetch_assoc()):
                            ?>
                            <td>
                                <?php echo $num_of_ahu++; ?>
                            </td>
                            <td>
                                <?php echo $dpdata['differ_name']; ?>
                            </td>
                            <td>
                                <?php echo $dpdata['pressure_limit']; ?>
                            </td>
                            <td>
                                <?php echo $dpdata['pressure_observed']; ?>
                            </td>
                            <td>
                                <?php echo $dpdata['status']; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                </table>
                <?php
                // ============================== TO ADJUST THE PAGE HEIGHT ==============================
    
                switch ($dprowcount) {
                    case 1:
                        echo '<div id="empty" style="height:260px; "></div>';
                        break;
                    case 2:
                        echo '<div id="empty" style="height:240px; "></div>';
                        break;
                    case 3:
                        echo '<div id="empty" style="height:220px; "></div>';
                        break;
                    case 4:
                        echo '<div id="empty" style="height:200px; "></div>';
                        break;
                    case 5:
                        echo '<div id="empty" style="height:180px; "></div>';
                        break;
                    case 6:
                        echo '<div id="empty" style="height:160px; "></div>';
                        break;
                    case 7:
                        echo '<div id="empty" style="height:140px; "></div>';
                        break;
                    case 8:
                        echo '<div id="empty" style="height:120px; "></div>';
                        break;
                    case 9:
                        echo '<div id="empty" style="height:100px; "></div>';
                        break;
                    case 10:
                        echo '<div id="empty" style="height:80px; "></div>';
                        break;
                    case 11:
                        echo '<div id="empty" style="height:60px; "></div>';
                        break;
                    default:
                        echo '<div id="empty" style="height:40px; "></div>';
                        break;
                }

                ; ?>

                <!-- ============================== AHU ROOM NOTE POINTS ============================= -->
                <?php if ($dp_txtarea = true) { ?>
                    <table style="border:1px dotted; ">
                        <td>
                            <span style="font-weight: bold; ">&nbsp;INFERENCE : The above test results meets the acceptance
                                criteria.</span><br>
                            <?php echo $row2['dp_txtarea']; ?>
                        </td>
                    </table>
                <?php } else { ?>
                    <table style="border:1px dotted; ">
                        <td>
                            <span style="font-weight: bold; ">&nbsp;INFERENCE : The above test results meets the acceptance
                                criteria.</span><br>
                        </td>
                    </table>
                <?php } ?>


                <?php include("footer.php");
            endfor;
        endwhile;
    } else {
        // ============================== EMPTY ROOM ============================= -->
        echo '<div style="page-break-after:always" align="center"> <strong>No records found. <strong></div>';
    } ?>
</body>

</html>