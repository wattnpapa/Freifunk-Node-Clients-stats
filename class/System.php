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