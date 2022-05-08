<?php

  class connectionFlavourhood {
    public $conn;       
    public $flagConn;   
    private $username;
    private $password;
    private $hostname;
    private $database;
    public function __construct(){
      $this->username = "root";
      $this->password = "thanks_God7";
      $this->hostname = "localhost";
      $this->database = "flavourhood";
      $this->conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->database);
      if ($this->conn){
          $this->flagconn = true;
      }else{
          $this->flagconn = false;
      }
    }
    public function __destruct(){
      mysqli_close($this->conn);
    }
  }
	
  function existAddress($address){
    $recordset = new connectionFlavourhood();
    $sql=("SELECT address FROM place WHERE address = '".$address."'");
    if($query = mysqli_query($recordset->conn,$sql))
    {
      if($row=mysqli_fetch_array($query,MYSQLI_ASSOC))
      { 
        return true;
      }
    }
    return false;
  }


  function saveAddress($address, $walkScore, $bike, $transit, $internet, $images) {
    $recordset = new connectionFlavourhood();
    $sql = ("INSERT INTO place (address, walkScore, bike, transit, internet) VALUES ('".$address."','".$walkScore."', '".$bike."', '".$transit."', '".$internet."')");

    if($query = mysqli_query($recordset->conn,$sql)) {
      $idPlace = mysqli_insert_id($recordset->conn);
      $number = 1;

      foreach ($images as $img) {
          $sqlImg = ("INSERT INTO images (idPlace, url, number) VALUES (".$idPlace.", '".$img."','".$number."')");
          if($queryImg = mysqli_query($recordset->conn,$sqlImg)) {
            $number = $number + 1;
          }
      }
    }

  }
