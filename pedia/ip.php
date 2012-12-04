<?php 
function getRealIpAddr() {
	$ip = '';
	if (! empty ( $_SERVER ['HTTP_CLIENT_IP'] )) {
		//check ip from share internet
		$ip = $_SERVER ['HTTP_CLIENT_IP'];
	} elseif (! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
		//to check ip is pass from proxy
		$ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER ['REMOTE_ADDR'];
	}
	return $ip;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>User Agent</title>
</head>

<body>
	<div style="width: 100%; font-size: 24px;"><?php echo getRealIpAddr(); ?></div>
</body>
</html>