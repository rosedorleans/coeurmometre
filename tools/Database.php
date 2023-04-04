<?php
namespace Tools;

Use PDO;
use PDOException;
abstract class Database
{
    protected $PDO;
    
    public function __construct(){
        try {
            $this->PDO = new PDO($_ENV["DATABASE"], $_ENV["DATABASE_USER"], $_ENV["DATABASE_PASS"], array(\PDO::MYSQL_ATTR_INIT_COMMAND =>  'SET NAMES utf8'));
        } catch (PDOException $e) {
            echo json_encode(array("message" => $e->getMessage()));
        }
    }
}
