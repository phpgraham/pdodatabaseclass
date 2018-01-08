<?php
include('Includes/config.php');

class Database{
  protected $db_port=DB_PORT;
  protected $db_host=DB_HOST;
  protected $db_database=DB_DATABASE;
  protected $db_username=DB_USERNAME;
  protected $db_password=DB_PASSWORD;

  private $dbh;
  private $error;
  private $stmt;

  public function __construct(){
    // Set DSN
    $dsn = 'mysql:port=' .$this->db_port . 'host=' . $this->db_host . ';dbname=' . $this->db_database;
    // Set options
    $options = array(
      PDO::ATTR_PERSISTENT    => true,
      PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
    );
    // Create a new PDO instanace
    try{
      $this->dbh = new PDO($dsn, $this->db_username, $this->db_password, $options);
    }
    // Catch any errors
    catch(PDOException $e){
      $this->error = $e->getMessage();
    }
  }

  public function query($query){
    $this->stmt = $this->dbh->prepare($query);
  }

  public function bind($param, $value, $type = null){
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }
    $this->stmt->bindValue($param, $value, $type);
  }

  public function resultset(){
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function single(){
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function rowCount(){
    return $this->stmt->rowCount();
  }

  public function lastInsertId(){
    return $this->dbh->lastInsertId();
  }

  public function beginTransaction(){
    return $this->dbh->beginTransaction();
  }

  public function endTransaction(){
    return $this->dbh->commit();
  }

  public function cancelTransaction(){
    return $this->dbh->rollBack();
  }

  public function debugDumpParams(){
    return $this->stmt->debugDumpParams();
  }

  public function execute(){
    return $this->stmt->execute();
  }
}
