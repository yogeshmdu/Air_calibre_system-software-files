<!-- ------------------ Connection of the localhost --------------- -->
<?php
// error_reporting(0);

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "acsindia_duplicate";

// $mysqli = new mysqli($servername, $username, $password, $dbname) or die(mysqli_error($mysqli));

// if ($mysqli->connect_errno) {
//    echo "Connection error! " . $mysqli->connect_error;
// }
// echo "Connected successfully";


$servername = "localhost";
$username = "acsindia_acsdb";
$password = "reports2022";
$dbname = "acsindia_ver2_dbnew";
error_reporting(0);

$mysqli = new mysqli($servername,$username,$password,$dbname) or die(mysqli_error($mysqli));

if($mysqli -> connect_errno){
   echo "Connection error! " . $mysqli -> connect_error;
}

$mysqli->query("SET SESSION sql_mode = ''");
?>
