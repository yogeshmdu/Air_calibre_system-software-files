<?php
session_start();
include ('web_acsdb.php');

$vou_format = "";
$emp_name = "";
$emp_id = "";
$vou_date = "";
$editvoucher = "";
$serial_no = "";

if (isset($_GET['vou_format'])) {
    $vou_format = $_GET['vou_format'];
}

if (isset($_GET['emp_name'])) {
    $emp_name = $_GET['emp_name'];
}

if (isset($_GET['emp_id'])) {
    $emp_id = $_GET['emp_id'];
}

if (isset($_GET['vou_date'])) {
    $vou_date = $_GET['vou_date'];
}

if (isset($_GET['edit'])) {
    $editvoucher = '&edit=yes';
}
if (isset($_GET['serial_no'])) {
    $serial_no = $_GET['serial_no'];
}

$new = true;
$edit = "";
$vou_ob = 0;
$vou_cb = 0;
$vou_particulares = "";
$vou_gst = 0;
$vou_tools = 0;
$vou_xerox = 0;
$vou_others = 0;
$vou_sundry = 0;
$vou_ot_trav = 0;
$vou_bod_ldg = 0;
$vou_lcl_trav = 0;
$vou_petrol = 0;
$vou_vh_service = 0;


// Fetch voucher details if vou_date is set
if (isset($_GET['serial_no'])) {
    $new = false;
    $emp_vou_query = $mysqli->query("SELECT * FROM emp_vou_details WHERE emp_id = '$emp_id' AND vou_date = '$vou_date'");
    // $emp_vou_query->bind_param('ss', $emp_id, $vou_date);
    $row_vou = $emp_vou_query->fetch_assoc();

    $vou_ob = $row_vou['vou_ob'];
    $vou_cb = $row_vou['vou_cb'];
    $vou_particulares = $row_vou['vou_particulares'];
    $vou_gst = $row_vou['vou_gst'];
    $vou_tools = $row_vou['vou_tools'];
    $vou_xerox = $row_vou['vou_xerox'];
    $vou_others = $row_vou['vou_others'];
    $vou_sundry = $row_vou['vou_sundry'];
    $vou_ot_trav = $row_vou['vou_ot_trav'];
    $vou_bod_ldg = $row_vou['vou_bod_ldg'];
    $vou_lcl_trav = $row_vou['vou_lcl_trav'];
    $vou_petrol = $row_vou['vou_petrol'];
    $vou_vh_service = $row_vou['vou_vh_service'];
    $vou_date = $row_vou['vou_date'];
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Voucher form </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/t-12/jquery-2.0.3.min_1.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        @media print {
            @page {
                margin-top: 0;
                margin-bottom: 0;
            }
        }

        .heading_container {
            text-align: center;
        }

        .head_container {
            text-align: center;
            border: 2px solid gray;
            width: 80%;
            display: flex;
            justify-content: space-evenly;
            padding: 20px 0 20px 0;
            margin-left: 150px;
        }

        .sub_heading {
            margin-left: 10%;
            font-variant: small-caps;
            display: flex;
            justify-content: space-between;
        }

        .upload_img {
            float: right;
            margin-left: 100px;
            display: flex;
        }

        .dropdown {
            margin-right: 20%;
            display: flex;
            justify-content: center;
        }

        .total_calc {
            float: right;
            margin-right: 100px;
        }

        .body_container {
            text-align: center;
            border: 2px solid gray;
            width: 80%;
            justify-content: space-evenly;
            padding: 20px 0 20px 0;
            margin-left: 150px;
        }

        .sec_container {
            text-align: center;
            border: 2px solid gray;
            width: 80%;
            justify-content: space-evenly;
            padding: 20px 0 20px 0;
            margin-left: 150px;
            display: flex;
        }

        .add1_cont {
            text-align: center;
            display: flex;
            justify-content: space-between;
            padding: 20px 10px 20px 10px;
        }

        #textarea_cont {
            width: 800px;
            height: 60px;
            margin-left: 180px;
        }

        .sub_btn {
            margin-top: 100px;
            text-align: center;
        }

        .news-item {
            display: none;
        }

        h3 {
            background-color: #660066;
            padding: 15px 50px 15px 50px;
            color: whitesmoke;
        }

        /* The media query / Responsive code are there */
        @media screen and (max-width:820px) {

            .heading_container {
                text-align: center;
            }

            .head_container {
                text-align: center;
                border: 1px solid gray;
                width: 80%;
                display: flex;
                justify-content: space-evenly;
                padding: 10px 0 10px 0;
                margin-left: 80px;
            }

            .sub_heading {
                margin-left: 5%;
                font-variant: small-caps;
                display: flex;
                justify-content: space-between;
            }

            .upload_img {
                float: right;
                margin-left: 50px;
                display: flex;
            }

            .dropdown {
                margin-right: 10%;
                display: flex;
                justify-content: center;
            }

            .total_calc {
                float: right;
            }

            .body_container {
                text-align: center;
                border: 2px solid gray;
                width: 80%;
                justify-content: space-evenly;
                padding: 10px 0 10px 0;
                margin-left: 75px;
            }

            .add1_cont {
                text-align: center;
                display: flex;
                justify-content: space-between;
                padding: 10px 5px 10px 5px;
            }

            #textarea_cont {
                width: 500px;
                height: 30px;
            }

            .sub_btn {
                margin-top: 50px;
                text-align: center;
            }

            .add1_cont {
                padding-right: 50px;
            }

            .name_cont {
                background-color: aqua;
                justify-content: space-between;
                flex-direction: row;
            }

            h3 {
                background-color: #660066;
                padding: 15px 50px 15px 50px;
                color: whitesmoke;
            }

        }
    </style>



