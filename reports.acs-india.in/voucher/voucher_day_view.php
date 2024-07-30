<?php
include 'web_acsdb.php';
$serial_no = "";
if (isset($_GET['serial_no'])) {
    $serial_no = $_GET['serial_no'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Day view</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .main_container {
            width: 800px;
            height: auto;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
            box-sizing: border-box;
            margin: 3% auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        .row {
            margin-left: 30px;
        }

        table {
            width: 100%;
            margin: 20px 0;
        }

        th,
        td {
            padding: 4px 8px;
            font-size: 12px;
            text-align: left;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>

</head>

<body>
    <?php

    include 'web_acsdb.php';

    $vou_query = ("SELECT * FROM emp_vou_details WHERE serial_no = '$serial_no'");
    $vou_query_result = $mysqli->query($vou_query);
    // $serial = 1;
    
    while ($vou_emp = $vou_query_result->fetch_assoc()):

        $seri_no = $vou_emp['serial_no'];
        $vou_emp_id = $vou_emp['emp_id'];
        $vou_emp_name = $vou_emp['emp_name'];
        $selec_vou_date = $vou_emp['vou_date'];
        $emp_vou_ob = $vou_emp['vou_ob'];

        $emp_vou_parti = $vou_emp['vou_particulares'];
        $emp_vou_gst = $vou_emp['vou_gst'];
        $emp_vou_xerox = $vou_emp['vou_xerox'];
        $emp_vou_other = $vou_emp['vou_others'];
        $emp_vou_tools = $vou_emp['vou_tools'];
        $emp_vou_trav = $vou_emp['vou_ot_trav'];
        $emp_vou_bod = $vou_emp['vou_bod_ldg'];
        $emp_vou_lcl = $vou_emp['vou_lcl_trav'];
        $emp_vou_petr = $vou_emp['vou_petrol'];
        $emp_vou_sundry = $vou_emp['vou_sundry'];
        $emp_vou_ser = $vou_emp['vou_vh_service'];

        $total_exp += $emp_vou_gst + $emp_vou_tools + $emp_vou_xerox + $emp_vou_other + $emp_vou_trav + $emp_vou_bod + $emp_vou_lcl + $emp_vou_petr + $emp_vou_sundry + $emp_vou_ser;

    endwhile;

    $vou_given_query = ("SELECT * FROM emp_adv_given WHERE s_no = '$serial_no'");
    $vou_given_result = $mysqli->query($vou_given_query);
    // $serial = 1;
    
    while ($vou_emp_give = $vou_given_result->fetch_assoc()):

        $emp_vou_giv += $vou_emp_give['given_amt'];

    endwhile;

    $full_total_exp = $total_exp + $emp_vou_giv;

    ?>
    <div class="main_container">
        <br>
        <div class="mb-3 row">
            <label for="employeeName" class="col-sm-3 col-form-label">Employee Name :</label>
            <div class="col-sm-4 mt-2">
                <?php echo $vou_emp_name; ?>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="employeeID" class="col-sm-3 col-form-label">Employee ID :</label>
            <div class="col-sm-4 mt-2">
                <?php echo $vou_emp_id; ?>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="voucherDate" class="col-sm-3 col-form-label">Voucher Date :</label>
            <div class="col-sm-4 mt-2">
                <?php echo $selec_vou_date; ?>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="voucherOpeningBalance" class="col-sm-3 col-form-label text-nowrap"> Opening Balance :</label>
            <div class="col-sm-4 mt-2">
                <?php echo $emp_vou_ob; ?>
            </div>
        </div>

        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>S.no</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Particulars</td>

                    <td><?php echo $emp_vou_parti; ?></td>
                    <td></td>
                </tr>
                
                <tr>
                    <td>2</td>
                    <td>GST</td>
                    <td></td>
                    <td><?php echo $emp_vou_gst; ?></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Tools</td>
                    <td></td>
                    <td><?php echo $emp_vou_tools; ?></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Xerox</td>
                    <td></td>
                    <td><?php echo $emp_vou_xerox; ?></td>
                </tr>
                
                <tr>
                    <td>5</td>
                    <td>Others</td>
                    <td></td>
                    <td><?php echo $emp_vou_other; ?></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Sundry</td>
                    <td></td>
                    <td><?php echo $emp_vou_sundry; ?></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Local Travel</td>
                    <td></td>
                    <td><?php echo $emp_vou_lcl; ?></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Petrol</td>
                    <td></td>
                    <td><?php echo $emp_vou_petr; ?></td>
                </tr>
                
                <tr>
                    <td>9</td>
                    <td>Vehicle service</td>
                    <td></td>
                    <td><?php echo $emp_vou_ser; ?></td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>Outstation Travel</td>
                    <td></td>
                    <td><?php echo $emp_vou_trav; ?></td>

                </tr>
                <tr>
                    <td>11</td>
                    <td>Boarding lodging</td>
                    <td></td>
                    <td><?php echo $emp_vou_bod; ?></td>

                </tr>
               
                
                <tr>
                    <td>12</td>
                    <td>Given Amount</td>
                    <td></td>
                    <td><?php echo $emp_vou_giv; ?></td>

                </tr>
                <tr>
                    <th></th>
                    <td></td>
                    <th> Total </th>
                    <th><?php echo $full_total_exp; ?></th>
                </tr>
            </tbody>
        </table>

        <div class="mb-3 row">
            <label for="voucherClosingBalance" class="col-sm-3 col-form-label text-nowrap" style="font-weight: bold;"> Closing Balance :</label>
            <strong class="col-sm-4 mt-2">
                <?php
                $closing_bal = $emp_vou_ob - $full_total_exp;
                echo $closing_bal;
                ?>
            </strong>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>