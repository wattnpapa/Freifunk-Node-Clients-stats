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

    private $rrdPath;

    /**
     * System constructor.
     */
    public function __construct()
    {
        $this->counterClients = 0;
        $this->counterOnlineNodes = 0;

        $this->rrdFile = dirname(__FILE__)."/../rrdData/system/system.rrd";
        $this->graphFolder = dirname(__FILE__)."/../graphs/system/";

    }

    public function addClients($clients){
        $this->counterClients += $clients;
    }

    public function addOnlineNode(){
        $this->counterOnlineNodes++;
    }

    private function createRRDFile(){
        $options = array(
            "--step", "60",            // Use a step-size of 5 minutes
            "DS:clients:GAUGE:600:0:U",
            "DS:nodes:GAUGE:600:0:U",
            "RRA:AVERAGE:0.5:1m:30d",
            "RRA:AVERAGE:0.5:1h:1y",
            "RRA:AVERAGE:0.5:1d:10y",
        );

        $ret = rrd_create($this->rrdFile, $options);
        echo rrd_error();
    }

    private function checkRRDFile(){
        return file_exists($this->rrdFile);
    }

    public function fillRRDData(){
        if(!$this->checkRRDFile()){
            $this->createRRDFile();
        }

        $data = Array();
        $data[] = time();

        $data[] = $this->counterClients;
        $data[] = $this->counterOnlineNodes;

        $string = implode(":",$data);

        echo $string;

        $ret = rrd_update($this->rrdFile, array($string));
        echo rrd_error();

        $this->makeGraphs();
    }

    private function makeGraphs(){
        $this->createGraphClients("-1h", "Online", 800, 200);
        $this->createGraphClients("-6h", "Online", 800, 200);
        $this->createGraphClients("-24h", "Online", 800, 200);
        $this->createGraphClients("-30d", "Online", 800, 200);
        $this->createGraphClients("-1y", "Online", 800, 200);

    }

    private function createGraphClients($start, $title, $width, $height) {
        $options = array(
            "--slope-mode",
            "--start", $start,
            "--title=$title",
            "--vertical-label=Clients",
            "--width",$width,
            "--height",$height,
            "--lower=0",
            "DEF:clients=".$this->rrdFile.":clients:AVERAGE",
            "DEF:nodes=".$this->rrdFile.":nodes:AVERAGE",
            "AREA:clients#00FF00:Clients online",
            "LINE2:nodes#FFFF00:nodes",
        );
        $ret = rrd_graph($this->graphFolder."graph".$start.".png",$options);
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
}