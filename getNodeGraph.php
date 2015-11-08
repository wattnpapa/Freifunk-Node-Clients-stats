<?php

include("class/Node.php");

//DEFAULTS
$mac = "e8de27f3004e";
$interval = "1d";
$width = 800;
$height = 200;
$type = "traffic";

if(isset($_GET['mac']))
    $mac = $_GET['mac'];

if(isset($_GET['type']))
    $type = $_GET['type'];

if(isset($_GET['width']))
    $width = $_GET['width'];

if(isset($_GET['height']))
    $height = $_GET['height'];

if(isset($_GET['interval']))
    $interval = $_GET['interval'];


$node = new Node();
$nodeinfo = new NodeInfo();
$nodeinfo->setNodeId($mac);
$node->setNodeinfo($nodeinfo);
$node->makeGraph($type,$interval,$width,$height);

$im = file_get_contents($node->getFileName($type,$interval,$width,$height));
header('content-type: image/png');
echo $im; 
?>