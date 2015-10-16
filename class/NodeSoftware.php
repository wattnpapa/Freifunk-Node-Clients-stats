<?php

include("NodeAutoupdater.php");
include("NodeFastd.php");
include("NodeBadtmanAdv.php");
include("NodeFirmware.php");

class NodeSoftware
{
    private $autoupdater;
    private $fastd;
    private $batmanAdv;
    private $firmware;

    /**
     * NodeSoftware constructor.
     * @param $autoupdater
     * @param $fastd
     * @param $batmanAdv
     * @param $firmware
     */
    public function __construct($autoupdater, $fastd, $batmanAdv, $firmware)
    {
        $this->autoupdater = $autoupdater;
        $this->fastd = $fastd;
        $this->batmanAdv = $batmanAdv;
        $this->firmware = $firmware;
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
    public function getFastd()
    {
        return $this->fastd;
    }

    /**
     * @param mixed $fastd
     */
    public function setFastd($fastd)
    {
        $this->fastd = $fastd;
    }

    /**
     * @return mixed
     */
    public function getBatmanAdv()
    {
        return $this->batmanAdv;
    }

    /**
     * @param mixed $batmanAdv
     */
    public function setBatmanAdv($batmanAdv)
    {
        $this->batmanAdv = $batmanAdv;
    }

    /**
     * @return mixed
     */
    public function getFirmware()
    {
        return $this->firmware;
    }

    /**
     * @param mixed $firmware
     */
    public function setFirmware($firmware)
    {
        $this->firmware = $firmware;
    }


}