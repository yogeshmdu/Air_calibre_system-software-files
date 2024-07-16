<?php
session_start();
include('web_acsdb.php');

$iqc_testing_dateformat = "";
if (isset($_GET["iqc_date"])) {
    $iqc_testing_dateformat = $_GET["iqc_date"];
}

$selectedReportId = "";
if (isset($_GET["report_id"])) {
    $selectedReportId = $_GET["report_id"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQC Report</title>

   
    <!-- Bootstrap CSS and JS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <script src="https://kit.fontawesome.com/14a4966bb0.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
       
        .head_section{
            /* padding: 10%; */
            width: 80%;
            display: flex;
        }
        h2{
            text-align: center;
            margin-left: 400px;
        }
        .back_btn a{
            margin-left: 50px;
            color: black;
            font-size: 1.8rem;
        }
        .main_internal{
            width: 80%;
            margin-left: 10%;
            font-size: small;
        }
        
    </style>

</head>

<body>
    <div class="main_internal">
      
        <form id="iqc_form" method="POST" action="iqc_reports_save.php?save=iqc_data">
            <br>
            <hr>
           
                <div class="head_section">
                    <div class="back_btn">
                        <a href="iqc_view_data.php?report_id=<?php echo $selectedReportId; ?>"><i class="fa-solid fa-circle-arrow-left"></i></a>
                    </div>
                    
                    <h2>Internal Quality Control</h2>
                </div>
                
                <button type="button" onclick="downloadPDF()" style="padding: 10px; float: right; margin: 50px;">Download PDF</button>
           
            <hr>

            <div id="area" class="table_iqc_div ">
                <!-- <form  id="form_post" method="post"> -->
                <table class="table table-bordered ">
                    <thead id="thead_table ">
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Instrument Name</th>
                            <th>Instrument Id No.</th>
                            <th>Parameters to Check</th>
                            <th>Acceptable Variation</th>
                            <th>Variation Observed</th>
                            <th>Result</th>
                            <th>Prepared By</th>
                            <th>Verified</th>
                            <th>remarks</th>
                        </tr>
                    </thead>
                    <?php
                    $iqcresult = $mysqli->query("SELECT * FROM internal_qty_ctrl where iqc_date = '$iqc_testing_dateformat'") or die($mysqli);
                    while ($iqcrow = $iqcresult->fetch_assoc()):
                    $iqc_sno = $iqcrow['s_no'];
                    $iqc_seq_no = $iqcrow['iqc_seq_no'];
                    $iqc_date = $iqcrow['iqc_date'];
                    $iqc_inst = $iqcrow['inst_name'];
                    $iqc_instid = $iqcrow['inst_id'];
                    $iqc_parameter = $iqcrow['parameter_check'];
                    $iqc_accp_var = $iqcrow['acceptable_variation'];
                    $iqc_var_obs = $iqcrow['variation_obs'];
                    $iqc_result = $iqcrow['result'];
                    $iqc_prepared = $iqcrow['prepared_by'];
                    $iqc_verified = $iqcrow['verified'];
                    $iqc_remarks = $iqcrow['iqc_remarks'];

                        ?>
                        <tbody>
                            <tr id="table_td" >
                                <td>
                                    <?php echo $iqc_seq_no; ?>
                                </td>
                                <td>
                                    <?php echo $iqc_date; ?>
                                </td>
                                
                                <td>
                                    <?php echo $iqc_inst; ?>
                                </td>
                                <td>
                                    <?php echo $iqc_instid; ?>
                                </td>
                                <td>
                                    <?php echo $iqc_parameter; ?>
                                </td>
                                <td>
                                    <?php echo $iqc_accp_var; ?> 
                                </td>
                                <td>
                                    <?php echo $iqc_var_obs; ?>
                                </td>
                                <td>
                                    <?php echo $iqc_result; ?>
                                </td>

                                <td>
                                    <?php echo $iqc_prepared; ?>
                                </td>
                                <td>
                                    <?php echo $iqc_verified; ?>
                                </td>
                                <td>
                                    <?php echo $iqc_remarks; ?>
                                </td>
                            </tr>

                        </tbody>
                    <?php endwhile; ?>
                </table>

            </div>
    </div>

    <script>
        window.jsPDF = window.jspdf.jsPDF;
        var docPDF = new jsPDF();

        function downloadPDF(invoiceNo){

            var elementHTML = document.querySelector("#area");
            docPDF.html(elementHTML, {
                callback: function(docPDF) {
                    docPDF.save(invoiceNo+'.pdf');
                },
                x: 5,
                y: 10,
                width: 200,
                windowWidth: 850
            });
        }
    </script>
</body>

</html>