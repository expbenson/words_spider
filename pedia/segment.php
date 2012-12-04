<?php
$str = (string)$_GET['word'];
if ($str) {
	$seg = new SaeSegment();
        $ret = $seg->segment($str, 1);
  if ($ret !== false) {
        $result = '';
        $filter = array('230', '154', '155');
    foreach ($ret as $r) {
      if (!in_array($r['word_tag'], $filter)) {
    	//print_r($r);
        //echo '<br><br>';
    	$result .= $r['word'] . ' ';
      }
    }
    echo $result;
  } else {
    //var_dump($seg->errno(), $seg->errmsg());
  }
}
?>