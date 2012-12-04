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
$result = $dbh->query('SELECT word,property FROM popular_words ORDER BY word_id DESC');
$rows = $result->fetchAll();
$words = array();
$str = 'SELECT word_chinese FROM pop_words WHERE word_chinese IN (';

foreach($rows as $row) {
	$property = json_decode($row['property'], TRUE);
	if (isset($property['can_output']) && $property['can_output'] == 0) {
		$words[] = '\'' . addslashes($row['word']) . '\'';
	}
}
$str .= implode(',', $words) . ')';

echo $str;
?>
<div><a href="export.php">导出</a></div>