<?php
namespace App\Model;
use PDO;
class UserModel{
  private $connString;
  private $userName;
  private $passCode;
  private $server;
  private $driver;
  private $sql;
  private $errorMessage;
  private $response;
  private $pdo_opt = array (
                          PDO::ATTR_ERRMODE  => PDO::ERRMODE_EXCEPTION,

                          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                          PDO::MYSQL_ATTR_INIT_COMMAND =>  "SET NAMES utf8"
                      );

  function __construct($userName,$passCode,$dbName,$driver='Mysql', $serverName='localhost')
  {
    $this->setCredentials($userName,$passCode,$dbName,$driver,$serverName);
    $this->StartConnection();
  }

  function StartConnection(){
    try{
        $this->sql = new PDO($this->connString, $this->userName, $this->passCode, $this->pdo_opt);
    }
      catch(PDOException $e){
      print_r( $e->getMessage());
    }
  }

  function getData($query,$data=array()){
    try{
      $cmd = $this->sql->prepare( $query );
      $cmd->execute($data);
      $this->response = $cmd->fetchAll();
      return $this->response;

    }catch(Exception $e){
     $this->response = print_r($e->getMessage());
      return $this->response;

    }
  }

  function eventData($query,$data=array()){
    try{
      $cmd = $this->sql->prepare( $query );
      $cmd->execute($data);
    }catch(Exception $e){
      $this->response->SetResponse(false, $e->getMessage());
      return $this->response;

    }

  }
  public function getColumns($query, $data=array()){

        $cmd = $this->sql->prepare( $query );
        $cmd->execute($data);
        $i=$cmd->columnCount();
        $numero=intval($i);

        for($j=0; $j<$numero ; $j++){
          $ret[]= $cmd->getColumnMeta($j);
       }

        return $ret;

  }


  function lastId(){
    try{
    return $this->sql->lastInsertId();
  }catch(Exception $e){
      print_r( $e->getMessage());
  }
  }


  function setCredentials($user,$pass,$dbName,$driver, $serv){
    switch($driver){

      case 'SqlServer':
          $this->connString   = 'sqlsrv:server='.$serv.';database='.$dbName;
          $this->userName     = $user;
          $this->passCode     = $pass;
      break;

      case 'Mysql':
          $this->connString   = 'mysql:host='.$serv.';dbname='.$dbName;
          $this->userName     = $user;
          $this->passCode     = $pass;
      break;
    }
  }
}
