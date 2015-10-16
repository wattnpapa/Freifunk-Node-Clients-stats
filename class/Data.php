<?php

class Data
{

    private $data;
    private $dataRaw;
    private $url;

    /**
     * Data constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }


    public function catchData(){
        $this->dataRaw = file_get_contents($this->url);
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