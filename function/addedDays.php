<?php
require_once('../../../../wp-load.php');

$date_begin = $_POST["dataBegin"];
$dateadded = strtotime(date("Y-m-d", strtotime($date_begin)) . $_POST["typeAdded"]);
echo date_i18n(get_option('date_format') , $dateadded);