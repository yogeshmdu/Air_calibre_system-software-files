<?php require 'web_acsdb.php';

$selectedReportId = "";
if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET["report_id"];
}


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Temp RH report group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
</head>

<body>
    <div class="main_container text-center">
        <h3>JC-<?php echo ltrim($selectedReportId, 'REP_'); ?> Temp RH List </h3>
    </div>
    <?php

    $testing_date_query = ("SELECT testing_date, report_id, COUNT(*) AS test_count FROM reports_ahu_details WHERE report_id ='$selectedReportId' AND temp_rh_qty > 0 GROUP BY testing_date ORDER BY testing_date") or die($mysqli);
    $test_file = $mysqli->query($testing_date_query);
    $count_of_files = mysqli_num_rows($test_file);
    if ($count_of_files > 0) {
    $serial = 1;
    while ($test_data = $test_file->fetch_assoc()):

        $testing_date = $test_data['testing_date'];
        $report_id = $test_data['report_id'];
        $test_count = $test_data['test_count'];

        ?>
        <ul class="list-group align-items-center">
            <li class="list-group-item d-flex justify-content-between align-items-center w-50">
                <div class="ms-2 me-auto d-flex">
                    <a style="text-decoration:none;"
                        href="Temprhreport.php?report_id=<?php echo $report_id; ?>&testing_date=<?php echo $testing_date; ?>&total_count=<?php echo $total_count * 1; ?>">
                        <div class="fw-bold list-group-item-action ">
                            <?php echo $serial++; ?> &nbsp; &rarr;
                            <?php echo $testing_date;
                            $total_count += $test_count; ?>
                        </div>
                    </a>
                </div>
                <span class="badge bg-primary rounded-pill"><?php echo $test_count; ?></span>
            </li>
        </ul>


    <?php endwhile; } else { ?>
        <br><br>
        <h6 style="text-align:center"> We Don't have any Data </h6>

    <?php } ?>
</body>

</html>