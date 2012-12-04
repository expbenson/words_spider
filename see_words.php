<?php
include 'config.php';
header("Content-type: text/html; charset=utf-8");
if($_SERVER['PHP_AUTH_USER']!='benson' || $_SERVER['PHP_AUTH_PW']!='damacheng'){
    header('WWW-Authenticate: Basic realm="My Protection"');
    header('HTTP/1.0 401 Unauthorized');
    exit('Plaese input password.');
}
try {
	$dbh = new PDO('mysql:host=' . SAE_MYSQL_HOST_M . ';dbname=' . SAE_MYSQL_DB . ';port=' . SAE_MYSQL_PORT . ';', 
								 SAE_MYSQL_USER, 
								 SAE_MYSQL_PASS, 
								 array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
} catch (PDOException $e) {
	echo 'Connect failed: ' . $e->getMessage();
	exit;
}
$result = $dbh->query('SELECT * FROM popular_words ORDER BY word_id DESC LIMIT 100');
$rows = $result->fetchAll();
if (isset($_GET['filter']) && $_GET['filter'] == 'un') {
	$tmp = array();
	foreach($rows as $row) {
		$property = json_decode($row['property'], TRUE);
		if (isset($property['can_output']) && $property['can_output'] == 0) {
			$tmp[] = $row;
		}
	}
	$rows = $tmp;
}
?>
<body>
	<div><a href="see_words.php">显示全部</a></div>
	<div><a href="?filter=un">只显示不能打出的单词</a></div>
	<table>
	<tr>
		<th>单词</th>
		<th>描述</th>
		<th>拼音</th>
		<th>能否输出</th>
	</tr>
	<?php foreach($rows as $row):
		$propertyArr = json_decode($row['property'], true);
		$desc = isset($propertyArr['desc'])? $propertyArr['desc'] : '';
		$canOutput = isset($propertyArr['can_output'])? $propertyArr['can_output'] : -1;
		switch ($canOutput) {
			case 1:
				$canOutputStr = '能';
				break;
			case 0:
				$canOutputStr = '不能打出';
				break;
			case -1:
				$canOutputStr = '未知';
				break;
		}
	?>
	<tr>
		<td><?php echo $row['word']; ?></td>
		<td><span style="display: inline-block; margin-left: 100px;"><?php echo $desc; ?></span></td>
		<td><span style="display: inline-block; margin-left: 100px;"><?php echo $row['pinyin']; ?></span></td>
		<td><span style="display: inline-block; margin-left: 100px;<?php echo ($canOutput == 0)? ' color:red;':''; ?>"><?php echo $canOutputStr; ?></span></td>
	</tr>
	<?php endforeach; ?>
	</table>
	<div>共<span style="color:red; font-size:26px;"><?php echo count($rows); ?></span>个词</div>
	<div><a href="clear.php" onClick="if (!confirm('确定提交?')) return false;">全部能打出</a></div>
</body>