<?php
include __DIR__ . '/../config.php';

$url = 'http://pinyin.sogou.com/dict/search.php?word=%CD%F8%C2%E7%C1%F7%D0%D0%D0%C2%B4%CA';
$content = file_get_contents($url);
if (!$content) {
	exit;
}
preg_match_all('/<a href="cell\.php\?id=4">[\s\S]+?<span>(.+)?<\/span>/', $content, $matches);
if (!$matches) exit('No matches.');
$words = $matches[1][0];
$words_utf = iconv('gb2312', 'UTF-8', $words);
$word_arr = preg_split("/[\x{3000}\x{3001}\.]+/u", $words_utf);

try {
	$dbh = new PDO('mysql:host=' . SAE_MYSQL_HOST_M . ';dbname=' . SAE_MYSQL_DB . ';port=' . SAE_MYSQL_PORT . ';', 
								 SAE_MYSQL_USER, 
								 SAE_MYSQL_PASS, 
								 array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
} catch (PDOException $e) {
	echo 'Connect failed: ' . $e->getMessage();
	exit;
}

$stmt = $dbh->prepare('INSERT INTO curl_words_log(sentence,create_time,link) VALUES(:sentence,:create_time,:link)');
$stmt->bindValue(':sentence', $words_utf);
$stmt->bindValue(':create_time', time(), PDO::PARAM_INT);
$stmt->bindValue(':link', $url);
$stmt->execute();

// 写入流行词表
$stmt2 = $dbh->prepare('INSERT INTO popular_words(word,part_of_speech,frequency,create_time,property) 
												VALUES(:word,:part_of_speech,:frequency,:create_time,:property)');
$arr_len = count($word_arr);
$now = time();
for ($i = 0; $i < $arr_len; $i+=2) {
	if (mb_strlen($word_arr[$i], 'UTF-8') < 2) {
		continue;
	}
	if ($word_arr[$i]) {
		$stmt2->bindValue(':word', $word_arr[$i]);
		$stmt2->bindValue(':part_of_speech', 0, PDO::PARAM_INT);
		$stmt2->bindValue(':frequency', 0, PDO::PARAM_INT);
		$stmt2->bindValue(':create_time', $now, PDO::PARAM_INT);
		$stmt2->bindValue(':property', json_encode(array('desc'=>$word_arr[$i+1])));
		$stmt2->execute();
	}
}
echo 'done';
?>
