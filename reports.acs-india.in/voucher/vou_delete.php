<?php
include "web_acsdb.php"; 

// Check if the required GET parameters are set
if (isset($_GET['sl_no']) && isset($_GET['emp_id']) && isset($_GET['vou_format'])) {
    $sl_no = $mysqli->real_escape_string($_GET['sl_no']);
    $emp_id = $mysqli->real_escape_string($_GET['emp_id']);
    $vou_format = $mysqli->real_escape_string($_GET['vou_format']);

    if($vou_format == 1){
        // DELETE query
        $delemp_det = "DELETE FROM emp_adv_given WHERE serial_no='$sl_no'";

        // Execution
        if ($mysqli->query($delemp_det) === TRUE) {
            echo "Record deleted successfully";
            // Redirect after successful deletion
            header("Location: voucher_files.php?vou_format=$vou_format&emp_id=$emp_id");
            exit();
        } else {
            echo "Error deleting record: " . $mysqli->error;
        }
    } else {
        // DELETE query
        $delemp_det = "DELETE FROM emp_vou_details WHERE serial_no='$sl_no'";

        // Execution
        if ($mysqli->query($delemp_det) === TRUE) {
            echo "Record deleted successfully";
            // Redirect after successful deletion
            header("Location: voucher_files.php?vou_format=$vou_format&emp_id=$emp_id");
            exit();
        } else {
            echo "Error deleting record: " . $mysqli->error;
        }
    }
} else {
    echo "Required parameters are missing";
}

// Close the database connection
$mysqli->close();
