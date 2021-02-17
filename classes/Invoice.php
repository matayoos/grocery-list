<?php
use Symfony\Component\DomCrawler\Crawler;

class Invoice{
    
    private string $url, $datetime;
    private float $finalValue, $discount;

    public function __construct(string $url, Crawler $crawler){
        $this->url = $url;

        $this->setDateTime($crawler);

        //Final value and discount
        $total = $crawler->filter('.NFCCabecalho')->last()->text();
        $total = explode(' ', $total);

        $this->setFinalValue(str_replace(',', '.', $total[3]));
        $this->setDiscount(str_replace(',', '.', $total[7]));
    }

    //Getters & Setters
    public function getUrl(){
        return $this->url;
    }

    public function setUrl(string $url){
        $this->url = $url;
    }

    public function getDateTime(){
        return $this->datetime;
    }

    public function setDateTime(Crawler $crawler){
        $datetime = $crawler->filter('.NFCCabecalho_SubTitulo')->eq(2)->text();

        $datetime = array_slice(explode(' ', $datetime), -2);
        $date = $datetime[0];
        $date = explode('/', $date);
        $date = $date[2] . '-' . $date[0] . '-' . $date[1];
        $time = $datetime[1];

        $this->datetime = $date . ' ' . $time;
    }

    public function getFinalValue(){
        return $this->finalValue;
    }

    public function setFinalValue(float $finalValue){

        $this->finalValue = $finalValue;
    }

    public function getDiscount(){
        return $this->discount;
    }

    public function setDiscount(float $discount){
        $this->discount = $discount;
    }

    public function returnValues(){
        return  'Url: ' . $this->getUrl() . "\n" .
                'Datetime: ' . $this->getDateTime() . "\n" .
                'FinalValue: R$' . $this->getFinalValue() . "\n" .
                'Discount: R$' . $this->getDiscount() . "\n";
    }
}