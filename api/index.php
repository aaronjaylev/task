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


$postdata = file_get_contents("php://input");

if ($postdata == '') {
	echo 'This is the api page';
	exit;
}

$json = json_decode($postdata, 1);

echo " json = " . print_r($json, 1);

private function PostSageTask($domain, $json) {
	if ($json == '') {
		die('json was blank');
	}
	$fp = fsockopen($domain, 80, $errno, $errstr, 30);
	if (!$fp) {
	   die("Error opening connection to www.sagetask.com: $errstr ($errno)");
   } else {
		$out = "POST /" . $page . " HTTP/1.0\r\n";
		$out .= "Host: www.evopages.com\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: " . strlen($data) . "\r\n";
		$out .= "Connection: Close\r\n\r\n";
		$out .= $data;
		
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

?>