<?php
require 'web_acsdb.php';

$selectedReportId = "";
$testing_dateformat = "";
$job_card_no = "";
$customer_name = "";
$cust_ads = "";

if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET['report_id'];
}
if (isset($_GET["test_date"])) {
    $testing_dateformat = $_GET['test_date'];
}
if (isset($_GET["job_card_no"])) {
    $job_card_no = $_GET['job_card_no'];
}
if (isset($_GET["customer_name"])) {
    $customer_name = $_GET['customer_name'];
}
if (isset($_GET["cust_address"])) {
    $cust_ads = $_GET['cust_address'];
}


// $contract_cust_name = "";
// $shift_status = "";
// $test_carried = "";
// $cust_address = "";
$emp_in_time = "";
$emp_out_time = "";
$instr_array = [];
$test_carried_array = [];
$shift_status = "";
$job_closing_remark = "";
$emp_array = [];
// $instrument_query = "SELECT * FROM instrument_details";
// $inst_result = $mysqli->query($instrument_query);


$employee_job_list_query = "SELECT * FROM employee_job_list WHERE report_id = '$selectedReportId' AND job_date = '$testing_dateformat'";
$result = $mysqli->query($employee_job_list_query);

while ($resultRow = $result->fetch_assoc()) {
    $updateIndicator = 1;
    $job_date = $resultRow['job_date'];
    // $report_id = $resultRow['report_id'];
    // $job_card_no = $resultRow['job_card_no'];

    $contract_cust_name = $resultRow['customer_name'];

    if ($contract_cust_name != Null) {
        $customer_name = $contract_cust_name;
    }

    $cust_test_address = $resultRow['cust_address'];
    if ($cust_test_address != Null) {
        $cust_ads = $cust_test_address;
    }
    $emp_in_time = $resultRow['emp_in_time'];
    $emp_out_time = $resultRow['emp_out_time'];
    $instr_array = explode(",", $resultRow['instr_used']);
    $shift_status = $resultRow['shift_status'];
    $test_carried_array = explode(",", $resultRow['carried_out']);
    $job_closing_remark = $resultRow['job_closing_remarks'];
    $emp_array = explode(",", $resultRow['testing_engg']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Entry Form</title>

    <!-- Bootstrap CSS and JS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
    <script src="https://kit.fontawesome.com/185b985911.js" crossorigin="anonymous"></script>

    <!-- Dropdown -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <style>
        .main_ptr {
            display: flex;
            /* margin-left: 30px; */
        }

        h1 {
            font-size: xx-large;
            text-align: center;
            margin-bottom: 9px;
            padding-left: 30%;
            /* margin-right: 90px; */
        }

        /*      
         #btn_submit{
                margin-left: 560px;
            } */
        @media (max-width: 390px) {
            .form-select{
                margin-top: 30px;
            }
            .center_resp{
                margin-left: 30px;
            }
            /* #btn_submit{
                margin-left: 130px;
            } */
             /* .form-label{
                font-size: px;
             }  */
        }
        @media (max-width: 460px) {
            .form-select{
                margin-top: 23px;
            }
            .fm_ctrl{
                margin-top: 0px;
            }
            #emp_in_time{
                margin-top: 23px;
            }
            /* #btn_submit{
                margin-left: 130px;
            } */
            /* .center_resp{
                margin-left: -12px;
            } */
            
        }
        @media (max-width: 430px) {
            .form-select{
                margin-top: 23px;
            }
            #emp_in_time{
                margin-top: -1px;
            }
            .center_resp{
                margin-left: 30px;
            }
            /* #btn_submit{
                margin-left: 130px;
            } */
            
        }
        /* @media (max-width: 600px) {
            #btn_submit{
                margin-left: 190px;
            }
        } */
        /* @media (max-width: 992px) {
            #btn_submit{
                margin-left: 100px;
            }
        } */

        
        @media (max-width: 1000px) {
            /* #btn_submit{
                margin-left: 230px;
            } */
        }
        @media (max-width: 1120px) {
             h1 {
            font-size: xx-large;
            text-align: center;
            margin-bottom: 9px;
            padding-left: 20%;
            /* margin-right: 90px; */
        }
            /* #btn_submit{
                margin-left: 290px;
            } */
        }
        .back_icon {
            /* padding-bottom: 66px; */
            color: #140c5e;
        }

        #bck-icn {
            padding-top: 13px;
            /* margin-right: 100%; */
            /* padding-left: 50px; */
            /* padding-right: 610px; */
            padding-left: 40px;
            color: #212529;
            font-size: x-large;
        }

    </style>

</head>

