
<?php 
$vou_format = "";
if(isset($_GET['vou_format'])){
    $vou_format = $_GET['vou_format'];
}
$emp_name = "";
if (isset($_GET["emp_name"])) {
    $emp_name = $_GET["emp_name"];
}
$emp_id = "";
if (isset($_GET["emp_id"])) {
    $emp_id = $_GET["emp_id"];
}
?>
<!DOCTYPE html>
<html lang="en">                                       

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> voucher data </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

  <style>
    table {
      width: 100%;
      margin-top: -8px;
      color: whitesmoke;
    }
    h3{
      background-color: #660066;
      padding: 15px 50px 15px 50px;
      color: whitesmoke;
    }

    thead {
      background-color: #AA98A2;
      
    }
    
    thead tr th{
      color: whitesmoke;
    }

    tbody tr {
      text-align: center;
    }

  </style>
</head>

<body>
<?php if( $vou_format != 1 ) { ?>
  <div class="full_cont">
    <table class="table">

      <h3 style="text-align: center; margin-top: 10px;"> ACS - Voucher Files </h3>
    
      <thead class="table text-center">
        <tr>
          <th class="th_data" rowspan="2" scope="col" > S.No </th>
          <th class="th_data" rowspan="2" scope="col"> Employee Name </th>
          <th class="th_data" rowspan="2" scope="col"> Employee ID </th>
          <th class="th_data" rowspan="2" scope="col"> Voucher Date </th>
          <th class="th_data" rowspan="2" scope="col" >Edit </th>
          <th class="th_data" rowspan="2" scope="col">View</th>
          <th class="th_data" rowspan="2" scope="col">Images</th>
          <th class="th_data" rowspan="2" scope="col">Delete</th>
        </tr>
      </thead>

      <tbody class="table-light">
      <?php 
          include 'web_acsdb.php';
          $no_query = ("SELECT * FROM emp_vou_details WHERE emp_id = '$emp_id' ORDER BY vou_date ASC");
          $no_query_result = $mysqli->query($no_query); 
          $serial = 1;
          
          while ($emp_details = $no_query_result->fetch_assoc()) :

          $serial_no = $emp_details['serial_no'];
          $emprrr_id = $emp_details['emp_id'];
          $emprrr_name = $emp_details['emp_name'];
          $vou_date = $emp_details['vou_date'];
          
        ?>
        <tr>
          <th scope="row"> <?php echo $serial++; ?> </th>
          <td><?php echo $emprrr_id ?> </td>
          <td> <?php echo $emprrr_name; ?> </td>
          
          <td><?php echo $vou_date; ?></td>
          <td class="first_cont ">
            <a href="voucher_form_update.php?vou_format=<?php echo $vou_format; ?>&serial_no=<?php echo $serial_no; ?>&emp_id=<?php echo $emp_id; ?>&emp_name=<?php echo $emp_name; ?>&vou_date=<?php echo $vou_date; ?>&edit=1"> <i class="fa-solid fa-user-pen" style="color: #050505;"></i></a>
          </td>
          <td class="first_cont">
            <a href="voucher_day_view.php?vou_format=<?php echo $vou_format; ?>&serial_no=<?php echo $serial_no; ?>"> <i class="fa-solid fa-eye" style="color: #030303;"></i></a>
          </td>
         
          <td>
            <a href="vou_img_check.php?vou_format=<?php echo $vou_format; ?>&serial_no=<?php echo $serial_no; ?>"><i class="fa-regular fa-images fa-shake" style="color: #0c0d0d;"></i></a>
          </td>
          <td class="first_cont">
          <a href="vou_delete.php?sl_no=<?php echo $serial_no; ?>&vou_format=<?php echo $vou_format; ?>&emp_id=<?php echo $emp_id; ?>">
            <i onclick="return confirm('Are you sure! want to delete?')" class="fa-solid fa-trash" style="color: #010409;" id="storeList"></i>
          </a>
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
  <script>

    function onDelete(td) {
      if (confirm('Do you want to delete this record?')) {
        row = td.parentElement.parentElement;
        document.getElementById('storeList').deleteRow(row.rowIndex);
        resetForm();
      }
    }

  </script>
<?php } else { ?> 
  <div class="full_cont">
    <table class="table ">
      
      <h3 style="text-align: center; margin-top: 10px;"> ACS - Office Voucher Format  </h3>
      
      <thead class="table text-center">
        <tr>
          <th class="th_data" scope="col"> S.No </th>
          <th class="th_data" scope="col"> ACS Name </th>
          <th class="th_data" scope="col"> ACS ID </th>
          <th class="th_data" scope="col"> Voucher Date </th>
          <th class="th_data" scope="col">Edit</th>
          <th class="th_data" scope="col">View</th>
          <th class="th_data" scope="col">Delete</th>
        </tr>
      </thead>

      <tbody class="table-light">
      <?php 
          include 'web_acsdb.php';
          $no_query = ("SELECT * FROM emp_adv_given WHERE emp_id = '$emp_id'");
          $no_query_result = $mysqli->query($no_query); 
          $serial = 1;
          
          while ($emp_details = $no_query_result->fetch_assoc()) :

          $serial_no = $emp_details['serial_no'];
          $emprrr_id = $emp_details['emp_id'];
          $emprrr_name = $emp_details['emp_name'];
          $vou_date = $emp_details['vou_date'];
          
        ?>
        <tr>
          <th scope="row"> <?php echo $serial++; ?> </th>
          <td><?php echo $emprrr_id ?> </td>
          <td> <?php echo $emprrr_name; ?> </td>
          
          <td><?php echo $vou_date; ?></td>
          <td class="first_cont ">
            <a href="voucher_form_update.php?vou_format=<?php echo $vou_format; ?>&serial_no=<?php echo $serial_no; ?>&emp_id=<?php echo $emp_id; ?>&emp_name=<?php echo $emp_name; ?>&vou_date=<?php echo $vou_date; ?>&edit=1"> <i class="fa-solid fa-user-pen" style="color: #050505;"></i></a>
          </td>
          <td class="first_cont">
            <a href="voucher_view.php?vou_format=<?php echo $vou_format; ?>&serial_no=<?php echo $serial_no; ?>"> <i class="fa-solid fa-eye" style="color: #030303;"></i></a>
          </td>
         
          <td class="first_cont">
          <a href="vou_delete.php?sl_no=<?php echo $serial_no; ?>&vou_format=<?php echo $vou_format; ?>&emp_id=<?php echo $emp_id; ?>">
            <i onclick="return confirm('Are you sure! want to delete?')" class="fa-solid fa-trash" style="color: #010409;" id="storeList"></i>
          </a>
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
  <?php } ?>

</body>

</html>