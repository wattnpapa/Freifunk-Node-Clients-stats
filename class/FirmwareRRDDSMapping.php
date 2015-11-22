<?php

include_once(dirname(__FILE__)."/RRDDSMapping.php");

/**
 * Created by IntelliJ IDEA.
 * User: johannes
 * Date: 14.11.15
 * Time: 15:22
 */
class FirmwareRRDDSMapping extends RRDDSMapping
{
    /**
     * FirmwareRRDDSMapping constructor.
     * @param $mapping
     */
    public function __construct() {
        $this->mappingFile = dirname(__FILE__)."/../rrdData/system/firmwareMapping.json";
        parent::__construct();
    }

}