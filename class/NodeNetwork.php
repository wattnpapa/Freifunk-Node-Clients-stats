<?php


/**
 * Class NodeNetwork
 */
class NodeNetwork
{


    private $addresses;
    private $mesh_interfaces;
    private $mac;
    private $mesh;

    /**
     * NodeNetwork constructor.
     * @param $addresses
     * @param $mesh_interfaces
     * @param $mac
     * @param $mesh
     */
    public function __construct($addresses, $mesh_interfaces, $mac, $mesh)
    {
        $this->addresses = $addresses;
        $this->mesh_interfaces = $mesh_interfaces;
        $this->mac = $mac;
        $this->mesh = $mesh;
    }

    /**
     * @return mixed
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param mixed $addresses
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
    }

    /**
     * @return mixed
     */
    public function getMeshInterfaces()
    {
        return $this->mesh_interfaces;
    }

    /**
     * @param mixed $mesh_interfaces
     */
    public function setMeshInterfaces($mesh_interfaces)
    {
        $this->mesh_interfaces = $mesh_interfaces;
    }

    /**
     * @return mixed
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * @param mixed $mac
     */
    public function setMac($mac)
    {
        $this->mac = $mac;
    }

    /**
     * @return mixed
     */
    public function getMesh()
    {
        return $this->mesh;
    }

    /**
     * @param mixed $mesh
     */
    public function setMesh($mesh)
    {
        $this->mesh = $mesh;
    }




}