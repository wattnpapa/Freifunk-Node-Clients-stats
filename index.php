<?php

include("class/Data.php");

$data = new Data("http://mesh.sjr-ol.de/data/nodes.json");

$data->catchData();

print_r($data->getData());

?>