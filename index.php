<?php

$type = "System";
$mac = "14cc2091ecd0";
if(isset($_GET['type']))
    $type = $_GET['type'];

if($type == "Node"){
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
            <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=clients" />
            <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=traffic" />
            <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=trafficPackages" />
            <h1>Last Day</h1>
            <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=clients" />
            <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=traffic" />
            <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=trafficPackages" />
            <h1>Last Month</h1>
            <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=clients" />
            <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=traffic" />
            <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=trafficPackages" />
            <h1>Last Year</h1>
            <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=clients" />
            <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=traffic" />
            <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=trafficPackages" />
            <?php
        }
    ?>

</body>
</html>