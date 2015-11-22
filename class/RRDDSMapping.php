<?php

/**
 * Created by IntelliJ IDEA.
 * User: johannes
 * Date: 14.11.15
 * Time: 15:22
 */
abstract class RRDDSMapping
{
    protected $mapping;
    protected $mappingFile;

    /**
     * FirmwareRRDDSMapping constructor.
     * @param $mapping
     */
    public function __construct() {
        $this->readFromFile();
    }

    private function readFromFile(){
        if(!file_exists($this->mappingFile)){
            $this->mapping = array();
            $this->wrtieToFile();
        }
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
        //$pattern = "/([^a-zA-Z0-9]+)/";
        //$code = preg_replace($pattern,"_",$name);
        $code = md5($name);
        $code = substr($code,0,15);
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

    public function getMappingSort(){
        $values = $this->mapping;
        asort($values);
        return $values;

    }


}