rrdtool tune system.rrd --data-source-rename nodes:nodesOnline
rrdtool tune rrdData/system/system.rrd DS:nodesOffline:GAUGE:600:0:U
