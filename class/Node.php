<?php

include("NodeFlags.php");
include("NodeStatistics.php");
include("NodeInfo.php");

class Node
{

    private $firstseen;
    private $lastseen;
    private $flag;
    private $statistics;
    private $nodeinfo;

   

    public function fillRRDData(){
        if(!$this->checkRRDFileExists()){
            $this->createRRDFile();
        }
        $data = Array();
        $data[] = time();
        
        //TODO: Memory Usage
        $memoryUsage = $this->getStatistics()->getMemoryUsage() * 100;
        $data[] = $memoryUsage;
        
        //$data[] = time();
        //TODO: clients
        $clients = $this->getStatistics()->getClients();
        $data[] = $clients;
        //TODO: rootfs_usage
        $rootfs = $this->getStatistics()->getRootfsUsage() * 100;
        $data[] = $rootfs;
        //TODO: loadavg
        $loadavg = $this->getStatistics()->getLoadavg();
        $data[] = $loadavg;
        //TODO: traffic
        $trafficMgmtRxbytes = $this->getStatistics()->getTraffic()->getMgmtRx()->getBytes();
        $data[] = $trafficMgmtRxbytes;
        $trafficMgmtRxpackets = $this->getStatistics()->getTraffic()->getMgmtRx()->getPackets();
        $data[] = $trafficMgmtRxpackets;
        $trafficMgmtTxbytes = $this->getStatistics()->getTraffic()->getMgmtTx()->getBytes();
        $data[] = $trafficMgmtTxbytes;
        $trafficMgmtTxpackets = $this->getStatistics()->getTraffic()->getMgmtTx()->getPackets();
        $data[] = $trafficMgmtTxpackets;
        $trafficRxbytes = $this->getStatistics()->getTraffic()->getRx()->getBytes();
        $data[] = $trafficRxbytes;
        $trafficRxpackets = $this->getStatistics()->getTraffic()->getRx()->getPackets();
        $data[] = $trafficRxpackets;
        $trafficTxbytes = $this->getStatistics()->getTraffic()->getTx()->getBytes();
        $data[] = $trafficTxbytes;
        $trafficTxpackets = $this->getStatistics()->getTraffic()->getTx()->getPackets();
        $data[] = $trafficTxpackets;
        $trafficForwardedBytes = $this->getStatistics()->getTraffic()->getForward()->getBytes();
        $data[] = $trafficForwardedBytes;
        $trafficForwardedPackets = $this->getStatistics()->getTraffic()->getForward()->getPackets();
        $data[] = $trafficForwardedPackets;
        
       
        $string = implode(":",$data);
        
        $ret = rrd_update($this->getRRDFileName(), array($string));
    }

    private function checkRRDFileExists(){
        return file_exists($this->getRRDFileName());
    }

    private function createRRDFile(){
        $options = array(
            "--step", "60",            // Use a step-size of 5 minutes
            "DS:memoryUsage:GAUGE:600:0:100",
            "DS:clients:GAUGE:600:0:U",
            "DS:rootfsUsage:GAUGE:600:0:100",
            "DS:loadavg:GAUGE:600:0:U",
            "DS:trafMgmtRxBy:COUNTER:600:0:U",
            "DS:trafMgmtRxPa:COUNTER:600:0:U",
            "DS:trafMgmtTxBy:COUNTER:600:0:U",
            "DS:trafMgmtTxPa:COUNTER:600:0:U",            
            "DS:trafRxBy:COUNTER:600:0:U",
            "DS:trafRxPa:COUNTER:600:0:U",            
            "DS:trafTxBy:COUNTER:600:0:U",
            "DS:trafTxPa:COUNTER:600:0:U",            
            "DS:trafForwardBy:COUNTER:600:0:U",
            "DS:trafForwardPa:COUNTER:600:0:U",
            "RRA:AVERAGE:0.5:1:10080", //every minute one week
            "RRA:AVERAGE:0.5:60:8760", //
            "RRA:AVERAGE:0.5:1440:5256",
        );

        $ret = rrd_create($this->getRRDFileName(), $options);
        echo rrd_error();
    }

    public function getRRDFileName(){
        return dirname(__FILE__)."/../rrdData/nodes/".$this->nodeinfo->getNodeId().".rrd";
    }
    
    public function getFileName($type, $interval, $width, $height){
        return dirname(__FILE__)."/../graphs/nodes/".$this->nodeinfo->getNodeId()."_".$type."_".$interval."_".$width."_".$height.".png";
    }

