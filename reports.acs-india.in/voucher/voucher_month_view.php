<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monthly view</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .main_container {
            width: 98%;
            height: 80%;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
            box-sizing: border-box;
            margin: 3% auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }


        th,
        td {
            padding: 4px 8px;
            font-size: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        td {
            background-color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <?php
    $emp_id = "";
    if (isset($_GET['emp_id'])) {
        $emp_id = $_GET['emp_id'];
    }
    $emp_name = "";
    if (isset($_GET['emp_name'])) {
        $emp_name = $_GET['emp_name'];
    }


    include 'web_acsdb.php';

    // Fetch given amounts and store them in an array mapped by date
    $given_amounts = [];
    $vou_given_query = ("SELECT vou_date, given_amt FROM emp_adv_given WHERE emp_id = '$emp_id'");
    $vou_given_result = $mysqli->query($vou_given_query);
    while ($row = $vou_given_result->fetch_assoc()) {
        $given_amounts[$row['vou_date']][] = $row['given_amt'];
    }
    ?>

    <div class="main_container">
        <h3><?php echo $emp_name; ?> Monthly Details</h3>
       
        <br>
        <div class="mb-3 row">
            <label for="employeeName" class="col-sm-3 col-form-label">Employee Name:</label>
            <div class="col-sm-4 mt-2">
                <?php echo $emp_name; ?>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="employeeID" class="col-sm-3 col-form-label">Employee ID:</label>
            <div class="col-sm-4 mt-2">
                <?php echo $emp_id; ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Opening Bal</th>
                    <th>GST Bills</th>
                    <th>Tools</th>
                    <th>Xerox</th>
                    <th>Local Travel</th>
                    <th>Petrol</th>
                    <th>Vehicle Service</th>
                    <th>Outstation Travel</th>
                    <th>Boarding & Lodging</th>
                    <th>Staff Welfare</th>
                    <th>Others</th>
                    <th>Given Amount</th>
                    <th>Voucher details</th>
                    <th>Day closing</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $vou_query = ("SELECT * FROM emp_vou_details WHERE emp_id = '$emp_id' ORDER BY vou_date ASC");
                $vou_query_result = $mysqli->query($vou_query);

                while ($vou_emp = $vou_query_result->fetch_assoc()):
                    $seri_no = $vou_emp['serial_no'];
                    $vou_emp_id = $vou_emp['emp_id'];
                    $vou_emp_name = $vou_emp['emp_name'];
                    $selec_vou_date = $vou_emp['vou_date'];
                    $emp_vou_ob = $vou_emp['vou_ob'];
                    $emp_vou_parti = $vou_emp['vou_particulares'];
                    $emp_vou_gst = $vou_emp['vou_gst'];
                    $emp_vou_xerox = $vou_emp['vou_xerox'];
                    $emp_vou_others = $vou_emp['vou_others'];
                    $emp_vou_sundry = $vou_emp['vou_sundry'];
                    $emp_vou_tools = $vou_emp['vou_tools'];
                    $emp_vou_trav = $vou_emp['vou_ot_trav'];
                    $emp_vou_bod = $vou_emp['vou_bod_ldg'];
                    $emp_vou_lcl = $vou_emp['vou_lcl_trav'];
                    $emp_vou_petr = $vou_emp['vou_petrol'];
                    $emp_vou_ser = $vou_emp['vou_vh_service'];
                    $total_exp += $emp_vou_gst + $emp_vou_xerox + $emp_vou_others + $emp_vou_sundry + $emp_vou_tools + $emp_vou_trav + $emp_vou_bod + $emp_vou_lcl + $emp_vou_petr + $emp_vou_ser;

                    // Retrieve the given amounts for the current date
                    $emp_vou_giv = isset($given_amounts[$selec_vou_date]) ? implode(",", $given_amounts[$selec_vou_date]) : '';

                    $day_clsing_bal = $emp_vou_ob - $total_exp;
                    ?>
                    <tr>
                        <td><?php echo $selec_vou_date; ?></td>
                        <td><?php echo $emp_vou_ob; ?></td>
                        <td><?php echo $emp_vou_gst; ?></td>
                        <td><?php echo $emp_vou_tools; ?></td>
                        <td><?php echo $emp_vou_xerox; ?></td>
                        <td><?php echo $emp_vou_lcl; ?></td>
                        <td><?php echo $emp_vou_petr; ?></td>
                        <td><?php echo $emp_vou_ser; ?></td>
                        <td><?php echo $emp_vou_trav; ?></td>
                        <td><?php echo $emp_vou_bod; ?></td>
                        <td><?php echo $emp_vou_sundry; ?></td>
                        <td><?php echo $emp_vou_others; ?></td>
                        <td style="width: 100px;"><?php echo $emp_vou_giv; ?></td>
                        <td><?php echo $emp_vou_parti; ?></td>
                        <td><?php echo $day_clsing_bal; ?></td>
                        <td><?php echo $total_exp; ?></td>
                    </tr>

                <?php endwhile; 

                $vou_query = ("SELECT * FROM emp_vou_details WHERE emp_id = '$emp_id'");
                $vou_query_result = $mysqli->query($vou_query);

                while ($vou_emp = $vou_query_result->fetch_assoc()) {

                    $emp_ob += $vou_emp['vou_ob'];
                    $emp_parti = $vou_emp['vou_particulares'];
                    $emp_gst += $vou_emp['vou_gst'];
                    $emp_xerox += $vou_emp['vou_xerox'];
                    $emp_others += $vou_emp['vou_others'];
                    $emp_sundry += $vou_emp['vou_sundry'];
                    $emp_tools += $vou_emp['vou_tools'];
                    $emp_trav += $vou_emp['vou_ot_trav'];
                    $emp_bod += $vou_emp['vou_bod_ldg'];
                    $emp_lcl += $vou_emp['vou_lcl_trav'];
                    $emp_petr += $vou_emp['vou_petrol'];
                    $emp_ser += $vou_emp['vou_vh_service'];
                    $total_exp_3 += $emp_vou_giv + $emp_gst + $emp_xerox + $emp_others + $emp_sundry + $emp_tools + $emp_trav + $emp_bod + $emp_lcl + $emp_petr + $emp_ser;

                    // Retrieve the given amounts for the current date
                    $emp_giv = isset($given_amounts[$selec_date]) ? implode(",", $given_amounts[$selec_vou_date]) : '';


                }
                ?>
                <tr>
                    <td style="font-weight: bold;">Total</td>
                    <td><?php echo $emp_ob; ?></td>
                    <td><?php echo $emp_gst; ?></td>
                    <td><?php echo $emp_tools; ?></td>
                    <td><?php echo $emp_xerox; ?></td>
                    <td><?php echo $emp_lcl; ?></td>
                    <td><?php echo $emp_petr; ?></td>
                    <td><?php echo $emp_ser; ?></td>
                    <td><?php echo $emp_trav; ?></td>
                    <td><?php echo $emp_bod; ?></td>
                    <td><?php echo $emp_sundry; ?></td>
                    <td><?php echo $emp_others; ?></td>
                    <td><?php echo $emp_giv; ?></td>
                    <td></td>
                    <td><?php echo $day_clsing_bal; ?></td>
                    <td><?php echo $total_exp_3; ?></td>

                </tr>
            </tbody>
        </table>
        <br>

        <div class="mb-3 row">
            <label for="voucherClosingBalance" class="fw-bold col-sm-3 col-form-label">Voucher Closing Balance:</label>
            <strong class="col-sm-4 mt-2">
                <?php
                $vou_closing_balance = $emp_ob - $total_exp_3;
                echo $vou_closing_balance; ?>
            </strong>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>