<?php

class Data
{

    private $data;
    private $dataRaw;

    /**
     * Data constructor.
     */
    public function __construct()
    {
    }

    public function catchData(){
        $this->dataRaw = file_get_contents("http://mesh.sjr-ol.de/data/nodes.json");
        $this->data = json_decode($this->dataRaw,true);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getDataRaw()
    {
        return $this->dataRaw;
    }

    

}