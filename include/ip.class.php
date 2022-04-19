<?php
Class ip{
    private string $ip;
    private string $country;
    private string $token = 'arFHNChZBzouDQNnCmLo';
    private string $browser;

    public function __construct(){
        $this->setIpAdress();
        $this->setCountry();
        $this->setBrowser();
    }
    
    private function setIpAdress(){
        $ip = getenv('HTTP_CLIENT_IP') ?: getenv('HTTP_X_FORWARDED_FOR') ?: getenv('HTTP_X_FORWARDED') ?: getenv('HTTP_FORWARDED_FOR') ?: getenv('HTTP_FORWARDED') ?: getenv('REMOTE_ADDR');
        $this->ip = $ip;
    }

    private function setCountry(){
        $ip_address = $this->getIpAddress();
        $jsondata = file_get_contents("http://timezoneapi.io/api/ip/?{$ip_address}&token={$this->getToken()}");
        $data = json_decode($jsondata, true);

        $country = ($data['meta']['code'] == '200') ? 'France' : '';
        $this->country = $country;
    }

    private function setBrowser(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $name = 'NA';

        if (preg_match('/MSIE/i', $agent) && !preg_match('/Opera/i', $agent)) {
            $name = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $agent)) {
            $name = 'Mozilla Firefox';
        } elseif (preg_match('/Chrome/i', $agent)) {
            $name = 'Google Chrome';
        } elseif (preg_match('/Safari/i', $agent)) {
            $name = 'Apple Safari';
        } elseif (preg_match('/Opera/i', $agent)) {
            $name = 'Opera';
        } elseif (preg_match('/Netscape/i', $agent)) {
            $name = 'Netscape';
        }

        $this->browser = $name;
    }

    public function getToken(){return $this->token;}
    public function getIpAddress(){return $this->ip;}
    public function getCountry(){return $this->country;}
    public function getBrowser(){return $this->browser;}
}