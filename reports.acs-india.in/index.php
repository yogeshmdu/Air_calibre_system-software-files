<!-- ######## Database connection ######## -->
<?php require 'web_acsdb.php';

$user_id = "";

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
// ============================== VALIDATION REPORT COLLECTION ==============================
$display = ("SELECT crm.*,customer.customer_name FROM customer,customer_reports_master crm
                WHERE crm.archived = '0' and crm.customer_id = customer.customer_id ORDER BY crm.creation_date DESC");
$result = $mysqli->query($display);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ACS Report Format</title>

  <!-- Bootstrap CSS and JS File -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Bootstrap icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Responsive Datatables with +/- collapse/expand buttons while the columns are hidden -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"> -->
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">
  <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
  <script src="https://kit.fontawesome.com/185b985911.js" crossorigin="anonymous"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <link href="style.css" rel="stylesheet">

  <link rel="icon" type="image/x-icon" href="./image/ACS.jpeg">

  <style>
    .nav-item a {
      color: black;
      text-decoration: none;
      padding: 15px;
    }

    #ser_icon {
      margin: 12px 10px 0 0;
    }

    #end_icon {
      margin: 12px 30px 15px 15px;
      color: #140c5e;
    }
    #end_icon:hover{
      color: #FFA500;
    }

    .nav-item a:hover {
      color: goldenrod;
    }
    .navbar .icon{
      display: none;
      color: black;
    }
    .checkbtn {
      font-size: 30px;
      color: black;
      margin-right: 40px;
      cursor: pointer;
      display: none;
    }
    a.active,
    a:hover {
      color: orange;
      transition: .5s;
    }

    #check {
      display: none;
    }

    
    @media (max-width: 992px) {
      nav .container-fluid ul {
        display: block;
        line-height: 80px;
        margin: 0 1px;
        margin-top: -14px;
        padding-top: 9px;
      }
      #ser_icon {
      margin: 12px 10px 0 0;
      display: block;
      color: black;
    }
    .d-flex #ser_icon{
        float: right;
      }

      .nav_links input[type=text] {
        border: 1px solid #ccc;
      }
    }
    @media (max-width: 1129px) {
      #ser_icon {
      margin: 12px 10px 0 0;
      display: block;
      color: black;
    }
    .d-flex #ser_icon{
        float: right;
      }

      
      .d-flex input[type=text],
      .d-flex #ser_icon {
        float: none;
        display: block;
      }

      .nav_links input[type=text] {
        border: 1px solid #ccc;
      }

      #end_icon {
      margin: 12px 30px 15px 15px;
    }
      .container-fluid .checkbtn {
        display: block;
      }
      nav .container-fluid ul {
        position: fixed;
        width: 100%;
        height: 100vh;
        background: #ecf0f3;
        top: 30px;
        left: -100%;
        text-align: center;
        transition: all .5s;
        z-index: 1;
      }

      nav .container-fluid ul {
        display: block;
        margin-top: 30px;
        text-align: center;
        line-height: 30px;
      }
      nav .container-fluid ul li a{
        font-size: 20px;
      }
      #check:checked~ul {
        left: 0;
      }

    }

  </style>
  <script>
    //Datatable
    $(document).ready(function () {
      $('#myReportsTable').DataTable(
        {
          responsive: true,
          aaSorting: [[0, "desc"]],
          columnDefs: [{
            orderable: false,
            targets: [5, 6, 7]
          }],
          searching: false,
          paging: false
        }
      );
      $('[data-toggle="tooltip"]').tooltip();

      // Add tooltip for collapsed(child) table row
      $('#myReportsTable tbody').on('click', 'td.dtr-control', function () {
        $('[data-toggle="tooltip"]').tooltip();
      });

    });




    function myFunction() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("myReportsTable");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        // td = tr[i].getElementsByTagName("td")[0];
        alltags = tr[i].getElementsByTagName("td");
        isFound = false;
        for (j = 0; j < alltags.length; j++) {
          td = alltags[j];
          if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
              j = alltags.length;
              isFound = true;
            }
          }
        }
        if (!isFound && tr[i].className !== "header") {
          tr[i].style.display = "none";
        }
      }
    }


  </script>
</head>

