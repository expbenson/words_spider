<?php
require '../lib/common.php';
$content = file_get_contents('Google.htm');
preg_match('/charset\s*=\s*(.*?)"/i', $content, $match);
if (isset($match[1]) && $match[1] != 'UTF-8') $content = @mb_convert_encoding($content, 'UTF-8', $match[1]);
$content = strip_script($content);
get_style($content);
$content = get_body($content);
file_put_contents('g.new', $content);