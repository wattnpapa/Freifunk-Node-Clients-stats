<?php


class NodeFlags
{
    private $gateway;
    private $online;

    /**
     * NodeFlags constructor.
     * @param $gateway
     * @param $online
     */
    public function __construct($gateway, $online)
    {
        $this->gateway = $gateway;
        $this->online = $online;
    }

    /**
     * @return mixed
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return mixed
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * @param mixed $online
     */
    public function setOnline($online)
    {
        $this->online = $online;
    }


}