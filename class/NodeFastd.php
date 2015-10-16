<?php

class NodeFastd
{
    private $version;
    private $enabled;

    /**
     * NodeFastd constructor.
     * @param $version
     * @param $enabled
     */
    public function __construct($version, $enabled)
    {
        $this->version = $version;
        $this->enabled = $enabled;
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
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

}