<?php

include("class/Data.php");

$data = new Data();

$data->catchData();

print_r($data->getData());

?>