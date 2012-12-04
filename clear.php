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
$stmt = $dbh->prepare('UPDATE popular_words SET property=:property WHERE word_id=:word_id');
foreach($rows as $row) {
	$property = json_decode($row['property'], TRUE);
	if (isset($property['can_output']) && $property['can_output'] == 0) {
		$property['can_output'] = 1;
		$propertyStr = json_encode($property);
		$stmt->bindValue(':word_id', $row['word_id'], PDO::PARAM_INT);
		$stmt->bindValue(':property', $propertyStr, PDO::PARAM_STR);
		$stmt->execute();
	}
}
echo 'done.<br /><a href="see_words.php">回到查看页面</a>';
?>