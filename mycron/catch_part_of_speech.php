<?php
include __DIR__ . '/../config.php';

$content = file_get_contents('http://apidoc.sinaapp.com/sae/SaeSegment.html');
if (!$content) {
	exit;
}

preg_match_all('/<span class="const-name">POSTAG_ID_(\w+)?<\/span>[\S\s]+?>\s(\d+)<[\s\S]*?<p class="short-description">(.+)?<\/p>/', 
							 $content, $matches, PREG_SET_ORDER);
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

$stmt = $dbh->prepare('INSERT INTO part_of_speech(pos_id,pos_code,pos_name) VALUES(:pos_id,:pos_code,:pos_name)');
foreach ($matches as $row) {
	$stmt->bindValue(':pos_id', $row[2], PDO::PARAM_INT);
	$stmt->bindValue(':pos_code', $row[1]);
	$stmt->bindValue(':pos_name', htmlspecialchars_decode($row[3]));
	$stmt->execute();
}
echo 'done';
?>
