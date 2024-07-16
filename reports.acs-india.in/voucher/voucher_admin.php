<?php 

include("web_acsdb.php");

$vou_format = "";

if(isset($_GET['vou_format'])){
    $vou_format = $_GET['vou_format'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Voucher Admin </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

  <style>
    table {
      width: 100%;
      border: 1px solid rgb(49, 48, 48);
      margin: 10px 10px 10px 10px;
    }

    thead {
      color: red;
      align-items: center;
    }

    tbody tr {
      text-align: center;
    }

    .th_data {
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="full_cont">
    <table class="table">

      <h3 style="text-align: center; margin-top: 10px;"> ACS - Reiumbersement Voucher </h3>

      <thead class="table text-center">
        <tr>
          <th class="th_data" rowspan="2" scope="col"> S.No </th>
          <th class="th_data" rowspan="2" scope="col"> Employee Name </th>
          <th class="th_data" rowspan="2" scope="col"> Date </th>
          <!-- <th class="th_data" rowspan="2" scope="col"> Closing Balance </th> -->
          <!-- <th class="th_data" rowspan="2" scope="col"> Voucher Advance </th> -->
          <th scope="col" colspan="3"> Voucher Action </th>
        </tr>
        <tr>
          <th class="py-2" scope="col" style="padding-left: 10px; padding-right: 10px;"> Add </th>
          <th class="py-2" scope="col" style="padding-left: 10px; ">Files</th>
          
        </tr>
      </thead>
      

      <tbody>
        
        <?php 
        
        $employee_detail = ("SELECT * FROM employee where user_type = 'Super' and employee_status = 'Active'");
        $employee_result = $mysqli->query($employee_detail);

        $serial = 1;
        
        while ($employee_details = $employee_result->fetch_assoc()) :

          $emp_id = $employee_details['employee_id'];
          $emp_name = $employee_details['employee_name'];
          // $emp_phone = $employee_details['phone'];
        ?>
        <tr>
          <th scope="row"> <?php echo $serial++; ?> </th>
          <td> <?php echo $emp_name; ?> </td>
          
          <td> <label for=""></label>

            <script>
              var d = (new Date()).toString().split(' ').splice(1, 3).join(' ');
              document.write(d)
            </script>
          </td>
          <!-- <td>
            <?php //echo $emp_phone; ?>
          </td> -->
          <!-- <td> <label for=""> 200000</label></td> -->

          <td class="first_cont ">
            <a href="voucher_form.php?vou_format=<?php echo $vou_format; ?>&emp_name=<?php echo $emp_name; ?>&emp_id=<?php echo $emp_id; ?>"> <i class="fa-solid fa-circle-plus" style="color: #0f0f0f;"></i></a>

          </td>
          <!-- <td class="first_cont">
            <a href="emp_name=<?php //echo $emp_name; ?>&emp_id=<?php //echo $emp_id; ?>"> <i class="fa-solid fa-user-pen" style="color: #050505;"></i></a>

          </td>
          <td class="first_cont">
            <a href="voucher_view.php"> <i class="fa-solid fa-eye" style="color: #030303;"></i></a>
          </td> -->
          <td>
          <a href="voucher_files.php?vou_format=<?php echo $vou_format; ?>&emp_name=<?php echo $emp_name; ?>&emp_id=<?php echo $emp_id; ?>"> <i class="fa-solid fa-folder-open" style="color: #050505;"></i></a>

          </td>
          
        </tr>

        <?php endwhile; ?>

      </tbody>
    </table>
  </div>

  <script src="https://kit.fontawesome.com/dcf20061c6.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>

</body>

</html>