<?php
date_default_timezone_set("Asia/Bangkok");
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
		
		$sql1 = "SELECT * FROM member1";
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
				
				
				$sql1 = "SELECT * FROM userstep1 WHERE userid='".$userid."'";
				$result = $link->query($sql1);
				$check_member="1";		
				if ($result->num_rows > 0) {
				// output data of each row
					while($row = $result->fetch_assoc()) {
								$step=$row["step"];
						}
					}
				
				
				if($step=="regis0")
				{
					if(preg_match("/^[0-9]+$/", $text) == 1)
					{
							
							$telephone=$text;
							
							
							$sql = "UPDATE userstep1 SET telephone='".$telephone."' WHERE userid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							
							$sql = "UPDATE member1 SET telephone='".$telephone."' WHERE userid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							$sql = "UPDATE userregister SET telephone='".$telephone."' WHERE uid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							

							$sql = "UPDATE userstep1 SET step='doneregis' WHERE userid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							
							$messages = [
									'type' => 'text',
									'text' => "	หมายเลขโทรศัพท์ของคุณคือ".$text."\n\nหากต้องการเเก้ไขเบอร์ \n\nกด * เพื่อเเก้ไขเบอร์\n\n สามารถพิมรหัสส่งชิงโชคได้เลย"
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
				if($step=="doneregis")
				{
					if($text=='0')
					{
							$messages = [
									'type' => 'text',
									'text' => "   ==== เมนูหลัก ====\n\n กด * เพื่อเเก้ไขหมายเลขโทรศัพท์\n\n กด 1 เพื่อดูเลขที่ส่งชิงโชค\n\n หรือสามารถพิมส่งเลขชิงโชคได้เลย"
								];

					}
					else if($text=='1')
					{
							
								
							$sql1 = "SELECT * FROM numberfromuser";
							$result = $link->query($sql1);
							$testsend="";
							if ($result->num_rows > 0) {
								// output data of each row
								while($row = $result->fetch_assoc()) {
									if($userid==$row["userid"])
									{
										$testsend=$testsend." ".$row["number"]."\n";
									}
								}
							}
							
							$messages = [
									'type' => 'text',
									'text' => "เลขที่ส่งชิงโชค"."\n\n".$testsend."กด 0 เมนูหลัก\n\n หรือสามารถพิมส่งเลขชิงโชคได้เลย"
								];
						
					}
					else if($text=='*')
					{
							$messages = [
									'type' => 'text',
									'text' => "โปรดพิมหมายเลยโทรศัพท์"
								];
								
							$sql = "UPDATE userstep1 SET step='regis0' WHERE userid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
						
					}
					else
					{
						// Create connection
						
						$sql1 = "SELECT * FROM numberfromuser";
						$result = $link->query($sql1);
						$checknuberuse="0";
						if ($result->num_rows > 0) {
							// output data of each row
							while($row = $result->fetch_assoc()) {
								if($text==$row["number"])
								{
									$checknuberuse="1";	
								}
							}
						}
						
						if($checknuberuse=="0")
						{
							  $sql1 = "SELECT * FROM number";
							  $result = $link->query($sql1);
							  $checknumber="0";
							  if ($result->num_rows > 0) {
								  // output data of each row
								  while($row = $result->fetch_assoc()) {
									  if($text==$row["number"])
									  {
										  $checknumber="1";
									  }
								  }
							  }
							  if($checknumber=="1")
							  {
								  $messages = [
											  'type' => 'text',
											  'text' => "ส่งรหัส".$text."เรียบร้อย\n\n กด 0 เพื่อกลับสู่เมนูหลัก\n\n หรือสามารถพิมรหัสส่งชิงโชคต่อได้เลย"
								  ];	
								  //$date = new DateTime('now');
								  //$dtz = new DateTimeZone("Asia/Bangkok"); //Your timezone
								  //$dateuse=NOW();
								  $DateResultNow=date("Y-m-d H:i:s", mktime(date("H")+0, date("i")+0, date("s")+0, date("m")+0 , date("d")+0, date("Y")+0));
								  
	  
								  
								  $sql = "INSERT INTO numberfromuser(id, userid, telephone, number, date)
											  VALUES ('', '$userid', '$telephone', '$text', '$DateResultNow')";
														  
											  if (mysqli_query($link, $sql)) {
														  echo "New record created successfully";
											  } 
											  else {
														  echo "Error: " . $sql . "<br>" . mysqli_error($link);
											  }
							  }
							  else
							  {
								  $messages = [
											  'type' => 'text',
											  'text' => "รหัส".$text."ไม่ตรงกับฐานข้อมูล\n\n กด 0 เพื่อกลับสู่เมนูหลัก \n\n โปรดตรวจสอบเลขชิงโชค"
								  ];	
								  
							  }
						}
						else
						{
							$messages = [
										  'type' => 'text',
										  'text' => "รหัส".$text."ถูกส่งชิงโชคเเล้ว\n\n กด 0 เพื่อกลับสู่เมนูหลัก\n\n โปรส่งรหัสอื่น"
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
							
							
							$sql = "UPDATE userstep1 SET telephone='".$telephone."' WHERE userid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							
							$sql = "UPDATE userregister SET telephone='".$telephone."' WHERE uid='".$userid."'";
															
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							
							
							$sql = "UPDATE userstep1 SET step='doneregis' WHERE userid='".$userid."'";
															
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
									
							$sql = "INSERT INTO member1(id, userid, telephone)
										VALUES ('', '$userid', '$telephone')";
													
										if (mysqli_query($link, $sql)) {
													echo "New record created successfully";
										} 
										else {
													echo "Error: " . $sql . "<br>" . mysqli_error($link);
										}
										
							$text33= "เบอร์โทรศัพท์ของคุณคือ ".$telephone."\n\nหากต้องการเเก้ไขเบอร์ \n\nกด * เพื่อเเก้ไขเบอร์\n\n สามารถพิมรหัสส่งชิงโชคได้เลย";
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
									
								$sql = "UPDATE userstep1 SET step='regis0' WHERE userid='".$userid."'";
																
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
										'text' => "กรุณาพิม # หรือ * เท่านั้น"
									];
							
						}
						
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