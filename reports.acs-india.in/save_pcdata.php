<?php

require('web_acsdb.php');

$colName = $_GET['colName'];
$colValue = $_GET['colValue'];

echo "colname ".$colName;
echo "colvalue ".$colValue;
echo "*****************";

$strArray = explode("-", $colName);

$av_seq_no = $strArray[1];
$column = $strArray[2];

$updateQuery = "UPDATE reports_pc_data SET ".$column." = "."'$colValue'". " where pc_seq_no = ".$av_seq_no;
echo $updateQuery;

$result = $mysqli->query($updateQuery);

echo "Success";
