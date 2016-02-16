# Freifunk-Node-Clients-stats

This project creates Freifunk Node Statistics on demand.

[![Code Climate](https://codeclimate.com/github/PowerPan/Freifunk-Node-Clients-stats/badges/gpa.svg)](https://codeclimate.com/github/PowerPan/Freifunk-Node-Clients-stats)

## Examples System Stats

### Online Clients

![Online Clients](https://stats.ffnw.de/getSystemGraph.php?type=clients&interval=1W&width=800)

### Online/Offline Nodes

![Online Nodes](https://stats.ffnw.de/getSystemGraph.php?type=nodes&interval=1W&width=800)

## Examples Client Stats

### Online Clients 

![Online Clients](https://stats.ffnw.de/getNodeGraph.php?interval=1W&mac=6466b38a58f2&type=clients)

### Traffic

![Online Clients](https://stats.ffnw.de/getNodeGraph.php?interval=1W&mac=6466b38a58f2&type=traffic)

## Requierments

* rrdtool > 1.5.0
* php5-rrd
* php5-curl

## Installation

1. Clone The Repo
2. Make the config File
3. Make a entry in your Crontab

### Clone The Repo

```
git clone https://github.com/PowerPan/Freifunk-Node-Clients-stats.git
```
### Config File

You must make a config.json File. You can use the config.json.example as an Template

In The Configfile you can enter the Data Crawl URL, the Name of your Community and the Logo URL.

### Crontab

```
bash createCronTabEntry.sh
```

## Get Images

## Performance

The Graphs are only made, if there is a request. A Graph is cached for one minute if the comes a second request in less than one minute the graph will not be redraw in this case the version from the disk is send to the client.
