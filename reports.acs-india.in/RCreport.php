<!-- ######## Database connection ######## -->
<?php require 'web_acsdb.php';

//============================== TO GET THE REPORT_ID ==============================-->

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


// ============================== AHU ROOM DETAILS ============================= -->

$query2 = ("SELECT * FROM reports_ahu_details WHERE report_id='$selectedReportId' AND rc_qty > 0 AND testing_date = '$testing_date'") or die($mysqli);
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

        $testing_due = $row2['testing_due_date'];
        $testing = $row2['testing_date'];
        $rc_instrument = $row2['rc_instrument_used'];
        $ahu_seq = $row2['ahu_seq_no'];
        $room_detai = $row2['room_details'];
        $numof_ahu_rooms = $row2['room_id'];

        //Updated Customer name from Service report
        $updated_customer_query = $mysqli->query("SELECT customer_name, cust_address FROM employee_job_list WHERE report_id = '$selectedReportId' AND job_date ='$testing'");
        while ($updated_customer_result_row = $updated_customer_query->fetch_assoc()) {
            $cust_name = $updated_customer_result_row["customer_name"];
            $address = $updated_customer_result_row["cust_address"];
        }



        // $num_of_ahu = substr($numof_ahu_rooms, -2);
        $num_of_ahu = 1;
        // echo $numof_ahu_rooms;
        // $rc_cycle = $row2['pc_cycle'];
        $rc_qty = $row2['rc_qty'];
        // echo $rc_qty;

        $tested_name = $row2['testing_engg'];
        // echo $tested_name;
        $rc_txtarea = $row2['rc_txtarea'];

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

        $query3 = ("SELECT * FROM instrument_details WHERE serial_no ='$rc_instrument'") or die($mysqli);

        $result3 = $mysqli->query($query3);
        $row3 = $result3->fetch_assoc();

        $inst_name = $row3['instrument_used'];
        $inst_make = $row3['instrument_make'];
        $inst_model = $row3['instrument_model'];
        $inst_id = $row3['instrument_sno'];
        $cal_date = $row3['calibration_date'];
        $cal_due_date = $row3['calibration_due_date'];

        // Obtained results for Recovery 
        $query4 = ("SELECT * FROM reports_rc_data WHERE ahu_seq_no ='$ahu_seq' and print_data = '2'") or die($mysqli);
        $result4 = $mysqli->query($query4);
        $rowcount = mysqli_num_rows($result4);



        $recoveryquery = ("SELECT * FROM reports_rc_data WHERE ahu_seq_no ='$ahu_seq'") or die($mysqli);
        $recoveryresult = $mysqli->query($recoveryquery);

        // To get the second values (worst values) of RC report process Query
        $sqlrcquery = ("SELECT * FROM reports_rc_data WHERE ahu_seq_no ='$ahu_seq' LIMIT 1 OFFSET 1");
        $resultrctime = $mysqli->query($sqlrcquery);
        $secrctime = $resultrctime->fetch_assoc();

        $rcsectimedata = $secrctime['time_duration'];
        // $subtracttime = round("H:i:s". abs($rctimeda - $rcsectimedata) / 60, 4)." minutes";
        // echo $rcsectimedata;

        // To print the Rc graph section
        $queryrc = ("SELECT * FROM reports_rc_data where ahu_seq_no ='$ahu_seq'");
        $resultpc_data = $mysqli->query($queryrc);

        if ($iso_class == 'ISO-05') {
            $numberofdata = [];
            $valupoint3 = [];
            $valupoint5 = [];
            $rctimeda = "";
            foreach ($resultpc_data as $graphdata) {
                $valupoint3[] = $graphdata['point3'];
                $valupoint5[] = $graphdata['point5'];
                $numberofdata[] = $graphdata['status'];
                $rctimeda = $graphdata['time_duration'];
                // echo '<br>';
                // $subtracttime = round(abs($rctimeda - $rcsectimedata) / 60, 2)." minutes";
            }
        } else {
            $numberofdata = [];
            $valupoint5 = [];
            $valu5point = [];
            $rctimeda = "";

            foreach ($resultpc_data as $graphdata) {
                $valupoint5[] = $graphdata['point5'];
                $valu5point[] = $graphdata['5point'];
                $numberofdata[] = $graphdata['status'];
                $rctimeda = $graphdata['time_duration'];
                // echo '<br>';
                // $subtracttime = round(abs($rctimeda - $rcsectimedata) / 60, 2)." minutes";
            }
        }
        // echo json_encode($numberofdata);
        // echo json_encode($valupoint3);
        // echo json_encode($valupoint5);
        // echo json_encode($valu5point);
        // echo $rctimeda;

        // Define the two dates
        $worstrctime = new DateTime($rcsectimedata);
        $finalrctime = new DateTime($rctimeda);

        // Calculate the time difference
        $timeDiffInSeconds = abs($finalrctime->getTimestamp() - $worstrctime->getTimestamp());

        // Calculate the hours, minutes, and seconds
        $hours = floor($timeDiffInSeconds / 3600);
        $minutes = floor(($timeDiffInSeconds % 3600) / 60);
        $seconds = $timeDiffInSeconds % 60;

        // Format the time difference as H:M:S
        $timeDiffFormatted = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>JC-<?php echo ltrim($selectedReportId, 'REP_'); ?> RC REPORT</title>

            <!-- Bootstrap CSS and JS File -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
                crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <!-- Css File link -->
            <link rel="stylesheet" href="reports_file.css">
            <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
        </head>

        <body>
            <?php if ($rowcount > 2) { ?>
                <!-- ============================== RECOVERY COUNT ============================== -->
                <br>
                <br><br><br>
                <br><br><br>
                <br><br>
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
                        <th colspan="4">RECOVERY &nbsp; TEST REPORT</th>
                    </tr>
                    <tr>
                        <td class="cust_first"><span style="font-weight: bold;"> Certificate No.</span></td>
                        <td class="cust_sec">
                            <span style="font-weight: bold;">
                                <?php echo $job_card; ?> &#47; RC - <?php 
                                    if($print_data != 0) {
                                        echo str_pad($total_count, 4, "0", STR_PAD_LEFT); echo '&#47;'; echo $num_page++; echo '&nbsp;'; echo 'of'; echo '&nbsp;'; echo "1";
                                    } else {
                                        echo str_pad($total_count++ + 1, 4, "0", STR_PAD_LEFT); echo '&#47;'; echo $num_page++; echo '&nbsp;'; echo 'of'; echo '&nbsp;'; echo "1";
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

                <table id="velo">
                    <tr>
                        <th colspan="7">OBTAINED &nbsp; TEST RESULTS</th>
                    </tr>
                    <tr>
                        <td rowspan="2"><span style="font-weight: bold;">S.No</span></td>
                        <td rowspan="2"><span style="font-weight: bold;">Description</span></td>
                        <td rowspan="2"><span style="font-weight: bold;">Recovery Time <br> Hr-Min-Sec</span></td>
                        <td rowspan="2"><span style="font-weight: bold;">Status</span></td>
                        <td rowspan="2"><span style="font-weight: bold;">Hr-Min-Sec</span></td>
                        <td colspan="2"><span style="font-weight: bold;">Concentration of Particle sizes / m&sup3; </span></td>
                    </tr>

                    <tr>
                        <?php if ($iso_class == 'ISO-05') { ?>
                            <td><span style="font-weight: bold;">&ge; 0.3 &micro;m/m&sup3;<sapn>
                            </td>
                            <td>
                                <sapn style="font-weight: bold;">&ge; 0.5 &micro;m/m&sup3;</sapn>
                            </td>
                        <?php } else { ?>
                            <td><span style="font-weight: bold;">&ge; 0.5 &micro;m/m&sup3;<sapn>
                            </td>
                            <td>
                                <sapn style="font-weight: bold;">&ge; 5.0 &micro;m/m&sup3;</sapn>
                            </td>
                        <?php } ?>
                    </tr>

                    <tr>
                        <td rowspan="3">
                            <?php echo $num_of_ahu++; ?>
                        </td>
                        <td rowspan="3">
                            <?php echo $room_detai; ?>
                        </td>


                        <td rowspan="3">
                            <?php echo $timeDiffFormatted; ?>
                        </td>
                        <td rowspan="3">Initial case <br>
                            Worst case <br>
                            Final </td>

                        <?php
                        while ($rcdata = $result4->fetch_assoc()):
                            if ($iso_class == 'ISO-05') { ?>
                                <td>
                                    <?php echo $rctimedata = $rcdata['time_duration']; ?>
                                </td>
                                <?php if($inst_model == "APEX Z50") { ?>
                                    <td>
                                        <?php echo number_format($rcdata['point3'], 1, '.', ''); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($rcdata['point5'], 1, '.', ''); ?>
                                    </td>
                                    <?php } else { ?>
                                    <td>
                                        <?php echo $rcdata['point3']; ?>
                                    </td>
                                    <td>
                                        <?php echo $rcdata['point5']; ?>
                                    </td>
                                <?php } ?>

                            <?php } else { ?>
                                <td>
                                    <?php echo $rctimedata = $rcdata['time_duration']; ?>
                                </td>
                                <?php if($inst_model == "APEX Z50") { ?>
                                    <td>
                                        <?php echo number_format($rcdata['point5'], 1, '.', ''); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($rcdata['5point'], 1, '.', ''); ?>
                                    </td>
                                    <?php } else { ?>
                                    <td>
                                        <?php echo $rcdata['point5']; ?>
                                    </td>
                                    <td>
                                        <?php echo $rcdata['5point']; ?>
                                    </td> 
                                <?php } ?>
                            <?php } ?>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <th colspan="7">Recovery Reading Chart</th>
                    </tr>

                </table>
                <!--============================== RECOVERY GRAPH GENERATION ==============================-->

                <div id="graphborder">
                    <div id="graphcontent">
                        <canvas id="chartdata<?php echo $ahu_seq; ?>"></canvas>
                    </div>
                </div>


                <!-- Rest of the code within the while loop -->
                <?php if ($iso_class == 'ISO-05') { ?>

                    <script>
                        const xValues<?php echo $ahu_seq; ?> = <?php echo json_encode($numberofdata); ?>;


                        new Chart("chartdata<?php echo $ahu_seq; ?>", {
                            type: "bar",
                            data: {
                                labels: xValues<?php echo $ahu_seq; ?>,
                                datasets: [{
                                    label: '0.3 values',
                                    data: <?php echo json_encode($valupoint3); ?>,
                                    borderColor: "SlateBlue",
                                    backgroundColor: "SlateBlue", // Set bar color to SlateBlue
                                    fill: false
                                }, {
                                    label: '0.5 values',
                                    data: <?php echo json_encode($valupoint5); ?>,
                                    borderColor: "orange", // Set border color to orange
                                    backgroundColor: "orange", // Set bar color to orange
                                    fill: false
                                }]
                            },
                            options: {
                                legend: { display: false },
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        }
                        );
                    </script>
                <?php } else { ?>
                    <script>
                        const xValues<?php echo $ahu_seq; ?> = <?php echo json_encode($numberofdata); ?>;


                        new Chart("chartdata<?php echo $ahu_seq; ?>", {
                            type: "bar",
                            data: {
                                labels: xValues<?php echo $ahu_seq; ?>,
                                datasets: [{
                                    label: '0.5 values',
                                    data: <?php echo json_encode($valupoint5); ?>,
                                    borderColor: "SlateBlue", // Set border color to SlateBlue
                                    backgroundColor: "SlateBlue", // Set bar color to SlateBlue
                                    fill: false
                                }, {
                                    label: '5.0 values',
                                    data: <?php echo json_encode($valu5point); ?>,
                                    borderColor: "orange", // Set border color to orange
                                    backgroundColor: "orange", // Set bar color to orange
                                    fill: false
                                }]
                            },
                            options: {
                                legend: { display: false },
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    </script>
                <?php } ?>


                <table id="velo">
                    <?php if ($iso_class == 'ISO-05') { ?>
                        <tr>
                            <td style="font-weight: bold;">&ge; 0.3 &micro;m/m&sup3; </td>
                            <?php while ($rcgraph = $recoveryresult->fetch_assoc()): 
                                
                                    if($inst_model == "APEX Z50") {
                                    ?>
                                        <td>
                                            <?php echo number_format($rcgraph['point3'], 1, '.', ''); ?>
                                        </td>
                                        <?php } else  { ?>
                                        <td>
                                            <?php echo $rcgraph['point3']; ?>
                                        </td>
                                    <?php } ?>
                             <?php endwhile; ?>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">&ge; 0.5 &micro;m/m&sup3; </td>
                            <?php foreach ($recoveryresult as $rcgtable): 
                                        if($inst_model == "APEX Z50") { ?>
                                            <td>
                                                <?php echo number_format($rcgtable['point5'], 1, '.', ''); ?>
                                            </td>
                                        <?php } else { ?>
                                            <td>
                                                <?php echo $rcgtable['point5']; ?>
                                            </td>
                                        <?php } ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td style="font-weight: bold;">&ge; 0.5 &micro;m/m&sup3; </td>
                            <?php while ($rcgraph = $recoveryresult->fetch_assoc()): 
                                    if($inst_model == "APEX Z50") { ?>
                                        <td>
                                            <?php echo number_format($rcgraph['point5'], 1, '.', ''); ?>
                                        </td>
                                        <?php } else { ?>
                                        <td>
                                            <?php echo $rcgraph['point5']; ?>
                                        </td>
                                    <?php } ?>
                            <?php endwhile; ?>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">&ge; 5.0 &micro;m/m&sup3; </td>
                            <?php foreach ($recoveryresult as $rcgtable): 
                                    if($inst_model == "APEX Z50") { ?>
                                        <td>
                                            <?php echo number_format($rcgtable['5point'], 1, '.', '');?>
                                        </td>
                                        <?php } else { ?>
                                        <td>
                                            <?php echo $rcgtable['5point']; ?>
                                        </td>
                                    <?php } ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php } ?>
                </table>
                <!-- ============================== AHU ROOM NOTE POINTS ============================= -->
                <?php if ($rc_txtarea = true) { ?>
                    <table style="border:1px dotted; ">
                        <td>
                            <span style="font-weight: bold; ">&nbsp;INFERENCE : The above test results meets the acceptance
                                criteria.</span><br>
                            &nbsp; Note : 1. For test spot details refer particle counts raw prints
                            2. Sample collected above the 3 feet from the floor level or at working place <br>
                            <?php echo $row2['rc_txtarea']; ?>
                        </td>
                    </table>
                <?php } else { ?>
                    <table style="border:1px dotted; ">
                        <td>
                            <span style="font-weight: bold; ">&nbsp;INFERENCE : The above test results meets the acceptance
                                criteria.</span><br>
                            &nbsp; Note : 1. For test spot details refer particle counts raw prints
                            2. Sample collected above the 3 feet from the floor level or at working place
                        </td>
                    </table>
                <?php } ?>

                <?php include('footer.php');
            }
    endwhile;
} else {
    // ============================== EMPTY ROOM ============================= -->    
    echo '<div style="page-break-after:always" align="center"><strong>No records found</strong></div>';
}
?>
</body>

</html>