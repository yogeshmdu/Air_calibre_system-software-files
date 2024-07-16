<!-- ACS database connection -->
<?php include "web_acsdb.php";

// To get the ahu seq no
$ahu_seq = "";
$selectedReportId = "";
if (isset($_GET["ahu_seq_no"])) {
    $ahu_seq = $_GET["ahu_seq_no"];
    $selectedReportId = $_GET["report_id"];
     
}
// To delete the AHU room details
$deleahu = "DELETE FROM reports_ahu_details WHERE ahu_seq_no = $ahu_seq ";

if ($mysqli->query($deleahu) == TRUE) {
    echo "Record deleted successfully";
    header("Location: rep_datasheet.php?report_id=$selectedReportId");  
} else {
    echo "Error deleting record: " . $mysqli;
}

$mysqli->close();
?>

