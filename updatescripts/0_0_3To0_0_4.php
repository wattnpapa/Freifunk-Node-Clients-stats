<?php
/**
 * Created by IntelliJ IDEA.
 * User: johannes
 * Date: 15.11.15
 * Time: 13:41
 */

include("../class/RRD.php");

$rrdFileNodes = "../rrdData/system/system.rrd";

//Rename nodes to nodesOnline
rrd_tune($rrdFileNodes,array("--data-source-rename","nodes:nodesOnline"));

RRD::addDS2RRDFile($rrdFileNodes,"nodesOffline");