<!-- ######## DATABASE CONNECTION ######## -->
<?php require 'web_acsdb.php'; ?>

<!--============================== TO GET THE REPORT_ID ==============================-->
<?php

$selectedReportId = "";
if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET["report_id"];

}

// ============================== TO GET JOB CARD NUM AND LOCATION ==============================
$query = ("SELECT crm.job_card_no, cust.customer_id, cust.customer_name,  cust.area, cust.city, crm.report_id, cust.address1, cust.address2
                FROM customer_reports_master crm, customer cust
                WHERE crm.report_id='$selectedReportId' AND crm.customer_id = cust.customer_id;") or die($mysqli);
$result = $mysqli->query($query);
$row = $result->fetch_assoc();

$area = $row['area'];
$city = $row['city'];
$address1 = $row['address1'];
$address2 = $row['address2'];
$customer_name = $row['customer_name'];
$job_card_no = $row['job_card_no'];

$comma = ", ";
$cust_ads = $address1 . $comma . $address2 . $comma . $area . $comma . $city;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/14a4966bb0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/185b985911.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
    <style>
        .main_ptr {
            display: flex;
        }

        h1 {
            padding-left: 37%;
            padding-top: 7px;
            font-size: x-large;

        }

        #table_item {
            display: flex;
            width: 100%;
            justify-content: center;
        }

        @media (max-width: 600px) {
            #table_item {
                width: 150%;
                margin-left: -120px;
            }

            h1 {
                padding-top: 9px;
                padding-left: 80px;
            }

            .back_icon {
                color: #140c5e;
            }

            #bck-icn {
                padding-top: 11px;
                padding-left: 40px;
                color: #212529;
                font-size: x-large;
            }
        }

        @media (max-width: 992px) {
            #table_item {
                width: 130%;
                margin-left: -80px;
            }

            .back_icon {
                color: #140c5e;
            }

            #bck-icn {
                padding-left: 40px;
                color: #212529;
                font-size: x-large;
            }

        }

        .back_icon {
            color: #140c5e;
        }

        #bck-icn {
            padding-top: 11px;
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
    <br><br>
    <hr>
    <div class="main_ptr">
        <div class="back_icon"><a href="index.php">
                <i id="bck-icn" class="fa-solid fa-circle-arrow-left"></i></a>
        </div>

        <h1>Job service report</h1>
        <br><br>
    </div>
    <hr>

    <?php




    $datequery = "SELECT report_id, testing_date FROM reports_ahu_details where report_id = '$selectedReportId' GROUP BY testing_date HAVING testing_date";
    $resultdate = $mysqli->query($datequery);
    $number = 0;
    while ($daterow = $resultdate->fetch_assoc()):
        $testing_dateformat = $daterow['testing_date'];



        ?>
        <div id="table_item">
            <div class="px-3 mt-3">
                <?php echo ++$number; ?>
            </div>

            <div class="list-group px-2 mt-2 col-6">

                <?php
                // $jc_count = "SELECT * FROM employee_job_list WHERE job_date = '$testing_dateformat'";
                // $jc_result_ct = $mysqli->query($jc_count);
                // $jc_value_ct = $jc_result_ct->fetch_assoc();
                // foreach ($jc_value_ct as $jc_res_ct) {
                //     $print_count = $jc_res_ct['shift_status'];
                //     // echo $print_count;
                // }
                ?>
                <a href="jc_view_report.php?report_id=<?php echo $row['report_id']; ?>&test_date=<?php echo $testing_dateformat; ?>"
                    class="list-group-item w-100 list-group-item-action list-group-item-danger"> Job closing Date order
                    <?php echo $testing_dateformat; ?>
                </a>
            </div>

            <div class="px-2 mt-2 ">
                <a
                    href="job_list.php?report_id=<?php echo $row['report_id']; ?>&test_date=<?php echo $testing_dateformat; ?>&job_card_no=<?php echo $job_card_no; ?>&customer_name=<?php echo $customer_name; ?>&cust_address=<?php echo $cust_ads; ?>">
                    <button type="button" class="w-100 btn ">
                        <i class="fa-solid fa-user-pen" style="color: #df1111;"></i>
                    </button>
                </a>
            </div>
        </div>
    <?php endwhile; ?>

    <!-- <div class="d-grid gap-2 col-6 mx-auto">
        <br>
        <a href="job_closing_form.php"></a>
        <button class="btn btn-dark"><i class="fa-solid fa-plus fa-bounce fa-lg"></i></button>
    </div> -->

    <script>
        const d = new Date();
        document.getElementById("datepro").innerHTML = d;
    </script>
</body>

</html>