<?php
include('config/database.php');
header('content-type: application/json');

if($_SERVER['REQUEST_METHOD']=="GET")
{
  if(isset($_GET['p_DataRequest']))
  {
    
    $p_DataRequest = isset($_GET['p_DataRequest']) ? $_GET['p_DataRequest'] : '';
    $p_startDate = isset($_GET['p_startDate']) ? $_GET['p_startDate'] : '';
    $p_endDate = isset($_GET['p_endDate']) ? $_GET['p_endDate'] : '';
    $pdo = Database::connect();
    $sql = "call test.usp_GetKPIData(?, ?, ?);";
    try {

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $p_DataRequest,PDO::PARAM_STR);
        $stmt->bindParam(2, $p_startDate,PDO::PARAM_STR);
        $stmt->bindParam(3, $p_endDate,PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
        $returnResult=[
          "status" => "true",
          "data" => $result,
          "message" => "Success"];
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
  
}


// if($_SERVER['REQUEST_METHOD']=="POST")
// {
//   $data = json_decode( file_get_contents( 'php://input' ), true );
  
//   $name = $data['name'];
//   $email = $data['email'];
  
//   $json = add_user_info($name,$email);
//   echo json_encode($json);
// }


?>