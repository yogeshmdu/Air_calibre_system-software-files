<!-- ######## DATABASE CONNECTION ######## -->
<?php
//session_start();
require 'web_acsdb.php';

// <!--============================== TO GET THE REPORT_ID ==============================-->
$selectedReportId = "";
if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET["report_id"];
}

if (isset($_GET["test_date"])) {
    $testingDate = $_GET["test_date"];
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
$customer_name = $row['customer_name'];
$job_card = $row['job_card_no'];
$address = $address1 . $comma . $address2 . $comma . $area . $comma . $city;


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job_closing_view</title>

    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        @media print {
            @page {
                margin-top: 0;
                margin-bottom: 0;
            }
        }

        * {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: small;
        }

        table {
            /* border: 1px solid black; */
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            /* width: auto;
            height: auto; */
            border: 1px solid black;
            padding: 8px;
            margin-bottom: none;
        }

        td img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        #acs-main {
            font-size: 23px;
            /* font-weight: bold; */
        }

        .acs-headings {
            width: 90%;
            font-size: 16px;
        }

        .cd {
            width: 18%;
            /* font-size: 10px; */
            font-weight: 200;
            /* text-align: center; */
        }

        #para {
            font-size: 10px;
            font-weight: normal;
        }

        .cda {
            width: 37%;
        }

        .test-carried-out {
            height: 50px;
        }

        .center-text-class {
            text-align: center;
        }
        .sign-tab {
            height: 40px;
        }
        .signature-area td {
            width: 24%;
            height: 20px;
        }

        tr.no-bottom-border td {
            border-bottom: none;
        }

        #bottom_table {
            border-top: none;
        }
    </style>
</head>

