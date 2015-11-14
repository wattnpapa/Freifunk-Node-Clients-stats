<?php

include("class/Node.php");

$type = "System";
$mac = "14cc2091ecd0";
if(isset($_GET['type']))
    $type = $_GET['type'];

if($type == "Node"){
    if(isset($_GET['mac']))
        $mac = $_GET['mac'];
}

$config = json_decode(file_get_contents("config.json"),true);

?>

<html>
<head>
    <title><?php echo $config["communityName"]; ?> Statistics</title>
    <!-- Bootstrap core CSS -->
    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

    <script type="text/javascript" src="lib/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" >
        $('#myTabs a').click(function (e) {
            e.preventDefault()
            $(this).tab('show')
        })

    </script>
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
        <h3 class="text-muted"><img src="<?php echo $config["logoUrl"]; ?>" height="50"/>&nbsp;<?php echo $config["communityName"]; ?> Statistics
        <?php
            if($type == "Node"){
                $node = new Node();
                $node->initFromFile($mac);
                echo " - Node: ".$node->getNodeinfo()->getHostname();
            };
        ?>

        </h3>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#hour" aria-controls="home" role="tab" data-toggle="tab">Hour</a></li>
                <li role="presentation"><a href="#day" aria-controls="day" role="tab" data-toggle="tab">Day</a></li>
                <li role="presentation"><a href="#week" aria-controls="week" role="tab" data-toggle="tab">Week</a></li>
                <li role="presentation"><a href="#month" aria-controls="month" role="tab" data-toggle="tab">Month</a></li>
                <li role="presentation"><a href="#year" aria-controls="year" role="tab" data-toggle="tab">Year</a></li>
            </ul>




        <?php
        if($type == "System"){
            ?>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="hour">
                    <img src="getSystemGraph.php?type=clients&interval=1H&width=800" />
                    <img src="getSystemGraph.php?type=nodes&interval=1H&width=800" />
                    <img src="getSystemGraph.php?type=firmware&interval=1H&width=800" />
                </div>
                <div role="tabpanel" class="tab-pane" id="day">
                    <img src="getSystemGraph.php?type=clients&interval=1D&width=800" />
                    <img src="getSystemGraph.php?type=nodes&interval=1D&width=800" />
                    <img src="getSystemGraph.php?type=firmware&interval=1D&width=800" />
                </div>
                <div role="tabpanel" class="tab-pane" id="week">
                    <img src="getSystemGraph.php?type=clients&interval=1W&width=800" />
                    <img src="getSystemGraph.php?type=nodes&interval=1W&width=800" />
                    <img src="getSystemGraph.php?type=firmware&interval=1W&width=800" />
                </div>
                <div role="tabpanel" class="tab-pane" id="month">
                    <img src="getSystemGraph.php?type=clients&interval=1m&width=800" />
                    <img src="getSystemGraph.php?type=nodes&interval=1m&width=800" />
                    <img src="getSystemGraph.php?type=firmware&interval=1m&width=800" />
                </div>
                <div role="tabpanel" class="tab-pane" id="year">
                    <img src="getSystemGraph.php?type=clients&interval=1Y&width=800" />
                    <img src="getSystemGraph.php?type=nodes&interval=1Y&width=800" />
                    <img src="getSystemGraph.php?type=firmware&interval=1Y&width=800" />
                </div>
            </div>
            <?php
        }
        if($type == "Node"){
            ?>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="hour">
                    <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=clients" />
                    <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=traffic" />
                    <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=trafficPackages" />
                    <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=memoryUsage" />
                    <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=rootfsUsage" />
                    <img src="getNodeGraph.php?interval=1H&mac=<?php echo $mac;?>&type=loadavg" />
                </div>
                <div role="tabpanel" class="tab-pane" id="day">
                    <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=clients" />
                    <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=traffic" />
                    <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=trafficPackages" />
                    <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=memoryUsage" />
                    <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=rootfsUsage" />
                    <img src="getNodeGraph.php?interval=1D&mac=<?php echo $mac;?>&type=loadavg" />
                </div>
                <div role="tabpanel" class="tab-pane" id="week">
                    <img src="getNodeGraph.php?interval=1W&mac=<?php echo $mac;?>&type=clients" />
                    <img src="getNodeGraph.php?interval=1W&mac=<?php echo $mac;?>&type=traffic" />
                    <img src="getNodeGraph.php?interval=1W&mac=<?php echo $mac;?>&type=trafficPackages" />
                    <img src="getNodeGraph.php?interval=1W&mac=<?php echo $mac;?>&type=memoryUsage" />
                    <img src="getNodeGraph.php?interval=1W&mac=<?php echo $mac;?>&type=rootfsUsage" />
                    <img src="getNodeGraph.php?interval=1W&mac=<?php echo $mac;?>&type=loadavg" />
                </div>
                <div role="tabpanel" class="tab-pane" id="month">
                    <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=clients" />
                    <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=traffic" />
                    <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=trafficPackages" />
                    <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=memoryUsage" />
                    <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=rootfsUsage" />
                    <img src="getNodeGraph.php?interval=1m&mac=<?php echo $mac;?>&type=loadavg" />
                </div>
                <div role="tabpanel" class="tab-pane" id="year">
                    <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=clients" />
                    <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=traffic" />
                    <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=trafficPackages" />
                    <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=memoryUsage" />
                    <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=rootfsUsage" />
                    <img src="getNodeGraph.php?interval=1Y&mac=<?php echo $mac;?>&type=loadavg" />
                </div>
            </div>
            <?php
        }
        ?>
        </div>
    </div>
</div> <!-- /container -->




</body>
</html>