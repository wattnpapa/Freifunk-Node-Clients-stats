<?php

include(dirname(__FILE__) ."/class/Data.php");

$config = json_decode(file_get_contents(dirname(__FILE__)."/config.json"),true);

$url =  $config["dataUrl"];
$data = new Data($url);

?>