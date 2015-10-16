<?php


class NodeStatistics
{
    private $memoryUsage;
    private $clients;
    private $rootfs_usage;
    private $uptime;
    private $gateway;
    private $loadavg;
    private $traffic;

    /**
     * NodeStatistics constructor.
     * @param $memoryUsage
     * @param $clients
     * @param $rootfs_usage
     * @param $uptime
     * @param $gateway
     * @param $loadavg
     * @param $traffic
     */
    public function __construct($memoryUsage, $clients, $rootfs_usage, $uptime, $gateway, $loadavg, $traffic)
    {
        $this->memoryUsage = $memoryUsage;
        $this->clients = $clients;
        $this->rootfs_usage = $rootfs_usage;
        $this->uptime = $uptime;
        $this->gateway = $gateway;
        $this->loadavg = $loadavg;
        $this->traffic = $traffic;
    }

    /**
     * @return mixed
     */
    public function getMemoryUsage()
    {
        return $this->memoryUsage;
    }

    /**
     * @param mixed $memoryUsage
     */
    public function setMemoryUsage($memoryUsage)
    {
        $this->memoryUsage = $memoryUsage;
    }

    /**
     * @return mixed
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param mixed $clients
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
    }

    /**
     * @return mixed
     */
    public function getRootfsUsage()
    {
        return $this->rootfs_usage;
    }

    /**
     * @param mixed $rootfs_usage
     */
    public function setRootfsUsage($rootfs_usage)
    {
        $this->rootfs_usage = $rootfs_usage;
    }

    /**
     * @return mixed
     */
    public function getUptime()
    {
        return $this->uptime;
    }

    /**
     * @param mixed $uptime
     */
    public function setUptime($uptime)
    {
        $this->uptime = $uptime;
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
    public function getLoadavg()
    {
        return $this->loadavg;
    }

    /**
     * @param mixed $loadavg
     */
    public function setLoadavg($loadavg)
    {
        $this->loadavg = $loadavg;
    }

    /**
     * @return mixed
     */
    public function getTraffic()
    {
        return $this->traffic;
    }

    /**
     * @param mixed $traffic
     */
    public function setTraffic($traffic)
    {
        $this->traffic = $traffic;
    }


}