<?php
include '../lib/common.php';
if (isset($_GET['url'])) {
	$url = trim($_GET['url']);
	$protocal = 'http';
	if (!preg_match('/^(https{0,1}):\/\//', strtolower($url), $match)) {
		$url = 'http://' . $url;
		$protocal = $match[1];
	}
	$ch = curl_init($url);
	$options = array (CURLOPT_HEADER => false, CURLOPT_RETURNTRANSFER => 1, CURLOPT_AUTOREFERER=>TRUE,
										CURLOPT_FOLLOWLOCATION=>TRUE, CURLOPT_MAXREDIRS=>3);
	if ($protocal === 'https') {
		$options[CURLOPT_SSL_VERIFYPEER] = FALSE;
		$options[CURLOPT_SSL_VERIFYHOST] = FALSE;
	}
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);
	curl_close($ch);
	
//	echo htmlspecialchars($content) . '<br />';
	preg_match('/charset=(\w+)/i', $content, $match);
//	var_dump($match);
//	exit;
	if (isset($match[1])) {
		$charset = $match[1];
		var_dump($charset);
		echo '<br />';
		$content = mb_convert_encoding($content, 'UTF-8', $charset); // 编码转换
		echo '去除脚本<br />';
		echo htmlspecialchars($content);exit;
		$content = strip_script($content);
		echo '获取样式<br />';
		$styleArr = get_style($content);
//		file_put_contents('b', $content);
//		exit;
//		print_r($styleArr);
//		exit;
		echo '获取body<br />';
		$content = get_body($content);
		exit;
		foreach ($styleArr as $style) {
			foreach ($style as $s) {
				file_put_contents('css', $s);
				echo $s;
//				exit;
//				$content = $s . $content;
				$content = preg_replace('/(.*>\s*)(<\/head\s*>.*)/isU', '${1}' . $s . '${2}', $content);
				break;
			}
			break;
		}
//		echo $content;exit;
		$content = preg_replace('/[\n\r]/', '', $content);
		$content = preg_replace('/(\s)\s+/', '$1', $content);
		echo '写入文件<br />';
		file_put_contents('a.html', $content);
		echo 'done';
//		echo $content;
	}
	exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>保存整个网页</title>
</head>

<body>
	<form action="" method="get">
		<table>
			<tr>
				<td>输入你想保存的网页链接：</td>
				<td><input type="text" name="url" style="width: 300px;" /></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Save" style="width: 100%;" /></td>
			</tr>
		</table>
	</form>
</body>
</html>