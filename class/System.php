<?php

/**
 * Created by IntelliJ IDEA.
 * User: johannes
 * Date: 08.11.15
 * Time: 12:12
 */
include_once("RRD.php");
include("FirmwareRRDDSMapping.php");
include("HardwareRRDDSMapping.php");
include("ColorTable.php");

class System
{
    private $counterClients;
    private $counterOnlineNodes;
    private $counterOfflineNodes;
    private $nodeFirmware;
    private $nodeHardware;
    private $autoupdater;

    private $rrdFile;
    private $rrdFirmwareFile;

    private $fimwareMapper;

    /**
     * System constructor.
     */
    public function __construct()
    {
        $this->counterClients = 0;
        $this->counterOnlineNodes = 0;
        $this->counterOfflineNodes = 0;
        $this->counterMeshConnections = 0;
        $this->nodeFirmware = array();
        $this->nodeHardware = array();


        $this->rrdFile = dirname(__FILE__)."/../rrdData/system/system.rrd";
        $this->rrdFirmwareFile = dirname(__FILE__)."/../rrdData/system/firmware.rrd";
        $this->rrdHardwareFile = dirname(__FILE__)."/../rrdData/system/hardware.rrd";
        $this->graphFolder = dirname(__FILE__)."/../graphs/system/";

        $this->fimwareMapper = new FirmwareRRDDSMapping();
        $this->hardwareMapper = new HardwareRRDDSMapping();

    }

    public function addClients($clients){
        $this->counterClients += $clients;
    }

    public function addMeshConnections($connections){
        $this->counterMeshConnections += $connections;
    }

    public function addOnlineNode(){
        $this->counterOnlineNodes++;
    }

    public function addOfflineNode(){
        $this->counterOfflineNodes++;
    }

    private function createRRDFile(){
        //RRD File Clients / Nodes
        $dataSources = array(
            "DS:clients:GAUGE:600:0:U",
            "DS:nodesOnline:GAUGE:600:0:U",
            "DS:nodesOffline:GAUGE:600:0:U",
            "DS:meshConnections:GAUGE:600:0:U");
        $options = RRD::getRRDFileOptions($dataSources);
        RRD::createRRDFile($this->rrdFile,$options);

    }

    private function createFirmwareRRDFile(){
        $initFirmware = "0.6";
        $initCodeFirmware = $this->fimwareMapper->addNameToMapping($initFirmware);
        $dataSources = array("DS:".$initCodeFirmware.":GAUGE:600:0:U",);
        $options = RRD::getRRDFileOptions($dataSources);
        RRD::createRRDFile($this->rrdFirmwareFile,$options);
    }

    private function createHardwareRRDFile(){
        echo "createHardwareRRD";
        $initFirmware = "TP-Link TL-WR841N/ND v9";
        $initCodeFirmware = $this->hardwareMapper->addNameToMapping($initFirmware);
        $dataSources = array("DS:".$initCodeFirmware.":GAUGE:600:0:U",);
        $options = RRD::getRRDFileOptions($dataSources);
        RRD::createRRDFile($this->rrdHardwareFile,$options);
    }

    private function checkRRDFile(){
        return file_exists($this->rrdFile);
    }

    private function checkFirmwareRRDFile(){
        return file_exists($this->rrdFirmwareFile);
    }

    private function checkHardwareRRDFile(){
        return file_exists($this->rrdHardwareFile);
    }

    public function getFileName($type, $interval, $width, $height){
        return dirname(__FILE__)."/../graphs/system/".$type."_".$interval."_".$width."_".$height.".png";
    }

