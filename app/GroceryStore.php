<?php

require_once 'Database.php';

use Symfony\Component\DomCrawler\Crawler;

class GroceryStore{

    private string $name, $address;

    public function __construct(Crawler $crawler){
        $this->setName($crawler);
        $this->setAddress($crawler);
    }

    public function getName(){
        return $this->name;
    }

    public function setName(Crawler $crawler){
        $this->name = $crawler->filter('.NFCCabecalho_SubTitulo')->text();
    }

    public function getAddress(){
        return $this->address;
    }

    public function setAddress(Crawler $crawler){
        $this->address = $crawler->filter('.NFCCabecalho_SubTitulo1')->eq(1)->text();       
    }

    public function storeValue(){
        $database = new Database();

        $check = $database->prepare("SELECT COUNT(*) FROM grocery_store WHERE name = :name");
        $check->bindValue(":name", $this->getName());
        $check->execute();
        $count = $check->fetchColumn();

        if($count > 0){
            return "grocery store already cadastrada!" . PHP_EOL;
        } else {
            try {
                if(!empty($this->getName()) && !empty($this->getAddress())){
                    $sql = $database->prepare("INSERT INTO grocery_store SET name = :name, address = :address");
                    $sql->bindValue(":name", $this->getName());
                    $sql->bindValue(":address", $this->getAddress());
                    $sql->execute();

                    return true;
                } else {
                    return false;
                }

            } catch (PDOException $e) {
                echo "Error " . $e->getMessage();
            }
        }
    }

    public function returnValues(){
        $database = new Database();

        $sql = "SELECT * FROM grocery_store";
        $sql = $database->query($sql);

        return $sql->fetchAll();
    }

    public function returnValuesString(){
        return 'Name: ' . $this->getName() . PHP_EOL . 'Address: ' .  $this->getAddress() . PHP_EOL;
    }
}