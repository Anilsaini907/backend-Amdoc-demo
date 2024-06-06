<?php
include('config/database.php');
header('content-type: application/json');

if($_SERVER['REQUEST_METHOD']=="GET")
{
    $pdo = Database::connect();
    $sql = "call test.usp_getAllUsers();";
    try {

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
        $returnResult=[
          "status" => "true",
          "data" => $result,
          "message" => "Success"
        ];
    } catch (PDOException $e) {
      $returnResult=[
        "status" => "false",
        "data" => $result,
        "message" => "Error"];
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    Database::disconnect();
    echo json_encode($returnResult);
  
}


if($_SERVER['REQUEST_METHOD']=="POST")
{

 $method= "AES-128-CTR";  
 $key = "Cemtics@2024";  
 $options = 0;
 $iv = '1234567891011121';
  $data = json_decode( file_get_contents( 'php://input' ), true );
  $p_DataRequest=$data['data_request'];
  $p_id=$data['id'];
  $p_userName = $data['username'];
  $encryption_value   = openssl_encrypt($data['password'], $method, $key,$options,$iv);
  $p_role_id = $data['role_id'];
  $p_password=$encryption_value ;
  
  $pdo = Database::connect();

  try {
$sql = "call test.usp_manageUser(?,?, ?, ?, ?);";
// Prepare and bind parameters
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $p_DataRequest);
$stmt->bindParam(2, $p_id);
$stmt->bindParam(3, $p_userName);
$stmt->bindParam(4, $p_role_id);
$stmt->bindParam(5, $p_password);

// Execute the statement
$stmt->execute();
// Get the output parameter value
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);;
// $loginResult = $row['IS_VALID'];
    $returnResult=[
      "status" => "true",
      "data" => $row,
      "message" => "Success"];
} catch (PDOException $e) {
  $returnResult=[
    "status" => "false",
    "data" => null,
    "message" => "Error"];
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
Database::disconnect();
echo json_encode($returnResult);

}



?>