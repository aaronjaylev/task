<?php

class SageTask {
	private $SageTaskDomain = 'www.sagetask.com';
	private $SageTaskPath = '/api/sagetask-api.php';
	
	private function PostSageTask($json) {
		if ($json == '') {
			die('json was blank');
		}
		$fp = fsockopen($this->SageTaskDomain, 80, $errno, $errstr, 30);
		if (!$fp) {
			die("Error opening connection to " . $this->SageTaskDomain . ": $errstr ($errno)");
		} else {
			$out = "POST /" . $this->SageTaskPath . " HTTP/1.0\r\n";
			$out .= "Host: " . $this->SageTaskDomain . "\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "Content-Length: " . strlen($json) . "\r\n";
			$out .= "Connection: Close\r\n\r\n";
			$out .= $json;

			fwrite($fp, $out);
			$st = '';
			while (!feof($fp)) {
				$st .= fgets($fp, 128);
			}
			fclose($fp);
			$a = explode("\n\r", $st, 2);

			return($a[1]);
		}
	}
	
	public function Register($Email, $Name) {
		$a = array();
		$a['Action'] = 'Register';
		$a['Email'] = $Email;
		$a['Name'] = $Name;
		$out = $this->PostSageTask(json_encode($a));
		return json_decode($out, 1);
	}
	
	public function GetTaskList($Email, $Password) {
		$a = array();
		$a['Action'] = 'GetTaskList';
		$a['Email'] = $Email;
		$a['Password'] = $Password;
		$out = $this->PostSageTask(json_encode($a));
		return $out;
		// return json_decode($out, 1);
	}
}

?>