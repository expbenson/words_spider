<?php
exit;
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
// 新建数据表part_of_speech
$dbh->query('CREATE TABLE IF NOT EXISTS part_of_speech(
							pos_id INT UNSIGNED NOT NULL PRIMARY KEY COMMENT \'词性id\',
							pos_code VARCHAR(255) NOT NULL UNIQUE COMMENT \'词性代号\',
							pos_name VARCHAR(255) NOT NULL UNIQUE COMMENT \'词性名称\')
							ENGINE=InnoDB
							DEFAULT CHARSET=utf8
							COLLATE=utf8_general_ci
							COMMENT=\'词性记录表\'');
// 新建数据表popular_words
$dbh->query('CREATE TABLE IF NOT EXISTS popular_words(
							word_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT \'词id\',
							word CHAR(24) NOT NULL UNIQUE COMMENT \'中文词\',
							part_of_speech INT UNSIGNED NOT NULL COMMENT \'词性\',
							frequency INT NOT NULL DEFAULT 0 COMMENT \'词频\',
							create_time INT NOT NULL COMMENT \'创建日期\',
							pinyin CHAR(41) DEFAULT NULL COMMENT \'拼音\',
							property TEXT DEFAULT NULL COMMENT \'其他属性\',
							CONSTRAINT fk_part_of_speech FOREIGN KEY (part_of_speech) REFERENCES part_of_speech(pos_id) ON DELETE CASCADE ON UPDATE CASCADE)
							ENGINE = InnoDB
							DEFAULT CHARSET=utf8
							COLLATE=utf8_general_ci
							COMMENT=\'流行词记录表\'');
$dbh->query('CREATE TABLE IF NOT EXISTS curl_words_log(
						 sentence TEXT NOT NULL COMMENT \'句子\',
						 create_time INT NOT NULL DEFAULT 0 COMMENT \'抓取时间\',
						 link VARCHAR(255) DEFAULT NULL COMMENT \'来源link\')
						 ENGINE=MyISAM
						 DEFAULT CHARSET=utf8
						 COLLATE=utf8_general_ci
						 COMMENT=\'爬取流行词日志\'');
echo 'done';
?>