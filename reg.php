<?php
$subject = '<div style="test">onmousedown="alert(\"hello\");" onclick="test"</div>';
$subject = '<a href="javascript:void(0);">test</a>';

$pattern = '/onmousedown=(?\'open\'"|\').*(?\'-open\'[^\\]\1)(?(open)(?!))/';
$pattern = '/(onabort|onblur|onchange|onclick|ondblclick|onerror|onfocus|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onresize|onselect|onsubmit|onunload)=(?P<quote>"|\').*[^\\\]\k<quote>/isU';
$pattern = '/("|\')\s*(?<js>javascript\s*:.*)[^\\\]\1/';

echo $pattern . "\n";

$result = preg_match_all($pattern, $subject, $match);
if ($result) {
	print_r($match);
	echo preg_replace($pattern, '$1#$1', $subject);
} else {
	echo 'no match';
}