<?php
include("web_acsdb.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
    <script src="https://kit.fontawesome.com/185b985911.js" crossorigin="anonymous"></script>
        <style>
            /* @media (max-width: 776px) {
                .txt_bx_resp{
                    width: 86%;
                }
            } */
            /* .container{
                width: 100%;
            } */
            .main_ptr{
                display: flex;
            }
            h3{
                margin-top: 10px;
                padding-left: 33%;
            }
            /* .txt_bx_resp{
                
            } */
            @media (max-width: 767.98px) {
                .text_resp{
                    font-size: small;
                }
                td{
                font-size: small;
               }
            }
            @media (max-width: 767.98px) {
                .text_resp{
                    font-size: small;
                }
                .txt_bx_resp{
                    width: 50%;
                }
                td{
                font-size: small;
                /* text-align: center; */
               }
            }
            @media (max-width: 992px) {
                .text_resp{
                    font-size: small;
                }
               td{
                font-size: small;
                /* text-align: center; */
               }
            }
            @media (max-width: 1090px) {
                .text_resp{
                    font-size: small;
                }
            }
     .back_icon{
    /* padding-bottom: 66px; */
    color: #140c5e;
   }
    #bck-icn{
    padding-top: 15px;
    margin-right: 100%;
    /* padding-left: 50px; */
    /* padding-right: 610px; */
    padding-left: 40px;
    color: #212529;
    font-size: x-large;
}
            
        </style>
</head>

<body>
<!-- <div class="col-md-2">
           <a href="index.php" class="btn btn-primary text-nowrap my-4 mx-3" role="button"><< Back</a>
                </div> -->
                <!-- <div class="container"> -->
                <hr>
        <div class="main_ptr">
       <div class="back_icon"><a href="index.php">
               <i id="bck-icn" class="fa-solid fa-circle-arrow-left"></i></a>
               </div>
    
    <h3>Instrument Usage Per Month</h3>
      </div>
      <hr>

    <div class="col-md-4">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <select name="selectinst" id="selectinst" class="form-control input-sm txt_bx_resp" onchange="this.form.submit()">
                <option value="" selected>Get All</option>
                <?php
                require('web_acsdb.php');
                $result1 = $mysqli->query("SELECT serial_no, instrument_used FROM instrument_details");
                while ($row1 = $result1->fetch_assoc()) {
                    $output = "<option value='" . $row1['serial_no'] . "'";

                    if ($_POST['selectinst'] == $row1['serial_no']) {
                        $output .= " selected='selected'";
                        $job_instr = $row1['serial_no'];
                    }
                    $output .= ">" . $row1['serial_no'] . "," . $row1['instrument_used'] . "</option>";
                    echo $output;
                }


                ?>
            </select>
        </form><br>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $inst_no = htmlspecialchars($_REQUEST['selectinst']);
        }
        ?>
    </div>
    </div>

    <table class="table table-hover">
        <thead>
            <tr class="bg-dark" style="color: white;">
                <th scope="col">Job_date</th>
                <th scope="col">Job_card_no</th>
                <th scope="col">Customer_Name</th>
                <th scope="col">Instrument_Name</th>
                <th scope="col">Ins_Model</th>
                <th scope="col">Ins_Serial</th>
                <th scope="col">Indendification_no</th>
                <th scope="col">Engineers_ID</th>

            </tr>

        </thead>
        <?php
        if (empty(!$inst_no)):
            $emp_instrument_query = ("SELECT * FROM employee_job_list WHERE instr_used LIKE '%" . $job_instr . "%' ");
            $emp_inst_result = $mysqli->query($emp_instrument_query);



            while ($emp_inst_details = $emp_inst_result->fetch_assoc()):
                $instrument_array = "";
                $instrument_array = $emp_inst_details['instr_used'];
                if ($instrument_array == null) {
                    continue; //Skip the below steps and continue with loop
                }
                $customer_name = $emp_inst_details['customer_name'];
                $job_date = $emp_inst_details['job_date'];
                $job_card_no = $emp_inst_details['job_card_no'];
                $tested_name = $emp_inst_details['testing_engg'];
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
                $instrument_det = ("SELECT * FROM instrument_details WHERE serial_no IN ($job_instr)");
                $inst_result = $mysqli->query($instrument_det);
                while ($inst_details = $inst_result->fetch_assoc()):

                    $instr_name = $inst_details['instrument_used'];
                    $serial_no = $inst_details['serial_no'];
                    $make = $inst_details['instrument_make'];
                    $model = $inst_details['instrument_model'];
                    $instr_id = $inst_details['instrument_id'];
                    $instr_sno = $inst_details['instrument_sno'];
                    ?>
                    <tbody>
                        <tr>
                            <td class="text_resp">
                                <?php echo $job_date; ?>
                            </td>
                            <td>
                                <?php echo $job_card_no; ?>
                            </td>
                            <td class="text_resp">
                                <?php echo $customer_name; ?>
                            </td>
                            <td class="text_resp">
                                <?php echo $instr_name; ?>
                            </td>
                            <td>
                                <?php echo $model; ?>
                            </td>
                            <td>
                                <?php echo $instr_id; ?>
                            </td>
                            <td>
                                <?php echo $instr_sno; ?>
                            </td>
                            <td>
                                <?php echo $tested_by; ?>
                            </td>

                        </tr>
                    </tbody>

                <?php endwhile;
            endwhile; ?>
        <?php else: ?>
            <?php
            $emp_instrument_query = ("SELECT * FROM employee_job_list ORDER BY job_date");
            $emp_inst_result = $mysqli->query($emp_instrument_query);


            while ($emp_inst_details = $emp_inst_result->fetch_assoc()):
                $instrument_array = "";
                $instrument_array = $emp_inst_details['instr_used'];
                if ($instrument_array == null) {
                    continue; //Skip the below steps and continue with loop
                }
                $customer_name = $emp_inst_details['customer_name'];
                $job_date = $emp_inst_details['job_date'];
                $job_card_no = $emp_inst_details['job_card_no'];
                $tested_name = $emp_inst_details['testing_engg'];
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
                $instrument_det = ("SELECT * FROM instrument_details WHERE serial_no IN ($instrument_array)");
                $inst_result = $mysqli->query($instrument_det);
                while ($inst_details = $inst_result->fetch_assoc()):

                    $instr_name = $inst_details['instrument_used'];
                    $serial_no = $inst_details['serial_no'];
                    $make = $inst_details['instrument_make'];
                    $model = $inst_details['instrument_model'];
                    $instr_id = $inst_details['instrument_id'];
                    $instr_sno = $inst_details['instrument_sno'];
                    ?>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $job_date; ?>
                            </td>
                            <td>
                                <?php echo $job_card_no; ?>
                            </td>
                            <td>
                                <?php echo $customer_name; ?>
                            </td>
                            <td>
                                <?php echo $instr_name; ?>
                            </td>
                            <td>
                                <?php echo $model; ?>
                            </td>
                            <td>
                                <?php echo $instr_id; ?>
                            </td>
                            <td>
                                <?php echo $instr_sno; ?>
                            </td>
                            <td>
                                <?php echo $tested_by;  ?>
                            </td>
                        </tr>
                    </tbody>

                <?php endwhile;
            endwhile; ?>
        <?php endif; ?>
    </table>
</body>

</html>