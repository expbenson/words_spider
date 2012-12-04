<?php
include 'simple_html_dom.php';

$html = file_get_html('http://jandan.net/ooxx');
$li = $html->find('ol li');
foreach ($li as $l) {
  if ($l->hasAttribute('id')) {
	$img = $l->find('img', 1);
        $result[] = $img->getAttribute('src');
  }
}
header('Content-type: application/json');
echo json_encode($result);