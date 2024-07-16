<?php

require('web_acsdb.php');

$colName = $_GET['colName'];
$colValue = $_GET['colValue'];

echo "colname ".$colName;
echo "colvalue ".$colValue;
echo "*****************";


$strArray = explode("-", $colName);

$dp_seq_no = $strArray[1];
$column = $strArray[2];

$updateQuery = "UPDATE reports_differ_pressure SET ".$column." = "."'$colValue'". " where differ_seq_id = ".$dp_seq_no;
echo $updateQuery;

$result = $mysqli->query($updateQuery);

echo "Success";