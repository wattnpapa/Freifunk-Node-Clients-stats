<?php

include("NodeHardware.php");
include("NodeLocation.php");
include("NodeSystem.php");
include("NodeSoftware.php");
include("NodeOwner.php");
include("NodeNetwork.php");

class NodeInfo
{

    /**
     * @var
     */
    private $hostname;
    /**
     * @var NodeHardware
     */
    private $hardware;
    /**
     * @var NodeLocation
     */
    private $location;
    /**
     * @var NodeSystem
     */
    private $system;
    /**
     * @var NodeSoftware
     */
    private $software;
    /**
     * @var
     */
    private $node_id;
    /**
     * @var
     */
    private $owner;
    /**
     * @var NodeNetwork
     */
    private $network;

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
     * @return NodeHardware
     */
    public function getHardware()
    {
        return $this->hardware;
    }

    /**
     * @param NodeHardware $hardware
     */
    public function setHardware($hardware)
    {
        $this->hardware = $hardware;
    }

    /**
     * @return NodeLocation
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param NodeLocation $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return NodeSystem
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * @param NodeSystem $system
     */
    public function setSystem($system)
    {
        $this->system = $system;
    }

    /**
     * @return NodeSoftware
     */
    public function getSoftware()
    {
        return $this->software;
    }

    /**
     * @param NodeSoftware $software
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
     * @return NodeNetwork
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param NodeNetwork $network
     */
    public function setNetwork($network)
    {
        $this->network = $network;
    }




}