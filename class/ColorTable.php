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

        "#1100FF",
        "#FF1100",
        "#00FF11",
        "#11FF00",
        "#0011FF",
        "#FF0011",
        "#FFFF00",
        "#00FFFF",
        "#FF00FF",
        "#FF0000",
        "#00FF00",
        "#0000FF",

        "#6633CC",
        "#CC6633",
        "#33CC66",
        "#66CC33",
        "#3366CC",
        "#CC3366",
        "#CCCC33",
        "#33CCCC",
        "#CC33CC",
        "#CC3333",
        "#33CC33",
        "#3333CC",
    );

    public static function getColor($index){
        return ColorTable::$color[$index];
    }
}