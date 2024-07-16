<?php

require('web_acsdb.php');

$colName = $_GET['colName'];
$colValue = $_GET['colValue'];

echo "colname ".$colName;
echo "colvalue ".$colValue;
echo "*****************";


$strArray = explode("-", $colName);

$sl_seq_no = $strArray[1];
$column = $strArray[2];

$updateQuery = "UPDATE reports_sl_data SET ".$column." = "."'$colValue'". " where sl_seq_id = ".$sl_seq_no;
echo $updateQuery;

$result = $mysqli->query($updateQuery);

echo "Success";