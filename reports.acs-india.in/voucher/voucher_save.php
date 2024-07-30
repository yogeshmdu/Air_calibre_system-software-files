<?php
ob_start();
include ('web_acsdb.php'); // Include your database connection file
if (isset($_GET['vou_format'])) {
    $vou_format = $_GET['vou_format'];
}
if ($vou_format == 1) {
    if ($_GET['save'] == 'vou_adv') {

        // Prepare and execute the insertion query for each data row
        date_default_timezone_set("Asia/Kolkata");
        $creation_date = date('Y-m-d H:i:s');
        $emp_name = $_POST['emp_name'];
        $emp_id = $_POST['emp_id'];
        $vou_format;
        $vou_date = $_POST['vou_date'];
        $jc_num = $_POST['jc_num'];
        $given_emp_id = $_POST['given_emp_id'];
        $given_amt = $_POST['given_amt'];
        $adv_details = $_POST['adv_details'];

        // Insert the data into the database
        $stmt = $mysqli->query("INSERT INTO emp_adv_given (emp_id, emp_name, vou_format, vou_date, jc_num, given_emp_id, given_amt, adv_details, creation_date) 
            VALUES ('$emp_id', '$emp_name', '$vou_format', '$vou_date', '$jc_num', '$given_emp_id', '$given_amt', '$adv_details', '$creation_date')") or die($mysqli);

        $serial_no = $mysqli->insert_id;

        $message = "Voucher Details:  Voucher Id:" . $serial_no . "details successfully Created...";

        if (isset($_GET['edit']) == 'yes') {

            // Update the data in the database
            $stmt_emp_adv = "UPDATE emp_adv_given SET
                emp_name = '$emp_name',
                emp_id = '$emp_id',
                vou_format = '$vou_format',
                vou_date = '$vou_date',
                jc_num = '$jc_num', 
                given_emp_id = '$given_emp_id', 
                given_amt = '$given_amt', 
                adv_details = '$adv_details', 
                creation_date = '$creation_date' WHERE s_no = '$serial_no'";
            $mysqli->query($stmt_emp_adv) or die($mysqli->error);
        }

        // Redirect after processing 
        header("location: voucher_index.php?&vou_format=$vou_format" . "&message=" . $message);
    }
} else {
    if ($_GET['save'] == 'vou_det') {

        // Prepare and execute the insertion query for each data row
        $emp_name = $_POST['emp_name'];
        $emp_id = $_POST['emp_id'];

        $vou_ob = $_POST['vou_ob'];
        $vou_cb = $_POST['vou_cb'];
        $vou_particulares = $_POST['vou_particulares'];
        $vou_gst = $_POST['vou_gst'];
        $vou_tools = $_POST['vou_tools'];
        $vou_xerox = $_POST['vou_xerox'];
        $vou_others = $_POST['vou_others'];
        $vou_sundry = $_POST['vou_sundry'];
        $vou_ot_trav = $_POST['vou_ot_trav'];
        $vou_bod_ldg = $_POST['vou_bod_ldg'];
        $vou_lcl_trav = $_POST['vou_lcl_trav'];
        $vou_petrol = $_POST['vou_petrol'];
        $vou_vh_service = $_POST['vou_vh_service'];

        date_default_timezone_set("Asia/Kolkata");
        $creation_date = date('Y-m-d H:i:s');
        $vou_date = $_POST['vou_date'];

        $serial_no = "";
        if (isset($_GET["serial_no"])) {
            $serial_no = $_GET["serial_no"];
        }
        $vou_format = "";
        if (isset($_GET["vou_format"])) {
            $vou_format = $_GET["vou_format"];
        }
        // Check if the request method is POST
        if (isset($_GET['edit']) == 'yes') {
            // Update query using prepared statement
            $emp_vou_det = "UPDATE emp_vou_details SET  
                        emp_name='$emp_name', 
                        emp_id='$emp_id', 
                        vou_format='$vou_format',
                        vou_ob='$vou_ob',
                        vou_cb='$vou_cb',
                        vou_particulares='$vou_particulares',
                        vou_gst='$vou_gst',
                        vou_tools='$vou_tools',
                        vou_xerox='$vou_xerox',
                        vou_others='$vou_others',
                        vou_sundry='$vou_sundry',
                        vou_ot_trav='$vou_ot_trav',
                        vou_bod_ldg='$vou_bod_ldg',
                        vou_lcl_trav='$vou_lcl_trav',
                        vou_petrol='$vou_petrol',
                        vou_vh_service='$vou_vh_service',
                        vou_date='$vou_date',
                        creation_date='$creation_date' WHERE serial_no='$serial_no'";

            $mysqli->query($emp_vou_det) or die($mysqli);
            $message = "Voucher Details : " . $emp_name . "Serial Id :" . $serial_no . "details updated successfully..,";

            $serial_no = $serial_nos;
            $serial_nos = $_POST['serial_no'];
            $jc_nums = $_POST['jc_num'];
            $emp_ids = $_POST['given_emp_id'];
            $amounts = $_POST['given_amt'];
            $files = $_FILES['uploadfile'];

            for ($i = 0; $i < count($serial_nos); $i++) {
                $serial_no = $mysqli->real_escape_string($serial_nos[$i]);
                $jc_num = $mysqli->real_escape_string($jc_nums[$i]);
                $given_emp_id = $mysqli->real_escape_string($emp_ids[$i]);
                $given_amt = $mysqli->real_escape_string($amounts[$i]);
                $file_path = '';

                // Handle file upload if a new file is provided
                if (!empty($files['name'][$i])) {
                    $file_name = $files['name'][$i];
                    $file_tmp = $files['tmp_name'][$i];
                    $file_path = "uploads/" . basename($file_name);
                    move_uploaded_file($file_tmp, $file_path);
                } else {
                    // Keep existing file path if no new file is uploaded
                    $result = $mysqli->query("SELECT file_path FROM emp_adv_given WHERE s_no = '$serial_no'");
                    $row_upd = $result->fetch_assoc();
                    $file_path = $row_upd['file_path'];
                }

                // Update the data in the database
                $stmt_emp_adv = "UPDATE emp_adv_given SET jc_num = '$jc_num', given_emp_id = '$given_emp_id', given_amt = '$given_amt', file_path = '$file_path' WHERE s_no = '$serial_no'";
                $mysqli->query($stmt_emp_adv) or die($mysqli->error);
            }

            // Redirect after processing 
            header("location: voucher_index.php?serial_no=$serial_no&vou_format=$vou_format" . "&message=" . $message);

        } else {
            date_default_timezone_set("Asia/Kolkata");
            $creation_date = date('Y-m-d H:i:s');

            

            // Insertion query using prepared statement
            $mysqli->query("INSERT INTO emp_vou_details 
                                (emp_name, emp_id, vou_format, vou_ob, vou_cb, vou_particulares, vou_gst, 
                                vou_tools, vou_xerox, vou_others, vou_sundry, vou_ot_trav, vou_bod_ldg, vou_lcl_trav, 
                                vou_petrol, vou_vh_service, vou_date, creation_date)     
                                VALUES ('$emp_name', '$emp_id', $vou_format, '$vou_ob', '$vou_cb', '$vou_particulares', '$vou_gst', '$vou_tools', 
                                '$vou_xerox', '$vou_others', '$vou_sundry', '$vou_ot_trav', '$vou_bod_ldg', '$vou_lcl_trav', 
                                '$vou_petrol', '$vou_vh_service', '$vou_date', '$creation_date')") or die($mysqli);

            $serial_no = $mysqli->insert_id;

            $message = "Voucher Details:  Voucher Id:" . $serial_no . "details successfully Created...";

            $expen_files = $_FILES['uploadfile_exp'];

            if (!empty($expen_files['name'][0])) {
                $file_paths = [];
                foreach ($expen_files['name'] as $key => $file_name_exp) {
                    $file_tmp_exp = $expen_files['tmp_name'][$key];
                    $file_exp = pathinfo($file_name_exp, PATHINFO_EXTENSION);
                    $new_file_name_exp = $serial_no . '_' . uniqid() . '.' . $file_exp;
                    $file_path_exp = "uploads/" . basename($new_file_name_exp);

                    if (move_uploaded_file($file_tmp_exp, $file_path_exp)) {
                        $file_paths[] = $file_path_exp;
                    }
                }

                // Convert array of file paths to a comma-separated string
                $file_paths_str = implode(',', $file_paths);

            }

            // Loop through the arrays of input fields
            $jc_nums = $_POST['jc_num'];
            $emp_ids = $_POST['given_emp_id'];
            $amounts = $_POST['given_amt'];
            $files = $_FILES['uploadfile'];

            for ($i = 0; $i < count($jc_nums); $i++) {
                $jc_num = $mysqli->real_escape_string($jc_nums[$i]);
                $given_emp_id = $mysqli->real_escape_string($emp_ids[$i]);
                $given_amt = $mysqli->real_escape_string($amounts[$i]);

                // Handle file upload
                $file_name = $files['name'][$i];
                $file_tmp = $files['tmp_name'][$i];
                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_file_name = $serial_no . '_' . uniqid() . '.' . $file_ext;
                $file_path = "uploads/" . basename($new_file_name);
                move_uploaded_file($file_tmp, $file_path);

                // Insert the data into the database
                $stmt = $mysqli->query("INSERT INTO emp_adv_given (emp_id, s_no, emp_name, vou_format, vou_date, jc_num, given_emp_id, given_amt, file_path, exp_file_path, creation_date) 
                            VALUES ('$emp_id', '$serial_no', '$emp_name', '$vou_format', '$vou_date', '$jc_num', '$given_emp_id', '$given_amt', '$file_path', '$file_paths_str', '$creation_date')") or die($mysqli);


                // Redirect or give feedback
                echo "Data inserted successfully.";
            }

            // Redirect after processing 
            header("location: voucher_index.php?serial_no=$serial_no&vou_format=$vou_format" . "&message=" . $message);
        }

    }
}
ob_end_flush();
