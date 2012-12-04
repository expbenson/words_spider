<?php
sae_xhprof_start();
$time = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (isset($_GET['timestamp'])) {
		$time = date('Y-m-d H:i:s', $_GET['timestamp']);
	}
}
sae_xhprof_end();
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>时间戳</title>
</head>

<body>
	<div>
		<table>
			<tr><td colspan="2">东八区</td></tr>
			<tr>
				<td>当前时间：</td>
				<td><?php echo date('Y-m-d H:i:s'); ?></td>
			</tr>
			<tr>
				<td>标准时间戳：</td>
				<td><?php echo time(); ?></td>
			</tr>
			<tr>
				<td>时间：</td>
				<td><?php echo $time; ?></td>
			</tr>
		</table>
	</div>
	<form action="" method="get">
		<input type="text" name="timestamp" placeholder="输入时间戳" autofocus>
		<br />
		<input type="submit" value="Submit" />
	</form>
</body>
</html>
