<?php

include(dirname(__FILE__)."/Node.php");
include(dirname(__FILE__)."/System.php");

class Data
{

    private $data;
    private $dataRaw;
    private $dataClients;
    private $url;
    private $nodes;
    private $system;


    /**
     * Data constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->system = new System();
        $this->catchData();
    }


    private function catchData(){
        if(!is_array($this->url)){
            $this->url = Array($this->url);
        }
        $this->dataClients = Array();
        foreach($this->url as $url){
            $this->dataRaw = $this->get_remote_data($url);
            $this->addClients((json_decode($this->dataRaw,true))['nodes']);
        }
        $this->parseData();
        $this->system->fillRRDData();

    }
    
    private functuion addClients($clients){
        foreach($clients as $nodeid => $nodedata){
            if(!in_array($nodeid,$this->dataClients)){
                $this->dataClients[$nodeid] = $nodedata;
            }
            else {
                if($this->dataClients[$nodeid]['flags']['online'] == false){
                    $this->dataClients[$nodeid] = $nodedata;
                }
            }
        }
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
        foreach($this->dataClients as $nodeData){
            $node = new Node();
            $node->setRawData(json_encode($nodeData));
            $node->parseRawData();
            $node->fillRRDData();

            if($node->getFlag()->getOnline()){
                $this->system->addOnlineNode();
            }
            else{
                $this->system->addOfflineNode();
            }

            $this->system->addClients($node->getStatistics()->getClients());
            $this->system->addNodeFirmware($node->getNodeinfo()->getSoftware()->getFirmware()->getRelase());
            $this->system->addNodeHardware($node->getNodeinfo()->getHardware()->getModel());
            $this->system->addMeshConnections(count($node->getNodeinfo()->getNetwork()->getMeshInterfaces()));

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
