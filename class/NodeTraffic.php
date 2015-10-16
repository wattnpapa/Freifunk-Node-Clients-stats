<?php

include("Traffic.php");

class NodeTraffic
{
    private $mgmtTx;
    private $forward;
    private $Rx;
    private $mgmtRx;
    private $tx;

    /**
     * NodeTraffic constructor.
     * @param $mgmtTx
     * @param $forward
     * @param $Rx
     * @param $mgmtRx
     * @param $tx
     */
    public function __construct($mgmtTx, $forward, $Rx, $mgmtRx, $tx)
    {
        $this->mgmtTx = $mgmtTx;
        $this->forward = $forward;
        $this->Rx = $Rx;
        $this->mgmtRx = $mgmtRx;
        $this->tx = $tx;
    }


    /**
     * @return mixed
     */
    public function getMgmtTx()
    {
        return $this->mgmtTx;
    }

    /**
     * @param mixed $mgmtTx
     */
    public function setMgmtTx($mgmtTx)
    {
        $this->mgmtTx = $mgmtTx;
    }

    /**
     * @return mixed
     */
    public function getForward()
    {
        return $this->forward;
    }

    /**
     * @param mixed $forward
     */
    public function setForward($forward)
    {
        $this->forward = $forward;
    }

    /**
     * @return mixed
     */
    public function getRx()
    {
        return $this->Rx;
    }

    /**
     * @param mixed $Rx
     */
    public function setRx($Rx)
    {
        $this->Rx = $Rx;
    }

    /**
     * @return mixed
     */
    public function getMgmtRx()
    {
        return $this->mgmtRx;
    }

    /**
     * @param mixed $mgmtRx
     */
    public function setMgmtRx($mgmtRx)
    {
        $this->mgmtRx = $mgmtRx;
    }

    /**
     * @return mixed
     */
    public function getTx()
    {
        return $this->tx;
    }

    /**
     * @param mixed $tx
     */
    public function setTx($tx)
    {
        $this->tx = $tx;
    }


}