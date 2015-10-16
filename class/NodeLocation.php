<?php

class NodeLocation
{
    private $latitude;
    private $longnitude;

    /**
     * NodeLocation constructor.
     * @param $latitude
     * @param $longnitude
     */
    public function __construct($latitude, $longnitude)
    {
        $this->latitude = $latitude;
        $this->longnitude = $longnitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongnitude()
    {
        return $this->longnitude;
    }

    /**
     * @param mixed $longnitude
     */
    public function setLongnitude($longnitude)
    {
        $this->longnitude = $longnitude;
    }

}