    public function fillRRDData(){
        if(!$this->checkRRDFile()){
            $this->createRRDFile();
        }

        $ds = RRD::getDSFromRRDFile($this->rrdFile);
        if(!in_array("meshConnections",$ds)){
            RRD::addDS2RRDFile($this->rrdFile,"meshConnections","GAUGE",600,0,"U");
        }

        $data = Array();
        $data[] = time();

        $data[] = $this->counterClients;
        $data[] = $this->counterOnlineNodes;
        $data[] = $this->counterOfflineNodes;
        $data[] = $this->counterMeshConnections/2;

        $string = implode(":",$data);

        echo $string;

        $ret = rrd_update($this->rrdFile, array($string));
        echo rrd_error();

        $this->fillFirmwareRRDData();
        $this->fillHardwareRRDData();
    }

    public function fillFirmwareRRDData(){
        if(!$this->checkFirmwareRRDFile()){
            $this->createFirmwareRRDFile();
        }

        $data = Array();
        $data[] = time();

        $firmwareDS = RRD::getDSFromRRDFile($this->rrdFirmwareFile);
        $tmpfirmware = array();
        foreach ($this->nodeFirmware as $key => $value){
            $firmKey = $this->fimwareMapper->addNameToMapping($key);
            if(!in_array($firmKey,$firmwareDS)){
                $firmwareDS[] = $key;
                RRD::addDS2RRDFile($this->rrdFirmwareFile,$firmKey,"GAUGE",600,0,"U");
            }
            //$tmpfirmware[$firmKey] = $value;
            $data[] = $value;
        }

        /*foreach($firmwareDS as $firm){
            //echo $firm."\n";
            $data[] = $tmpfirmware[$firm];
        }*/

        $string = implode(":",$data);

        $ret = rrd_update($this->rrdFirmwareFile, array($string));
        echo rrd_error();
    }

    public function fillHardwareRRDData(){
        if(!$this->checkHardwareRRDFile()){
            $this->createHardwareRRDFile();
        }

        $data = Array();
        $data[] = time();

        $hardwareDS = RRD::getDSFromRRDFile($this->rrdHardwareFile);
        $tmphardware = array();
        foreach ($this->nodeHardware as $key => $value){
            $hardKey = $this->hardwareMapper->addNameToMapping($key);
            if(!in_array($hardKey,$hardwareDS)){
                $hardwareDS[] = $key;
                RRD::addDS2RRDFile($this->rrdHardwareFile,$hardKey,"GAUGE",600,0,"U");
            }
            $tmphardware[$hardKey] = $value;
        }

        foreach($hardwareDS as $hard){
            //echo $firm."\n";
            $data[] = $tmphardware[$hard];
        }

        $string = implode(":",$data);

        $ret = rrd_update($this->rrdHardwareFile, array($string));
        echo rrd_error();
    }

    public function makeGraph($type, $interval, $width, $height){
        if($this->checkReDrawGraph($this->getFileName($type,$interval, $width, $height))){
            switch ($type) {
                case "clients":
                    $this->createClientsGraph($interval, "Online Clients", $width, $height);
                    break;
                case "nodes":
                    $this->createNodeGraph($interval, "Online/Offline Nodes", $width, $height);
                    break;
                case "clientnodes":
                    $this->createClientNodeGraph($interval, "Clients/Nodes", $width, $height);
                    break;
                case "firmware":
                    $this->createFirmwareGraph($interval, "Firmware Versions", $width, $height);
                    break;
                case "hardware":
                    $this->createHardwareGraph($interval, "Hardware Models", $width, $height);
                    break;
                case "meshConnections":
                    $this->createMeshConnectionsGraph($interval, "Mesh Connections", $width, $height);
                    break;
            }
        }
    }

    private function checkReDrawGraph($filename){
        return true;
        if(!file_exists($filename))
            return true;
        $filetime = filemtime($filename);
        if(!$filetime)
            return true;
        if( ($filetime+60) > time() ){
            return false;
        }
        return true;
    }

