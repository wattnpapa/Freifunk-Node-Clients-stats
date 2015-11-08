<?php

include("class/System.php");

//DEFAULTS
$interval = "1d";
$width = 800;
$height = 200;

if(isset($_GET['interval']))
    $interval = $_GET['interval'];

if(isset($_GET['type']))
    $type = $_GET['type'];

if(isset($_GET['width']))
    $width = $_GET['width'];

$system = new System();


$system->makeGraph($interval,$width,$height);

$im = file_get_contents($system->getFileURL($interval,$width,$height));
header('content-type: image/png');
echo $im; 
?>