<?php

require('web_acsdb.php');

$colName = $_GET['colName'];
$colValue = $_GET['colValue'];

echo "colname ".$colName;
echo "colvalue ".$colValue;
echo "*****************";

//$colName = "avf_1_v1";
//$colValue = 34;

//echo "colname1 ".$colName;
//echo "colvalue1 ".$colValue;

$strArray = explode("-", $colName);

$av_seq_no = $strArray[1];
$column = $strArray[2];

$updateQuery = "UPDATE air_velocity SET ".$column." = "."'$colValue'". " where av_seq_no = ".$av_seq_no;
echo $updateQuery;

$result = $mysqli->query($updateQuery);

echo "Success";
