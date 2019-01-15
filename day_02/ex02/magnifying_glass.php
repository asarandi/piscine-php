#!/usr/bin/php
<?php
function magnify($arr) {
//	var_dump($arr);
	$i = 1;
	$str = $arr[0];
	while (isset($arr[$i])) {
		if (strlen($arr[$i]) > 0) {
			$str = preg_replace('/'.$arr[$i].'/', strtoupper($arr[$i]), $str);
		}
		$i++;
	}
	return ($str);
}
if ($argc > 1){
	$str = file_get_contents($argv[1]);
	$str = preg_replace_callback('/\s+title="(.+?)".*?>/s', 'magnify', $str);
	$str = preg_replace_callback('/<a.*?>(.+?)</s', 'magnify', $str);
	$str = preg_replace_callback('/<div.*?>(.+?)</s', 'magnify', $str);
	$str = preg_replace_callback('/<span.*?>(.+?)</s', 'magnify', $str);


/*	$str = preg_replace_callback('/<a.+?>(.+)</s', 'magnify', $str);
 */
	echo $str;
}
?>
