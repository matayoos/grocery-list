<?php

use Curl\Curl;
use Symfony\Component\DomCrawler\Crawler;

class GroceryStore{

    private string $name, $address;

    public function __construct(string $url){
        $curl = new Curl();
        $curl->get($url);
        $html = $curl->response;

        $crawler = new Crawler($html);

        $this->setName($crawler->filter('.NFCCabecalho_SubTitulo')->text());
        $this->setAddress($crawler->filter('.NFCCabecalho_SubTitulo1')->eq(1)->text());
    }

    public function getName(){
        return $this->name;
    }

    public function setName(string $name){
        $this->name = $name;
    }

    public function getAddress(){
        return $this->address;
    }

    public function setAddress(string $address){
        $this->address = $address; 
    }

    public function returnValues(){
        return 'Name: ' . $this->getName() . "\t Address: " .  $this->getAddress() . "\n";
    }

}