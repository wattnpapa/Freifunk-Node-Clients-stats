<?php

include("Node.php");

class Data
{

    private $data;
    private $dataRaw;
    private $url;
    private $nodes;

    /**
     * Data constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->catchData();
    }


    private function catchData(){
        //$this->dataRaw = file_get_contents($this->url);
        
        $this->dataRaw = $this->get_remote_data($this->url);
        //echo $this->dataRaw;
        $this->data = json_decode($this->dataRaw,true);
        $this->parseData();
    }
    
    private function get_remote_data($url, $post_paramtrs=false) {   
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); 
        if($post_paramtrs){
            curl_setopt($c, CURLOPT_POST,TRUE);
            curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&".$post_paramtrs );
        }  
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
        curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
        curl_setopt($c, CURLOPT_MAXREDIRS, 10);
        $follow_allowed= ( ini_get('open_basedir') || ini_get('safe_mode')) ? false:true;
        if ($follow_allowed){
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
        curl_setopt($c, CURLOPT_REFERER, $url);
        curl_setopt($c, CURLOPT_TIMEOUT, 60);
        curl_setopt($c, CURLOPT_AUTOREFERER, true);         
        curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
        $data=curl_exec($c);
        $status=curl_getinfo($c);
        curl_close($c);
        preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si',  $status['url'],$link);
        $data=preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si','$1=$2'.$link[0].'$3$4$5', $data);
        $data=preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si','$1=$2'.$link[1].'://'.$link[3].'$3$4$5', $data);
        if($status['http_code']==200) {
            return $data;
        } elseif($status['http_code']==301 || $status['http_code']==302) { 
            if (!$follow_allowed){
                if(empty($redirURL)){
                    if(!empty($status['redirect_url'])){
                        $redirURL=$status['redirect_url'];
                    }
                }   
                if(empty($redirURL)){
                    preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);
                    if (!empty($m[2])){
                        $redirURL=$m[2]; 
                    } 
                } 
                if(empty($redirURL)){
                    preg_match('/href\=\"(.*?)\"(.*?)here\<\/a\>/si',$data,$m); 
                    if (!empty($m[1])){ 
                        $redirURL=$m[1]; 
                    } 
                }   
                if(!empty($redirURL)){
                    $t=debug_backtrace(); 
                    return call_user_func( $t[0]["function"], trim($redirURL), $post_paramtrs);
                }
            }
        } 
        return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:".json_encode($status)."<br/><br/>Last data got<br/>:$data";
    }

    private function parseData(){
        $this->nodes = Array();
        foreach($this->data['nodes'] as $nodeData){

            ////////
            $flags = new NodeFlags($nodeData['flags']['gateway'],$nodeData['flags']['online']);
            ////////

            ////////
            if(isset($nodeData['statistics']['traffic'])){
                $mgmtTx = new Traffic($nodeData['statistics']['traffic']['mgmt_tx']['packets'],$nodeData['statistics']['traffic']['mgmt_tx']['bytes']);
                $forward = new Traffic($nodeData['statistics']['traffic']['forward']['packets'],$nodeData['statistics']['traffic']['forward']['bytes']);
                $rx = new Traffic($nodeData['statistics']['traffic']['rx']['packets'],$nodeData['statistics']['traffic']['rx']['bytes']);
                $mgmtRx = new Traffic($nodeData['statistics']['traffic']['mgmt_rx']['packets'],$nodeData['statistics']['traffic']['mgmt_rx']['bytes']);
                $tx = new Traffic($nodeData['statistics']['traffic']['tx']['packets'],$nodeData['statistics']['traffic']['tx']['bytes']);
                $nodeTraffic = new NodeTraffic($mgmtTx,$forward,$rx,$mgmtRx,$tx);
            }
            else{
                $nodeTraffic = new NodeTraffic(new Traffic(0,0),new Traffic(0,0),new Traffic(0,0),new Traffic(0,0),new Traffic(0,0));
            }
            ////////

            ////////
            if(isset($nodeData['statistics']['memory_usage']))
                $memoryUsage = $nodeData['statistics']['memory_usage'];
            else
                $memoryUsage = 0;
            if(isset($nodeData['statistics']['clients']))
                $clients = $nodeData['statistics']['clients'];
            else
                $clients = 0;
            if(isset($nodeData['statistics']['rootfs_usage']))
                $rootfsUsage = $nodeData['statistics']['rootfs_usage'];
            else
                $rootfsUsage = 0;
            if(isset($nodeData['statistics']['uptime']))
                $uptime = $nodeData['statistics']['uptime'];
            else
                $uptime = 0;
            if(isset($nodeData['statistics']['gateway']))
                $gateway = $nodeData['statistics']['gateway'];
            else
                $gateway = 0;
            if(isset($nodeData['statistics']['loadavg']))
                $loadavg = $nodeData['statistics']['loadavg'];
            else
                $loadavg = 0;

            $statistics = new NodeStatistics($memoryUsage
                                            ,$clients
                                            ,$rootfsUsage
                                            ,$uptime
                                            ,$gateway
                                            ,$loadavg
                                            ,$nodeTraffic);
            ////////


            ////////
            $hostname = $nodeData['nodeinfo']['hostname'];

            if(isset($nodeData['nodeinfo']['hardware']['nproc']))
                $nproc = $nodeData['nodeinfo']['hardware']['nproc'];
            else
                $nproc = 0;

            $hardware = new NodeHardware($nproc,$nodeData['nodeinfo']['hardware']['model']);

            if(isset($nodeData['nodeinfo']['location']))
                $location = new NodeLocation($nodeData['nodeinfo']['location']['latitude'],$nodeData['nodeinfo']['location']['longitude']);
            else
                $location = new NodeLocation(0,0);
            if(isset($nodeData['nodeinfo']['system']))
                $system = new NodeSystem($nodeData['nodeinfo']['system']['site_code']);
            else
                $system = new NodeSystem("");

            $autoupdate = new NodeAutoupdater($nodeData['nodeinfo']['software']['autoupdater']['branch'],$nodeData['nodeinfo']['software']['autoupdater']['enabled']);
            $fastd = new NodeFastd($nodeData['nodeinfo']['software']['fastd']['version'],$nodeData['nodeinfo']['software']['fastd']['enabled']);
            if(isset($nodeData['nodeinfo']['software']['batman-adv']['compat']))
                $compat = $nodeData['nodeinfo']['software']['batman-adv']['compat'];
            else
                $compat = "";
            $batman = new NodeBadtmanAdv($nodeData['nodeinfo']['software']['batman-adv']['version'],$compat);
            $firmware = new NodeFirmware($nodeData['nodeinfo']['software']['firmware']['base'],$nodeData['nodeinfo']['software']['firmware']['release']);
            $software = new NodeSoftware($autoupdate,$fastd,$batman,$firmware);

            $node_id = $nodeData['nodeinfo']['node_id'];

            if(isset($nodeData['nodeinfo']['owner']['contact']))
                $owner = new NodeOwner($nodeData['nodeinfo']['owner']['contact']);
            else
                $owner = new NodeOwner("");

            if(isset($nodeData['nodeinfo']['network']['mesh']))
                $mesh = $nodeData['nodeinfo']['network']['mesh'];
            else
                $mesh = "";

            $network = new NodeNetwork($nodeData['nodeinfo']['network']['addresses'],$nodeData['nodeinfo']['network']['mesh_interfaces'],$nodeData['nodeinfo']['network']['mac'],$mesh);

            $nodeinfo = new NodeInfo();
            $nodeinfo->setHostname($hostname);
            $nodeinfo->setHardware($hardware);
            $nodeinfo->setLocation($location);
            $nodeinfo->setSystem($system);
            $nodeinfo->setSoftware($software);
            $nodeinfo->setNodeId($node_id);
            $nodeinfo->setOwner($owner);
            $nodeinfo->setNetwork($network);

            ////////

            ////////
            //$node = new Node($nodeData['firstseen'],$nodeData['lastseen'],$flags,$statistics,$nodeinfo);
            $node = new Node();
            $node->setFirstseen($nodeData['firstseen']);
            $node->setLastseen($nodeData['lastseen']);
            $node->setFlag($flags);
            $node->setStatistics($statistics);
            $node->setNodeinfo($nodeinfo);
            $node->fillRRDData();

            $this->nodes[$node->getNodeinfo()->getNetwork()->getMac()] = $node;

            ////////



            //echo $node->getNodeinfo()->getNetwork()->getMac();
            //echo "<br>####################################<br>";
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getDataRaw()
    {
        return $this->dataRaw;
    }

    /**
     * @return mixed
     */
    public function getNodes()
    {
        return $this->nodes;
    }





}