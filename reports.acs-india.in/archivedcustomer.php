<?php require_once 'web_acsdb.php';


$selectedReportId = "";
if (isset($_GET["report_id"])) {
	$selectedReportId = $_GET["report_id"];
	echo '<script>alert("Viewing for Report Id: ' . $selectedReportId . '")</script>';
}

$archivedsql = "UPDATE customer_reports_master SET archived ='1' WHERE report_id = '$selectedReportId' ";

if ($mysqli->query($archivedsql) === TRUE) {
	// echo "Record archived successfully";
	header("Location: index.php");
} else {
	echo "Record can't archived";
}

$mysqli->close();

?>