<body>
    <br><br>
    <?php
    $masterdataquery = $mysqli->query("SELECT * FROM reports_ahu_details where report_id = '$selectedReportId' and testing_date='$testingDate'")
        or die($mysqli);

    $reultdata = $masterdataquery->fetch_assoc();

    $perday_test = $reultdata['testing_date'];
   
    $witness = $reultdata['witness_by'];
    $department = $reultdata['department'];
    $tested_are = $reultdata['tested_area'];
    



    $instrment_query = $mysqli->query("SELECT * FROM employee_job_list WHERE  report_id = '$selectedReportId' and job_date = '$perday_test'");
    $inst_val = $instrment_query->fetch_assoc();

    $instrment_full_name = $inst_val['carried_out'];
    $in_time = $inst_val['emp_in_time'];
    $out_time = $inst_val['emp_out_time'];
    $cust_name_details = $inst_val['customer_name'];
    if ($cust_name_details != Null) {
        $customer_name = $cust_name_details;
    }
    $cust_adrs_details = $inst_val['cust_address'];
    if ($cust_adrs_details != Null) {
        $address = $cust_adrs_details;
    }
    $job_closing_remark = $inst_val['job_closing_remarks'];
    $testing_en = $inst_val['testing_engg'];
    $empArray = explode(",", $testing_en);
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
    ?>
    
    <button type="button" onclick="downloadPDF()" style="padding: 10px; float: right; margin: 50px;">Download PDF</button>
    
    <div id="area">
        <table >
            <th colspan="4" id="acs-main">AIR CALIBRE SYSTEMS <br>
                <p id="para"><b>Contact:</b> 044-42152074 &centerdot; <b>E-mail:</b> customersupport@acs-india.in
                    &centerdot; <b>Website:</b> www.acs-india.in</p>
            </th>
            <td rowspan="2"><img src="image/ACS.jpg" alt="Air Calibre Systems Logo" width="80px"></td>
            <tr>
                <th colspan="4" class="acs-headings">JOB CARD CUM SERVICE REPORT</th>
            </tr>
            <!-- cd - client-details -->
            <!-- cda - client-details-textarea -->
            <tr>
                <td class="cd">Client Name</td>
                <td class="cda">
                    <?php echo $customer_name; ?>
                </td>
                <td colspan="2" class="cd">Job Card No.</td>
                <td class="cda">
                    <?php echo $job_card; ?>
                </td>
            </tr>
            <tr>
                <td class="cd">Plant Address</td>
                <td class="cda">
                    <?php echo $address; ?>
                </td>
                <td colspan="2" class="cd">Tested By</td>
                <td class="cda">
                    <?php echo $tested_by; ?>
                </td>
            </tr>
            <tr>
                <td class="cd">Witnessed By</td>
                <td class="cda">
                    <?php echo $witness; ?>
                </td>
                <td colspan="2" class="cd">Tested Area</td>
                <td class="cda">
                    <?php echo $tested_are; ?>
                </td>
            </tr>
            <tr>
                <td class="cd">Department</td>
                <td class="cda">
                    <?php echo $department; ?>
                </td>
                <td colspan="2" class="cd">Testing Date</td>
                <td class="cda">
                    <?php echo $perday_test; ?>
                </td>
            </tr>
            <tr>
                <td class="cd">In time</td>
                <td class="cda">
                    <?php echo $in_time; ?>
                </td>
                <td class="cd" colspan="2">Out time</td>
                <td class="cda">
                    <?php echo $out_time; ?>
                </td>
            </tr>
            <tr>
                <td class="cd" colspan="5">Site Address:</td>
            </tr>


            <th colspan="7" class="acs-headings">SUMMATION</th>
            <tr>
                <!-- <td class="cd center-text-class">(&check;)</td>
                <td class="cd">Work Status:</td>
                <td class="cd">In Time:</td>
                <td class="cd">Out Time:</td> -->
            </tr>
            <tr>
                <td class="cd test-carried-out">Test(s) Carried Out</td>
                <td colspan="6">
                    <?php
                    $inst_sql = "SELECT * FROM test_carried_details WHERE serial_no IN ($instrment_full_name)";
                    $inst_result = $mysqli->query($inst_sql);

                    while ($inst_row = $inst_result->fetch_assoc()) {
                        $another_inst = $inst_row['test_description'];

                        echo $another_inst;
                        echo ",";
                    } ?>
                </td>
            </tr>

            <tr>
                <td class="cd test-carried-out">Note : </td>
                <td colspan="6">
                    <?php echo $job_closing_remark; ?>
                </td>
            </tr>
            <!-- <tr>
                <td colspan="5" class="cd">&nbsp;</td>
            </tr>
            <tr class="no-bottom-border">
                <td colspan="5" class="cd">&nbsp;</td>
            </tr> -->
        </table>
        <table>
            <th id="bottom_table" colspan="5" class="acs-headings">CUSTOMER FEEDBACK</th>
            <tr>
                <td rowspan="2" class="cd">Tick mark at the appropriate place</td>
                <td class="cd center-text-class">4</td>
                <td class="cd center-text-class">3</td>
                <td class="cd center-text-class">2</td>
                <td class="cd center-text-class">1</td>
            </tr>
            <tr>
                <td class="cd center-text-class">Excellent</td>
                <td class="cd center-text-class">Good</td>
                <td class="cd center-text-class">Average</td>
                <td class="cd center-text-class">Poor</td>
            </tr>
            <tr>
                <td class="cd">Attitude</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="cd">Communication</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="cd">Trouble Shooting</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="cd">Quality of our Service</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="cd">Responsiveness & Promptness</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" class="cd sign-tab">Your Valuable Suggestions / Improvement Points can be stated here:
                    <br><br><br><br><br><br>
                </td>
            </tr>
            <tr class="signature-area">
                <td class="cd">Prepared By <br><br><br><br><br><br> Engineer Signature:</td>
                <td colspan="2" class="cd sign-tab">Customer Signature & Seal<br><br><br><br><br><br><br></td>
                <td colspan="2" class="cd sign-tab">Action Plan, if any <em>(filled by us)</em> <br><br><br><br><br><br> MR
                </td>
            </tr>
        </table>
    </div>
    
    <!-- <?php
    //$inst_sql = "SELECT * FROM instrument_details WHERE serial_no IN ($instrment_full_name)";
    ///$inst_result = $mysqli->query($inst_sql);
    
    //while ($inst_row = $inst_result->fetch_assoc()):
    //$another_inst = $inst_row["instrument_used"];
    ?>
                    <?php //echo $another_inst;
                    //endwhile; ?> -->


    <script>
        window.jsPDF = window.jspdf.jsPDF;
        var docPDF = new jsPDF();

        function downloadPDF(invoiceNo){

            var elementHTML = document.querySelector("#area");
            docPDF.html(elementHTML, {
                callback: function(docPDF) {
                    docPDF.save(invoiceNo+'.pdf');
                },
                x: 5,
                y: 10,
                width: 200,
                windowWidth: 850
            });
        }
    </script>
</body>

</html>