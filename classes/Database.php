<?php 
class Database{
    public PDO $database;
    public function __construct()
    {
        try{
            $this->database = new PDO('mysql:host=localhost;dbname=grocery_list','root','password');
        }
        catch (Exception $e){
            die('Error: '.$e->getMessage());
        }
    }
}