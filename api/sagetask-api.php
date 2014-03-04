<?php

$ErrorMsg = '';

if (! isset($PostData)) {
	$PostData = file_get_contents("php://input");
}

if ($PostData == '') {
	$ErrorMsg = 'PostData was blank';
} else {
	$json = json_decode($PostData, 1);
	if ($json === false) {
		$ErrorMsg = 'could not decode json';
	}
}

function MailSetup() {
   include_once $_SERVER['DOCUMENT_ROOT'] . "/class.phpmailer5.php";
   include_once $_SERVER['DOCUMENT_ROOT'] . "/class.smtp.php";

   $mail = new PHPMailer();  // create a new object
   $mail->IsSMTP(); // enable SMTP
   $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
   $mail->SMTPAuth = true;  // authentication enabled
   $mail->SMTPSecure = 'ssl';
   $mail->Host = 'mail.sagetask.com';
   $mail->Port = 465; 
   $mail->Username = 'smtp@sagetask.com';  
   $mail->Password = 'sagetasksmtp';      
   
   return $mail;
}  

function Login(&$db, &$json, &$ErrorMsg) {
	if ($json['Email'] == '') {
		$ErrorMsg = 'Email was blank';
		return false;
	} else if ($json['Password'] == '') {
		$ErrorMsg = 'Password was blank';
		return false;
	} else {
		$UserInfo = $db->SelectRow('Users', array('Email' => $json['Email'],
			'Password' => $json['Password']));
		if ($UserInfo === false) {
			$ErrorMsg = 'Invalid Login';
			return false;
		} else {
			return $UserInfo;
		}
	}
}

function Register($Email, $Password) {
	$mail = MailSetup(); // include_functions.php

	$mail->AddAddress($Email);
	$mail->From = "aaronjay@sagetask.com";
	$mail->FromName = "Sage Task";
	$mail->Subject = "Sage Task Registration";
	
	$mail->Body = "This is the Sage Task Registration.<br />
<br />
Email: $Email<br />
Password: <b>$Password</b><br />
<br />
Thank you,<br />
<br />
Sage Task";

	$mail->IsHTML(1);

	if (!$mail->Send()) {
		return "There has been a mail error: " . $mail->ErrorInfo;
	} else {
		return "";
	}
}

function getTableData(&$db, $UserID, $SortBy) {
	$sort = 'TaskID';
	if ($SortBy == 'Title') {
		$sort = 'Title';
	} else if ($SortBy == 'DueDate') {
		$sort = 'DueDate';
	} else if ($SortBy == 'Completed') {
		$sort = 'Status';
	}
	$results = $db->SelectRows('Tasks', array('UserID' => $UserID), 0, $sort);
	$Data = array();
	while ($info = mysql_fetch_assoc($results)) {
		unset($info['UserID']);
		if ($info['DueDate'] == '0000-00-00' || $info['DueDate'] == '1970-01-01') {
			$info['DueDate'] = '';
		}
		$Data[] = $info;
	}
	return $Data;
}



if ($ErrorMsg == '') {

	include "../config.php";
	$Data = '';
	if ($json['Action'] == 'Register') {
		if ($json['Email'] == '') {
			$ErrorMsg = 'Email was blank';
		} else {
			$UserInfo = $db->SelectRow('Users', array('Email' => $json['Email']));
			if ($UserInfo !== false) {
				$ErrorMsg = 'Email is already registered';
			} else {
				$Password = mt_rand(1000, 9999);
				$UserID = $db->InsertRow('Users', array('Email' => $json['Email'],
					'Name' => $json['Name'],
					'Password' => $Password));
				$ErrorMsg = Register($json['Email'], $Password);
				if ($ErrorMsg == '') {
					$Data = 'Email Sent';
				}
			}
		}
	} else if ($json['Action'] == 'SignIn') {
		$UserInfo = Login($db, $json, $ErrorMsg);
		if ($ErrorMsg == '') {
			$Data = array('Message' => 'Success', 'Name' => $UserInfo['Name']);
		}
	} else if ($json['Action'] == 'GetTaskList') {
		$UserInfo = Login($db, $json, $ErrorMsg);
		if ($ErrorMsg == '') {
			$Data =	getTableData($db, $UserInfo['UserID'], $UserInfo['SortBy']);
		}
	} else if ($json['Action'] == 'Sort') {
		$UserInfo = Login($db, $json, $ErrorMsg);
		if ($ErrorMsg == '') {
			$db->UpdateRows('Users', array('SortBy' => $json['SortBy']), array('UserID' => $UserInfo['UserID'])); // Save value in the database
			$Data =	getTableData($db, $UserInfo['UserID'], $json['SortBy']);
		}
	} else {
		$ErrorMsg = 'Unknown Action: ' . $json['Action'];
	}
}

$a = array();
$a['ErrorMsg'] = $ErrorMsg;
if (isset($Data)) {
	$a['Data'] = $Data;
}

echo json_encode($a);


?>