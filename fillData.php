<?php

include("class/Data.php");

$config = json_decode(file_get_contents("config.json"),true);

$data = new Data($config["dataUrl"]);

?>