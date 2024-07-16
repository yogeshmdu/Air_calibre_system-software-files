<?php require 'web_acsdb.php';

// ======================================  ARCHIVED REPORTS ======================================

$display = ("SELECT crm.*,customer.customer_name FROM customer,customer_reports_master crm
                WHERE crm.archived = '1' and crm.customer_id = customer.customer_id ORDER BY crm.report_seq_no DESC;");
$result = $mysqli->query($display);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived reports</title>

    <!-- Bootstrap CSS and JS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/185b985911.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css" />
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
    <style>
        #heading_div {
            width: 100%;
            height: 50px;
            background: white;
            text-align: center;
            padding-top: 10px;
            /* margin-top: 40px; */
            display: flex;
        }

        #heading_div>h3 {
            font-family: sans-serif;
            color: #4f4f4f;
            font-size: 1.5em;
            text-align: center;
            text-transform: uppercase;
            padding-bottom: 60px;
            padding-left: 30%;
        }

        #archivedReportsTable th {
            font-size: 0.8rem;
            background-color: #B0C4DE;
            border-bottom: none;
            color: #4F4F4F;
        }

        .back_icon {
            color: #140c5e;
        }

        #bck-icn {
            color:  #212529;
            font-size: x-large;
            padding-left: 70px;

        }
        @media (max-width: 600px) {
            #heading_div {
            width: 100%;
            height: 50px;
            background: white;
            text-align: center;
            padding-top: 10px;
            /* margin-top: 40px; */
            display: flex;
        }

        #heading_div>h3 {
            font-family: sans-serif;
            color: #4f4f4f;
            font-size: 1.5em;
            text-align: center;
            text-transform: uppercase;
            padding-bottom: 60px;
            /* font-size: medium; */
        }
        .back_icon {
            color: #140c5e;
        }

        #bck-icn {
            color: #140c5e;
            font-size: x-large;
            padding-left: 70px;
            padding-right: 30px;

        }
        }
        @media (max-width: 767px) {
            #heading_div {
            width: 100%;
            height: 50px;
            background: white;
            text-align: center;
            padding-top: 10px;
            /* margin-top: 40px; */
            display: flex;
        }

        #heading_div>h3 {
            font-family: sans-serif;
            color: #4f4f4f;
            font-size: 1.5em;
            text-align: center;
            text-transform: uppercase;
            padding-bottom: 60px;
            /* font-size: medium; */
        }
        .back_icon {
            color: #140c5e;
        }

        #bck-icn {
            color: #140c5e;
            font-size: x-large;
            padding-left: 70px;
            padding-right: 30px;

        }
        }
    </style>
</head>

<body>
  
    <div class="fixed-top" id="heading_div">
  
        
        <div class="back_icon">
            <a href="index.php"><i id="bck-icn" class="fa-solid fa-circle-arrow-left"></i></a>
        </div>
        
        <h3>List of Archived Reports</h3>
        
    </div>
    <div style="padding-top: 50px;">
        <table id="archivedReportsTable" class="nowrap table table-sm table-striped" style="width:100%;">
            <thead>
                <tr class="text-center text-nowrap">
                    <th>S.No</th>
                    <th>Report ID</th>
                    <th>Customer Name</th>
                    <th>Creation Date</th>
                    <th>Archived Reports</th>
                </tr>
            </thead>
            <tbody class="text-center text-nowrap">
                <?php while ($disrow = $result->fetch_assoc()) : ?>
                    <tr>
                        <td>
                            <?php echo $disrow['report_seq_no']; ?>
                        </td>
                        <td>
                            <?php echo $disrow['report_id']; ?>
                        </td>
                        <td>
                            <?php echo $disrow['customer_name']; ?>
                        </td>
                        <td>
                            <?php echo $disrow['creation_date']; ?>
                        </td>
                        <td class="text-wrap">
                            <a href="AVreport.php? report_id=<?php echo $disrow['report_id']; ?>" class="btn btn-sm text-black rounded" role="button">AV</a>
                            <a href="PCreport.php? report_id=<?php echo $disrow['report_id']; ?>" class="btn btn-sm text-black rounded" role="button">PC</a>
                            <a href="FIreport.php? report_id=<?php echo $disrow['report_id']; ?>" class="btn btn-sm text-black rounded" role="button">FI</a>
                            <a href="RCreport.php? report_id=<?php echo $disrow['report_id']; ?>" class="btn btn-sm text-black rounded" role="button">RC</a>
                            <a href="SIreport.php? report_id=<?php echo $disrow['report_id']; ?>" class="btn btn-sm text-black rounded" role="button">SI</a>
                            <a href="Lightreport.php? report_id=<?php echo $disrow['report_id']; ?>" class="btn btn-sm text-black rounded" role="button">LI</a>
                            <a href="DPreport.php? report_id=<?php echo $disrow['report_id']; ?>" class="btn btn-sm text-black rounded" role="button">DP</a>
                            <a href="Temprhreport.php? report_id=<?php echo $disrow['report_id']; ?>" class="btn btn-sm text-black rounded" role="button">TRH</a>
                            <a href="restore_archived.php? report_id=<?php echo $disrow['report_id']; ?>" name="archive" class="btn btn-sm text-white rounded" style="background-color: #140c5e;">Unarchive</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>