<?php
include('web_acsdb.php');

$selectedReportId = "";
if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET["report_id"];
}

$iqc_testing_dateformat = "";
if (isset($_GET["iqc_date"])) {
    $iqc_testing_dateformat = $_GET["iqc_date"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQC Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/185b985911.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
    <style>
        .main_internal {
            padding-top: 0px;
            margin-bottom: 0px;
        }

        h2 {
            padding-top: 20px;
            text-align: center;
            padding-left: 600px;
            text-wrap: nowrap;
        }

        .view_btn {
            padding-top: 22px;
            padding-left: 240px;
        }

        .save_btn {
            padding-top: 22px;
            padding-left: 30px;
            float: right;
        }

        .btn {
            padding: 9px 33px 9px 33px;
            font-size: medium;
            text-wrap: nowrap;
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
            /* background: red; */
            width: 30px;
            table-layout: space_between;
        }

        #list-names {
            border: none;
            width: 95%;
        }

        select#list-names {
            border: 0px;
            outline: 0px;
        }

        #list-names_2 {
            border: none;
            width: 95%;
        }

        select#list-names_2 {
            border: 0px;
            outline: 0px;
        }

        #text_td {
            border: none;
            width: 95%;
        }

        input[type=text],
        select {
            width: 100%;
            padding: 12px 20px;
            margin: 0px 0;
            border: 0px;
            outline: 0px;
        }

        .list-group {
            padding-top: 9%;
            margin-left: 26%;
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
    <hr>
    <div class="main_ptr">
        <div class="back_icon"><a href="internal_qty_ctrl.php?report_id=<?php echo $selectedReportId; ?>">
            <i id="bck-icn" class="fa-solid fa-circle-arrow-left"></i></a>
        </div>
        <!-- <h1>Job service report</h1> -->
    </div>
    <hr>

    <div class="table table-bordered">
        <div class="list-group mt-0 col-6">
            <?php
            
            $datequery = "SELECT * FROM internal_qty_ctrl WHERE report_id='$selectedReportId' GROUP BY iqc_date";
            $resultdate = $mysqli->query($datequery);
            $number = 0;
            while ($daterow = $resultdate->fetch_assoc()):
                $iqc_testing_dateformat = $daterow['iqc_date'];
                $tested_reportid = $daterow['report_id'];

                ?>
                <p><?php echo $tested_reportid; ?></p>
                <a href="iqc_final_view.php?&iqc_date=<?php echo $iqc_testing_dateformat;?>&report_id=<?php echo $selectedReportId; ?>"
                    class="list-group-item list-group-item-action list-group-item-primary">
                    <?php echo $iqc_testing_dateformat; echo '&'; echo $tested_reportid; ?>
                </a>
            <?php endwhile; ?>
        </div>

    </div>

</body>

</html>