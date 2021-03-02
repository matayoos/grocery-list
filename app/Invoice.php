<?php

require_once 'Database.php';

use Symfony\Component\DomCrawler\Crawler;

class Invoice{
    
    private string $id, $url, $datetime;
    private float $grandTotal, $discount;

    public function __construct(string $url, Crawler $crawler){
        $this->url = $url;

        $this->setDateTime($crawler);
        $this->setid($crawler);

        $total = $crawler->filter('.NFCCabecalho')->last()->text();
        $total = explode(' ', $total);

        $this->setGrandTotal(str_replace(',', '.', $total[3]));
        $this->setDiscount(str_replace(',', '.', $total[7]));
    }

    //Getters & Setters
    public function getId(){
        return $this->id;
    }

    public function setid(Crawler $crawler){
        $id = $crawler->filter('.NFCCabecalho_SubTitulo')->eq(5)->text();
        $id = implode(explode(' ', $id));

        $this->id = $id;
    }

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
        $date = $date[2] . '-' . $date[1] . '-' . $date[0];

        $time = $datetime[1];

        $this->datetime = $date . ' ' . $time;
    }

    public function getGrandTotal(){
        return $this->grandTotal;
    }

    public function setGrandTotal(float $grandTotal){
        $this->grandTotal = $grandTotal;
    }

    public function getDiscount(){
        return $this->discount;
    }

    public function setDiscount(float $discount){
        $this->discount = $discount;
    }

    public function returnValues(){
        $database = new Database();

        $sql = "SELECT * FROM invoice";
        $sql = $database->sql;

        return $sql->fetchAll();
    }

    public function storeValues(){
        $database = new Database();

        $check = $database->prepare("SELECT COUNT(*) FROM invoice WHERE id = :id");
        $check->bindValue(":id", $this->getId());
        $check->execute();
        $count = $check->fetchColumn();

        if($count > 0){
            return "invoice already registered!" . PHP_EOL;
        } else {
            try{
                if(!empty($this->getUrl()) && !empty($this->getDateTime()) && !empty($this->getGrandTotal()) && !empty($this->getDiscount())){
                    $sql = $database->prepare("INSERT INTO invoice SET id = :id, url = :url, datetime = :datetime, grand_total = :grand_total, discount = :discount");
                    $sql->bindValue(":id", $this->getId());
                    $sql->bindValue(":url", $this->getUrl());
                    $sql->bindValue(":datetime", $this->getDateTime());
                    $sql->bindValue(":grand_total", $this->getGrandTotal());
                    $sql->bindValue(":discount", $this->getDiscount());
                    $sql->execute();

                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e){
                echo "Erro " . $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function returnValuesString(){
        return  'id: ' . $this->getid() . PHP_EOL .
                'Url: ' . $this->getUrl() . PHP_EOL .
                'Datetime: ' . $this->getDateTime() . PHP_EOL .
                'grandTotal: R$' . $this->getGrandTotal() . PHP_EOL .
                'Discount: R$' . $this->getDiscount() . PHP_EOL;
    }
}