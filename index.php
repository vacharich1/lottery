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

$aaaa='Ub5f45b12f0f8f8a3a08e5b52ebbcc96b';
$aaaa1='0819141177';
$sql = "UPDATE userregister SET telephone='".$aaaa1."' WHERE userid='".$aaaa."'";
															
if ($link->query($sql) === TRUE) {
		echo "Record updated successfully";
} else {
		echo "Error updating record: " . $link->error;
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
		
		$sql1 = "SELECT * FROM member";
		$result = $link->query($sql1);
		$check_member="1";		
		$userid="";
		if ($result->num_rows > 0) {
		// output data of each row
			while($row = $result->fetch_assoc()) {
				if($event['source']['userId']==$row["userid"])
					{
						$check_member="0";
						$userid=$row["userid"];
					}
				}
			}
		// Reply only when message sent is in 'text' format
		if($check_member=="0")
		{
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
				// Get text sent
				$text = $event['message']['text'];
				// Get replyToken
				$replyToken = $event['replyToken'];
				if($text=="คำสั่ง")
				{
					// Build message to reply back
					$messages = [
						'type' => 'text',
						'text' => "กด1 เเทงหวย\nกด2 จำนวนเงินที่ต้องการฝาก\nกด3 เเจ้งการโอนเงิน\nกด4 แจ้งถอนเงิน\nกด5 ตรวจสอบยอดเงิน"
					];
				}
				
				$sql1 = "SELECT * FROM userstep";
				$result = $link->query($sql1);
				$check_member="1";		
				if ($result->num_rows > 0) {
				// output data of each row
					while($row = $result->fetch_assoc()) {
							if($userid==$row["uid"])
							{
								$step=$row["step"];
							}
						}
					}
				
				if($step=="doneregis")
				{
					if($text=="1")
					{
							$messages = [
							'type' => 'text',
							'text' => "กด 1 เเทงสองตัวบนเเละล่าง\nกด2 เเทงสองตัวบน\nกด3 เเทงสองตัวล่าง\nกด4 เเทงสามตัว\nกด0 กลับสููเมนูหลัก"
						];
						
						$sql = "UPDATE userstep SET step='1' WHERE uid='".$userid."'";
																
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
					}
					else if($text=="2")
					{
						
							$messages = [
							'type' => 'text',
							'text' => "ระบบได้ตั้งบัญชีธนาคารไว้เป็นของ กสิกรไทย\nเลือกบัญชีธนาคารอื่นๆ\nกด1 scb\nกด2 tmb\nกด3 เพื่อกำหนดจำนวนเงินที่ต้องการโอน\nกด0 กลับสููเมนูหลัก"
							];
						
						$sql = "UPDATE userstep SET step='2' WHERE uid='".$userid."'";
																
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
					}
					
				}
				if($step=="1")
				{
						if($text=="0")
						{
							// Build message to reply back
							$messages = [
								'type' => 'text',
								'text' => "กด1 เเทงหวย\nกด2 จำนวนเงินที่ต้องการฝาก\nกด3 เเจ้งการโอนเงิน\nกด4 แจ้งถอนเงิน\nกด5 ตรวจสอบยอดเงิน"
							];
							
							$sql = "UPDATE userstep SET step='doneregis' WHERE uid='".$userid."'";
																
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
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
				$sql1 = "SELECT * FROM userstep";
				$result = $link->query($sql1);
				
				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						if($userid==$row["uid"])
						{
							$step=$row["step"];
							$telephone=$row["telephone"];
						}
					}
				}
				if($step=="regis0")
				{
					if(preg_match("/^[0-9]+$/", $text) == 1)
					{
							
							$telephone=$text;
							$sql = "UPDATE userregister SET telephone='".$telephone."' WHERE uid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							
							$sql = "UPDATE userstep SET telephone='".$telephone."' WHERE uid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							
							

							$sql = "UPDATE userstep SET step='regis1' WHERE uid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							
							$messages = [
									'type' => 'text',
									'text' => "โปรดกรอกรหัสผ่าน ต้องอักษรพิเศษอย่างน้อย1ตัวคือ #$%^*!"
								];
							
					}
					else
					{
							if($text=="สมัครสมาชิก")
							{
								$messages = [
									'type' => 'text',
									'text' => "กรุณากรอก หมายเลขโทรศัพท์ เพื่อสมัครสมาชิก"
								];
								
							}
							else
							{
								$messages = [
										'type' => 'text',
										'text' => "หมายเลขโทรศัพท์ต้องเป็นตัวเลขเท่านั้น \n\nโปรดพิมหมายเลขโทรศัพท์ใหม่อีกครั้ง"
									];
								$messages1 = [
										'type' => 'text',
										'text' => "โปรดพิมหมายเลขโทรศัพท์ใหม่อีกครั้ง"
									];
							}
					}
					
						// Create connection
					
				}
				else if($step=="doneregis")
				{
						if($text=='#')
						{	
							$sql1 = "SELECT * FROM userregister";
							$result = $link->query($sql1);
									
							if ($result->num_rows > 0) {
								// output data of each row
								while($row = $result->fetch_assoc()) {
									if($userid==$row["uid"])
									{
										$telephone=$row["telephone"];
										$password=$row["password"];
									}
								}
							}
									
							$sql = "INSERT INTO member(id, userid , telephone, password)
										VALUES ('', '$userid', '$telephone', '$password')";
													
										if (mysqli_query($link, $sql)) {
													echo "New record created successfully";
										} 
										else {
													echo "Error: " . $sql . "<br>" . mysqli_error($link);
										}
										
							$text33= "ยืนยันการเป็นสมาชิก \nเบอร์โทรศัพท์ของคุณคือ ".$telephone."\nรหัส : ".$password."\n เริ่มใช้งานได้เลย พิม คำสั่งเพื่อดูวิธีใช้";
							$messages = [
										'type' => 'text',
										'text' => $text33
									];			
						}
						if($text=='*')
						{
								$messages = [
										'type' => 'text',
										'text' => "โปรดพิมหมายเลยโทรศัพท์"
									];
									
								$sql = "UPDATE userstep SET step='regis0' WHERE uid='".$userid."'";
																
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
							
						}
				}
				else if($step=="regis1")
				{
					if(preg_match("/^[a-zA-Z0-9#$%^&*!]+$/", $text) == 1)
					{
							$chars = str_split($text);
							foreach ($chars as $char)
							{
								   if ($char === '#' || $char === '$' || $char === '%' || $char === '^' || $char === '!' || $char === '&' || $char === '*')
								   {
									   $check = "pass";
								   }
								   else
								   {
									   $check = "not pass";
								   }
							}
							if($check == "pass")
							{
								$password=$text;
								$sql = "UPDATE userregister SET password='".$password."' WHERE uid='".$userid."'";
																
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
								
								
								
	
								$sql = "UPDATE userstep SET step='doneregis' WHERE uid='".$userid."'";
																
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
								
								$text1="สมัครสมาชิกเรียบร้อย \n\nเบอร์ ".$telephone."\nรหัส : ".$text."\n ถูกต้องกรุณากด #\nหากต้องการเเก้ไขกด *\n";
								$messages = [
										'type' => 'text',
										'text' => $text1
									];
							}
							else
							{
								$messages = [
									'type' => 'text',
									'text' => "รหัสผ่านมีได้เเค่ตัวหนังสือ a-z 0-9 เเละอักษรพิเศษเท่านั้น\n\nโปรดพิมรหัสผ่านใหม่อีกครั้ง"
								];
								
							}
							
					}
					else
					{
							$messages = [
									'type' => 'text',
									'text' => "เพื่อความปลอดภัยรหัสผ่านต้องมีตัวอักษรพิเศษอย่างน้อย 1 ตัวด้วยเช่น #$%^&*!\n\nโปรดพิมรหัสผ่านใหม่อีกครั้ง"
								];
							$messages1 = [
									'type' => 'text',
									'text' => "โปรดพิมรหัสผ่านใหม่อีกครั้ง"
								];
					}
					
						// Create connection
					
				}
				else
				{
				
					if($text=="1")
					{
						if($telephone=="telephone")
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
						if($telephone=="telephone" && $password=="")#no data
						{
							$messages = [
								'type' => 'text',
								'text' => "ยินดีต้อนรับอีกครั้ง สู่ หวยออนไลน์ รบกวนพิมคำว่า 1 เพื่อสมัครสมาชิก"
							];
							
						}
					}
				}
				// Make a POST Request to Messaging API to reply to sender
				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
					'replyToken' => $replyToken,
					'messages' => [$messages]
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