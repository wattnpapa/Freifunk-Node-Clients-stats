<?php

include("RRDDSMapping.php");
/**
 * Created by IntelliJ IDEA.
 * User: johannes
 * Date: 14.11.15
 * Time: 15:22
 */
class HardwareRRDDSMapping extends RRDDSMapping
{

    /**
     * FirmwareRRDDSMapping constructor.
     * @param $mapping
     */
    public function __construct() {
        $this->mappingFile = dirname(__FILE__)."/../rrdData/system/hardwareMapping.json";
        parent::__construct();
    }

}