</head>

<body>
    <?php if ($vou_format != 1) {

        $vou_opening = ("SELECT * FROM emp_adv_given WHERE emp_id ='$emp_id' AND vou_date='$vou_date'");
        $vou_op_data = $mysqli->query($vou_opening);
        while ($vou_op_res = $vou_op_data->fetch_assoc()):

            $vou_ob_givend += $vou_op_res['given_amt'];

        endwhile;
        // $previous_date = date('Y-m-d', strtotime('yesterday'));
        // $today_date = date('Y-m-d');
    
        $vou_balance = ("SELECT * FROM emp_vou_details WHERE emp_id='$emp_id' AND vou_date='$vou_date'");
        $vou_result = $mysqli->query($vou_balance);
        while ($vou_det = $vou_result->fetch_assoc()):

            $gst_exp = $vou_det['vou_gst'];
            $tool_exp = $vou_det['vou_tools'];
            $xerox_exp = $vou_det['vou_xerox'];
            $other_exp = $vou_det['vou_others'];
            $vou_sundry = $vou_det['vou_sundry'];
            $ot_trav_exp = $vou_det['vou_ot_trav'];
            $bod_ldg_exp = $vou_det['vou_bod_ldg'];
            $lcl_trav_exp = $vou_det['vou_lcl_trav'];
            $pertrol_exp = $vou_det['vou_petrol'];
            $vh_service_exp = $vou_det['vou_vh_service'];

            $vou_dy_ob = $vou_det['vou_ob'];

            $total_exp = $vou_ob_givend + $gst_exp + $tool_exp + $xerox_exp + $other_exp + $vou_sundry + $ot_trav_exp + $bod_ldg_exp + $lcl_trav_exp + $pertrol_exp + $vh_service_exp;

        endwhile;

        $vou_cb = $vou_dy_ob - $total_exp;

        // echo $vou_cb;
        ?>



        <div class="full_cont">
            <form id="vou_form"
                action="voucher_save.php?save=vou_det<?php echo $editvoucher; ?>&vou_format=<?php echo $vou_format; ?>"
                method="POST" enctype="multipart/form-data">
                <div class="heading_container">
                    <h3>ACS - Reiumbersement Voucher</h3>
                </div>

                <div class="sub_heading">
                    <h4>Voucher details :</h4>
                </div>
                <div class="head_container">
                    <div class="name_cont">
                        <label for="emp_name " class="col-md-8 col-form-label text-nowrap">Employee Name</label>
                        <div class="col">
                            <input type="text" class="form-control form-control-md" id="emp_name" name="emp_name"
                                value="<?php echo $emp_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="name_cont">
                        <label for="emp_id " class="col-md-8 col-form-label text-nowrap">Employee ID</label>
                        <div class="col">
                            <input type="text" class="form-control form-control-md" id="emp_id" name="emp_id"
                                value="<?php echo $emp_id; ?>" readonly>
                        </div>
                    </div>
                    <div class="name_cont">
                        <label for="vou_date" class="col-md-8 col-form-label text-nowrap"> Voucher date</label>
                        <div class="col">
                            <input type="date" class="form-control form-control-md" id="vou_date" name="vou_date"
                                value="<?php echo $vou_date; ?>" readonly>
                        </div>
                    </div>
                    <div class="name_cont">
                        <label for="vou_ob" class="col-md-8 col-form-label text-nowrap"> Opening Balance</label>
                        <div class="col">
                            <input type="number" class="form-control form-control-md" id="vou_ob" name="vou_ob"
                                value="<?php echo $vou_dy_ob; ?>" readonly>
                        </div>
                    </div>
                    <div class="name_cont">
                        <label for="vou_cb" class="col-md-8 col-form-label text-nowrap">Closing Balance</label>
                        <div class="col">
                            <input type="number" class="form-control form-control-md" id="vou_cb" name="vou_cb"
                                value="<?php echo $vou_cb; ?>" readonly>
                        </div>
                    </div>
                </div>

                <br>
                <div class="sub_heading">
                    <h4> Overhead Expense:</h4>
                    <div class="dropdown">
                        <label for="vou_type" class="col-form-label">
                            <h6> Choose &nbsp; : &nbsp; </h6>
                        </label>
                        <div class="col mb-3">
                            <select class="col-lg-11 form-control boxselect" name="vou_type" id="vou_type">
                                <option value="All" disabled selected class="tablinks"
                                    onclick="openVoucher(event, 'select')">Select Voucher</option>
                                <option value="Cat1">Master</option>
                                <option value="Cat2">Inter site</option>
                                <option value="Cat3">Intra site</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="body_container">
                    <div class="name_cont">
                        <label for="vou_particulares" class="col-form-label">
                            <h5>Particulars:</h5>
                        </label><br>
                        <textarea class="form-control" id="textarea_cont"
                            name="vou_particulares"><?php echo $vou_particulares; ?></textarea>
                    </div><br>

                    <div class="add1_cont ">
                        <div class="name_cont" style="width: 180px;">
                            <label for="vou_gst" class="col-md-1 col-form-label text-nowrap"> GST Bills</label>
                            <div class="col">
                                <input type="number" class="form-control expense-input" id="vou_gst" name="vou_gst"
                                    value="<?php echo $vou_gst; ?>">
                            </div>
                        </div>
                        <div class="name_cont" style="width: 180px;">
                            <label for="vou_tools" class="col-md-1 col-form-label text-nowrap"> Tools</label>
                            <div class="col">
                                <input type="number" class="form-control expense-input" id="vou_tools" name="vou_tools"
                                    value="<?php echo $vou_tools; ?>">
                            </div>
                        </div>

                        <div class="name_cont" style="width: 180px;">
                            <label for="vou_xerox" class="col-md-1 col-form-label text-nowrap "> Xerox </label>
                            <div class="col">
                                <input type="number" class="form-control expense-input" id="vou_xerox" name="vou_xerox"
                                    value="<?php echo $vou_xerox; ?>">
                            </div>
                        </div>
                        <div class="name_cont" style="width: 180px;">
                            <label for="vou_others" class="col-md-1 col-form-label text-nowrap "> Others </label>
                            <div class="col">
                                <input type="number" class="form-control expense-input" id="vou_others" name="vou_others"
                                    value="<?php echo $vou_others; ?>">
                            </div>
                        </div>

                        <div class="name_cont" style="width: 180px;">
                            <label for="vou_sundry" class="col-md-1 col-form-label text-nowrap "> Sundry </label>
                            <div class="col">
                                <input type="number" class="form-control expense-input" id="vou_sundry" name="vou_sundry"
                                    value="<?php echo $vou_sundry; ?>">
                            </div>
                        </div>

                    </div>

                    <div class="add1_cont news-list">
                        <div class="name_cont news-item" data-category="Cat1, Cat2">
                            <label for="vou_lcl_trav" class="col-md-8 col-form-label text-nowrap">Local Travel</label>
                            <div class="col">
                                <input type="number" class="form-control form-control-md expense-input" id="vou_lcl_trav"
                                    name="vou_lcl_trav" value="<?php echo $vou_lcl_trav; ?>">
                            </div>
                        </div>
                        <div class="name_cont news-item" data-category="Cat1, Cat2">
                            <label for="vou_petrol" class="col-md-8 col-form-label text-nowrap">Petrol</label>
                            <div class="col">
                                <input type="number" class="form-control form-control-md expense-input" id="vou_petrol"
                                    name="vou_petrol" value="<?php echo $vou_petrol; ?>">
                            </div>
                        </div>
                        <div class="name_cont news-item" data-category="Cat1, Cat2">
                            <label for="vou_vh_service" class="col-md-8 col-form-label text-nowrap">Vehicle Service</label>
                            <div class="col">
                                <input type="number" class="form-control form-control-md expense-input" id="vou_vh_service"
                                    name="vou_vh_service" value="<?php echo $vou_vh_service; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="add1_cont news-list" style="justify-content: space-evenly;">
                        <div class="name_cont news-item" data-category="Cat1, Cat3">
                            <label for="vou_ot_trav" class="col-md-8 col-form-label text-nowrap">Outstation travel</label>
                            <div class="col">
                                <input type="number" class="form-control form-control-md expense-input" id="vou_ot_trav"
                                    name="vou_ot_trav" value="<?php echo $vou_ot_trav; ?>">
                            </div>
                        </div>
                        <div class="name_cont news-item" data-category="Cat1, Cat3">
                            <label for="vou_bod_ldg" class="col-md-8 col-form-label text-nowrap">Boarding and
                                Lodging</label>
                            <div class="col">
                                <input type="number" class="form-control form-control-md expense-input" id="vou_bod_ldg"
                                    name="vou_bod_ldg" value="<?php echo $vou_bod_ldg; ?>">
                            </div>
                        </div>
                    </div>
                </div><br>

                <script>
                    $(document).ready(function () {
                        $('#addRow').click(function () {
                            var newRow = `
                                        <div class="name_count row">
                                            <div class="col-sm">
                                                <label for="jc_num" class="col-form-label text-nowrap">JC no</label>
                                                <input type="text" class="form-control form-control-sm" name="jc_num[]" value="<?php echo $jc_num; ?>">
                                            </div>
                                            <div class="col-sm">
                                                <label for="given_emp_id" class="col-form-label text-nowrap">EMP Name</label>
                                                <select required name="given_emp_id[]" class="form-control" data-live-search="false">
                                                    <?php
                                                    $employelist = $mysqli->query("SELECT * FROM employee where employee_status = 'Active'") or die($mysqli);
                                                    while ($empdetail = $employelist->fetch_assoc()):
                                                        $emp_id = $empdetail['employee_id'];
                                                        $emp_name = $empdetail['employee_name'];
                                                        ?>
                                                            <option value="<?php echo $emp_id; ?>" <?php if ($row_update['given_emp_id'] == $emp_id)
                                                                   echo 'selected'; ?>>
                                                                <?php echo $emp_name; ?>
                                                            </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="col-sm">
                                                <label for="given_amt" class="col-form-label text-nowrap">Given Amount</label>
                                                <input type="text" class="form-control form-control-sm" name="given_amt[]" value="<?php echo $given_amt; ?>">
                                            </div>
                                            <div class="col-sm">
                                                <label for="given_proof_type" class="col-form-label text-nowrap">Given Proof</label>
                                                <input type="file" class="form-control form-control-sm" name="uploadfile[]">
                                                <div><?php echo $stored_images; ?></div>
                                            </div>
                                            <div class="col-sm d-flex align-items-end">
                                                <button type="button" class="btn btn-danger removeRow">Remove</button>
                                            </div>
                                        </div>
                                    `;
                            $('#formContainer').append(newRow);
                        });

                        $(document).on('click', '.removeRow', function () {
                            $(this).closest('.name_count').remove();
                        });
                    });
                </script>
                <div class="sec_container">
                    <div id="formContainer">
                        <?php

                        $result_adv = $mysqli->query("SELECT * FROM emp_adv_given WHERE s_no = '$serial_no'");
                        while ($row_update = $result_adv->fetch_assoc()):
                            ?>
                            <div class="name_count row">
                                <div class="col-sm">
                                    <label for="jc_num" class="col-form-label text-nowrap">JC no</label>
                                    <input type="text" class="form-control form-control-sm" name="jc_num[]"
                                        value="<?php echo $row_update['jc_num']; ?>">
                                </div>
                                <div class="col-sm">
                                    <label for="given_emp_id" class="col-form-label text-nowrap">EMP Name</label>
                                    <select required name="given_emp_id[]" class="form-control" data-live-search="false">
                                        <?php
                                        $employelist = $mysqli->query("SELECT * FROM employee where employee_status = 'Active'") or die($mysqli);
                                        while ($empdetail = $employelist->fetch_assoc()):
                                            $emp_id = $empdetail['employee_id'];
                                            $emp_name = $empdetail['employee_name'];
                                            ?>
                                            <option value="<?php echo $emp_id; ?>" <?php if ($row_update['given_emp_id'] == $emp_id)
                                                   echo 'selected'; ?>>
                                                <?php echo $emp_name; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <label for="given_amt" class="col-form-label text-nowrap">Given Amount</label>
                                    <input type="text" class="form-control form-control-sm" name="given_amt[]"
                                        value="<?php echo $row_update['given_amt'] ?>">
                                </div>
                                <div class="col-sm">
                                    <label for="given_proof_type" class="col-form-label text-nowrap">Given Proof</label>
                                    <input type="file" class="form-control form-control-sm" name="uploadfile[]">
                                    <div><?php echo $row_update['file_path']; ?></div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <button type="button" id="addRow" class="btn btn-primary mt-2">Add Row</button>
                </div>
                <br>

                <div class="sub_btn">
                    <button class="btn btn-success" type="submit" name="upload">Update</button>
                </div>
            </form>
            <br>

        </div>





        <script>
            $('select#vou_type').change(function () {
                var filter = $(this).val()
                filterList(filter);
            });

            //News filter function
            function filterList(value) {
                var list = $(".news-list .news-item");
                $(list).fadeOut("fast");
                if (value == "All") {
                    $(".news-list").find("div").each(function (i) {
                        $(this).delay(200).slideDown("fast");
                    });
                } else {
                    //Notice this *=" <- This means that if the data-category contains multiple options, it will find them
                    //Ex: data-category="Cat1, Cat2"
                    $(".news-list").find("div[data-category*=" + value + "]").each(function (i) {
                        $(this).delay(200).slideDown("fast");
                    });
                }
            }
        </script>
    <?php } else {
        if (isset($_GET['vou_date'])) {
            $vou_date = $_GET['vou_date'];
        } else {
            date_default_timezone_set("Asia/Kolkata");
            $vou_date = date('Y-m-d');
        }
        ;
        if (isset($_GET['emp_id'])) {
            $emp_id = $_GET['emp_id'];
        }
        if (isset($_GET['vou_format'])) {
            $vou_format = $_GET['vou_format'];
        }
        ?>
        <form id="vou_form"
            action="voucher_save.php?save=vou_adv<?php echo $editvoucher; ?>&serial_no=<?php echo $serial_no; ?>&vou_format=<?php echo $vou_format; ?>"
            method="POST" enctype="multipart/form-data">
            <div class="heading_container">
                <h2>ACS - Reiumbersement Voucher</h2>
            </div>

            <div class="sub_heading">
                <h4>Voucher details :</h4>
            </div>
            <?php

            $result_adv = $mysqli->query("SELECT * FROM emp_adv_given WHERE vou_date = '$vou_date' AND vou_format = '$vou_format' AND emp_id = '$emp_id'");
            $row_update = $result_adv->fetch_assoc();
            ?>
            <div class="head_container">
                <div class="name_cont">
                    <label for="emp_name " class="col-md-8 col-form-label text-nowrap">Employee Name</label>
                    <div class="col">
                        <input type="text" class="form-control form-control-md" id="emp_name" name="emp_name"
                            value="<?php echo $row_update['emp_name']; ?>" readonly>
                    </div>
                </div>
                <div class="name_cont">
                    <label for="emp_id " class="col-md-8 col-form-label text-nowrap">Employee ID</label>
                    <div class="col">
                        <input type="text" class="form-control form-control-md" id="emp_id" name="emp_id"
                            value="<?php echo $row_update['emp_id']; ?>" readonly>
                    </div>
                </div>
                <div class="name_cont">
                    <label for="vou_date" class="col-md-8 col-form-label text-nowrap"> Voucher date</label>
                    <div class="col">
                        <input type="date" class="form-control form-control-md" id="vou_date" name="vou_date"
                            value="<?php echo $vou_date; ?>">
                    </div>
                </div>
                <div class="name_count">
                    <div class="col">
                        <label for="given_emp_id" class="col-form-label text-nowrap">EMP Name</label>
                        <select required name="given_emp_id" class="form-control" data-live-search="false">
                            <?php
                            $employelist = $mysqli->query("SELECT * FROM employee where employee_status = 'Active'") or die($mysqli);
                            while ($empdetail = $employelist->fetch_assoc()):
                                $emp_id = $empdetail['employee_id'];
                                $emp_name = $empdetail['employee_name'];
                                ?>
                                <option value="<?php echo $emp_id; ?>" <?php if ($row_update['given_emp_id'] == $emp_id)
                                       echo 'selected'; ?>>
                                    <?php echo $emp_name; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="name_cont">
                    <label for="given_amt" class="col-md-8 col-form-label text-nowrap">Given Amount</label>
                    <div class="col">
                        <input type="number" class="form-control form-control-md" id="given_amt" name="given_amt"
                            value="<?php echo $row_update['given_amt']; ?>">
                    </div>
                </div>
                <div class="name_cont">
                    <textarea class="form-control" name="adv_details"
                        id="adv_details"><?php echo $row_update['adv_details']; ?></textarea>
                </div>
                <div class="sub_btn">
                    <button class="btn btn-success" type="submit" name="upload">Update</button>
                </div>
            </div>
        </form>
    <?php } ?>
</body>

</html>