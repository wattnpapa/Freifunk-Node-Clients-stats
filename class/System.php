<?php

/**
 * Created by IntelliJ IDEA.
 * User: johannes
 * Date: 08.11.15
 * Time: 12:12
 */
class System
{
    private $counterClients;
    private $counterOnlineNodes;
    private $counterOfflineNodes;
    private $nodeFirmware;
    private $autoupdater;

    private $rrdPath;

    /**
     * System constructor.
     */
    public function __construct()
    {
        $this->counterClients = 0;
        $this->counterOnlineNodes = 0;
        $this->counterOfflineNodes = 0;
        $this->nodeFirmware = array();


        $this->rrdFile = dirname(__FILE__)."/../rrdData/system/system.rrd";
        $this->graphFolder = dirname(__FILE__)."/../graphs/system/";

    }

    public function addClients($clients){
        $this->counterClients += $clients;
    }

    public function addOnlineNode(){
        $this->counterOnlineNodes++;
    }

    public function addOfflineNode(){
        $this->counterOfflineNodes++;
    }

    private function createRRDFile(){
        //RRD File Clients / Nodes
        $options = array(
            "--step", "60",            // Use a step-size of 5 minutes
            "DS:clients:GAUGE:600:0:U",
            "DS:nodesOnline:GAUGE:600:0:U",
            "DS:nodesOffline:GAUGE:600:0:U",
            "RRA:AVERAGE:0.5:1:10080", //every minute one week
            "RRA:AVERAGE:0.5:60:8760", //
            "RRA:AVERAGE:0.5:1440:5256",
        );

        $ret = rrd_create($this->rrdFile, $options);
        echo rrd_error();

        //RRD File Firmware
    }

    private function checkRRDFile(){
        return file_exists($this->rrdFile);
    }

    public function getFileName($type, $interval, $width, $height){
        return dirname(__FILE__)."/../graphs/system/".$type."_".$interval."_".$width."_".$height.".png";
    }


    public function fillRRDData(){
        if(!$this->checkRRDFile()){
            $this->createRRDFile();
        }

        $data = Array();
        $data[] = time();

        $data[] = $this->counterClients;
        $data[] = $this->counterOnlineNodes;
        $data[] = $this->counterOfflineNodes;

        $string = implode(":",$data);

        echo $string;

        $ret = rrd_update($this->rrdFile, array($string));
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
        );
        $ret = rrd_graph($this->getFileName("clients", $start, $width, $height),$options);
        echo rrd_error();
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
            "AREA:nodesOffline#ff0000:nodesOffline",
            "STACK:nodesOnline#00FF00:nodesOnline",
        );
        $ret = rrd_graph($this->getFileName("nodes", $start, $width, $height),$options);
        echo rrd_error();
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
}