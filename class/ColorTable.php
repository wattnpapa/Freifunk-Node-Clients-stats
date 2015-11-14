<?php

/**
 * Created by IntelliJ IDEA.
 * User: johannes
 * Date: 14.11.15
 * Time: 16:30
 */
class ColorTable
{
    public static $color = array(
        "#6600CC",
        "#CC6600",
        "#00CC66",
        "#66CC00",
        "#0066CC",
        "#CC0066",
        "#CCCC00",
        "#00CCCC",
        "#CC00CC",
        "#CC0000",
        "#00CC00",
        "#0000CC",
    );

    public static function getColor($index){
        return ColorTable::$color[$index];
    }
}