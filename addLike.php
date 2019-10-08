<?php



require(realpath (dirname(__FILE__))."/Database.php");
  $con = new Database(); 
  
  


    $postId = @$_POST["postid"];
    $type = @$_POST["type"];
  
    if($type==1){
   


      date_default_timezone_set('Europe/Istanbul');
    $data = array(
    "postId" =>  $postId,
   
    "ipAddr" =>$_SERVER['REMOTE_ADDR'] ,
    "time" => date("H:i:s"));
    $result = $con->InsertData($data);
    print_r($result);



}  
if($type==2){
  
  date_default_timezone_set('Europe/Istanbul');
$data = array(
"postId" =>  $postId,
"ipAddr" =>$_SERVER['REMOTE_ADDR'] );
$result = $con->DeleteData($data);
print_r($result);

}




?>