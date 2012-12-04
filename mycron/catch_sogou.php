<?php
include __DIR__ . '/../config.php';

$content = file_get_contents('http://pinyin.sogou.com/dict/');
if (!$content) {
	exit;
}
//$headers = $f->responseHeaders();
//print_r($headers);
//exit;
preg_match_all('/id="w_id_\d+.+?(\d+).+>(.+)<.+/', $content, $matches, PREG_SET_ORDER);
if (!$matches) exit('No matches.');

try {
	$dbh = new PDO('mysql:host=' . SAE_MYSQL_HOST_M . ';dbname=' . SAE_MYSQL_DB . ';port=' . SAE_MYSQL_PORT . ';', 
								 SAE_MYSQL_USER, 
								 SAE_MYSQL_PASS, 
								 array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
} catch (PDOException $e) {
	echo 'Connect failed: ' . $e->getMessage();
	exit;
}

// 将结果保存到数据库
$now = time();
$stmt = $dbh->prepare('INSERT INTO popular_words(word,part_of_speech,frequency,create_time,property) 
											 VALUES(:word,:part_of_speech,:frequency,:create_time,:property)');
foreach ($matches as $row) {
	$word = iconv('gb2312', 'UTF-8', $row[2]);
	if (mb_strlen($word, 'UTF-8') < 2) {
		continue;
	}
	$stmt->bindValue(':word', $word);
	$stmt->bindValue(':part_of_speech', 0);
	$stmt->bindValue(':frequency', 0);
	$stmt->bindValue(':create_time', $now);
	$stmt->bindValue(':property', json_encode(array('exponent'=>$row[1])));
	$stmt->execute();
}
echo 'done';
?>
