<?php
$host= "sql6.freemysqlhosting.net";

$db = "sql6156804";
$CHAR_SET = "charset=utf8"; 
 
$username = "sql6156804";    
$password = "18n6QVscXg"; 
	

$link = mysqli_connect($host, $username, $password, $db);
if (!$link) {
    	die('Could not connect: ' . mysqli_connect_errno());
}
else
{
	echo "connect";
}
	
	
$access_token = 'XhHg/KrKivfXx2z2z+gM4rrkxgHVDrS8ZzqlmoZB9M3atvmyHBCRLFwvY08BCxTAKrX2gl1W+4hioLqRNIhEevHXg8MvNUDlL/sN2aDc/20+bXzxdmo6xnJA/i1gj0m/ObJ5qOKD8Lwi43SyEdkEKwdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if($event['source']['userId'] == 'U7fd7eee8c6ab03c5f8c12b51b47a09c8')
		{
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
				// Get text sent
				$text = $event['message']['text'];
				// Get replyToken
				$replyToken = $event['replyToken'];
	
				// Build message to reply back
				$messages = [
					'type' => 'text',
					'text' => "asddasdasd"
				];
	
				// Make a POST Request to Messaging API to reply to sender
				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
					'replyToken' => $replyToken,
					'messages' => [$messages],
				];
				$post = json_encode($data);
				$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
	
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);
	
				echo $result . "\r\n";
			}#if event
		}#if check id
		else
		{
			    
				$text = $event['message']['text'];
				$replyToken = $event['replyToken'];
				$userid=(string)$event['source']['userId'];
				$telephone="telephone";
				$password="";
				$pin="";
				$step="";
				$check="000";
				// Create connection
				$link = mysqli_connect($host, $username, $password, $db);
				// Check connection
				if ($link ->connect_error) {
					die("Connection failed: " . $link->connect_error);
				} 
				
				$sql = "SELECT * FROM userstep";
				$result = $link->query($sql);
				
				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						if($userid==$row["uid"])
						{
							$telephone=$row["telephone"];
							$password=$row["password"];
							$step=$row["step"];
							
						}
						echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
					}
				} else {
					echo "0 results";
				}
				$link->close();
				
				if($text=="สมัครสมาชิก")
				{
					if($telephone=="")
					{
						$sql = "INSERT INTO userregister(id, uid , telephone, password, pin)
									VALUES ('', '$userid', '$telephone', '$password','$pin')";
												
									if (mysqli_query($link, $sql)) {
												echo "New record created successfully";
									} 
									else {
												echo "Error: " . $sql . "<br>" . mysqli_error($link);
									}
									
						
						$step="regis0";
						
						
						$sql = "INSERT INTO userstep(id, uid, telephone, step)
								VALUES ('', '$userid', '$telephone', '$step')";
											
								if (mysqli_query($link, $sql)) {
											echo "New record created successfully";
								} 
								else {
											echo "Error: " . $sql . "<br>" . mysqli_error($link);
								}
								
					}
					$messages = [
						'type' => 'text',
						'text' => "กรุณากรอก หมายเลขโทรศัพท์"
					];
				}
				else
				{
					if($telephone=="" && $password=="")#no data
					{
						$messages = [
							'type' => 'text',
							'text' => "ยินดีต้อนรับอีกครั้ง สู่ หวยออนไลน์ รบกวนพิมคำว่า สมัครสามาชิก เพื่อสมัครสมาชิก"
						];
					}
					else
					{
						if($telephone="telephone")#update telephone number
						{
								// Create connection
								$link = mysqli_connect($host, $username, $password, $db);
								// Check connection
								if ($link ->connect_error) {
									die("Connection failed: " . $link->connect_error);
								} 
												
								$sql = "UPDATE userregister SET telephone=$text WHERE uid=$userid";
								
								if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
								} else {
									echo "Error updating record: " . $link->error;
								}
								
								$link->close();
								
								$messages = [
									'type' => 'text',
									'text' => "โปรดกรอกรหัสผ่าน"
								];
							
						}
						
					}
				}
	
				// Make a POST Request to Messaging API to reply to sender
				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
					'replyToken' => $replyToken,
					'messages' => [$messages],
				];
				$post = json_encode($data);
				$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
	
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);
			
		}
	}#if forach
}
echo "OK";