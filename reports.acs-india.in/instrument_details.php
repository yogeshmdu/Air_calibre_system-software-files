<?php

include("./web_acsdb.php");

$instrument_det = ("SELECT * FROM instrument_details");
$inst_result = $mysqli->query($instrument_det);





// echo $instr_name;
// echo '</br>';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
    <script src="https://kit.fontawesome.com/185b985911.js" crossorigin="anonymous"></script>

    <style>
        .main_ptr {
            display: flex;
        }

        h3 {
            margin-top: 18px;
            margin-bottom: 25px;
            margin-left: 40%;
            /* padding-left: 35%; */
        }

        .back_icon {
            /* padding-bottom: 66px; */
            color: #140c5e;
        }

        #bck-icn {
            padding-top: 25px;
            /* margin-right: 100%; */
            /* padding-left: 50px; */
            /* padding-right: 610px; */
            padding-left: 40px;
            color: #212529;
            font-size: x-large;
        }
        @media (max-width: 600px) {
            h3 {
                padding-left: 23%;
                font-size: x-large;
                /* margin-top: 18px; */
                margin-bottom: 25px;
            }
            .back_icon {
            /* padding-bottom: 66px; */
            color: #140c5e;
        }

        #bck-icn {
            padding-top: 25px;
            /* margin-right: 100%; */
            /* padding-left: 50px; */
            padding-right: 0px;
            padding-left: 40px;
            color: #212529;
            font-size: x-large;
        }
        }
        @media (max-width: 768px) {
            h3 {
                padding-left: 27%;
                font-size: x-large;
                /* margin-top: 18px; */
                margin-bottom: 25px;
            }
            .back_icon {
            /* padding-bottom: 66px; */
            color: #140c5e;
        }

        #bck-icn {
            padding-top: 25px;
            /* margin-right: 100%; */
            /* padding-left: 50px; */
            padding-right: 0px;
            padding-left: 40px;
            color: #212529;
            font-size: x-large;
        }
        }

       
        @media (max-width: 991px) {
            h3 {
                padding-right: 0%;
                font-size: x-large;
                /* margin-top: 18px; */
                margin-bottom: 25px;
            }
            .back_icon {
            /* padding-bottom: 66px; */
            color: #140c5e;
        }

        #bck-icn {
            padding-top: 25px;
            /* margin-right: 100%; */
            /* padding-left: 50px; */
            padding-right: 0px;
            padding-left: 40px;
            color: #212529;
            font-size: x-large;
        }
        }
    </style>
</head>

<body>
    <!-- <div class="col-md-2">
           <a href="index.php" class="btn btn-primary text-nowrap my-4 mx-3" role="button"><< Back</a>
                </div> -->
    <div class="main_ptr">
        <div class="back_icon"><a href="index.php">
                <i id="bck-icn" class="fa-solid fa-circle-arrow-left"></i></a>
        </div>

        <h3>Instrument Details</h3>
    </div>

    <table class="table table-hover ">

        <thead>
            <tr class="bg-dark" style="color: white;">
                <th scope="col">S.No</th>
                <th scope="col">Instrument Name</th>
                <th scope="col">Make</th>
                <th scope="col">Model Number</th>
                <th scope="col">Serial no</th>
                <th scope="col">Indendification no</th>
                <th scope="col">Calibration date</th>
                <th scope="col">calibration due date</th>
            </tr>
        </thead>
        <?php

        while ($inst_details = $inst_result->fetch_assoc()) :

            $instr_name = $inst_details['instrument_used'];
            $serial_no = $inst_details['serial_no'];
            $make = $inst_details['instrument_make'];
            $model = $inst_details['instrument_model'];
            $instr_id = $inst_details['instrument_id'];
            $instr_sno = $inst_details['instrument_sno'];
            $cali_date = $inst_details['calibration_date'];
            $cali_due_date = $inst_details['calibration_due_date'];
        ?>
            <tbody>
                <tr>
                    <th scope="row"><?php echo $serial_no; ?></th>
                    <td><?php echo $instr_name; ?></td>
                    <td><?php echo $make; ?></td>
                    <td><?php echo $model; ?></td>
                    <td><?php echo $instr_id; ?></td>
                    <td><?php echo $instr_sno; ?></td>
                    <td><?php echo $cali_date; ?></td>
                    <td><?php echo $cali_due_date; ?></td>
                </tr>
            </tbody>
        <?php endwhile; ?>
    </table>
</body>

</html>