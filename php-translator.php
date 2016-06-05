<?php

class PHPTranslator {

    public $clients=[];
    private $_debug = false;

    public function addClient($id, $secret) {
           $this->clients[]=["id"=>$id, "secret"=>$secret];
    }
    
    private function getAccesToken ($clientID, $clientSecret){
        
        $authUrl      = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
        $scopeUrl     = "http://api.microsofttranslator.com";
        $grantType    = "client_credentials";
        
        $paramArr = array (
                 'grant_type'    => $grantType,
                 'scope'         => $scopeUrl,
                 'client_id'     => $clientID,
                 'client_secret' => $clientSecret
            );
            
            $paramArr = http_build_query($paramArr);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $authUrl);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $strResponse = curl_exec($ch);
            $curlErrno = curl_errno($ch);
            if($curlErrno){
                $curlError = curl_error($ch);
                throw new Exception($curlError);
            }
            curl_close($ch);
            
            $objResponse = json_decode($strResponse);
            if($this->_debug) var_dump($objResponse);
            return $objResponse->access_token;
        
    }
    
    private function curlRequest($url, $authHeader, $postData=''){
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array($authHeader,"Content-Type: text/xml"));
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
        if($postData) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        $curlResponse = curl_exec($ch);
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            throw new Exception($curlError);
        }
        curl_close($ch);
        return $curlResponse;
    }
    
    
    public function translate($text, $to="en", $from="auto") {

           $client = $this->clients[rand(0,count($this->clients)-1)];
           
        $accessToken  = $this->getAccesToken($client["id"], $client["secret"]);
        $authHeader = "Authorization: Bearer ". $accessToken;
        $requestUrl = "http://api.microsofttranslator.com/V2/Http.svc/Translate?text=".urlencode($text)."&to=$to";

        if($from!="auto")
            $requestUrl.="&from=$from";

        $strResponse = $this->curlRequest($requestUrl, $authHeader);
        $xmlObj = simplexml_load_string($strResponse);
        return $xmlObj[0][0];
    }

    public function debug($value){
        $this->_debug = $value;
    }
}



$trans = new PHPTranslator;
$trans->debug(true);
$trans->addClient("fireveined", "D6waee/WZ7GsbwIBq7hSargXGRpFRL0B6mEW3kjrTCs=");
echo $trans->translate("To jest bardzo długa podróż.");