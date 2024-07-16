<?php
session_start();
include('web_acsdb.php');


$selectedReportId = "";
if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET["report_id"];
}

$editiqc = "";
if (isset($_GET["edit"])) {
    $editiqc = "&edit=yes";
}

$iqc_parameter = "";
$iqc_accp_var = "";
$iqc_var_obs = "";
$iqc_result = "";
$iqc_prepared = "";
$iqc_verified = "";
$iqc_seq_no = "";
$iqc_remarks = "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQC Form</title>
    <!-- Bootstrap CSS and JS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/14a4966bb0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/185b985911.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
    <style>
        .main_internal {
            padding-top: 0px;
            margin-bottom: 0px;
        }

        h2 {
            padding-top: 20px;
            text-align: center;
            padding-left: 350px;
            text-wrap: nowrap;
            display: flex;
        }


        .table_iqc_div {
            padding-top: 0px;
        }

        .sub_main_div {
            display: flex;
            padding-bottom: 16px;
            margin-bottom: 0px;
        }

        #thead_table tr th {
            background-color: lightslategrey;
            width: 30px;
            table-layout: space_between;
        }

       

        input[type=text],
        select {
            width: 100%;
            padding: 12px 20px;
            margin: 0px 0;
            border: 0px;
            outline: 0px;
        }

        .back_icon {
            /* padding-bottom: 66px; */
            color: #140c5e;
        }

        #bck-icn {
            padding-top: 24px;
            padding-left: 36px;
            color: #212529;
            font-size: x-large;
        }
        .header_btn{
            margin-left: 200px;
            margin-top: 15px;
            gap: 20px;
        }
    </style>
    
</head>

