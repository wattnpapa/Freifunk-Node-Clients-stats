<?php

/**
 * Created by IntelliJ IDEA.
 * User: johannes
 * Date: 14.11.15
 * Time: 15:22
 */
class FirmwareRRDDSMapping
{
    private $mapping;
    private $mappingFile;

    /**
     * FirmwareRRDDSMapping constructor.
     * @param $mapping
     */
    public function __construct() {
        $this->mappingFile = dirname(__FILE__)."/../rrdData/system/firmwareMapping.json";
        $this->readFromFile();
    }

    private function readFromFile(){
        $fd = fopen($this->mappingFile, 'r');
        $this->mapping = json_decode(fread($fd,filesize($this->mappingFile)),true);
        fclose($fd);
    }

    private function wrtieToFile(){
        $fd = fopen($this->mappingFile, 'w');
        fwrite($fd,json_encode($this->mapping));
        fclose($fd);
    }

    public function getNameForCode($code){
        return $this->mapping[$code];
    }

    public function getCodeForName($name){
        $pattern = "/([^a-zA-Z0-9]+)/";
        $code = preg_replace($pattern,"",$name);
        return $code;
    }

    public function addNameToMapping($name){
        $code = $this->getCodeForName($name);
        if(!in_array($name,$this->mapping)){
            $this->mapping[$code] = $name;
            $this->wrtieToFile();
        }
        return $code;
    }


}