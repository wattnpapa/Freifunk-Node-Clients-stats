<?php

include("NodeHardware.php");
include("NodeLocation.php");
include("NodeSystem.php");
include("NodeSoftware.php");
include("NodeOwner.php");
include("NodeNetwork.php");

class NodeInfo
{
    private $hostname;
    private $hardware;
    private $location;
    private $system;
    private $software;
    private $node_id;
    private $owner;
    private $network;

    /**
     * NodeInfo constructor.
     * @param $hostname
     * @param $hardware
     * @param $location
     * @param $system
     * @param $software
     * @param $node_id
     * @param $owner
     * @param $network
     */
    public function __construct($hostname, $hardware, $location, $system, $software, $node_id, $owner, $network)
    {
        $this->hostname = $hostname;
        $this->hardware = $hardware;
        $this->location = $location;
        $this->system = $system;
        $this->software = $software;
        $this->node_id = $node_id;
        $this->owner = $owner;
        $this->network = $network;
    }

    /**
     * @return mixed
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param mixed $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return mixed
     */
    public function getHardware()
    {
        return $this->hardware;
    }

    /**
     * @param mixed $hardware
     */
    public function setHardware($hardware)
    {
        $this->hardware = $hardware;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * @param mixed $system
     */
    public function setSystem($system)
    {
        $this->system = $system;
    }

    /**
     * @return mixed
     */
    public function getSoftware()
    {
        return $this->software;
    }

    /**
     * @param mixed $software
     */
    public function setSoftware($software)
    {
        $this->software = $software;
    }

    /**
     * @return mixed
     */
    public function getNodeId()
    {
        return $this->node_id;
    }

    /**
     * @param mixed $node_id
     */
    public function setNodeId($node_id)
    {
        $this->node_id = $node_id;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param mixed $network
     */
    public function setNetwork($network)
    {
        $this->network = $network;
    }




}