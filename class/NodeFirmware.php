<?php


class NodeFirmware
{
    private $base;
    private $relase;

    /**
     * NodeFirmware constructor.
     * @param $base
     * @param $relase
     */
    public function __construct($base, $relase)
    {
        $this->base = $base;
        $this->relase = $relase;
    }

    /**
     * @return mixed
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param mixed $base
     */
    public function setBase($base)
    {
        $this->base = $base;
    }

    /**
     * @return mixed
     */
    public function getRelase()
    {
        return $this->relase;
    }

    /**
     * @param mixed $relase
     */
    public function setRelase($relase)
    {
        $this->relase = $relase;
    }


}