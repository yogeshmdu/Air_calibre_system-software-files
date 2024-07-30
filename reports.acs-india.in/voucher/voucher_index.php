<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Voucher Index</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

  <style>
    * {
      font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
      font-size: small;
    }

    body {
      margin: 3% 4% 4% 4%;
    }


    table {
      width: 100%;
      margin-top: -8px;
    }

    h3 {
      background-color: #424242;
      padding: 15px 50px 15px 50px;
      color: whitesmoke;
    }

    thead {
      background-color: goldenrod;

    }

    thead tr th {
      color: whitesmoke;
    }

    tbody tr {
      text-align: center;

    }


    .dropdown-menu {
      animation: fadeInDown 0.3s ease-out;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item {
      transition: background-color 0.2s ease;
    }

    .dropdown-item:hover {
      background-color: #f8f9fa;
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <?php
  include ("web_acsdb.php");

  $vou_format = '';

  if (isset($_GET['vou_format'])) {
    $vou_format = $_GET['vou_format'];
  }
  ?>

  <div class="full_cont">
    <table class="table">
      <h3 style="text-align: center; margin-top: 10px;"> ACS - Reimbursement Voucher </h3>

      <thead class="table text-center">
        <tr>
          <th class="th_data" scope="col"> S.No </th>
          <th class="th_data" scope="col"> Employee Name </th>
          <th class="th_data" scope="col"> Date </th>
          <th class="th_data" scope="col">Add</th>
          <?php if ($vou_format != 1): ?>
            <th class="th_data" scope="col">Income</th>
          <?php else: ?>
            <th class="th_data" scope="col">Outcome</th>
          <?php endif; ?>
          <th class="th_data" scope="col">Monthly</th>
          <th class="th_data" scope="col">Files</th>
        </tr>
      </thead>

      <tbody class="table-light">
        <?php
        if ($vou_format != 1) {

          $employee_detail = $vou_format != 1 ? "SELECT * FROM employee WHERE employee_status = 'Active'" : "SELECT * FROM employee WHERE user_type = 'Super' AND employee_status = 'Active'";
          $employee_result = $mysqli->query($employee_detail);
          $serial = 1;

          while ($employee_details = $employee_result->fetch_assoc()) {
            $emp_id = $employee_details['employee_id'];
            $emp_name = $employee_details['employee_name'];
            ?>
            <tr>
              <th scope="row"> <?php echo $serial++; ?> </th>
              <td> <?php echo $emp_name; ?> </td>
              <td>
                <?php
                date_default_timezone_set("Asia/Kolkata");
                echo $vou_date = date('Y-m-d');
                ?>
              </td>
              <td class="first_cont ">
                <div class="dropdown">
                  <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-envelope"></i>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                      <a class="dropdown-item"
                        href="voucher_form.php?vou_date=<?php echo $vou_date; ?>&vou_format=<?php echo $vou_format; ?>&emp_name=<?php echo $emp_name; ?>&emp_id=<?php echo $emp_id; ?>">
                        <i class="fa-solid fa-circle-plus" style="color: #0f0f0f;"></i> &nbsp; &nbsp; New
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-item">
                        <input type="date" id="datePicker-<?php echo $serial; ?>" class="form-control">
                        <a id="dateLink-<?php echo $serial; ?>" class="btn btn-primary mt-2" href="">Select Date</a>
                      </div>
                    </li>
                  </ul>
                </div>
              </td>
              <td class="first_cont">
                <a href="voucher_received_amt.php?emp_name=<?php echo $emp_name; ?>&emp_id=<?php echo $emp_id; ?>"> <i
                    class="fa-solid fa-person-rays" style="color: #050505;"></i></a>
              </td>
              <td class="first_cont">
                <a href="voucher_month_view.php?emp_name=<?php echo $emp_name; ?>&emp_id=<?php echo $emp_id; ?>"> <i
                    class="fa-solid fa-eye" style="color: #030303;"></i></a>
              </td>
              <td>
                <a
                  href="voucher_files.php?vou_format=<?php echo $vou_format; ?>&emp_name=<?php echo $emp_name; ?>&emp_id=<?php echo $emp_id; ?>">
                  <i class="fa-solid fa-folder-open" style="color: #050505;"></i></a>
              </td>
            </tr>

            <script>
              document.addEventListener('DOMContentLoaded', function () {
                const datePicker = document.getElementById('datePicker-<?php echo $serial; ?>');
                const dateLink = document.getElementById('dateLink-<?php echo $serial; ?>');
                const empName = '<?php echo $emp_name; ?>';
                const empId = '<?php echo $emp_id; ?>';
                const vouFormat = '<?php echo $vou_format; ?>';

                datePicker.addEventListener('change', function () {
                  const selectedDate = this.value;

                  if (selectedDate) {
                    dateLink.href = `voucher_form.php?vou_date=${selectedDate}&vou_format=${vouFormat}&emp_name=${empName}&emp_id=${empId}`;
                  } else {
                    dateLink.href = '#';
                  }
                });

                dateLink.addEventListener('click', function (event) {
                  const selectedDate = datePicker.value;
                  if (!selectedDate) {
                    event.preventDefault();
                    alert('Please select a date.');
                  }
                });
              });
            </script>
          <?php } ?>

        <?php } else {

          $employee_detail = ("SELECT * FROM employee where user_type = 'Super' and employee_status = 'Active' ");
          $employee_result = $mysqli->query($employee_detail);

          $serial = 1;

          while ($employee_details = $employee_result->fetch_assoc()):

            $emp_id = $employee_details['employee_id'];
            $emp_name = $employee_details['employee_name'];
            // $emp_phone = $employee_details['phone'];
            ?>
            <tr>
              <th scope="row"> <?php echo $serial++; ?> </th>
              <td> <?php echo $emp_name; ?> </td>

              <td>
                <?php
                date_default_timezone_set("Asia/Kolkata");
                echo $vou_date = date('Y-m-d');
                ?>
              </td>

              <td class="first_cont">
                <div class="dropdown">
                  <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-envelope"></i>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                      <a class="dropdown-item"
                        href="voucher_form.php?vou_date=<?php echo $vou_date; ?>&vou_format=<?php echo $vou_format; ?>&emp_name=<?php echo $emp_name; ?>&emp_id=<?php echo $emp_id; ?>">
                        <i class="fa-solid fa-circle-plus" style="color: #0f0f0f;"></i> &nbsp; &nbsp; New
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-item">
                        <input type="date" id="datePicker-<?php echo $serial; ?>" class="form-control">
                        <a id="dateLink-<?php echo $serial; ?>" class="btn btn-primary mt-2" href="">Select Date</a>
                      </div>
                    </li>
                  </ul>
                </div>
              </td>
              <td class="first_cont">
                <a href="voucher_off_outcome.php?emp_name=<?php echo $emp_name; ?>&emp_id=<?php echo $emp_id; ?>"> <i
                    class="fa-solid fa-person-rays" style="color: #050505;"></i></a>
              </td>
              <td class="first_cont">
                <a
                  href="voucher_month_view.php?emp_name=<?php echo $emp_name; ?>&emp_id=<?php echo $emp_id; ?>&vou_format=<?php echo $vou_format; ?>">
                  <i class="fa-solid fa-eye" style="color: #030303;"></i></a>
              </td>
              <td>
                <a
                  href="voucher_files.php?vou_format=<?php echo $vou_format; ?>&emp_name=<?php echo $emp_name; ?>&emp_id=<?php echo $emp_id; ?>">
                  <i class="fa-solid fa-folder-open" style="color: #050505;"></i></a>
              </td>

            </tr>
            <script>
              document.addEventListener('DOMContentLoaded', function () {
                const datePicker = document.getElementById('datePicker-<?php echo $serial; ?>');
                const dateLink = document.getElementById('dateLink-<?php echo $serial; ?>');
                const empName = '<?php echo $emp_name; ?>';
                const empId = '<?php echo $emp_id; ?>';
                const vouFormat = '<?php echo $vou_format; ?>';

                datePicker.addEventListener('change', function () {
                  const selectedDate = this.value;

                  if (selectedDate) {
                    dateLink.href = `voucher_form.php?vou_date=${selectedDate}&vou_format=${vouFormat}&emp_name=${empName}&emp_id=${empId}`;
                  } else {
                    dateLink.href = '#';
                  }
                });

                dateLink.addEventListener('click', function (event) {
                  const selectedDate = datePicker.value;
                  if (!selectedDate) {
                    event.preventDefault();
                    alert('Please select a date.');
                  }
                });
              });
            </script>
          <?php endwhile;
        } ?>
      </tbody>
    </table>
  </div>

  <script src="https://kit.fontawesome.com/dcf20061c6.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>