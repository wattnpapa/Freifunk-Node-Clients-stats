<?php

include("NodeFlags.php");
include("NodeStatistics.php");
include("NodeInfo.php");
include_once("RRD.php");

class Node
{

    private $firstseen;
    private $lastseen;
    private $flag;
    private $statistics;
    private $nodeinfo;

    private $rawDataJson;
    private $rawData;


    private function getRawFilePath($mac){
        return dirname(__FILE__)."/../nodedata/".$mac.".json";
    }

    public function initFromFile($mac){
        $this->readRawData($mac);
    }

    private function writeRawData($mac){
        $fd = fopen($this->getRawFilePath($mac), 'w');
        fwrite($fd,$this->rawDataJson);
        fclose($fd);
    }

    private function readRawData($mac){
        $fd = fopen($this->getRawFilePath($mac), 'r');
        $this->rawDataJson = fread($fd,filesize($this->getRawFilePath($mac)));
        fclose($fd);
        $this->rawData = json_decode($this->rawDataJson,true);
        $this->parseRawData();
    }

    public function setRawData($json){
        $this->rawDataJson = $json;
        $this->rawData = json_decode($this->rawDataJson,true);
        $this->writeRawData($this->rawData['nodeinfo']['node_id']);
    }

    public function parseRawData(){

        ////////
        $flags = new NodeFlags($this->rawData['flags']['gateway'],$this->rawData['flags']['online']);
        ////////

        ////////
        if(isset($this->rawData['statistics']['traffic'])){
            $mgmtTx = new Traffic($this->rawData['statistics']['traffic']['mgmt_tx']['packets'],$this->rawData['statistics']['traffic']['mgmt_tx']['bytes']);
            $forward = new Traffic($this->rawData['statistics']['traffic']['forward']['packets'],$this->rawData['statistics']['traffic']['forward']['bytes']);
            $rx = new Traffic($this->rawData['statistics']['traffic']['rx']['packets'],$this->rawData['statistics']['traffic']['rx']['bytes']);
            $mgmtRx = new Traffic($this->rawData['statistics']['traffic']['mgmt_rx']['packets'],$this->rawData['statistics']['traffic']['mgmt_rx']['bytes']);
            $tx = new Traffic($this->rawData['statistics']['traffic']['tx']['packets'],$this->rawData['statistics']['traffic']['tx']['bytes']);
            $nodeTraffic = new NodeTraffic($mgmtTx,$forward,$rx,$mgmtRx,$tx);
        }
        else{
            $nodeTraffic = new NodeTraffic(new Traffic(0,0),new Traffic(0,0),new Traffic(0,0),new Traffic(0,0),new Traffic(0,0));
        }
        ////////

        ////////
        if(isset($this->rawData['statistics']['memory_usage']))
            $memoryUsage = $this->rawData['statistics']['memory_usage'];
        else
            $memoryUsage = 0;
        if(isset($this->rawData['statistics']['clients']))
            $clients = $this->rawData['statistics']['clients'];
        else
            $clients = 0;
        if(isset($this->rawData['statistics']['rootfs_usage']))
            $rootfsUsage = $this->rawData['statistics']['rootfs_usage'];
        else
            $rootfsUsage = 0;
        if(isset($this->rawData['statistics']['uptime']))
            $uptime = $this->rawData['statistics']['uptime'];
        else
            $uptime = 0;
        if(isset($this->rawData['statistics']['gateway']))
            $gateway = $this->rawData['statistics']['gateway'];
        else
            $gateway = 0;
        if(isset($this->rawData['statistics']['loadavg']))
            $loadavg = $this->rawData['statistics']['loadavg'];
        else
            $loadavg = 0;

        $statistics = new NodeStatistics($memoryUsage
            ,$clients
            ,$rootfsUsage
            ,$uptime
            ,$gateway
            ,$loadavg
            ,$nodeTraffic);
        ////////


        ////////
        $hostname = $this->rawData['nodeinfo']['hostname'];

        if(isset($this->rawData['nodeinfo']['hardware']['nproc']))
            $nproc = $this->rawData['nodeinfo']['hardware']['nproc'];
        else
            $nproc = 0;

        $hardware = new NodeHardware($nproc,$this->rawData['nodeinfo']['hardware']['model']);

        if(isset($this->rawData['nodeinfo']['location']))
            $location = new NodeLocation($this->rawData['nodeinfo']['location']['latitude'],$this->rawData['nodeinfo']['location']['longitude']);
        else
            $location = new NodeLocation(0,0);
        if(isset($this->rawData['nodeinfo']['system']))
            $system = new NodeSystem($this->rawData['nodeinfo']['system']['site_code']);
        else
            $system = new NodeSystem("");

        $autoupdate = new NodeAutoupdater($this->rawData['nodeinfo']['software']['autoupdater']['branch'],$this->rawData['nodeinfo']['software']['autoupdater']['enabled']);
        if(isset($this->rawData['nodeinfo']['software']['fastd']))
            $fastd = new NodeFastd($this->rawData['nodeinfo']['software']['fastd']['version'],$this->rawData['nodeinfo']['software']['fastd']['enabled']);
        else
            $fastd = new NodeFastd("","");
        if(isset($this->rawData['nodeinfo']['software']['batman-adv']['compat']))
            $compat = $this->rawData['nodeinfo']['software']['batman-adv']['compat'];
        else
            $compat = "";
        $batman = new NodeBadtmanAdv($this->rawData['nodeinfo']['software']['batman-adv']['version'],$compat);
        $firmware = new NodeFirmware($this->rawData['nodeinfo']['software']['firmware']['base'],$this->rawData['nodeinfo']['software']['firmware']['release']);
        $software = new NodeSoftware($autoupdate,$fastd,$batman,$firmware);

        $node_id = $this->rawData['nodeinfo']['node_id'];

        if(isset($this->rawData['nodeinfo']['owner']['contact']))
            $owner = new NodeOwner($this->rawData['nodeinfo']['owner']['contact']);
        else
            $owner = new NodeOwner("");

        if(isset($this->rawData['nodeinfo']['network']['mesh']))
            $mesh = $this->rawData['nodeinfo']['network']['mesh'];
        else
            $mesh = "";

        $network = new NodeNetwork($this->rawData['nodeinfo']['network']['addresses'],$this->rawData['nodeinfo']['network']['mesh_interfaces'],$this->rawData['nodeinfo']['network']['mac'],$mesh);

        $nodeinfo = new NodeInfo();
        $nodeinfo->setHostname($hostname);
        $nodeinfo->setHardware($hardware);
        $nodeinfo->setLocation($location);
        $nodeinfo->setSystem($system);
        $nodeinfo->setSoftware($software);
        $nodeinfo->setNodeId($node_id);
        $nodeinfo->setOwner($owner);
        $nodeinfo->setNetwork($network);

        ////////

        ////////
        //$node = new Node($this->rawData['firstseen'],$this->rawData['lastseen'],$flags,$statistics,$nodeinfo);
        $this->setFirstseen($this->rawData['firstseen']);
        $this->setLastseen($this->rawData['lastseen']);
        $this->setFlag($flags);
        $this->setStatistics($statistics);
        $this->setNodeinfo($nodeinfo);
        ////////
    }

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
        $nodeid = $this->nodeinfo->getNodeId();
        $hostname = $this->nodeinfo->getHostname();
        $nodeHeaderText = $hostname." - ".$nodeid;
        if($this->checkReDrawGraph($this->getFileName($type,$interval, $width, $height))){
            switch ($type) {
                case "clients":
                    $this->createGraphClients($interval, "Clients Online - " . $nodeHeaderText, $width, $height);
                    break;
                case "traffic":
                    $this->createGraphTraffic($interval, "Traffic Bytes - " . $nodeHeaderText, $width, $height);
                    break;
                case "trafficPackages":
                    $this->createGraphTrafficPackages($interval, "Traffic Packages - " . $nodeHeaderText, $width, $height);
                    break;
                case "memoryUsage":
                    $this->createGraphMemory($interval, "Memory Usage - " . $nodeHeaderText, $width, $height);
                    break;
                case "rootfsUsage":
                    $this->createGraphRootFs($interval, "RootFS Usage - " . $nodeHeaderText, $width, $height);
                    break;
                case "loadavg":
                    $this->createGraphLoadAvg($interval, "Load Average - " . $nodeHeaderText, $width, $height);
                    break;
            }
        }
    }

    private function checkReDrawGraph($filename){
        $filetime = filemtime($filename);
        if(!$filetime)
            return true;
        if( ($filetime+60) > time() ){
            return false;
        }
        return true;
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
            "AREA:clients#00FF00:Clients online"
        );
        RRD::createRRDGraph($this->getFileName("clients", $start, $width, $height),$options);

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
            "--upper-limit=100",
            "DEF:memoryUsage=".$this->getRRDFileName().":memoryUsage:AVERAGE",
            "AREA:memoryUsage#00FF00:memoryUsage",
        );
        RRD::createRRDGraph($this->getFileName("memoryUsage", $start, $width, $height),$options);
    }

    private function createGraphRootFs($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=RootFS Usage in %",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "--upper-limit=100",
            "DEF:rootfsUsage=".$this->getRRDFileName().":rootfsUsage:AVERAGE",
            "AREA:rootfsUsage#00FF00:rootfsUsage",
        );
        RRD::createRRDGraph($this->getFileName("rootfsUsage", $start, $width, $height),$options);
    }

    private function createGraphLoadAvg($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Load Average",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "DEF:loadavg=".$this->getRRDFileName().":loadavg:AVERAGE",
            "AREA:loadavg#00FF00:loadavg",
        );
        RRD::createRRDGraph($this->getFileName("loadavg", $start, $width, $height),$options);
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
        RRD::createRRDGraph($this->getFileName("traffic", $start, $width, $height),$options);
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
        RRD::createRRDGraph($this->getFileName("trafficPackages", $start, $width, $height),$options);
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