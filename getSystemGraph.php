<?php

include("class/Node.php");

//DEFAULTS
$mac = "14cc2091ecd0";
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


$node = new Node();
$nodeinfo = new NodeInfo();
$nodeinfo->setNodeId($mac);
$node->setNodeinfo($nodeinfo);
$node->makeGraph($type,$width,$height);

$im = file_get_contents($node->getFileName($type));
header('content-type: image/png');
echo $im; 
?>