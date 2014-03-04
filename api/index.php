<?php

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