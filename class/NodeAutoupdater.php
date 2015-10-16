<?php

/**
 * Created by IntelliJ IDEA.
 * User: op49265
 * Date: 16.10.2015
 * Time: 15:45
 */
class NodeAutoupdater
{
    private $branch;
    private $enabled;

    /**
     * NodeAutoupdater constructor.
     * @param $branch
     * @param $enabled
     */
    public function __construct($branch, $enabled)
    {
        $this->branch = $branch;
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param mixed $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
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