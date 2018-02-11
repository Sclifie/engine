<?php
namespace Web\Engine;

use PDO;

class DB
{
    // параметры нужно получать из config.json
    private $servername;
    private $db_name;
    private $username;
    private $password;

    private function __construct()
    {
//        $this->servername = 'localhost';
//        $this->db_name = 'WildConcert';
//        $this->username = 'root';
//        $this->password = '';
    }

    protected static $_instance;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function setDBConfig($db_config){
        $this->servername = $db_config['servername'];
        $this->db_name = $db_config['db_name'];
        $this->username = $db_config['username'];
        $this->password = $db_config['password'];
    }

    private function DBConnect(){
        $connection = new PDO("mysql:host=$this->servername;dbname=$this->db_name", $this->username, $this->password);
        return $connection;
    }

    public function createTable($sql_stting){
        try{
            $connect = $this->DBConnect();
            $connect->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $connect->exec($sql_stting);
        } catch (\PDOException $e){
            var_dump($e->getMessage());
        }
    }

    public function selectAllFromTable($sql_string){
        $connect = $this->DBConnect();
        $statement = $connect->query($sql_string);
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;  // return массив, вида [ 0 => [id=>1, ...], 1 => [id=>2...] ... ]
    }

    public function insertIntoTable($sql_string, array $param) {
        $connect = $this->DBConnect();
        $statement = $connect->prepare($sql_string);
        return $statement->execute($param);  // return true - false
    }

    public function selectByParam($sql_string, array $param){
        $connect = $this->DBConnect();
        $statement = $connect->prepare($sql_string);
        $statement->execute($param);
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        return $data; // return массив, вида [ id=>1,'title' => 'Guitar', .... ]
    }


    public function selectByParamFetchAll($sql_string, array $param){
        $connect = $this->DBConnect();
        $statement = $connect->prepare($sql_string);
        $statement->execute($param);
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

}