<body>
    <?php 
    $selected_instr = ("SELECT instrument_used from schedule where report_id = '$selectedReportId'");
    $inst_data = $mysqli->query($selected_instr);
    $final_instru = $inst_data->fetch_assoc();

    $used_instru = $final_instru['instrument_used'];

    $data_ins_name = explode(",", $used_instru);
        // // echo $data_ins_name;
        $inclause = "";
        foreach ($data_ins_name as $arrValue) {
            $inclause = $inclause . "'" . $arrValue . "',";
        }
        // echo $inclause; EXIT;
        $instr_name = "(" . substr($inclause, 0, -1) . ")";
    // echo $instr_name;
    
    // echo $selectedReportId; 
    
    ?>
   
    <div class="main_internal">
        
      
        <form id="iqc_form" method="POST" action="iqc_reports_save.php?save=iqc_data&report_id=<?php echo $selectedReportId; ?>">
            <div class="sub_main_div">
                <div class="back_icon">
                    <a href="index.php"><i id="bck-icn" class="fa-solid fa-circle-arrow-left"></i></a>
                </div>
                <div id="mySelect" onchange="myFunction()">
                    <input class="ms-5 mt-4 px-2" type="date" name="iqc_date" value="dd-mm-yyyy" id="iqc_date" required/>
                </div>
                <h2>Internal Quality Control</h2>
                <div class="header_btn">
                    <button type="button" onclick="redirectToPage()" class="btn btn-danger sm px-2 ms-3 " id="viewone" name="view">View Data</button>
            
                    <button type="submit" class="btn btn-primary" name="save">Save</button>
                </div>
            </div>


            <div class="table_iqc_div">
                <!-- <form  id="form_post" method="post"> -->
                <table class="table table-bordered">
                    <thead id="thead_table">
                        <tr class="text-center">
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Instrument Name</th>
                            <th>Instrument Id No.</th>
                            <th>Parameters to Check</th>
                            <th>Acceptable Variation</th>
                            <th>Variation Observed</th>
                            <th>Result</th>
                            <th>Prepared By</th>
                            <th>Verified</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <?php

                    $instrument_det = ("SELECT * FROM instrument_details where serial_no in $instr_name ");
                    $inst_result = $mysqli->query($instrument_det);
                    while ($inst_details = $inst_result->fetch_assoc()):

                        $instr_name = $inst_details['instrument_used'];
                        $serial_no = $inst_details['serial_no'];
                        $instr_id = $inst_details['instrument_id'];

                        ?>
                        <tbody>
                            
                            <tr class="text-center">
                                <td scope="row">
                                    <label><?php echo $serial_no; ?></label>
                                </td>
                                <td class="dated">                                                                     
                                </td>
                                
                                <td>
                                    <label for="instr_name"></label>
                                    <input type="text" id="instr_name" name=<?php echo "data[".$serial_no."][instr_name]"?> value="<?php echo $instr_name; ?>">
                                </td>
                                <td>
                                    <label for="instr_id"></label>
                                    <input type="text" id="instr_id" name=<?php echo "data[".$serial_no."][instr_id]"?> value="<?php echo $instr_id; ?>">
                                </td>
                                <td>
                                    <label for="iqc_parameter"></label>
                                    <select name=<?php echo "data[".$serial_no."][iqc_parameter]"?> id="iqc_parameter">
                                        <option <?php echo ($iqc_parameter = true) ? ' selected ' : ''; ?> value="To Check For Zero Error">To Check For Zero Error</option>
                                        <option <?php echo ($iqc_parameter = true) ? ' selected ' : ''; ?> value="To Check the CFM Using LAF">To Check the CFM Using LAF</option>
                                        <option <?php echo ($iqc_parameter = true) ? ' selected ' : ''; ?> value="To Check The PAO OIL level">To Check The PAO OIL level</option>
                                        <option <?php echo ($iqc_parameter = true) ? ' selected ' : ''; ?> value="To Check The Flow Rate & Zero Error">To Check The Flow Rate & Zero Error</option>
                                        <option <?php echo ($iqc_parameter = true) ? ' selected ' : ''; ?> value="To Check The Elecrtical Circuit & Smoke Level">To Check The Elecrtical Circuit & Smoke Level</option>
                                        <option <?php echo ($iqc_parameter = true) ? ' selected ' : ''; ?> value="To Check the battery Condition & Storage Capacity">To Check the battery Condition & Storage Capacity</option>
                                        <option <?php echo ($iqc_parameter = true) ? ' selected ' : ''; ?> value="To Check the Display Value & Battery Condition">To Check the Display Value & Battery Condition</option>
                                        <option <?php echo ($iqc_parameter = true) ? ' selected ' : ''; ?> value="To Check the battery Condition">To Check the battery Condition</option>
                                        <option <?php echo ($iqc_parameter = true) ? ' selected ' : ''; ?> value="To Check the battery Condition & Zero Error">To Check the battery Condition & Zero Error</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="iqc_accp_var"></label>
                                    <select name=<?php echo "data[".$serial_no."][iqc_accp_var]"?> id="iqc_accp_var">
                                        <option <?php echo ($iqc_accp_var = true) ? ' selected ' : ''; ?> value="Must Attain Zero Value">Must Attain Zero Value</option>
                                        <option <?php echo ($iqc_accp_var = true) ? ' selected ' : ''; ?> value="Between 300 to 400">Between 300 to 400</option>
                                        <option <?php echo ($iqc_accp_var = true) ? ' selected ' : ''; ?> value="OIL Should be up to the Marking Level">OIL Should be up to the Marking Level</option>
                                        <option <?php echo ($iqc_accp_var = true) ? ' selected ' : ''; ?> value="Flow Rate Should be 50 or 100 ±5%/ Must Attain Zero Value">Flow Rate Should be 50 or 100 ±5%/ Must Attain Zero Value</option>
                                        <option <?php echo ($iqc_accp_var = true) ? ' selected ' : ''; ?> value="Should Observe Dense White Smoke">Should Observe Dense White Smoke</option>
                                        <option <?php echo ($iqc_accp_var = true) ? ' selected ' : ''; ?> value="Battery Should Be Full / Storage Time Should be Minimum 2 hrs">Battery Should Be Full / Storage Time Should be Minimum 2 hrs</option>
                                        <option <?php echo ($iqc_accp_var = true) ? ' selected ' : ''; ?> value="Battery Condition Should be Minimum 30 days / Display Value Should be in Ambient Condition">Battery Condition Should be Minimum 30 days / Display Value Should be in Ambient Condition</option>
                                        <option <?php echo ($iqc_accp_var = true) ? ' selected ' : ''; ?> value="Minimum Battery Level Should be 30%">Minimum Battery Level Should be 30%</option>
                                        <option <?php echo ($iqc_accp_var = true) ? ' selected ' : ''; ?> value="Minimum Battery Level Should be 30% / Must Attain Zero Error">Minimum Battery Level Should be 30% / Must Attain Zero Error</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="iqc_var_obs"></label>
                                    <select name=<?php echo "data[".$serial_no."][iqc_var_obs]"?> id="iqc_var_obs">
                                        <option <?php echo ($iqc_var_obs = true) ? ' selected ' : ''; ?> value="With in the Limit">With in the Limit</option>
                                        <option <?php echo ($iqc_var_obs = true) ? ' selected ' : ''; ?> value="Out of the Limit">Out of the Limit</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="iqc_result"></label>
                                    <select name=<?php echo "data[".$serial_no."][iqc_result]"?> id="iqc_result">
                                        <option <?php echo ($iqc_result == "PASS") ? ' selected ' : ''; ?> value="PASS">Pass</option>
                                        <option <?php echo ($iqc_result == "FAIL") ? ' selected ' : ''; ?> value="FAIL">Fail</option>
                                    </select>
                                </td>

                                <td>
                                    <label for="iqc_prepared"></label>
                                    <input type="text" id="iqc_prepared" name=<?php echo "data[".$serial_no."][iqc_prepared]"?> value="<?php echo $iqc_prepared; ?>">
                                </td>
                                <td>
                                    <label for="iqc_verified"></label>
                                    <input type="text" id="iqc_verified" name=<?php echo "data[".$serial_no."][iqc_verified]"?> value="<?php echo $iqc_verified; ?>">
                                </td>
                                <td>
                                    <label for="iqc_remarks"></label>
                                    <input type="text" id="iqc_remarks" name=<?php echo "data[".$serial_no."][iqc_remarks]"?> value="<?php echo $iqc_remarks; ?>">
                                </td>
                            </tr>
                        </tbody>
                    <?php endwhile; ?>
                </table>
            </div>
            
        </form>
    </div>

            
    <script>
        function redirectToPage() {
            // Redirect to the desired page
            window.location.href = "iqc_view_data.php?report_id=<?php echo $selectedReportId; ?>";
        }


        function myFunction() {
            var x = document.getElementById("iqc_date").value;
            //document.getElementByName("dated").innerHTML = x;
            var columnCells = document.querySelectorAll('.dated');

            // Set the same data to all cells in the column
            columnCells.forEach(function(cell) {
                cell.innerHTML = x;
            });
        }
    </script>
</body>

</html>