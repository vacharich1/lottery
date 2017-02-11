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
					if($text=="1")
					{
						
							$sql1 = "SELECT * FROM userstep WHERE uid='".$userid."'";
							$result = $link->query($sql1);
							$check_member="1";		
							if ($result->num_rows > 0) {
							// output data of each row
								while($row = $result->fetch_assoc()) {

											$credit=$row["credit"];
									}
								}	
							
							if($credit=="0")
							{
									$messages = [
									'type' => 'text',
									'text' => "คุณไม่มีเครดิต\nกรุณาเติมเงิน\nกด 2 เพิ่มเข้าใช้งานในส่วนนี้ \nขออภัยอย่างสูง"
								];
								
							}
							else
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
					}
					else if($text=="2")
					{
						
							$messages = [
							'type' => 'text',
							'text' => "เลือกบัญชีธนาคารที่ต้องการโอน\n\nกด1 scb\nกด2 kbank\nกด0 กลับสููเมนูหลัก"
							];
							
							$sql = "UPDATE userstep SET step='2' WHERE uid='".$userid."'";
																		
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
						
					
					}
					else if($text=="3")
					{
							$messages = [
								'type' => 'text',
								'text' => "เเจ้งยอดโอน\n\nระบบยังไม่สามารถใช้งานในส่วนนี้ได้"
							];
						
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
						
							$sql1 = "SELECT * FROM userstep WHERE uid='".$userid."'";
							$result = $link->query($sql1);
							$check_member="1";		
							if ($result->num_rows > 0) {
							// output data of each row
								while($row = $result->fetch_assoc()) {

											$credit=$row["credit"];
									}
								}	
								
							$sql = "UPDATE userstep SET step='4' WHERE uid='".$userid."'";
																		
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
								
							$messages = [
							'type' => 'text',
							'text' => "ยอดเงินของคุณ มี".$credit." บาท ต้องการถอนผ่านทาง\n\nกด 1 seven\nกด 2 บัญชี\nกด 3 bitcoin"
							];
							
							
						
					}
					else if($text=="0")
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
								'text' => "=== เมนูหลัก ===\n\nกด1 เเทงหวย\nกด2 จำนวนเงินที่ต้องการฝาก\nกด3 เเจ้งการโอนเงิน\nกด4 แจ้งถอนเงิน\nกด5 ตรวจสอบยอดเงิน"
							];
						
					}
					
				}
				else if($step=="4")#กด 1
				{
					if($text=="1")#ถอนเงิน seven
					{
						$seven_id="not";
						$sql1 = "SELECT * FROM userwithdrawinformation WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {

										$seven_id=$row["idcarduseseven"];
								}
						}	
						if($seven_id=="not")#ครั้งเเรกไม่มีประวัติถอนเงินด้วย seven
						{
							$sql = "UPDATE userstep SET step='412' WHERE uid='".$userid."'";
																		
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
								
							$messages = [
									'type' => 'text',
									'text' => "ท่านสามารถรับเงินผ่าน seven ได้ทุกสาขา อาจมีค่าธรรมเนียม 30 บาท\n\nกรุณากรอกหมายเลขบัตรประชาชน ของท่านเพื่อใช้ในการรับเงิน"
								];
								
							
							
						}
						else
						{
							$sql = "UPDATE userstep SET step='411' WHERE uid='".$userid."'";
																		
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
							$messages = [
									'type' => 'text',
									'text' => "ท่านสามารถรับเงินผ่าน seven ได้ทุกสาขา อาจมีค่าธรรมเนียม 30 บาท เลขบัตรประชาชนของท่านคือ".$seven_id."\n\nถูกต้องกด #\nเเก้ไขกด *"
								];
								
							
						}
						
					}
					else if($text=="2")
					{
						$bank_id="not";
						$sql1 = "SELECT * FROM userwithdrawinformation WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {

										$bank_id=$row["bankaccount"];
								}
						}	
						if($bank_id=="not")#ครั้งเเรกไม่มีประวัติถอนเงินด้วย บัญชี
						{
							$sql = "UPDATE userstep SET step='422' WHERE uid='".$userid."'";
																		
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
								
							$messages = [
									'type' => 'text',
									'text' => "กรุณากรอก หมายเลขบัญชี"
								];
								
							
							
						}
						else
						{
							$sql = "UPDATE userstep SET step='421' WHERE uid='".$userid."'";
																		
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
							$messages = [
									'type' => 'text',
									'text' => "บัญชีของคุณคือ".$bank_id."\n\nถูกต้องกด #\nเเก้ไขกด *"
								];
								
							
						}
						
					}
					else if($text=="3")
					{
						$messages = [
								'type' => 'text',
								'text' => "bitcoin coming soon...."
							];
						
					}
					else
					{
						$messages = [
								'type' => 'text',
								'text' => "กด 1 seven\nกด 2 บัญชี\nกด 3 bitcoin"
							];
						
					}
					
					
					
				}
				else if($step=="421")
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
								}
							}
							
						$sql = "UPDATE userstep SET step='4222222' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "เครดิตของคุณคือ ".$credit_cal."\n\nกรุณากรอกจำนวนเงินที่ต้องการถอน"
							];
						
					}
					else if($text=="*")
					{		
										
						$sql = "UPDATE userstep SET step='422' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "กรุณากรอกเลขบัญชีธนาคาร"
							];
						
					}
					else
					{
						$messages = [
										'type' => 'text',
										'text' => "กรุณาพิม # หรือ * เท่านั้น"
									];
						
					}
					
				}
				else if($step=="422")#ถอนเงิน บัญชีธนาคาร
				{
					if(preg_match("/^[0-9]+$/", $text) == 1)
					{
						$user_id="not";
						$sql1 = "SELECT * FROM userwithdrawinformation WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {

										$user_id=$row["uid"];
								}
						}	
						if($user_id=="not")#ครั้งเเรกไม่มีประวัติถอนเงินด้วย บัญชี
						{
							$sql = "INSERT INTO userwithdrawinformation(id, uid , bankaccount, nameandbranchbank, account_owner, idcarduseseven, bitcoinaccount, type)
							VALUES ('', '$userid', '$text', 'not', 'not', 'not', 'not', 'bank')";
										
							if (mysqli_query($link, $sql)) {
										echo "New record created successfully";
							} 
							else {
										echo "Error: " . $sql . "<br>" . mysqli_error($link);
							}
							
							
							$sql = "UPDATE userstep SET step='411' WHERE uid='".$userid."'";
																			
									if ($link->query($sql) === TRUE) {
											echo "Record updated successfully";
									} else {
											echo "Error updating record: " . $link->error;
									}
									
									
							$messages = [
									'type' => 'text',
									'text' => "บัญชีธนาคารของคุณคือ".$text."\n\nถูกต้องกด #\nเเก้ไขกด *"
								];
						}
						else
						{
							
							$sql = "UPDATE userwithdrawinformation SET bankaccount='".$text."' WHERE uid='".$userid."'";									
							if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
							
							$messages = [
									'type' => 'text',
									'text' => "บัญชีธนาคารของคุณคือ".$text."\n\nถูกต้องกด #\nเเก้ไขกด *"
								];
						}
						
						$sql = "UPDATE userstep SET step='4221' WHERE uid='".$userid."'";
																		
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
								'text' => "บัญชีธนาคารต้องเป็นตัวเลขเท่านั้น"
							];
						
						
					}
					
					
				}
				else if($step=="4222222")
				{
					if(preg_match("/^[0-9]+$/", $text) == 1)
					{
						$sql1 = "SELECT * FROM userstep WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						$credit="0";
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$credit_cal=$row["credit"];
								}
							}
							
						if((int)$text>(int)$credit_cal)
						{
							     $messages = [
										'type' => 'text',
										'text' => "กรอกจำนวนเงินเกินเครดิต\n\nเครดิตของคุณคือ  ".$credit_cal."\n\nโปรดกรอกจำนวนเงินใหม่อีกครั้ง"
								];
							
							
						}
						else if((int)$text<=0)
						{
							     $messages = [
										'type' => 'text',
										'text' => "จำนวนเงิน ต้องมากกว่า 0 บาท\n\nโปรดกรอกจำนวนเงินใหม่อีกครั้ง"
								];
							
							
						}
						else
						{
							
							$sql = "UPDATE userstep SET step='4222223' WHERE uid='".$userid."'";
																			
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							$place="aaaaa";
							sleep(0.3);
							
							$sql1 = "SELECT * FROM userwithdrawinformation WHERE uid='".$userid."'";
							$result = $link->query($sql1);
							$check_member="1";		
							$credit="0";
							if ($result->num_rows > 0) {
							// output data of each row
								while($row = $result->fetch_assoc()) {
											$place=$row["type"];
									}
								}
								
							sleep(0.2);	
							$sql = "INSERT INTO userwithdrawtracsection(id, uid , type, money, transection, sendtouser, changecredit)
							VALUES ('', '$userid', '$place', '$text', 'not', 'not', 'not')";
										
							if (mysqli_query($link, $sql)) {
										echo "New record created successfully";
							} 
							else {
										echo "Error: " . $sql . "<br>" . mysqli_error($link);
							}
							
							
							$messages = [
									'type' => 'text',
									'text' => "คุณถอนเงินจำนวน ".$text." โดยรับเงินที่ ".$place."\n\nยืนยันกด #\nยกเลิกกด *"
							];
						}
					}
					else
					{
							$messages = [
								'type' => 'text',
								'text' => "จำนวนเงินต้องเป็นตัวเลขเท่านั้น"
							];
						
						
					}	
					
					
				}
				else if($step="4222223")
				{
					if($text=="#")
					{
						
						$sql = "UPDATE userstep SET step='doneregis' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						
						
						$sql1 = "SELECT * FROM userwithdrawtracsection WHERE uid='".$userid."' AND transection='not'";
						$result = $link->query($sql1);
						$check_member="1";		
						$credit="0";
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$credit_withdraw=$row["money"];
								}
							}
						
						$sql1 = "SELECT * FROM userstep WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						$credit="0";
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$credit_cal=$row["credit"];
								}
							}
						$newcredit=(int)$credit_cal-(int)$credit_withdraw;
						$newcredit_str=(string)$newcredit;
						
						$sql = "UPDATE userwithdrawtracsection SET transection='process' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$sql = "UPDATE userstep SET credit='".$newcredit_str."' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "เครติคคุณเหลือ".$newcredit."\nคุณถอนเงินออกมาจำนวน\n".$credit_withdraw." บาท\n\nระบบกำลังดำเนินการ ใช้เวลา ไม่เกิน 3 ชั่วโมง\n\nกด0 กลับสููเมนูหลัก"
							];
						
					}
					else if($text=="*")
					{		
						$sql = "DELETE FROM userwithdrawtracsection WHERE uid='".$userid."' AND transection='not'";
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
								'text' => "ยกเลิกเรียบร้อย\n\nกด0 กลับสููเมนูหลัก"
							];
						
					}
					else
					{
						$messages = [
										'type' => 'text',
										'text' => "กรุณาพิม # หรือ * เท่านั้น"
									];
						
					}
					
					
				}
				else if($step=="4222")#ชื่อเจ้าของบัญชี
				{
					$user_id="not";
					$sql1 = "SELECT * FROM userwithdrawinformation WHERE uid='".$userid."'";
					$result = $link->query($sql1);
					$check_member="1";		
					if ($result->num_rows > 0) {
					// output data of each row
						while($row = $result->fetch_assoc()) {
	
									$user_id=$row["uid"];
							}
					}	
					if($user_id=="not")#ครั้งเเรกไม่มีประวัติถอนเงินด้วย บัญชี
					{
						$sql = "INSERT INTO userwithdrawinformation(id, uid , bankaccount, nameandbranchbank, account_owner, idcarduseseven, bitcoinaccount, type)
						VALUES ('', '$userid', 'not', 'not', '$text', 'not', 'not', 'bank')";
									
						if (mysqli_query($link, $sql)) {
									echo "New record created successfully";
						} 
						else {
									echo "Error: " . $sql . "<br>" . mysqli_error($link);
						}
						
						
						$sql = "UPDATE userstep SET step='411' WHERE uid='".$userid."'";
																		
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
								
								
						$messages = [
								'type' => 'text',
								'text' => "ชื่อเจ้าของบัญชีธนาคารของคุณคือ".$text."\n\nถูกต้องกด #\nเเก้ไขกด *"
							];
					}
					else
					{
						
						$sql = "UPDATE userwithdrawinformation SET account_owner='".$text."' WHERE uid='".$userid."'";									
						if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
						
						$messages = [
								'type' => 'text',
								'text' => "ชื่อเจ้าของบัญชีธนาคารของคุณคือ".$text."\n\nถูกต้องกด #\nเเก้ไขกด *"
							];
					}
					
					$sql = "UPDATE userstep SET step='42221' WHERE uid='".$userid."'";
																	
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
				
					
					
					
				}
				else if($step=="42221")#ยืนยันชื่อเจ้าของบัญชี
				{
					if($text=="#")
					{
						
						$sql = "UPDATE userstep SET step='42222' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "ยืนยันชื่อเจ้าของบัญชีเรียบร้อย\n\nกรุณากรอกชื่อธนาคารเเละสาขา"
							];
						
					}
					else if($text=="*")
					{		
										
						$sql = "UPDATE userstep SET step='4222' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "กรุณากรอกชื่อเจ้าของบัญชี"
							];
						
					}
					else
					{
						$messages = [
										'type' => 'text',
										'text' => "กรุณาพิม # หรือ * เท่านั้น"
									];
						
					}
					
				}
				else if($step=="42222")#ยืนยันชื่อธนาคาร
				{
					$user_id="not";
					$sql1 = "SELECT * FROM userwithdrawinformation WHERE uid='".$userid."'";
					$result = $link->query($sql1);
					$check_member="1";		
					if ($result->num_rows > 0) {
					// output data of each row
						while($row = $result->fetch_assoc()) {
	
									$user_id=$row["uid"];
							}
					}	
					if($user_id=="not")#ครั้งเเรกไม่มีประวัติถอนเงินด้วย บัญชี
					{
						$sql = "INSERT INTO userwithdrawinformation(id, uid , bankaccount, nameandbranchbank, account_owner, idcarduseseven, bitcoinaccount, type)
						VALUES ('', '$userid', 'not', '$text', 'not', 'not', 'not', 'not')";
									
						if (mysqli_query($link, $sql)) {
									echo "New record created successfully";
						} 
						else {
									echo "Error: " . $sql . "<br>" . mysqli_error($link);
						}
						
								
						$messages = [
								'type' => 'text',
								'text' => "ธนาคารของคุณคือ".$text."\n\nถูกต้องกด #\nเเก้ไขกด *"
							];
					}
					else
					{
						
						$sql = "UPDATE userwithdrawinformation SET nameandbranchbank='".$text."' WHERE uid='".$userid."'";									
						if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
						
						$messages = [
								'type' => 'text',
								'text' => "ธนาคารของคุณคือ".$text."\n\nถูกต้องกด #\nเเก้ไขกด *"
							];
					}
					
					$sql = "UPDATE userstep SET step='422221' WHERE uid='".$userid."'";
																	
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
					
				}
				else if($step=="422221")#ยืนยันชื่อธนาคาร
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
								}
							}
						
						$sql1 = "SELECT * FROM userwithdrawinformation WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						$credit="0";
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$bank1=$row["bankaccount"];
										$bank2=$row["account_owner"];
										$bank3=$row["nameandbranchbank"];
								}
						}
						
						$type=$bank1."  ".$bank2."  ".$bank3;
						
						$sql = "UPDATE userstep SET step='4222222' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$sql = "UPDATE userwithdrawinformation SET type='".$type."' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "ยืนยันบัญชีเรียบร้อย กรุณากรอกชื่อเจ้าของบัญชี\n\nเลขที่บัญชีคือ ".$bank1."\nชื่อบัญชีคือ ".$bank2."\nข้อมูลธนาคาร ".$bank3."\n\nเครดิตของคุณคือ".$credit_cal."\n\nกรุณากรอกจำนวนเงินที่ต้องการถอน"
							];
						
						
					}
					else if($text=="*")
					{		
										
						$sql = "UPDATE userstep SET step='42222' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "กรุณากรอก ชื่อธนาคารเเละสาขา"
							];
						
					}
					else
					{
						$messages = [
										'type' => 'text',
										'text' => "กรุณาพิม # หรือ * เท่านั้น"
									];
						
					}
					
				}
				else if($step=="4221")#ยืนยันบัญชี
				{
					if($text=="#")
					{
						
						$sql = "UPDATE userstep SET step='4222' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "ยืนยันบัญชีเรียบร้อย กรุณากรอกชื่อเจ้าของบัญชี"
							];
						
					}
					else if($text=="*")
					{		
										
						$sql = "UPDATE userstep SET step='422' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "กรุณากรอก หมายเลขบัญชี"
							];
						
					}
					else
					{
						$messages = [
										'type' => 'text',
										'text' => "กรุณาพิม # หรือ * เท่านั้น"
									];
						
					}
					
				}
				else if($step=="412")#ถอนเงิน seven ไม่มีประวิตื
				{
					if(preg_match("/^[0-9]+$/", $text) == 1)
					{
						$user_id="not";
						$sql1 = "SELECT * FROM userwithdrawinformation WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
		
										$user_id=$row["uid"];
								}
						}	
						if($user_id=="not")#ครั้งเเรกไม่มีประวัติถอนเงินด้วย บัญชี
						{
							$sql = "INSERT INTO userwithdrawinformation(id, uid , bankaccount, nameandbranchbank, account_owner, idcarduseseven, bitcoinaccount, type)
							VALUES ('', '$userid', 'not', 'not', 'not', '$text', 'not', 'seven')";
										
							if (mysqli_query($link, $sql)) {
										echo "New record created successfully";
							} 
							else {
										echo "Error: " . $sql . "<br>" . mysqli_error($link);
							}
						}
						else
						{
							$sql = "UPDATE userwithdrawinformation SET idcarduseseven='".$text."' WHERE uid='".$userid."'";									
							if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
						}
						
						
						$sql = "UPDATE userstep SET step='411' WHERE uid='".$userid."'";
																		
								if ($link->query($sql) === TRUE) {
										echo "Record updated successfully";
								} else {
										echo "Error updating record: " . $link->error;
								}
								
								
						$messages = [
								'type' => 'text',
								'text' => "เลขบัตรประชาชนของคุณคือ".$text."\n\nถูกต้องกด #\nเเก้ไขกด *"
							];
							
							
					}
					else
					{
						$messages = [
									'type' => 'text',
									'text' => "เลขบัตรประชาชนต้องเป็นตัวเลขเท่านั้น"
								];
						
					}
					
				}
				else if($step=="411")#ถอนเงิน seven มีประวัติ
				{
					if($text=="#")
					{
						
						$sql = "UPDATE userstep SET step='4111' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "โปรกรอกจำนวนเงินที่ต้องการถอน"
							];
						
					}
					else if($text=="*")
					{		
										
						$sql = "UPDATE userstep SET step='4112' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "กรุณากรอกเลขประจำตัวบัตรประชาชน"
							];
						
					}
					else
					{
						$messages = [
										'type' => 'text',
										'text' => "กรุณาพิม # หรือ * เท่านั้น"
									];
						
					}
					
					
					
				}
				else if($step=="4112")#กดเเก้ไขบัตรประชาชน
				{
					if(preg_match("/^[0-9]+$/", $text) == 1)
					{
						$sql = "UPDATE userstep SET step='411' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$sql = "UPDATE userwithdrawinformation SET idcarduseseven='".$text."' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$sql = "UPDATE userwithdrawinformation SET type='seven' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "เลขบัตรประชาชนของคุณคือ".$text."\nยืนยันกด #\nเเก้ไขกด *"
							];
					}
					else
					{
						$messages = [
									'type' => 'text',
									'text' => "เลขบัตรประชาชนต้องเป็นตัวเลขเท่านั้น"
								];
					}
					
				}
				else if($step=="4111")#กรอก จำนวนเงินถอน
				{
					if(preg_match("/^[0-9]+$/", $text) == 1)
					{
						$sql1 = "SELECT * FROM userstep WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						$credit="0";
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$credit_cal=$row["credit"];
								}
							}
							
						if((int)$text>(int)$credit_cal)
						{
							     $messages = [
										'type' => 'text',
										'text' => "กรอกจำนวนเงินเกินเครดิต\n\nเครดิตของคุณคือ  ".$credit_cal."\n\nโปรดกรอกจำนวนเงินใหม่อีกครั้ง"
								];
							
							
						}
						else if((int)$text<=0)
						{
							     $messages = [
										'type' => 'text',
										'text' => "จำนวนเงิน ต้องมากกว่า 0 บาท\n\nโปรดกรอกจำนวนเงินใหม่อีกครั้ง"
								];
							
							
						}
						else
						{
						
							$sql = "UPDATE userstep SET step='41111' WHERE uid='".$userid."'";
																			
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							$place="aaaaa";
							sleep(0.3);
							
							$sql1 = "SELECT * FROM userwithdrawinformation WHERE uid='".$userid."'";
							$result = $link->query($sql1);
							$check_member="1";		
							$credit="0";
							if ($result->num_rows > 0) {
							// output data of each row
								while($row = $result->fetch_assoc()) {
											$place=$row["type"];
									}
								}
								
							sleep(0.2);	
							$sql = "INSERT INTO userwithdrawtracsection(id, uid , type, money, transection, sendtouser, changecredit)
							VALUES ('', '$userid', '$place', '$text', 'not', 'not', 'not')";
										
							if (mysqli_query($link, $sql)) {
										echo "New record created successfully";
							} 
							else {
										echo "Error: " . $sql . "<br>" . mysqli_error($link);
							}
							
							
							$messages = [
									'type' => 'text',
									'text' => "คุณถอนเงินจำนวน ".$text." โดยรับเงินที่ ".$place."\n\nยืนยันกด #\nยกเลิกกด *"
							];
						}
					}
					else
					{
							$messages = [
								'type' => 'text',
								'text' => "จำนวนเงินต้องเป็นตัวเลขเท่านั้น"
							];
						
						
					}
					
					
				}
				else if($step=="41111")#เช็คจำนวนเงินถอน
				{
					if($text=="#")
					{
						
						$sql = "UPDATE userstep SET step='doneregis' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$sql = "UPDATE userwithdrawtracsection SET transection='process' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$sql1 = "SELECT * FROM userwithdrawtracsection WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						$credit="0";
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$credit_withdraw=$row["money"];
								}
							}
						
						$sql1 = "SELECT * FROM userstep WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						$credit="0";
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$credit_cal=$row["credit"];
								}
							}
						$newcredit=(int)$credit_cal-(int)$credit_withdraw;
						$newcredit_str=(string)$newcredit;
						
						$sql = "UPDATE userstep SET credit='".$newcredit_str."' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						$messages = [
								'type' => 'text',
								'text' => "เครติคคุณคือ".$newcredit."\nคุณถอนเงินออกมาจำนวน\n".$credit_withdraw." บาท\n\nระบบกำลังดำเนินการ ใช้เวลา ไม่เกิน 3 ชั่วโมง\n\nกด0 กลับสููเมนูหลัก"
							];
						
					}
					else if($text=="*")
					{		
						$sql = "DELETE FROM userwithdrawtracsection WHERE uid='".$userid."' AND transection='not'";
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
								'text' => "ยกเลิกเรียบร้อย\n\nกด0 กลับสููเมนูหลัก"
							];
						
					}
					else
					{
						$messages = [
										'type' => 'text',
										'text' => "กรุณาพิม # หรือ * เท่านั้น"
									];
						
					}
					
					
					
				}
				else if($step=="2")#กด 1
				{
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
						else if($text=="1" || $text=="2")#กด0 ย้อนเมนูหลัก
						{
							$bankname="0";
							if($text=="1")
							{
								$messages = [
								'type' => 'text',
								'text' => "คุณเลือกบัญชีโอนของธนาคาร scb\n\nโปรดกรอกจำนวนเงินที่ต้องการโอน"
								];
								
								$bankname="scb";
								
							}
							if($text=="2")
							{
								$messages = [
								'type' => 'text',
								'text' => "คุณเลือกบัญชีโอนของธนาคาร kbank\n\nโปรดกรอกจำนวนเงินที่ต้องการโอน"
								];
								
								$bankname="kbank";
							}
							
							$sql = "INSERT INTO bankdeposit(id, uid , bank, depositmoney, sucessornot, messagetouser)
										VALUES ('', '$userid', '$bankname', 'not', 'not', 'not')";
													
										if (mysqli_query($link, $sql)) {
													echo "New record created successfully";
										} 
										else {
													echo "Error: " . $sql . "<br>" . mysqli_error($link);
										}
										
							// Build message to reply back
							
							
							$sql = "UPDATE userstep SET step='21' WHERE uid='".$userid."'";
																
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
								'text' => "เลือกบัญชีธนาคารที่ต้องการโอน\n\nกด1 scb\nกด2 kbank\nกด0 กลับสููเมนูหลัก"
							];
							
						}
						
				}
				else if($step=="21")#กรอกจำนวนเงินโอนธนาคาร
				{
					if(preg_match("/^[0-9]+$/", $text) == 1)
					{
							
							$sql = "UPDATE bankdeposit SET depositmoney='".$text."' WHERE uid='".$userid."' AND sucessornot='not'";
																
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}			
										
							$sql = "UPDATE userstep SET step='212' WHERE uid='".$userid."'";
																
							if ($link->query($sql) === TRUE) {
									echo "Record updated successfully";
							} else {
									echo "Error updating record: " . $link->error;
							}
							
							$messages = [
									'type' => 'text',
									'text' => "กด * เพื่อยกเลิก\nกด # เพื่อยืนยันการฝากเงิน"
								];
					}
					else
					{
						if((int)$text<=0)
						{
							     $messages = [
										'type' => 'text',
										'text' => "จำนวนเงิน ต้องมากกว่า 0 บาท\n\nโปรดกรอกจำนวนเงินใหม่อีกครั้ง"
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
					
					
				}
				else if($step=="212")#กรอกจำนวนเงินโอนธนาคาร
				{
					if($text=="#")
					{
						$sql = "UPDATE bankdeposit SET sucessornot='sucess' WHERE uid='".$userid."' AND sucessornot='not'";
																
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}	
						sleep(0.3);
						
						$sql = "UPDATE userstep SET step='doneregis' WHERE uid='".$userid."'";
																		
						if ($link->query($sql) === TRUE) {
								echo "Record updated successfully";
						} else {
								echo "Error updating record: " . $link->error;
						}
						
						
						$sql1 = "SELECT * FROM bankdeposit WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						$credit="0";
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$bank=$row["bank"];
										$money=$row["depositmoney"];
								}
						}
						$messages = [
								'type' => 'text',
								'text' => "เเจ้งการฝากเงินเรียบร้อยที่บัญชี".$bank."\nจำนวนเงิน".$money."\n\nรอการยืนยันเลขบัญชีทางข้อความได้เลย\n\nกด 1 เเทงต่อ\nกด 0 กลับสู่เมนูหลัก"
							];
						
					}
					else if($text=="*")
					{
						$sql = "DELETE FROM bankdeposit WHERE uid='".$userid."' AND sucessornot='not'";
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
								'text' => "ยกเลิก เรียบร้อย\n\nกด 1 เเทงต่อ\nกด 0 กลับสู่เมนูหลัก"
							];
						
					}
					else
					{
						$messages = [
										'type' => 'text',
										'text' => "กรุณาพิม # หรือ * เท่านั้น"
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
						else if($text=="1" || $text=="2" || $text=="3" || $text=="4")
						{
							if($text=="1")
							{
								$type_lottery="11";#เเทงสองตัวบนเเละล่าง
							}
							if($text=="2" || $text=="3" || $text=="4")
							{
								
								if($text=="2")
								{
									$type_lottery="12";#เเทงสองตัวบน
								}
								if($text=="3")
								{
									$type_lottery="13";#เเทงสองตัวล่าง
								}
								if($text=="4")
								{
									$type_lottery="14";#เเทงสามตัว
								}
								$text="1";
							}
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
							if($credit!="0")
							{
								if($text=="1")
								{
									if($type_lottery=="11")
									{
									// Build message to reply back
										$messages = [
											'type' => 'text',
											'text' => "เเทงหวย 2 ตัวบนเเละล่าง\n\nกรุณากรอกหมายเลข 2 ตัว"
										];
									}
									else if($type_lottery=="12")
									{
										$messages = [
											'type' => 'text',
											'text' => "เเทงหวย 2 ตัวบน\n\nกรุณากรอกหมายเลข 2 ตัว"
										];
										
									}
									else if($type_lottery=="13")
									{
										$messages = [
											'type' => 'text',
											'text' => "เเทงหวย 2 ตัวล่าง\n\nกรุณากรอกหมายเลข 2 ตัว"
										];
									}
									else
									{
										$messages = [
											'type' => 'text',
											'text' => "เเทงหวย 3 ตัว\n\nกรุณากรอกหมายเลข 2 ตัว"
										];
										
									}
									
									$sql = "UPDATE userstep SET step='11' WHERE uid='".$userid."'";
																		
									if ($link->query($sql) === TRUE) {
											echo "Record updated successfully";
									} else {
											echo "Error updating record: " . $link->error;
									}
									
									$sql = "UPDATE userstep SET lotterytype='".$type_lottery."' WHERE uid='".$userid."'";
																		
									if ($link->query($sql) === TRUE) {
											echo "Record updated successfully";
									} else {
											echo "Error updating record: " . $link->error;
									}
									
								}
							}
							
						}
						else
						{
							$messages = [
										'type' => 'text',
										'text' => "ใช้งานได้เฉพาะกด 0 - 4 ท่านั้น"
									];
							
						}
						
						
					
				}
				else if($step=="11")#กรอกเลขเเทง
				{
					$sql1 = "SELECT * FROM userstep WHERE uid='".$userid."'";
					$result = $link->query($sql1);
					$check_member="1";		
					$credit="0";
					if ($result->num_rows > 0) {
					// output data of each row
						while($row = $result->fetch_assoc()) {
									$type_lottery=$row["lotterytype"];
									$credit=$row["credit"];
							}
					}
					if($type_lottery=="11")
					{
						$type_change="เเทงหวย 2 ตัวบนเเละล่าง";
					
					}
					else if($type_lottery=="12")
					{
						$type_change="เเทงหวย 2 ตัวบน";
						
					}
					else if($type_lottery=="13")
					{
						$type_change="เเทงหวย 2 ตัวล่าง";
					}
					else
					{
						$type_change="เเทงหวย 3 ตัว";
						
					}
					$count_text = strlen($text);
					if($type_lottery=="14")
					{
						$count_check_lottery_len=3;
					}
					else
					{
						$count_check_lottery_len=2;
					}
					if($count_text==$count_check_lottery_len)
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
							$sql = "INSERT INTO lottery(id, uid, lottery, price, type, buy_book)
										VALUES ('', '$userid', '$text', '$price', '$type_lottery', 'book')";
													
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
							if($type_lottery=="14")
							{
								$messages = [
								'type' => 'text',
								'text' => "หมายเลขต้องเป็น 3 หลักเท่านั้น"
								];
							}
							else
							{
								$messages = [
								'type' => 'text',
								'text' => "หมายเลขอยู่ในเลขระหว่าง 0-99 เท่านั้น"
								];
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
					
				}
				else if($step=="111")#กรอกจำนวนเงินเข้ามา
				{
					
					if(preg_match("/^[0-9]+$/", $text) == 1)
					{
						$sql1 = "SELECT * FROM userstep WHERE uid='".$userid."'";
						$result = $link->query($sql1);
						$check_member="1";		
						$credit="0";
						if ($result->num_rows > 0) {
						// output data of each row
							while($row = $result->fetch_assoc()) {
										$credit_cal=$row["credit"];
								}
							}
							
						if((int)$text>(int)$credit_cal)
						{
							     $messages = [
										'type' => 'text',
										'text' => "กรอกจำนวนเงินเกินเครดิต\n\nเครดิตของคุณคือ  ".$credit_cal."\n\nโปรดกรอกจำนวนเงินใหม่อีกครั้ง"
								];
							
							
						}
						else if((int)$text<=0)
						{
							     $messages = [
										'type' => 'text',
										'text' => "จำนวนเงิน ต้องมากกว่า 0 บาท\n\nโปรดกรอกจำนวนเงินใหม่อีกครั้ง"
								];
							
							
						}
						else
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
								'text' => "ยืนยันการซื้อ เรียบร้อย\nคุณมีเครดิตเหลือ ".$newcredit_str."\n\nกด 1 เเทงต่อ\nกด 0 กลับสู่เมนูหลัก"
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
								'text' => "ยกเลิก เรียบร้อย\nคุณมีเครดิตเหลือ ".$credit."\n\nกด 1 เเทงต่อ\nกด 0 กลับสู่เมนูหลัก"
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
								'text' => "=== เมนูหลัก ===\n\nกด1 เเทงหวย\nกด2 จำนวนเงินที่ต้องการฝาก\nกด3 เเจ้งการโอนเงิน\nกด4 แจ้งถอนเงิน\nกด5 ตรวจสอบยอดเงิน"
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
										
							$text33= "ยืนยันการเป็นสมาชิก \nเบอร์โทรศัพท์ของคุณคือ ".$telephone."\nรหัส : ".$password."\n เริ่มใช้งานได้เลย พิม 0 เพื่อดูคำสั่งใช้งาน";
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
									
								$sql = "UPDATE userstep SET step='regis0' WHERE uid='".$userid."'";
																
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