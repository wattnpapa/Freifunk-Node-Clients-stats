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
        $suchmuster = "/(ds\[)(.*[a-zA-Z0-9])(\])/";
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

    public static function addDS2RRDFile($rrdfile,$dsname,$type,$heartbeat,$min,$max){
        $option = array("DS:".$dsname.":".$type.":".$heartbeat.":".$min.":".$max);
        rrd_tune($rrdfile,$option);
    }
}