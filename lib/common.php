<?php
function strip_script($content) {
	$content = preg_replace('/<script.*>.*<\/script\s*>/isU', '', $content);
	$scriptArr = array('onclick', 'onblur', 'onchange', 'onmouseover', 'onmouseout', 'onmousedown');
	$pattern = '/on(abort|blur|change|click|dblclick|error|focus|keydown|keypress|keyup|load|mousedown|mousemove|mouseout|mouseover|mouseup|reset|resize|select|submit|unload)=(?P<quote>"|\').*[^\\\]\k<quote>/isU';
	$content = preg_replace($pattern, '', $content);
	return preg_replace('/("|\')\s*(?<js>javascript\s*:.*)[^\\\]\1/', '$1#$1', $content);
}

function get_style(&$content) {
	$pattern = '/<style.*>.*<\/style\s*>/isU';
	$result = preg_match_all($pattern, $content, $match);
	$content = preg_replace($pattern, '', $content);
//	print_r($match);
	return $match;
}

function strip_head($content) {
	return preg_replace('/<head.*>.*<\/head\s*>/isU', '', $content);
}

function get_body($content) {
	$pattern = '/<body.*>(.*)(<\/body\s*>|$)/is';
	$pattern = '/<body.*?>(.*)/is';
	preg_match($pattern, $content, $match);
	return $match[1];
}