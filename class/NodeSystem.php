<?php

class NodeSystem
{
    private $site_code;

    /**
     * NodeSystem constructor.
     * @param $site_code
     */
    public function __construct($site_code)
    {
        $this->site_code = $site_code;
    }

    /**
     * @return mixed
     */
    public function getSiteCode()
    {
        return $this->site_code;
    }

    /**
     * @param mixed $site_code
     */
    public function setSiteCode($site_code)
    {
        $this->site_code = $site_code;
    }

}