<?php

//action.php

$connect = new PDO("mysql:host=localhost;dbname=kca", "root", "");
$received_data = json_decode(file_get_contents("php://input"));
$data = array();
if($received_data->action == 'fetchall')
{
 $query = "
 SELECT * FROM students 
 ORDER BY surname DESC
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
  ':surname' => $received_data->surname,
  ':middlename' => $received_data->middlename,
  ':firstname' => $received_data->firstname,
  ':age' => $received_data->age,
  ':guardian' => $received_data->guardian,
  ':phone' => $received_data->phone,
  ':regno' => $received_data->regno,
 );

 $query = "
 INSERT INTO users 
 (Surname, Middle Name, First Name, Age, Guardian, Telephone Number, Reg_No) 
 VALUES (:surname, :middlename, :firstname, :age, :guardian, :phone, :regno )
 ";

 $statement = $connect->prepare($query);

 $statement->execute($data);

 $output = array(
  'message' => 'Data Inserted'
 );

 echo json_encode($output);
}
?>