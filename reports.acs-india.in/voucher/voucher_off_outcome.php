<?php
include 'web_acsdb.php';
$emp_id = "";
if (isset($_GET['emp_id'])) {
    $emp_id = $_GET['emp_id'];
}
$emp_name = "";
if (isset($_GET['emp_name'])) {
    $emp_name = $_GET['emp_name'];
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Received Amount view</title>
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



    $vou_given_query = ("SELECT * FROM emp_adv_given WHERE emp_id = '$emp_id' AND vou_format = 1");
    $vou_given_result = $mysqli->query($vou_given_query);
    // $serial = 1;
    


    $serial = 1;
    ?>
    <div class="main_container">
        <br>
        <div class="mb-3 row">
            <label for="employeeName" class="col-sm-3 col-form-label">Employee Name :</label>
            <div class="col-sm-4 mt-2">
                <?php echo $emp_name; ?>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="employeeID" class="col-sm-3 col-form-label">Employee ID :</label>
            <div class="col-sm-4 mt-2">
                <?php echo $emp_id; ?>
            </div>
        </div>

        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>S.no</th>
                    <th>Date</th>
                    <th>Received From</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php

                while ($vou_emp_give = $vou_given_result->fetch_assoc()):

                    $emp_vou_giv = $vou_emp_give['given_amt'];
                    $vou_date = $vou_emp_give['vou_date'];
                    $receiver_id = $vou_emp_give['given_emp_id'];
                    $emp_vou_total += $vou_emp_give['given_amt'];

                    $get_emp_query = $mysqli->query("SELECT employee_name, employee_id FROM employee WHERE employee_id = '$receiver_id'");
                    $get_emp_result = $get_emp_query->fetch_assoc();
                    $receiver_name = $get_emp_result['employee_name'];

                    ?>
                    <tr>
                        <td><?php echo $serial++; ?></td>
                        <td><?php echo $vou_date; ?></td>
                        <td><?php echo $receiver_name; ?></td>
                        <td><?php echo $emp_vou_giv; ?></td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="mb-3 row">
            <label for="voucherClosingBalance" class="col-sm-3 col-form-label text-nowrap" style="font-weight: bold;">
                Total received amount :</label>
            <strong class="col-sm-4 mt-2">
                <?php

                echo $emp_vou_total;
                ?>
            </strong>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>