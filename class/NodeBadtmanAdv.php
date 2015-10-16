<?php

class NodeBadtmanAdv
{
    private $version;
    private $compat;

    /**
     * NodeBadtmanAdv constructor.
     * @param $version
     * @param $compat
     */
    public function __construct($version, $compat)
    {
        $this->version = $version;
        $this->compat = $compat;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getCompat()
    {
        return $this->compat;
    }

    /**
     * @param mixed $compat
     */
    public function setCompat($compat)
    {
        $this->compat = $compat;
    }




}