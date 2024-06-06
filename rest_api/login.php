<?php
include('config/database.php');
header('content-type: application/json');

if($_SERVER['REQUEST_METHOD']=="POST")
{
  $method= "AES-128-CTR";  
  $key = "Cemtics@2024";  
  $options = 0;
  $iv = '1234567891011121';
  $data = json_decode( file_get_contents( 'php://input' ), true );
  $p_username = $data['p_username'];
  $p_password   = openssl_encrypt($data['p_password'], $method, $key,$options,$iv);
  $pdo = Database::connect();

try {
$sql = "CALL test.usp_Login(?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $p_username);
$stmt->bindParam(2, $p_password);
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