<?php

include("class/System.php");

//DEFAULTS
$interval = "1d";
$width = 800;
$height = 200;
$type = "clients";

if(isset($_GET['interval']))
    $interval = $_GET['interval'];

if(isset($_GET['type']))
    $type = $_GET['type'];

if(isset($_GET['width']))
    $width = $_GET['width'];

if(isset($_GET['height']))
    $height = $_GET['height'];

$system = new System();

$system->makeGraph($type,$interval,$width,$height);

$im = file_get_contents($system->getFileName($type,$interval,$width,$height));
header('content-type: image/png');
echo $im; 
?>