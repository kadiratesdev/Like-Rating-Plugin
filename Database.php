<?php

class Database{

  public  function Connection(){
       global $tableprefix;
        try {
            require_once('../../../wp-config.php');
            $table_prefix;
            $dbName = DB_NAME;
            $host = DB_HOST;
            $dbUser = DB_USER;
            $dbPass = DB_PASSWORD;
            $db = new PDO("mysql:host=$host;dbname=$dbName", "$dbUser", "$dbPass");
            return $db;
       } catch ( PDOException $e ){
            print $e->getMessage();
       }
    }
    public function InsertData($data){
      
        global $table_prefix;
         $db = $this->Connection();
        $tablename = $table_prefix."likePlugin";
       
        $query = $db->prepare("INSERT INTO $tablename SET
            postId = ?,
            ipAddr = ?,
            time = ?");
            $insert = $query->execute(array(
         $data["postId"],   $data["ipAddr"],  $data["time"]
            ));
           
             $last_id = $db->lastInsertId();
             print_r(" Basarili");

    }

    public function DeleteData($data){
      
        global $table_prefix;
      
        $db = $this->Connection();
        $tablename = $table_prefix."likePlugin";
        $sql = 'DELETE FROM  '.$tablename.' WHERE postId = '.$data["postId"]." and ipAddr = '".$data["ipAddr"]."'";
        $status = $db->exec($sql);
        print_r(" Basarili");


        }


  

    
    

}
?>