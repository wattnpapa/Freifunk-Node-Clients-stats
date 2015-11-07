<?php

include("class/Node.php");

$mac = "54e6fcaf3efc";
$node = new Node();
$nodeinfo = new NodeInfo();
$nodeinfo->setNodeId($mac);
$node->setNodeinfo($nodeinfo);
$node->makeGraph();
?>