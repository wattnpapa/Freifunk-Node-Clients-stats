#!/usr/bin/bash

#rename nodes to nodesOnline
rrdtool tune ../rrdData/system/system.rrd --data-source-rename nodes:nodesOnline

#move file to tmp
mv ../rrdData/system/system.rrd /tmp/tmp.rrd

#create new rrd with old data
rrdtool create ../rrdData/system/system.rrd --step 60 --source /tmp/tmp.rrd DS:clients:GAUGE:600:0:U DS:nodesOnline:GAUGE:600:0:U DS:nodesOffline:GAUGE:600:0:U RRA:AVERAGE:0.5:1:10080 RRA:AVERAGE:0.5:60:8760 RRA:AVERAGE:0.5:1440:5256