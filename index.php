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
					else if($text=="5")
					{
							$messages = [
								'type' => 'text',
								'text' => "ขณะนี้ ยอดเงินของคุณมี ".$credit." บาท"
							];
						
					}
					else if($text=="4")
					{
							$messages = [
								'type' => 'text',
								'text' => "ขณะนี้ ยอดเงินของคุณมี ".$credit." บาท"
							];
						
					}
					else
					{
							$messages = [
							'type' => 'text',
							'text' => "กด1 เเทงหวย\nกด2 จำนวนเงินที่ต้องการฝาก\nกด3 เเจ้งการโอนเงิน\nกด4 แจ้งถอนเงิน\nกด5 ตรวจสอบยอดเงิน"
						];
						
					}
					
				}
				else if($step=="1")#กด 1
				{
						$credit="0";
						if($text=="0")#กด0 ย้อนเมนูหลัก
						{
							// Build message to reply back
							$messages = [
								'type' => 'text',
								'text' => "=== เมนูหลัก ===\n\nกด1 เเทงหวย\nกด2 จำนวนเงินที่ต้องการฝาก\nกด3 เเจ้งการโอนเงิน\nกด4 แจ้งถอนเงิน\nกด5 ตรวจสอบยอดเงิน"
							];
							
							$sql = "UPDATE userstep SET step='doneregis' WHERE uid='".$userid."'";
																
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
						}
						else
						{
							$sql1 = "SELECT * FROM userstep";
							$result = $link->query($sql1);
							$check_member="1";		
							if ($result->num_rows > 0) {
							// output data of each row
								while($row = $result->fetch_assoc()) {
										if($userid==$row["uid"])
										{
											$credit=$row["credit"];
										}
									}
								}	
							
							if($credit=="0")
							{
									$messages = [
									'type' => 'text',
									'text' => "กรุณาเติมเงินกด 2 เพิ่มเข้าใช้งานในส่วนนี้ ขออภัยอย่างสูง"
								];
								
							}
							
						}
						if($credit!="0")
						{
							if($text=="1")
							{
								// Build message to reply back
								$messages = [
									'type' => 'text',
									'text' => "เเทงหวย 2 ตัวบนเเละล่าง\n\nกรุณากรอกหมายเลข 2 ตัว"
								];
								
								$sql = "UPDATE userstep SET step='11' WHERE uid='".$userid."'";
																	
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
								
							}
						}
						
					
				}
				else if($step=="11")#กรอกเลขเเทง
				{
					$count_text = strlen($text);
					if($count_text==2)
					{
						if(preg_match("/^[0-9]+$/", $text) == 1)
						{
								$messages = [
								'type' => 'text',
								'text' => "คุณมีเครดิต ".$credit." บาท\n\n โปรดกรอกจำนวนเงิน"
							];
							$sql = "UPDATE userstep SET step='111' WHERE uid='".$userid."'";
																
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							$price="not";
							$type="11";
							$sql = "INSERT INTO lottery(id, uid, lottery, price, type, buy_book)
										VALUES ('', '$userid', '$text', '$price', '$type', 'book')";
													
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
								'text' => "หมายเลขเป็นได้เฉพาะตัวเลขเท่านั้น"
							];
						}
					}
					else
					{
						if(preg_match("/^[0-9]+$/", $text) == 1)
						{
								$messages = [
								'type' => 'text',
								'text' => "หมายเลขอยู่ในเลขระหว่าง 00-99 เท่านั้น"
							];
						}
						else
						{
							$messages = [
								'type' => 'text',
								'text' => "หมายเลขเป็นได้เฉพาะตัวเลขเท่านั้น"
							];
						}
						
					}
					
				}
				else if($step=="111")#กรอกจำนวนเงินเข้ามา
				{
					
					if(preg_match("/^[0-9]+$/", $text) == 1)
					{
						
						
						$sql = "UPDATE userstep SET step='1111' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$sql = "UPDATE userstep SET usernotcon='".$text."' WHERE uid='".$userid."'";
																
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$sql = "UPDATE lottery SET price='".$text."' WHERE uid='".$userid."' AND buy_book='book'";
																
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						sleep(0.5);
						
						$sql1 = "SELECT * FROM lottery WHERE uid='".$userid."' AND buy_book='book'";
						$result = $link->query($sql1);
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$lottery_show=$row["lottery"];
								}
							}
						
						
						$messages = [
								'type' => 'text',
								'text' => "คุณซื้อ  ".$lottery_show."\nจำนวน ".$text." บาท\nกด# ยืนยันการซื้อ\nกด* เพื่อยกเลิก"
						];
					}
					else
					{
							$messages = [
								'type' => 'text',
								'text' => "จำนวนเงินต้องเป็นตัวเลขเท่านั้น"
							];
						
						
					}
				}
				else if($step=="1111")#กรอกจำนวนเงินเข้ามา
				{
					if($text=="#")
					{
						$sql1 = "SELECT * FROM userstep WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						$credit="0";
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$credit_cal=$row["credit"];
										$price_buy_last=$row["usernotcon"];
										$priceall=$row["usemoney"];
								}
							}
						$newcredit=(int)$credit_cal-(int)$price_buy_last;
						$useall=(int)$priceall+(int)$price_buy_last;
						
						$newcredit_str=(string)$newcredit;
						$useall_str=(string)$useall;
						
						$sql = "UPDATE userstep SET credit='".$newcredit_str."' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$sql = "UPDATE userstep SET usemoney='".$useall_str."' WHERE uid='".$userid."'";
																		
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
						$sql = "UPDATE lottery SET buy_book='buy' WHERE uid='".$userid."' AND buy_book='book'";
																
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "ยืนยันการซื้อ เรียบร้อย\nคุณมีเครดิตเหลือ ".$newcredit_str."\n ต้องการดูเมนูพิม\n --> คำสั่ง"
							];
					}
					else if($text=="*")
					{
						$sql = "DELETE FROM lottery WHERE uid='".$userid."' AND buy_book='book'";
										
										if ($link->query($sql) === TRUE) {
											echo "Record deleted successfully";
										} else {
											echo "Error deleting record: " . $conn->error;
										}
										
										
						$sql = "UPDATE userstep SET step='doneregis' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "ยกเลิก เรียบร้อย\nคุณมีเครดิตเหลือ ".$credit."\n ต้องการดูเมนูพิม\n --> คำสั่ง"
							];
					}
					else
					{
						$messages = [
								'type' => 'text',
								'text' => "กด# เพื่อยืนยันการสั่งซื้อ\nกด* เพื่อยกเลิก"
							];
					}
					
				}
				else
				{
						$messages = [
							'type' => 'text',
							'text' => "กด 1 เเทงสองตัวบนเเละล่าง\nกด2 เเทงสองตัวบน\nกด3 เเทงสองตัวล่าง\nกด4 เเทงสามตัว\nกด0 กลับสููเมนูหลัก"
						];
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