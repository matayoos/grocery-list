<?php

use Curl\Curl;
use Symfony\Component\DomCrawler\Crawler;

class items{

    private string $id, $description, $category;
    private float $quantity, $unity, $unityValue, $finalValue; 

    public function __construct($url){

        $curl = new Curl();
        $curl->get($url);
        $html = $curl->response;

        $crawler = new Crawler($html);

        //$items = array();

        $id_items = $crawler->filterXPath('//tr[contains(@id, "Item + ")]')->evaluate('substring-after(@id, "+ ")');
        
        foreach($id_items as $key){
            $item = $crawler->filterXPath('//*[@id="Item + ' . $key. '"]')->each(function (Crawler $node){
                return $node->text();
            });
            
            $item = explode(' ', $item[0]);

            $this->setId($item[0]);
            $this->setDescription(implode(" ", array_slice($item, 1, -4)));

            $value = array_slice($item, -4);

            $this->setQuantity($value[0]);
            $this->setUnity($value[1]);
            $this->setUnityValue($value[2]);
            $this->setFinalValue($value[3]);
        
            /*
            $items["item-$key"] = array(
                'codigo' => $item[0],
                'descricao' => $description, 
                'qtde' => $value[0],
                'un' => $value[1],
                'vl_unit' => $value[2],
                'vl_total' => $value[3],
            );
            */
        }        
    }

    //Getters & Setters

    public function getId(){
        return $this->id;
    }

    public function setId(string $id){
        $this->id = $id;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription(string $description){
        $this->description = $description;
    }

    public function getCategory(){
        return $this->category;
    }

    public function setCategory(string $category){
        $this->category = $category;
    }

    public function getQuantity(){
        return $this->quantity;
    }

    public function setQuantity(float $quantity){
        $this->quantity = $quantity;
    }

    public function getUnity(){
        return $this->unity;
    }

    public function setUnity(string $unity){
        $this->unity = $unity;
    }

    public function getUnityValue(){
        return $this->unityValue;
    }

    public function setUnityValue(float $unityValue){
        $this->unityValue = $unityValue;
    }

    public function getFinalValue(){
        return $this->finalValue;
    }

    public function setFinalValue(float $finalValue){
        $this->finalValue = $finalValue;
    }

}