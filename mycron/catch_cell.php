<?php
include __DIR__ . '/../config.php';

$url = 'http://pinyin.sogou.com/dict/cell.php?id=4';
$content = file_get_contents($url);
if (!$content) {
	exit;
}
// echo $content;
// $content_utf = iconv('gb2312', 'UTF-8', $content);
$content_utf = mb_convert_encoding($content, 'UTF-8', 'gb2312');
preg_match_all("/<dd>(.*)?\x{3000}(.*)?</u", $content_utf, $matches, PREG_SET_ORDER);
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

// 写入流行词表
$stmt = $dbh->prepare('INSERT INTO popular_words(word,part_of_speech,frequency,create_time,property) 
												VALUES(:word,:part_of_speech,:frequency,:create_time,:property)');
$stmt2 = $dbh->prepare('INSERT INTO curl_words_log(sentence,create_time,link) VALUES(:sentence,:create_time,:link)');

$now = time();
foreach ($matches as $row) {
	if (mb_strlen($row[1], 'UTF-8') < 2) {
		continue;
	}
	$stmt->bindValue(':word', $row[1]);
	$stmt->bindValue(':part_of_speech', 0, PDO::PARAM_INT);
	$stmt->bindValue(':frequency', 0, PDO::PARAM_INT);
	$stmt->bindValue(':create_time', $now, PDO::PARAM_INT);
	$stmt->bindValue(':property', json_encode(array('desc'=>$row[2])));
	$stmt->execute();
	
	$stmt2->bindValue(':sentence', $row[0]);
	$stmt2->bindValue(':create_time', $now, PDO::PARAM_INT);
	$stmt2->bindValue(':link', $url);
	$stmt2->execute();
}
echo 'done';
?>
