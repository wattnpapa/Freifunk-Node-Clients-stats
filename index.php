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
    <title>Freifunk Statsitics</title>
    <!-- Bootstrap core CSS -->
    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

</head>
<body>
<div class="container">
    <div class="header clearfix">
        <!--<nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation" class="active"><a href="#">Home</a></li>
                <li role="presentation"><a href="#">About</a></li>
                <li role="presentation"><a href="#">Contact</a></li>
            </ul>
        </nav>-->
        <h3 class="text-muted">Freifunk Statsitics</h3>
    </div>
    <div class="row">
        <div class="col-lg-12">
        <?php
        if($type == "System"){
            ?>
            <h1>Last Hour</h1>
            <img src="getSystemGraph.php?interval=1H&width=800" />
            <h1>Last Day</h1>
            <img src="getSystemGraph.php?interval=1D&width=800" />
            <h1>Last Month</h1>
            <img src="getSystemGraph.php?interval=1m&width=800" />
            <h1>Last Year</h1>
            <img src="getSystemGraph.php?interval=1Y&width=800" />
            <?php
        }
        if($type == "Node"){
            ?>
            <h1>Last Hour</h1>
            <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=clients" />
            <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=traffic" />
            <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=trafficPackages" />
            <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=memoryUsage" />
            <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=rootfsUsage" />
            <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=loadavg" />
            <h1>Last Day</h1>
            <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=clients" />
            <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=traffic" />
            <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=trafficPackages" />
            <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=memoryUsage" />
            <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=rootfsUsage" />
            <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=loadavg" />
            <h1>Last Month</h1>
            <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=clients" />
            <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=traffic" />
            <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=trafficPackages" />
            <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=memoryUsage" />
            <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=rootfsUsage" />
            <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=loadavg" />
            <h1>Last Year</h1>
            <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=clients" />
            <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=traffic" />
            <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=trafficPackages" />
            <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=memoryUsage" />
            <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=rootfsUsage" />
            <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=loadavg" />
            <?php
        }
        ?>
        </div>
    </div>
</div> <!-- /container -->




</body>
</html>