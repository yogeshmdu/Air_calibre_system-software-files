<?php

session_start();



require('web_acsdb.php');

?>



<!DOCTYPE html>

<html lang="en">



<head>

  <meta charset="utf-8">

  <title>Employee Job List</title>

  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <meta content="ACS, 'Air Calibre Systems','clean rooms and validation'" name="keywords">

  <meta content="" name="description">



  <!-- Google Fonts -->

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700"
    rel="stylesheet">



  <!-- Bootstrap CSS File -->

  <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css"
    rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet">

  <link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">

  <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">


</head>



<body>



  <!--==========================

    Header

  ============================-->

  <?php include("header.php"); ?>

  <!-- #header -->



  <main id="main">

    <section class="section-bg wow">

      <div class="container" style="padding-top:100px;">

        <div class="section-header">

          <h3>Employee Job List</h3>

        </div>



        <div class="col-md-4">

          <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

            <select name="selectemp" id="selectemp" class="form-control input-sm" onchange="this.form.submit()">

              <option value="" selected>Get All</option>

              <?php

              require('web_acsdb.php');

              $result1 = $mysqli->query("SELECT employee_id, employee_name FROM employee");

              while ($row1 = $result1->fetch_assoc()) {

                //echo '<option value="'.$row["employee_id"].'">'.$row["employee_id"].','.$row["employee_name"].'</option>';
              
                $output = "<option value='" . $row1['employee_id'] . "'";

                if ($_POST['selectemp'] == $row1['employee_id']) {

                  $output .= " selected='selected'";

                }

                $output .= ">" . $row1['employee_id'] . "," . $row1['employee_name'] . "</option>";

                echo $output;

              }

              ?>

            </select>

          </form>

        </div><br>



        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

          $empid = htmlspecialchars($_REQUEST['selectemp']);

        }

        ?>



        <?php if (empty($empid)): ?>

          <div class="col-md-12">

            <div id="alldata">

              <h3 style="text-align:center; background: lightgray;">All Employees Job List</h3>





              <table style="width:100%" id="job_table"
                class="table table-striped table-hover table-bordered controls controls-row">

                <thead>

                  <tr>

                    <th>Employee Id</th>

                    <th>Job Card No</th>

                    <th>PO No</th>

                    <th>Customer Name</th>

                    <th>Executed on</th>

                    <th>Job Status</th>

                  </tr>

                </thead>



                <tbody>

                  <?php

                  require('web_acsdb.php');

                  $result2 = $mysqli->query("SELECT * FROM employee") or die($mysqli->error);

                  while ($row2 = $result2->fetch_assoc()): ?>

                    <tr style="background: lightgray; font-size:16px;font-weight:bold;">

                      <th colspan="6" style="text-align:center;">
                        <?php

                        $id = $row2['employee_id'];

                        $name = $row2['employee_name'];

                        echo $name;

                        ?> Job List
                      </th>

                      <th style="display: none"></th>

                      <th style="display: none"></th>

                      <th style="display: none"></th>

                      <th style="display: none"></th>

                      <th style="display: none"></th>

                    </tr>



                    <?php

                    $result3 = $mysqli->query("SELECT * FROM schedule WHERE employee_id LIKE '%" . $id . "%'") or die($mysqli->error);

                    while ($row3 = $result3->fetch_assoc()): ?>

                      <?php $status = $row3['job_status'];

                      if ($status !== 'COMPLETED' && $status !== 'CLOSED'): ?>

                        <tr>

                          <td>
                            <?php echo $id; ?>
                          </td>

                          <td>
                            <?php echo $row3['job_card_no']; ?>
                          </td>

                          <td>
                            <?php echo $row3['po_no']; ?>
                          </td>

                          <td>
                            <?php

                            $custid = $row3['customer_id'];

                            $result4 = $mysqli->query("SELECT customer_name FROM customer WHERE customer_id='$custid'") or die($mysqli->error);

                            $row4 = $result4->fetch_assoc();

                            $cname = $row4['customer_name'];

                            echo $cname;

                            ?>
                          </td>

                          <td nowrap>
                            <?php echo $row3['visit_date']; ?>
                          </td>

                          <td>
                            <?php echo $row3['job_status']; ?>
                          </td>

                        </tr>

                      <?php endif; ?>

                    <?php endwhile; ?>

                  <?php endwhile; ?>

                </tbody>



              </table>

            </div>



          </div>

        <?php else: ?>

          <div class="col-md-12">

            <div id="specificdata">

              <?php

              require('web_acsdb.php');



              $result5 = $mysqli->query("SELECT * FROM schedule WHERE employee_id LIKE '%" . $empid . "%'") or die($mysqli->error);

              ?>

              <h3 style="text-align:center; background: lightgray;">

                <?php

                $result6 = $mysqli->query("SELECT employee_name FROM employee WHERE employee_id='$empid'") or die($mysqli->error);

                $row6 = $result6->fetch_assoc();

                $employeename = $row6['employee_name'];

                echo $employeename; ?> Job List
              </h3>

              <table style="width:100%" id="emp_job_table"
                class="table table-striped table-hover table-bordered controls controls-row">

                <thead>

                  <tr>

                    <th>Employee Id</th>

                    <th>Job Card No</th>

                    <th>Customer Name</th>

                    <th>Executed on</th>

                    <th>Job Status</th>

                  </tr>

                </thead>



                <tbody>

                  <?php

                  while ($row5 = $result5->fetch_assoc()): ?>

                    <?php $status = $row5['job_status'];

                    if ($status !== 'COMPLETED' && $status !== 'CLOSED'): ?>

                      <tr>

                        <td>
                          <?php echo $empid; ?>
                        </td>

                        <td>
                          <?php echo $row5['job_card_no']; ?>
                        </td>

                        <td>
                          <?php

                          $custid = $row5['customer_id'];

                          $result7 = $mysqli->query("SELECT customer_name FROM customer WHERE customer_id='$custid'") or die($mysqli->error);

                          $row7 = $result7->fetch_assoc();

                          $custname = $row7['customer_name'];

                          echo $custname;

                          ?>
                        </td>

                        <td nowrap>
                          <?php echo $row5['visit_date']; ?>
                        </td>

                        <td>
                          <?php echo $row5['job_status']; ?>
                        </td>

                      </tr>

                    <?php endif; ?>

                  <?php endwhile; ?>



                </tbody>



              </table>

            </div>



          </div>

        <?php endif; ?>

        <div>



    </section>

  </main>




  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

  <!-- Uncomment below i you want to use a preloader -->

  <!-- <div id="preloader"></div> -->




  <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>




  <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>

  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>



  <!--Enquiry Form JavaScript File-->



  <!-- Template Main Javascript File -->

</body>

</html>