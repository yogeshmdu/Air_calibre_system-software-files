<?php
session_start();
require 'web_acsdb.php';
if (strlen(isset($_GET["message"])) > 0) {
  echo '<script>alert("' . $_GET["message"] . '")</script>';
}
$selectedReportId = "";
if (strlen($_GET["report_id"]) > 0) {
  $selectedReportId = $_GET["report_id"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Datasheet Info</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="ACS, 'Air Calibre Systems','clean rooms and validation'" name="keywords">
  
    <!-- Bootstrap CSS and JS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Responsive Datatables with Fixed headers -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />

  <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">

  <link rel="stylesheet" href="style.css"/>

  <script>
        $(document).ready(function () {
            //Datatable Initialization
            $('#ahu_table').DataTable(
                {
                  fixedColumns: {
                      left: 4,
                      right: 1
                  },
                  paging: false,
                  ordering: false,
                  searching: false,
                  scrollCollapse: true,
                  scrollX: true
                }
            );
        });
	</script>
</head>
<body>
    <!-- ==================================================== DATASHEET INFORMATION ==================================================== -->
    <main id="main">
        <div class="container-fluid">
        <br>
        <form id="datasheet_form" method="POST" action="report_save.php?save=DS">
            <!--  ========================= BACK AND ADD AHU ROOM BUTTONS ========================= -->
            <div class="row">
                <div class="col-md-2">
                    <a href="index.php" class="btn btn-primary text-nowrap" role="button"><< Back</a>
                </div>
                <div class="col-md-7 text-center">
                    <h2>Datasheet Info</h2>
                    <input type="hidden" id="report_id" name="report_id" value="<?php echo $selectedReportId; ?>">
                </div>
                <div class="col-md-3 text-end" id="menu_row">
                    <a href="reports_ahu_details.php?report_id=<?php echo $selectedReportId; ?>">
                        <button class="btn btn-primary text-nowrap" name="ahu_button" type="button">Add AHU-Room Details</button>
                    </a>
                </div>
            </div>
        </form>
        <hr>
        <?php
            $crmresult = $mysqli->query("SELECT crm.*, sch.customer_id, sch.employee_id, crm.testing_date, 
                cust.customer_name, cust.city, crm.department
                FROM customer_reports_master crm, schedule sch, customer cust
                where crm.report_id = '$selectedReportId' and
                      crm.job_card_no = sch.job_card_no and 
                      trim(sch.customer_id) = trim(cust.customer_id)") or die($mysqli);
            $crmrow = $crmresult->fetch_assoc();
        ?>
        <div id="datasheet_info" class="form-group row">
            <label for="staticEmail" class="col-lg-1 col-form-label text-nowrap">Report #</label>
            <div class="col-lg-3">
                <input type="text" readonly class="form-control form-control input-sm bg-light" name="report_id"
                      id="report_id" value="<?php echo $selectedReportId; ?>">
            </div>

            <label for="staticEmail" class="col-lg-1 col-form-label text-nowrap">Client Name</label>
            <div class="col-lg-3">
                <input type="text" readonly class="form-control form-control input-sm bg-light" name="clientname"
                      id="clientname" value="<?php echo $crmrow['customer_name']; ?>">
                
            </div>

            <label for="staticEmail" class="col-lg-1 col-form-label text-nowrap">Plant address</label>
                <div class="col-lg-3">
                    <input type="text" readonly class="form-control form-control input-sm bg-light" name="city" id="city"
                          value="<?php echo $crmrow['city']; ?>">
                </div>
          </div>
          <br>

          <!-- ==================================================== AHU ROOM DETAILS ==================================================== -->
          <div class="row pl-3 pr-3" id="ahu_list">
              <table id="ahu_table" class="compact display" style="width: 100%;">
                  <thead class="thead-dark text-center bg-light">
                      <tr>
                          <th rowspan="2">AHU Seq#</th>
                          <th class="text-wrap" rowspan="2">AHU/Eqp Name</th>
                          <th rowspan="2">Room Id</th>
                          <th rowspan="2">Room Description</th>
                          <th colspan="3">Air Velocity</th>
                          <th>Filter<br>Integrity</th>
                          <th colspan="4">Particle Count</th>
                          <th>Differential<br>Pressure </th>
                          <th>Sound <br>Level Test</th>
                          <th>Light<br>intensity</th>
                          <th>Temperature<br>RH test</th>
                          <th>Recovery Test</th>
                          <th rowspan="2">Action</th>
                      </tr>
                      <tr>
                          <th>Qty</th>
                          <th>Area</th>
                          <th>Room Volume</th>
                          <th>Filter Qty</th>
                          <th>No. Of Loc</th>
                          <th>Room Area</th>
                          <th>PC Time</th>
                          <th>PC Volume</th>
                          <th>DP Qty</th>
                          <th>SL Qty</th>
                          <th>LUX Qty</th>
                          <th>TRH Qty</th>
                          <th>RC Qty </th>
                      </tr>
                  </thead>
          <tbody class="text-center">
              <?php
                  $recordCount = 0;
                  $ahuresult = $mysqli->query("SELECT * FROM reports_ahu_details where report_id = '$selectedReportId' ") or die($mysqli);
                  while ($row = $ahuresult->fetch_assoc()):
              ?>
              <tr>
                  <td>
                      <?php echo ++$recordCount; ?>
                  </td>
                  <td class="text-nowrap">
                    <?php echo $row['ahu_name'];?>
                  </td>
                  <td class="text-nowrap">
                    <?php echo $row['room_id']; ?>
                  </td>
                  <td class="text-nowrap">
                    <?php echo trim($row['room_details']); ?>
                  </td>
                  <td>
                    <?php echo $row['av_qty']; ?>
                  </td>
                  <td>
                    <?php echo $row['av_area']; ?>
                  </td>
                  <td>
                    <?php echo $row['av_room_volume']; ?>
                  </td>
                  <td>
                    <?php echo $row['filter_integrity_qty']; ?>
                  </td>
                  <td>
                    <?php echo $row['pc_loc_nos']; ?>
                  </td>
                  <td>
                    <?php echo $row['pc_room_area']; ?>
                  </td>
                  <td>
                    <?php echo $row['pc_time']; ?>
                  </td>
                  <td>
                    <?php echo $row['pc_volume']; ?>
                  </td>
                  <td>
                    <?php echo $row['dp_quty']; ?>
                  </td>
                  <td>
                    <?php echo $row['sl_qty']; ?>
                  </td>
                  <td>
                    <?php echo $row['lg_qty']; ?>
                  </td>
                  <td>
                    <?php echo $row['temp_rh_qty']; ?>
                  </td>
                  <td>
                    <?php echo $row['rc_qty']; ?>
                  </td>
                  <!-- ========================= EDIT AND DELETE BUTTONS ========================= -->
                  <td class="text-nowrap">
                      <a href="reports_ahu_details.php?edit=1&ahu_seq_no=<?php echo $row['ahu_seq_no']; ?>">
                          <button type="button" class="btn btn-outline-primary btn-sm rounded-circle">
                              <i class="bi bi-pencil-square" style="font-size: 0.9rem;"></i>
                          </button>
                      </a>
                      <a href="delete_ahu.php?delete=deleahu&ahu_seq_no=<?php echo $row['ahu_seq_no']; ?>&report_id=<?php echo $selectedReportId; ?>">
                          <button type="button" onclick="return confirm('Are you sure! want to delete?')" class="btn btn-outline-danger btn-sm rounded-circle">
                              <i class="bi bi-trash" style="font-size: 0.9rem;"></i>
                          </button>
                      </a>
                  </td>
              </tr>
              <?php endwhile; ?>
          </tbody>
      </table>
    </div>
  </div>
</main>
</body>
</html>