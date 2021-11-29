<?php

//action.php

$connect = new PDO("mysql:host=localhost;dbname=mydb", "root", "");
$received_data = json_decode(file_get_contents("php://input"));
$data = array();

if($received_data->action == 'fetchall')
{
 $query = "
 SELECT * FROM employees 
 ORDER BY first DESC
 ";
 $statement = $connect->prepare($query);
 $statement->execute();
 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
  $data[] = $row;
 }
 echo json_encode($data);
}

if($received_data->action == 'insert')
{
 $data = array(
  ':first' => $received_data->first,
  ':last' => $received_data->last,
  ':address' => $received_data->address,
  ':position' => $received_data->position
 );

 $query = "
 INSERT INTO users 
 (Surname, first, last, address, position) 
 VALUES (:first, :last, :address, :position)
 ";

 $statement = $connect->prepare($query);

 $statement->execute($data);

 $output = array(
  'message' => 'Data Inserted'
 );

 echo json_encode($output);
}
?>