<body> 
    <div class="container">
    <!--<div class="col-md-2">
            <a href="job_service_sheet.php?report_id=<?php //echo $selectedReportId; ?>" class="btn btn-primary text-nowrap my-3" role="button"><< Back</a>
        </div> -->
        <hr>
        <div class="main_ptr">
            <div class="back_icon"><a href="job_service_sheet.php?report_id=<?php echo $selectedReportId ?>">
                    <i id="bck-icn" class="fa-solid fa-circle-arrow-left"></i></a>
            </div>
            
            <h1>Job Description</h1>
        </div>
        <hr>
        
        <form action="job_list_save.php?updateIndicator=<?php echo $updateIndicator; ?>" method="POST">
            <div class="mb-3">
                <label for="job_date" class="form-label">Job Date</label>
                <input type="text" class="form-control" id="job_date" name="job_date"
                    value="<?php echo $testing_dateformat; ?>" readonly>
            </div>
           
            <div class="d-flex gap-2 mb-6">
                <div class="col-md-6">
                    <label for="report_id" class="form-label">Report ID</label>
                    <input type="text" class="form-control" id="report_id" name="report_id"
                        value="<?php echo $selectedReportId; ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="job_card_no" class="form-label">Job Card No</label>
                    <input type="text" class="form-control" id="job_card_no" name="job_card_no"
                        value="<?php echo $job_card_no; ?>" readonly>
                </div>

            </div>
            
            <br>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                <label class="form-check-label" for="flexSwitchCheckDefault">Customer</label>
            </div>
            <br>
            <div class="collapse multi-collapse" id="customer_name_correction">
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Contract Customer Name</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                        value="<?php echo $customer_name ?>">
                </div>
                <div class="mb-3">
                    <label for="cust_address" class="form-label">Customer Address</label>
                    <input type="text" class="form-control" id="cust_address" name="cust_address"
                        value="<?php echo $cust_ads ?>">
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    // Initially hide the collapsible section
                    $('#customer_name_correction').hide();

                    // Toggle the visibility of the collapsible section when the checkbox is clicked
                    $('#flexSwitchCheckDefault').change(function () {
                        if (this.checked) {
                            $('#customer_name_correction').slideDown();
                        } else {
                            $('#customer_name_correction').slideUp();
                        }
                    });
                });
            </script>






            <div class="d-flex gap-2 mb-6">
                <div class="col">
                    <label for="emp_in_time" class="form-label">Engineer's In <span class="center_resp">Time</span></label>
                    <input type="time" class="form-control fm_ctrl" id="emp_in_time" name="emp_in_time"
                        value="<?php echo $emp_in_time; ?>">
                </div>
                <div class="col">
                    <label for="emp_out_time" class="form-label">Engineer's Out <span class="center_resp">Time</span></label>
                    <input type="time" class="form-control" id="emp_out_time" name="emp_out_time"
                        value="<?php echo $emp_out_time; ?>">
                </div>
                <div class="col">
                    <label for="shift_status" class="form-label">Shift status</label>
                    <select id="shift_status" class="form-select" name="shift_status">
                        <option selected>Select shift</option>
                        <option <?php echo ($shift_status == 1) ? ' selected ' : ''; ?>
                            value="<?php echo 1; ?>">1</option>
                        <option <?php echo ($shift_status == 2) ? ' selected ' : ''; ?>
                            value="<?php echo 2; ?>">2</option>
                        <option <?php echo ($shift_status == 3) ? ' selected ' : ''; ?>
                            value="<?php echo 3; ?>">3</option>
                        <option <?php echo ($shift_status == 4) ? ' selected ' : ''; ?>
                            value="<?php echo 4; ?>">4</option>
                        <option <?php echo ($shift_status == 5) ? ' selected ' : ''; ?>
                            value="<?php echo 5; ?>">5</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="instr_used" class="mt-3 col-form-label text-nowrap">Instrument name</label>
                <div>
                    <select name="instr_used[]" class="selectpicker form-control" id="instr_used" multiple
                        data-live-search="false">
                        <?php
                        $eqpresult = $mysqli->query("SELECT * FROM instrument_details") or die($mysqli->error);

                        while ($eqpdetail = $eqpresult->fetch_assoc()):
                            $serial_no = $eqpdetail['serial_no'];
                            $inst_model = $eqpdetail['instrument_model'];
                            $inst_make = $eqpdetail['instrument_make'];
                            $instr_used = $eqpdetail['instrument_used'];
                            ?>
                            <option <?php echo (in_array($serial_no, $instr_array)) ? 'selected' : ''; ?>
                                value="<?php echo $serial_no; ?>">
                                <?php echo $instr_used;
                                echo "--";
                                echo $inst_make;
                                echo "--";
                                echo $inst_model; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2 mb">
                <div class="col">
                    <label for="testing_engg" class="mt-3 form-label ">Emp name</label>
                    <select name="testing_engg[]" class="selectpicker form-control" id="testing_engg" multiple
                        data-live-search="false">
                        <?php
                        $employee_query = $mysqli->query("SELECT * FROM employee WHERE employee_status = 'Active'") or die($mysqli->error);

                        while ($emp_data = $employee_query->fetch_assoc()):
                            $serial_no = $emp_data['serial_no'];
                            $emp_id = $emp_data['employee_id'];
                            $emp_status = $emp_data['employee_status'];
                            $emp_name = $emp_data['employee_name'];
                            ?>
                            <option <?php echo (in_array($emp_id, $emp_array)) ? 'selected' : ''; ?>
                                value="<?php echo $emp_id; ?>">
                                <?php echo $emp_name; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col">
                    <label for="test_name" class="mt-3 form-label text-nowrap">Test carried out</label>
                    <select id="test_name" class="selectpicker w-100" name="carried_out[]" multiple data-live-search="false">
                        <?php
                        $test_carried = $mysqli->query("SELECT * FROM test_carried_details") or die($mysqli->error);

                        while ($testdetail = $test_carried->fetch_assoc()):
                            $serial_no = $testdetail['serial_no'];
                            $test_inst_det = $testdetail['inst_det'];
                            $test_name = $testdetail['test_description'];
                            ?>
                            <option <?php echo (in_array($serial_no, $test_carried_array)) ? 'selected' : ''; ?>
                                value="<?php echo $serial_no; ?>">
                                <?php echo $test_name; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="w-100 mt-4 mb-2">
                <label for="job_closing_remarks" class="form-label">Remark :</label> <br>
                <textarea class="form-control w-100" id="job_closing_remarks"
                    name="job_closing_remarks"><?php echo $job_closing_remark; ?></textarea>
            </div>


            <button type="submit" class="btn btn-primary mt-5" id="btn_submit">Submit</button>
        </form>
    </div>
</body>

</html>