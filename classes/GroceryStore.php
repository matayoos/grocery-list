<?php
require_once 'Database.php';
use Symfony\Component\DomCrawler\Crawler;

class GroceryStore extends Database{

    private string $name, $address;

    public function __construct(Crawler $crawler){
        parent::__construct();

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

    public function storeValues(){
        try {
            if(!empty($this->getName()) && !empty($this->getAddress())){
                $sql = $this->database->prepare("INSERT INTO grocery_store SET name = :name, address = :address");
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

    public function returnValues(){
        $sql = "SELECT * FROM grocery_store";
        $sql = $this->database->query($sql);

        return $sql->fetchAll();
    }
}