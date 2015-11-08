<?php

$type = "System";
$mac = "14cc2091ecd0";
if(isset($_GET['type']))
    $type = $_GET['type'];

if($type == "node"){
    if(isset($_GET['mac']))
        $mac = $_GET['mac'];
}

?>

<html>
<head>
    <title>FF Stats</title>
</head>
<body>
    <?php
        if($type == "System"){
            ?>
            <h1>Last Hour</h1>
            <img src="getSystemGraph.php?interval=1H" />
            <h1>Last Day</h1>
            <img src="getSystemGraph.php?interval=1D" />
            <h1>Last Month</h1>
            <img src="getSystemGraph.php?interval=1m" />
            <h1>Last Year</h1>
            <img src="getSystemGraph.php?interval=1Y" />
            <?php
        }
        if($type == "Node"){
            ?>
            <h1>Last Hour</h1>
            <img src="getNodeGraph.php?interval=1H&type=clients" />
            <img src="getNodeGraph.php?interval=1H&type=traffic" />
            <img src="getNodeGraph.php?interval=1H&type=trafficPackages" />
            <h1>Last Day</h1>
            <img src="getNodeGraph.php?interval=1D&type=clients" />
            <img src="getNodeGraph.php?interval=1D&type=traffic" />
            <img src="getNodeGraph.php?interval=1D&type=trafficPackages" />
            <h1>Last Month</h1>
            <img src="getNodeGraph.php?interval=1m&type=clients" />
            <img src="getNodeGraph.php?interval=1m&type=traffic" />
            <img src="getNodeGraph.php?interval=1m&type=trafficPackages" />
            <h1>Last Year</h1>
            <img src="getNodeGraph.php?interval=1Y&type=clients" />
            <img src="getNodeGraph.php?interval=1Y&type=traffic" />
            <img src="getNodeGraph.php?interval=1Y&type=trafficPackages" />
            <?php
        }
    ?>

</body>
</html>