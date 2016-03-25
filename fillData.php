<?php

include(dirname(__FILE__) ."/class/Data.php");

$config = json_decode(file_get_contents(dirname(__FILE__)."/config.json"),true);
$meshviewerConfig = json_decode(file_get_contents($config['meshviewerConfig']),true);

$url = array();
foreach($meshviewerConfig['dataPath'] as $dataPath){
    $url [] = $dataPath."nodes.json";
}

$data = new Data($url);

?>