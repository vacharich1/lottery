<?php
$host= "sql6.freemysqlhosting.net";

$db = "sql6157803";
$CHAR_SET = "charset=utf8"; 
 
#$username = "sql6156804";    
#$password = "18n6QVscXg"; 

$username = "sql6157803";    
$password = "XErmELW5R3"; 
	

$link = mysqli_connect($host, $username, $password, $db);
if (!$link) {
    	die('Could not connect: ' . mysqli_connect_errno());
}

$sql1 = "SELECT * FROM userwithdrawinformation ";
$result = $link->query($sql1);
$check_member="1";		
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {
		echo "[ Id ]   : ".$row["uid"]."  [ Bankaccount ] : ".$row["bankaccount"]."  [ Nameandbranch ]  :".$row["nameandbranchbank"]."  [  Account owner ]  : ".$row["account_owner"]." [ Thai id card ]  : ".$row["idcarduseseven"];
	}
}	
