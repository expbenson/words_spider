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
$result = $dbh->query('SELECT * FROM popular_words ORDER BY word_id DESC');
$rows = $result->fetchAll();
$words = array();
$str = 'INSERT INTO `pop_words`(`word_chinese`,`word_pinyin`,`word_version`,`baidu_f`,`google_f`,`mul_f`) VALUES';
$str .= PHP_EOL;

foreach($rows as $row) {
	$property = json_decode($row['property'], TRUE);
	if (isset($property['can_output']) && $property['can_output'] == 0) {
		$words[] = $row;
		$str .= "('" . addslashes($row['word']) . "','" . addslashes($row['pinyin']) . "'," . date('ymd') . ',0,0,0),';
		$str .= PHP_EOL;
	}
}
$str = substr($str, 0, -2) . ';';
echo $str;