    public function makeGraph($type, $interval, $width, $height){
        switch($type){
            case "clients":         $this->createGraphClients($interval,"Client Node: ".$this->nodeinfo->getNodeId(), $width, $height);
                                    break;
            case "traffic":         $this->createGraphTraffic($interval,"Traffic Bytes Node: ".$this->nodeinfo->getNodeId(), $width, $height);
                                    break;
            case "trafficPackages": $this->createGraphTrafficPackages($interval,"Traffic Packages Node: ".$this->nodeinfo->getNodeId(), $width, $height);
                                    break;
            case "memoryUsage":     $this->createGraphMemory($interval,"Memory Usage Node: ".$this->nodeinfo->getNodeId(), $width, $height);
                                    break;
        }
        
    }
    
    private function createGraphClients($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Clients",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "DEF:clients=".$this->getRRDFileName().":clients:AVERAGE",
            "AREA:clients#00FF00:Clients online",
        );
        $ret = rrd_graph($this->getFileName("clients",$start, $width, $height),$options);
        echo rrd_error();
    }

    private function createGraphMemory($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Memory Usage in %",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "DEF:memoryUsage=".$this->getRRDFileName().":memoryUsage:AVERAGE",
            "AREA:memoryUsage#00FF00:memoryUsage",
        );
        $ret = rrd_graph($this->getFileName("memoryUsage",$start, $width, $height),$options);
        echo rrd_error();
    }
    
    private function createGraphTraffic($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Traffic Bytes",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "DEF:trafRxBy=".$this->getRRDFileName().":trafRxBy:AVERAGE",
            "DEF:trafTxBy=".$this->getRRDFileName().":trafTxBy:AVERAGE",
            "DEF:trafMgmtTxBy=".$this->getRRDFileName().":trafMgmtTxBy:AVERAGE",
            "DEF:trafMgmtRxBy=".$this->getRRDFileName().":trafMgmtRxBy:AVERAGE",
            "DEF:trafForwardBy=".$this->getRRDFileName().":trafForwardBy:AVERAGE",
            "LINE2:trafRxBy#00FF00:trafRxBy",
            "LINE2:trafTxBy#F06FF0:trafTxBy",
            "LINE2:trafMgmtTxBy#0F0FF0:trafMgmtTxBy",
            "LINE2:trafMgmtRxBy#FFFF0F:trafMgmtRxBy",
            "LINE2:trafForwardBy#124f77:trafForwardBy",
        );
        $ret = rrd_graph($this->getFileName("traffic",$start, $width, $height),$options);
        echo rrd_error();
    }

    private function createGraphTrafficPackages($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Traffic Packages",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "DEF:trafRxPa=".$this->getRRDFileName().":trafRxPa:AVERAGE",
            "DEF:trafTxPa=".$this->getRRDFileName().":trafTxPa:AVERAGE",
            "DEF:trafMgmtTxPa=".$this->getRRDFileName().":trafMgmtTxPa:AVERAGE",
            "DEF:trafMgmtRxPa=".$this->getRRDFileName().":trafMgmtRxPa:AVERAGE",
            "DEF:trafForwardPa=".$this->getRRDFileName().":trafForwardPa:AVERAGE",
            "LINE2:trafRxPa#00FF00:trafRxPa",
            "LINE2:trafTxPa#F06FF0:trafTxPa",
            "LINE2:trafMgmtTxPa#0F0FF0:trafMgmtTxPa",
            "LINE2:trafMgmtRxPa#FFFF0F:trafMgmtRxPa",
            "LINE2:trafForwardPa#124f77:trafForwardPa",
        );
        $ret = rrd_graph($this->getFileName("trafficPackages",$start, $width, $height),$options);
        echo rrd_error();
    }

     
    /**
     * @return mixed
     */
    public function getFirstseen()
    {
        return $this->firstseen;
    }

    /**
     * @param mixed $firstseen
     */
    public function setFirstseen($firstseen)
    {
        $this->firstseen = $firstseen;
    }

    /**
     * @return mixed
     */
    public function getLastseen()
    {
        return $this->lastseen;
    }

    /**
     * @param mixed $lastseen
     */
    public function setLastseen($lastseen)
    {
        $this->lastseen = $lastseen;
    }

    /**
     * @return mixed
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param mixed $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * @return mixed
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param mixed $statistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * @return mixed
     */
    public function getNodeinfo()
    {
        return $this->nodeinfo;
    }

    /**
     * @param mixed $nodeinfo
     */
    public function setNodeinfo($nodeinfo)
    {
        $this->nodeinfo = $nodeinfo;
    }


}