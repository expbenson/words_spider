<?php
$content = file_get_contents('a');
preg_match('/charset=(\w+)/i', $content, $match);
$charset = $match[1];
//var_dump($charset);
//exit;
$result = mb_convert_encoding($content, 'UTF-8', $charset);
//iconv("ISO-8859-1","UTF-8",$content);
var_dump(htmlspecialchars($result));
//file_put_contents('b', $content);