<?php
class User {
    private String $name;
    private String $ip;
    private String $browser;
    private String $code;
    private String $country;

    public function __construct($name, $ip, $browser, $code = null, $country){
        $this->setName($name);
        $this->setIp($ip);
        $this->setBrowser($browser);
        $this->setCode($code);
        $this->setCountry($country);
    }

    public function getName(){ return $this->name;}
    public function getIp(){ return $this->ip;}
    public function getBrowser(){ return $this->browser;}
    public function getCode(){ return $this->code;}
    public function getCountry(){ return $this->country;}

    public function setName($name){ $this->name = $name;}
    public function setIp($ip){ $this->ip = $ip;}
    public function setBrowser($browser){ $this->browser = $browser;}
    public function setCode($code){ $this->code = $code;}
    public function setCountry($country){ $this->country = $country;}

}
?>