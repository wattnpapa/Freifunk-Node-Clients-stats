# Freifunk-Node-Clients-stats

This project creates Freifunk Node Statistics on demand.

## Requierments

php5-rrd
php5-curl

## Installation

1. Clone The Repo
2. Make the config File
3. Make a entry in your Crontab

### Clone The Repo

```
git clonehttps://github.com/PowerPan/Freifunk-Node-Clients-stats.git
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
