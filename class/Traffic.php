<?php


class Traffic
{
    private $packets;
    private $bytes;

    /**
     * Traffic constructor.
     * @param $packets
     * @param $bytes
     */
    public function __construct($packets, $bytes)
    {
        $this->packets = $packets;
        $this->bytes = $bytes;
    }

    /**
     * @return mixed
     */
    public function getPackets()
    {
        return $this->packets;
    }

    /**
     * @param mixed $packets
     */
    public function setPackets($packets)
    {
        $this->packets = $packets;
    }

    /**
     * @return mixed
     */
    public function getBytes()
    {
        return $this->bytes;
    }

    /**
     * @param mixed $bytes
     */
    public function setBytes($bytes)
    {
        $this->bytes = $bytes;
    }


}