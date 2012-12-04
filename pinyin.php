<?php
include 'config.php';
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

$result = $dbh->query("SELECT * FROM popular_words WHERE `pinyin` IS NULL OR `pinyin`='' ORDER BY word_id DESC");
$pinyinRows = $result->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$stmt = $dbh->prepare('UPDATE popular_words SET pinyin=:pinyin,property=:property WHERE word_id=:word_id');
	foreach ($pinyinRows as $row) {
		if (isset($_POST['pinyin_' . $row['word_id']])) {
			$property = json_decode($row['property'], TRUE);
			$property['can_output'] = $_POST['shurufa_' . $row['word_id']];
			$property = json_encode($property);
			$stmt->bindValue(':pinyin', $_POST['pinyin_' . $row['word_id']], PDO::PARAM_STR);
			$stmt->bindValue(':property', $property, PDO::PARAM_STR);
			$stmt->bindValue(':word_id', $row['word_id'], PDO::PARAM_INT);
			$stmt->execute();
		}
	}
	echo 'done.<br />';
	echo '<a href="see_words.php">跳转查看结果</a>';
	exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>输入拼音</title>
</head>

<body>
	<form action="" method="post">
		<table>
			<tr>
				<th>单词</th>
				<th>拼音</th>
				<th>是否能打出</th>
			</tr>
			<?php foreach($pinyinRows as $row): ?>
			<tr>
				<td><?php echo $row['word']; ?></td>
				<td><input type="text" name="pinyin_<?php echo $row['word_id']; ?>"></td>
				<td>
					<label><input type="radio" name="shurufa_<?php echo $row['word_id']; ?>" value="1">能</label><br />
					<label><input type="radio" name="shurufa_<?php echo $row['word_id']; ?>" value="0">否</label>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		<div class="submitPinyin"><input type="submit" value="提交" /></div>
	</form>
</body>
</html>
