<?php

class NodeHardware
{
    private $nproc;
    private $model;

    /**
     * NodeHardware constructor.
     * @param $nproc
     * @param $model
     */
    public function __construct($nproc, $model)
    {
        $this->nproc = $nproc;
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getNproc()
    {
        return $this->nproc;
    }

    /**
     * @param mixed $nproc
     */
    public function setNproc($nproc)
    {
        $this->nproc = $nproc;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

}