
<?php
 
 abstract class Generic_handler  {
  
    private $db;
    protected $sqlProperties;
    protected $sqlPropertiesObj;
   
    public function __construct(protected \PDO $pdo){
      $this->db = $pdo;
      $json_data = file_get_contents($this->sqlProperties);
      $this->sqlPropertiesObj = json_decode($json_data);
    }


    public function insert($obj)  {
       // $obj =  $this->createObject(json_decode($json));
        $query = $this->getJsonProperty($this->sqlPropertiesObj, 'Insert');
        $statement = $this->db->prepare($query);
        if(!$statement){
            throw new Exception("Generic handler : can not prepare insert statement with obj : " . get_class($obj) . "");
        }
        $this->setInsertParams($statement, $obj);
        $statement->execute();
        return $this->db->lastInsertId();
    }

    abstract protected function setInsertParams($statement, $obj);
    abstract protected function setUpdateParams($statement, $obj);
    abstract protected function mapper($statement);
    abstract protected function mapperAll($statement);


    public function select($json) {
        $query = $this->getJsonProperty($this->sqlPropertiesObj, 'Select');
        $statement = $this->db->prepare($query);
        $id = $this->getJsonProperty($json, "id");
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        return $this->mapper($statement);
    }

    protected function getJsonProperty($json, $property){
        if(isset($json->$property)){
            return $json->$property;
        }else{
            throw new Exception("Property : " . $property . " does not exist");
        }
    }

    
 }
  

 ?>