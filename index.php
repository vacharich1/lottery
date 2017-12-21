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
				if($text=="คำสั่ง" || $text=="0")
				{
					// Build message to reply back
					$messages = [
								'type' => 'text',
								'text' => "=== เมนูหลัก ===\n\nกด1 เเทงหวย\nกด2 จำนวนเงินที่ต้องการฝาก\nกด3 เเจ้งการโอนเงิน\nกด4 แจ้งถอนเงิน\nกด5 ตรวจสอบยอดเงิน"
					];
				}
				
				$sql1 = "SELECT * FROM userstep WHERE uid='".$userid."'";
				$result = $link->query($sql1);
				$check_member="1";		
				$credit="0";
				if ($result->num_rows > 0) {
				// output data of each row
					while($row = $result->fetch_assoc()) {
								$step=$row["step"];
								$credit=$row["credit"];
						}
					}
				
				if($step=="doneregis")
				{
					if($text=="0")
					{
							$messages = [
								'type' => 'text',
								'text' => "=== เมนูหลัก ===\n\nกด1 เเทงหวย\nกด2 จำนวนเงินที่ต้องการฝาก\nกด3 เเจ้งการโอนเงิน\nกด4 แจ้งถอนเงิน\nกด5 ตรวจสอบยอดเงิน"
							];
						
					}
					else
					{
							$messages = [
									'type' => 'text',
									'text' => "คุณไม่มีเครดิต\nกรุณาเติมเงิน\n\nกด 2 เติมเงินเพื่อเข้าใช้งานในส่วนนี้ \n\nขออภัยอย่างสูง \n\nกด 0 กลับสู่เมนูหลัก"
								];
							
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
				$sql1 = "SELECT * FROM userstep1";
				$result = $link->query($sql1);
				
				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						if($userid==$row["userid"])
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
							
							
							$sql = "UPDATE userstep1 SET telephone='".$telephone."' WHERE uid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							
							

							$sql = "UPDATE userstep1 SET step='doneregis' WHERE uid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							
							$messages = [
									'type' => 'text',
									'text' => "	หมายเลขโทรศัพท์ของคุณคือ".$text."\n\nกด # ยืนยันหมายเลขถูกต้อง \n\nกด * เเก้ไขหมายเลข"
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
									}
								}
							}
									
							$sql = "INSERT INTO member(id, userid , telephone, password)
										VALUES ('', '$userid', '$telephone', '')";
													
										if (mysqli_query($link, $sql)) {
													echo "New record created successfully";
										} 
										else {
													echo "Error: " . $sql . "<br>" . mysqli_error($link);
										}
										
							$text33= "ยืนยันการเป็นสมาชิก \nเบอร์โทรศัพท์ของคุณคือ ".$telephone."\n\n เริ่มใช้งานได้เลย พิม 0 เพื่อดูคำสั่งใช้งาน";
							$messages = [
										'type' => 'text',
										'text' => $text33
									];			
						}
						else if($text=='*')
						{
								$messages = [
										'type' => 'text',
										'text' => "โปรดพิมหมายเลยโทรศัพท์"
									];
									
								$sql = "UPDATE userstep1 SET step='regis0' WHERE uid='".$userid."'";
																
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
							
						}
						else
						{
							$messages = [
										'type' => 'text',
										'text' => "กรุณาพิม # หรือ * เท่านั้น 5555 "
									];
							
						}
						
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
							
							
							$sql = "INSERT INTO userstep(id, uid, telephone, step, credit, usernotcon, usemoney)
									VALUES ('', '$userid', '$telephone', '$step', '0', '0', '0')";
												
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
						if($telephone=="telephone")#no data
						{
							$messages = [
								'type' => 'text',
								'text' => "ยินดีต้อนรับ สู่ ระบบชิงโชค\n\nโปรดกรอก หมายเลขโทรศัพท์ เพื่อส่งรหัสชิงโชค"
							];
							
							$step="regis0";
							
							
							$sql = "INSERT INTO userstep1(id, userid, telephone, step)
									VALUES ('', '$userid', '$telephone', '$step')";
												
									if (mysqli_query($link, $sql)) {
												echo "New record created successfully";
									} 
									else {
												echo "Error: " . $sql . "<br>" . mysqli_error($link);
									}
									
							$sql = "INSERT INTO userregister(id, uid , telephone, password, pin)
										VALUES ('', '$userid', '$telephone', '$password','$pin')";
													
										if (mysqli_query($link, $sql)) {
													echo "New record created successfully";
										} 
										else {
													echo "Error: " . $sql . "<br>" . mysqli_error($link);
										}
										
							
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