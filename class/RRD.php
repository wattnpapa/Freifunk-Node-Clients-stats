<?php

/**
 * Created by IntelliJ IDEA.
 * User: johannes
 * Date: 14.11.15
 * Time: 13:59
 */
class RRD
{
    public static function getDSFromRRDFile($file){
        $info = rrd_info($file);
        $suchmuster = "/(ds\[)(.*[a-zA-Z0-9_])(\])/";
        $ds = array();
        foreach ($info as $key => $value){
            preg_match($suchmuster, $key, $treffer);
            if(count($treffer) > 1){
                if(!in_array($treffer[2],$ds)){
                    $ds[] = $treffer[2];
                }
            }
        }
        return $ds;
    }

    public static function addDS2RRDFile($rrdFileName,$newDS){
        //Get Existing Datasources
        $datassources = self::getDSFromRRDFile($rrdFileName);

        //Add New DataSources
        $datassources[] = $newDS;

        //Create array with DS Options
        $dsoptions = array();
        foreach($datassources as $ds){
            $dsoptions[] = RRD::getDSOption($ds);
        }

        //get RRD Options
        $rrdOptions = RRD::getRRDFileOptions($dsoptions);

        //Move OriginalFile to /tmp
        $tmpFileName = "/tmp/tmp.rrd";
        rename($rrdFileName,$tmpFileName);

        //add source options
        $rrdOptions[] = "--source";
        $rrdOptions[] = $tmpFileName;

        //create new RRDFile
        RRD::createRRDFile($rrdFileName,$rrdOptions);
    }

    public static function createRRDFile($filename,$options){
        $ret = rrd_create($filename, $options);
        echo rrd_error();
    }

    public static function getRRDFileOptions($dataSources){
        $options = array("--step", "60");
        foreach($dataSources as $data) {
            $options[] = $data;
        }
        $options[] = "RRA:AVERAGE:0.5:1:10080"; //every minute one week
        $options[] = "RRA:AVERAGE:0.5:60:8760"; //
        $options[] = "RRA:AVERAGE:0.5:1440:5256";
        return $options;
    }

    public static function getDSOption($dsname){
        return "DS:".$dsname.":GAUGE:600:0:U";
    }

}