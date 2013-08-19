<?php
session_start();
include("functions.php");

$site = $_POST['site'];
$query = $_POST['query'];
$request = $_POST['request'];

$filename = date('j-F-Y-his') . "-export-" . $_POST['request'] . ".xls";

$contents = exportResults( $request, $site, $query);

header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename='.$filename);

echo $contents;

?>
