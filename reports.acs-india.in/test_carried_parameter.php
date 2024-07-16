<?php
include 'web_acsdb.php';


$test_desc = "";
$testing_paramter_query = $mysqli->query("SELECT * FROM test_carried_details");



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS and JS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/14a4966bb0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/185b985911.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">
    <style>
        .container {
            margin-top: -86px;
        }

        .main_ptr {
            display: flex;
            /* margin-left: 30px; */
        }

        h1 {
            font-size: xx-large;
            text-align: center;
            margin-bottom: 20px;
            padding-left: 31%;
            /* padding-top: 11px; */
            /* margin-right: 90px; */
        }

        @media (max-width: 600px) {
            .container {
                margin-top: -80px;
            }

            h1 {
                font-size: medium;
                text-align: center;
                padding-top: 16px;
                margin-bottom: 0px;
                margin-right: 90px;
            }

            /* .btn {
                margin-left: 190px;
            } */

            .table {
                width: 80%;
                margin-left: 40px;
            }

            .back_icon {
                /* padding-bottom: 66px; */
                color: #140c5e;
            }

            #bck-icn {
                padding-top: 10px;
                padding-right: 10px;
                color: #212529;
                font-size: x-large;
            }
        }
        @media (max-width: 768px) {
            .container {
                margin-top: -80px;
            }

            h1 {
                font-size: medium;
                text-align: center;
                padding-top: 16px;
                margin-bottom: 0px;
                margin-right: 90px;
            }
            /* .opinions { height: auto;} 
            .clientsTable { width: 100%;} */

            /* .btn {
                margin-left: 190px;
            } */

            .table {
                width: 80%;
                margin-left: 40px;
            }

            .back_icon {
                /* padding-bottom: 66px; */
                color: #140c5e;
            }

            #bck-icn {
                padding-top: 10px;
                padding-right: 8px;
                color: #212529;
                font-size: x-large;
            }
        }
        @media (max-width: 991px) {
            .container {
                margin-top: -80px;
            }

            h1 {
                font-size: medium;
                text-align: center;
                padding-top: 16px;
                margin-bottom: 0px;
                margin-right: 90px;
            }

            /* .btn {
                margin-left: 190px;
            } */

            .table {
                width: 80%;
                margin-left: 40px;
            }

            .back_icon {
                /* padding-bottom: 66px; */
                color: #140c5e;
            }

            #bck-icn {
                padding-top: 10px;
                padding-right: 8px;
                color: #212529;
                font-size: x-large;
            }
        }

        .back_icon {
            /* padding-bottom: 66px; */
            color: #140c5e;
        }

        #bck-icn {
            padding-top: 15px;
            /* padding-right: 300px; */
            color: #212529;
            font-size: x-large;
        }
    </style>

</head>

<body>
    <!-- <div class="col-md-2">
           <a href="index.php" class="btn btn-primary text-nowrap my-4 mx-3" role="button"><< Back</a>
                </div> -->
    <br><br><br><br>
    <div class="container">
        <hr>
        <div class="main_ptr">

            <div class="back_icon"><a href="index.php">
                    <i id="bck-icn" class="fa-solid fa-circle-arrow-left"></i></a>
            </div>
            <h1>Testing Parameter Updation</h1>

        </div>
        <hr>
        <table class="table">
            <thead class="table-dark">
                <tr class="text-center">
                    <th scope="col">Serial No</th>
                    <th scope="col">Testing Parameter</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    while ($test_para_result = $testing_paramter_query->fetch_assoc()):
                        $test_desc = $test_para_result['test_description'];
                        $serial_no = $test_para_result['serial_no'];
                        ?>
                        <td class="text-center">
                            <?php echo $serial_no; ?>
                        </td>
                        <td>
                            <?php echo $test_desc; ?>
                        </td>
                        <td class="text-center"><i class="fa-solid fa-user-pen" style="color: #df1111;"></i> </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <form action="testing_para_save.php" method="POST">
            <div class="mt-5">
                <label for="test_description" class="form-label">Testing Parameter</label>
                <input type="text" class="form-control" id="test_description" name="test_description"
                    value="<?php echo $test_desc; ?>">
                <button type="submit" class="btn btn-success mt-5">ADD</button>
            </div>

        </form>
    </div>

</body>

</html>