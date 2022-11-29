
<?php
 
 abstract class Generic_handler  {
  
    private $db;
    protected $sqlProperties;
    protected $sqlPropertiesObj;
   
    public function __construct(protected \PDO $pdo){
      $this->db = $pdo;
    }

    public function select($json) {
        //  file_put_contents('./log_'.date("j.n.Y").'.txt', $this->sqlProperties.PHP_EOL, FILE_APPEND);
        $property = 'Select';
        $query = $this->getJsonProperty($this->sqlPropertiesObj, $property);
        $statement = $this->db->prepare($query);
        $id = $this->getJsonProperty($json, "id");
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        return $this->mapper($statement);
    }

    public function insert($obj) {
        $property = 'Insert';
       // $obj =  $this->createObject(json_decode($json));
        $query = $this->getJsonProperty($this->sqlPropertiesObj, $property);
        $statement = $this->db->prepare($query);
        $this->getInsertParams($statement, $obj);
        $statement->execute();
        return $this->db->lastInsertId();
    }

    abstract protected function getInsertParams();
    abstract protected function getUpdateParams();

    protected function getJsonProperty($json, $property){
        if(isset($json->$property)){
            return $json->$property;
        }else{
            throw new Exception("Property : " . $property . " does not exist");
        }
    }

    
 }
  

 ?>