<body>
  <!-- ============================== PAGE INDEX STARTING ============================== -->

  <nav class="navbar navbar-expand-lg" id="nav_head">
    <div class="container-fluid">
    <input type="checkbox" id="check">
      <label for="check" class="checkbtn"><i class="fa-solid fa-bars"></i></label>
      <ul class="nav">
          <li class="nav-item">
              <a class="nav-link" href="test_carried_parameter.php">Parameters</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="instrument_details.php">Instrument</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="instr_monthly.php">Monthly action</a>
          </li>

          <?php 
        
          $get_user = $mysqli->query("SELECT user_id, user_type FROM employee WHERE user_id = '$user_id'");
          $get_user_result = $get_user->fetch_assoc();

          $user_type = $get_user_result['user_type'];

          if($user_type=='super') : ?>

          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="voucherDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Expenses
              </a>
              <ul class="dropdown-menu" aria-labelledby="voucherDropdown">
                  <li><a class="dropdown-item" href="voucher/voucher_index.php?vou_format=<?php echo $vou_format=1; ?>">Office </a></li>
                  <li><a class="dropdown-item" href="voucher/voucher_index.php?vou_format=<?php echo $vou_format=2; ?>">Staff </a></li>
              </ul>
          </li>
          <?php endif; ?>
        </ul>

      <!-- <div class="collapse navbar-collapse justify-content-end" id="navbarTogglerDemo01"> -->
        <form class="d-flex search_box" role="search">
          <i id="ser_icon" class="fa-solid fa-magnifying-glass fa-beat"></i>
          <input type="text" id="myInput" class="table-filter" onkeyup="myFunction()" data-table="compact"
            placeholder="Search..." title="Type in a name" />
          <a href="logout.php"><i id="end_icon" class="fa-sharp fa-solid fa-right-from-bracket"></i></a>
        </form>
      </div>
    </div>
  </nav>


  <div id="index_table_div">

    <table id="myReportsTable" class="compact display nowrap table" style="width:100%;">

      <thead class="text-start" id="table_head">
        <tr class="text-center">
          <th>S.No</th>
          <th>Report ID</th>
          <th>Customer Name</th>
          <th> Creation Date</th>
          <th>Report Status</th>
          <th>Action</th>
          <th>View Reports
            <a href="archivedreports.php" class="px-2" data-toggle="tooltip" title="Archived Reports">
              <i class="fa-solid fa-chart-bar"></i>
            </a>
          </th>
          <th>Service Report</th>
          <th>IQC Checking</th>
        </tr>
      </thead>
      <tbody class="text-start">
        <?php while ($disrow = $result->fetch_assoc()): ?>
          <tr class="text-center">
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
            <?php
            $report_id = $disrow['report_id'];
            $repcntresult = $mysqli->query("SELECT report_id, count(report_id) as rep_count from reports_ahu_details where report_id= '$report_id'");
            $repcntrow = $repcntresult->fetch_assoc();
            $report_status = 'PENDING';
            if ($repcntrow['rep_count'] > 0) {
              $report_status = '<span style="color:green;">IN-PROGRESS</span>';
            }
            ?>
            <td>
              <?php echo $report_status; ?>
            </td>
            <td class="text-center">
              <a href="rep_datasheet.php?report_id=<?php echo $report_id; ?>" data-toggle="tooltip"
                title="Update Report Details">
                <i class="fa-solid fa-file-circle-plus"></i>
              </a>
            </td>
            <td class="text-center">
              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                Reports
              </button>
              <ul class="dropdown-menu">
                <li><a href="av_group.php? report_id=<?php echo $report_id; ?>" class="btn btn-sm text-black rounded"
                    style="background-color: #d8dfeb; width:75%; margin-bottom: 2px;" role="button">AV</a></li>
                <li><a href="fi_group.php? report_id=<?php echo $report_id; ?>" class="btn btn-sm text-black rounded"
                    style="background-color: #d8dfeb; width:75%; margin-bottom: 2px;" role="button">FI</a></li>
                <li><a href="pc_group.php? report_id=<?php echo $report_id; ?>" class="btn btn-sm text-black rounded"
                    style="background-color: #d8dfeb; width:75%; margin-bottom: 2px;" role="button">PC</a></li>
                <li><a href="sl_group.php? report_id=<?php echo $report_id; ?>" class="btn btn-sm text-black rounded"
                    style="background-color: #d8dfeb; width:75%; margin-bottom: 2px;" role="button">SI</a></li>
                <li><a href="lux_group.php? report_id=<?php echo $report_id; ?>" class="btn btn-sm text-black rounded"
                    style="background-color: #d8dfeb; width:75%; margin-bottom: 2px;" role="button">LI</a></li>
                <li><a href="db_group.php? report_id=<?php echo $report_id; ?>" class="btn btn-sm text-black rounded"
                    style="background-color: #d8dfeb; width:75%; margin-bottom: 2px;" role="button">DP</a></li>
                <li><a href="rc_group.php? report_id=<?php echo $report_id; ?>" class="btn btn-sm text-black rounded"
                    style="background-color: #d8dfeb; width:75%; margin-bottom: 2px;" role="button">RC</a></li>
                <li><a href="temp_rh_group.php? report_id=<?php echo $report_id; ?>" class="btn btn-sm text-black rounded"
                    style="background-color: #d8dfeb; width:75%; margin-bottom: 2px;" role="button">TRH</a></li>
                <li><a href="archivedcustomer.php? report_id=<?php echo $report_id; ?>" name="archive"
                    class="btn btn-sm rounded" id="archive_button"
                    style="background-color: #140c5e; color: #fff; margin-left: -5px;">Archive</a></li>
              </ul>
            </td>
            <td class="text-center">
              <a href="job_service_sheet.php?report_id=<?php echo $report_id; ?>" data-toggle="tooltip"
                title="Service Report">
                <i class="fa-solid fa-person-walking-dashed-line-arrow-right fa-beat-fade"></i>
              </a>
            </td>
            <td class="text-center">
              <a href="internal_qty_ctrl.php?report_id=<?php echo $report_id; ?>" title="IQC service report"><i class="fa-solid fa-business-time"></i></a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</body>

</html>