    private function createClientsGraph($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Clients Online",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "DEF:clients=".$this->rrdFile.":clients:AVERAGE",
            "AREA:clients#00FF00:Clients online",
            "GPRINT:clients:LAST: Current\:%8.0lf",
            "GPRINT:clients:AVERAGE: Average\:%8.0lf",
            "GPRINT:clients:MAX: Maximum\:%8.0lf",
            "GPRINT:clients:MIN: Minimum\:%8.0lf",
        );
        RRD::createRRDGraph($this->getFileName("clients", $start, $width, $height),$options);
    }

    private function createMeshConnectionsGraph($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Mesh Connections",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "DEF:meshConnections=".$this->rrdFile.":meshConnections:AVERAGE",
            "AREA:meshConnections#00FF00:Mesh Connections",
        );
        RRD::createRRDGraph($this->getFileName("meshConnections", $start, $width, $height),$options);
    }

    private function createClientNodeGraph($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Clients",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "DEF:nodesOnline=".$this->rrdFile.":nodesOnline:AVERAGE",
            "DEF:nodesOffline=".$this->rrdFile.":nodesOffline:AVERAGE",
            "DEF:clients=".$this->rrdFile.":clients:AVERAGE",
            "AREA:nodesOnline#00FF00:nodesOnline",
            "GPRINT:nodesOnline:LAST:\t   Current\:%8.0lf",
            "GPRINT:nodesOnline:AVERAGE: Average\:%8.0lf",
            "GPRINT:nodesOnline:MAX: Maximum\:%8.0lf",
            "GPRINT:nodesOnline:MIN: Minimum\:%8.0lf",
            "COMMENT:\l",
            "AREA:nodesOffline#ff0000:nodesOffline:STACK",
            "GPRINT:nodesOffline:LAST:\t  Current\:%8.0lf",
            "GPRINT:nodesOffline:AVERAGE: Average\:%8.0lf",
            "GPRINT:nodesOffline:MAX: Maximum\:%8.0lf",
            "GPRINT:nodesOffline:MIN: Minimum\:%8.0lf",
            "COMMENT:\l",
            "LINE2:clients#0066cc:Clients online",
            "GPRINT:clients:LAST:\tCurrent\:%8.0lf",
            "GPRINT:clients:AVERAGE: Average\:%8.0lf",
            "GPRINT:clients:MAX: Maximum\:%8.0lf",
            "GPRINT:clients:MIN: Minimum\:%8.0lf",
            "COMMENT:\l",

        );
        RRD::createRRDGraph($this->getFileName("clientnodes", $start, $width, $height),$options);
    }

    private function createNodeGraph($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Clients",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "DEF:nodesOnline=".$this->rrdFile.":nodesOnline:AVERAGE",
            "DEF:nodesOffline=".$this->rrdFile.":nodesOffline:AVERAGE",
            "AREA:nodesOnline#00FF00:nodesOnline",
            "COMMENT:\t",
            "GPRINT:nodesOnline:LAST: Current\:%8.0lf",
            "GPRINT:nodesOnline:AVERAGE: Average\:%8.0lf",
            "GPRINT:nodesOnline:MAX: Maximum\:%8.0lf",
            "GPRINT:nodesOnline:MIN: Minimum\:%8.0lf",
            "COMMENT:\l",
            "AREA:nodesOffline#ff0000:nodesOffline:STACK",
            "COMMENT:\t",
            "GPRINT:nodesOffline:LAST:Current\:%8.0lf",
            "GPRINT:nodesOffline:AVERAGE: Average\:%8.0lf",
            "GPRINT:nodesOffline:MAX: Maximum\:%8.0lf",
            "GPRINT:nodesOffline:MIN: Minimum\:%8.0lf",
            "COMMENT:\l",

        );
        RRD::createRRDGraph($this->getFileName("nodes", $start, $width, $height),$options);
    }

    private function createFirmwareGraph($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Firmware",
            "--width",$width,
            "--height",$height,
            "--lower=0"
        );
        //$firmwareDS = RRD::getDSFromRRDFile($this->rrdFirmwareFile);
        //sort($firmwareDS);
        $firmwareDS = array();
        $firmwareDSsort = $this->fimwareMapper->getMappingSort();

        foreach($firmwareDSsort as $key => $value){
            $firmwareDS[] = $key;
        }

        for($i = 0;$i < count($firmwareDS); $i++){
            $firmware = $firmwareDS[$i];
            $options[] = "DEF:".$firmware."=".$this->rrdFirmwareFile.":".$firmware.":AVERAGE";
            if($i == 0)
                $options[] = "AREA:".$firmware.ColorTable::getColor($i).":".$this->fimwareMapper->getNameForCode($firmware)."\l";
            else
                $options[] = "STACK:".$firmware.ColorTable::getColor($i).":".$this->fimwareMapper->getNameForCode($firmware)."\l";
            //echo $firmware."<br>";
        }
        //print_r($options);

        RRD::createRRDGraph($this->getFileName("firmware", $start, $width, $height),$options);
    }

    private function createHardwareGraph($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", "-".$start,
            "--title=$title",
            "--vertical-label=Hardware",
            "--width",$width,
            "--height",$height,
            "--lower=0"
        );
        //$hardwareDS = RRD::getDSFromRRDFile($this->rrdHardwareFile);
        //sort($hardwareDS);

        $hardwareDS = array();
        $hardwareDSsort = $this->hardwareMapper->getMappingSort();

        foreach($hardwareDSsort as $key => $value){
            $hardwareDS[] = $key;
        }

        for($i = 0;$i < count($hardwareDS); $i++){
            $hardware = $hardwareDS[$i];
            $options[] = "DEF:".$hardware."=".$this->rrdHardwareFile.":".$hardware.":AVERAGE";
            if($i == 0)
                $options[] = "AREA:".$hardware.ColorTable::getColor($i).":".$this->hardwareMapper->getNameForCode($hardware)."\t";
            else
                $options[] = "STACK:".$hardware.ColorTable::getColor($i).":".$this->hardwareMapper->getNameForCode($hardware)."\t";
            $options[] =  'GPRINT:'.$hardware.':AVERAGE:%8.0lf %s\l';
        }
        //print_r($options);
        $options[] = "--tabwidth";
        $options[] = "350";
        RRD::createRRDGraph($this->getFileName("hardware", $start, $width, $height),$options);
    }


    /**
     * @return mixed
     */
    public function getCounterClients()
    {
        return $this->counterClients;
    }

    /**
     * @return mixed
     */
    public function getCounterOnlineNodes()
    {
        return $this->counterOnlineNodes;
    }

    /**
     * @return int
     */
    public function getCounterOfflineNodes()
    {
        return $this->counterOfflineNodes;
    }

    /**
     * @param int $counterOfflineNodes
     */
    public function setCounterOfflineNodes($counterOfflineNodes)
    {
        $this->counterOfflineNodes = $counterOfflineNodes;
    }

    /**
     * @return array
     */
    public function getNodeFirmware()
    {
        return $this->nodeFirmware;
    }

    /**
     * @param array $nodeFirmware
     */
    public function setNodeFirmware($nodeFirmware)
    {
        $this->nodeFirmware = $nodeFirmware;
    }

    /**
     * @return mixed
     */
    public function getAutoupdater()
    {
        return $this->autoupdater;
    }

    /**
     * @param mixed $autoupdater
     */
    public function setAutoupdater($autoupdater)
    {
        $this->autoupdater = $autoupdater;
    }

    /**
     * @return mixed
     */
    public function getRrdPath()
    {
        return $this->rrdPath;
    }

    /**
     * @param mixed $rrdPath
     */
    public function setRrdPath($rrdPath)
    {
        $this->rrdPath = $rrdPath;
    }

    public function addNodeFirmware($firmware){
        if(isset($this->nodeFirmware[$firmware])){
            $this->nodeFirmware[$firmware]++;
        }
        else{
            $this->nodeFirmware[$firmware] = 1;
        }
    }

    public function addNodeHardware($hardware){
        if(isset($this->nodeHardware[$hardware])){
            $this->nodeHardware[$hardware]++;
        }
        else{
            $this->nodeHardware[$hardware] = 1;
        }
    }
}