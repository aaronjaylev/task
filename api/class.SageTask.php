<?php

/**
* SageTask.com
*
* SageTask.com is a Website, API, and SDK for Creating and Managing simple tasks.
*
* This code is visible on http://www.sagetask.com.  It is a "one page" website
* and uses JQuery, JQueryUI, Twitter Bootstrap to format the pages.  Connection
* to the MySQL database is done through an API which can also be called directly.
* I hope you enjoy viewing, using and learning from this code.  You may use it for 
* your own projects if you give me credit by leaving this license notification 
* in your files.  Happy Coding.  Aaron Jay
*
* @package SageTask
* @author Aaron Jay Lev <aaronjaylev@gmail.com>
* @copyright Copyright (c) 2014, Aaron Jay Lev
* @link http://www.sagetask.com
* @example http://www.sagetask.com/
* @license http://www.apache.org/licenses/LICENSE-2.0
*
* Copyright 2014 Aaron Jay Lev
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

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