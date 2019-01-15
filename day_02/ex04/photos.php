#!/usr/bin/php
<?php

/*

e1z4r13p9% php photos.php 'https://www.42.fr'
e1z4r13p9% php photos.php 'https://www.42.us.org/'
e1z4r13p9% ls -lR
total 19
-rwxr-xr-x  1 asarandi  october  1034 Oct  4 18:31 photos.php
drwxr-xr-x  2 asarandi  october  4096 Oct  4 18:32 www.42.fr
drwxr-xr-x  2 asarandi  october  4096 Oct  4 18:32 www.42.us.org

./www.42.fr:
total 1012
-rw-r--r--  1 asarandi  october    1146 Oct  4 18:34 42_logo_black.svg
-rw-r--r--  1 asarandi  october  516238 Oct  4 18:34 home_big.jpg

./www.42.us.org:
total 11088
-rw-r--r--  1 asarandi  october     1149 Oct  4 18:34 42_logo_black.svg
-rw-r--r--  1 asarandi  october   233857 Oct  4 18:34 713px-Ada_Lovelace_portrait-2.jpg
-rw-r--r--  1 asarandi  october   120346 Oct  4 18:34 Brian-Airbnb-CEO.png
-rw-r--r--  1 asarandi  october     9132 Oct  4 18:34 airbnb1.png
-rw-r--r--  1 asarandi  october     3873 Oct  4 18:34 businessinsider-logo.png
-rw-r--r--  1 asarandi  october  1093378 Oct  4 18:34 certificate_group_photo_01-1.jpg
-rw-r--r--  1 asarandi  october    39109 Oct  4 18:34 dorsey-portrait.png
-rw-r--r--  1 asarandi  october    44894 Oct  4 18:34 evan-portrait.png
-rw-r--r--  1 asarandi  october      292 Oct  4 18:34 facebook-f.svg
-rw-r--r--  1 asarandi  october     2148 Oct  4 18:34 forbes-logo.png
-rw-r--r--  1 asarandi  october     2868 Oct  4 18:34 fortune-logo.png
-rw-r--r--  1 asarandi  october      517 Oct  4 18:34 google-plus.svg
-rw-r--r--  1 asarandi  october  1211445 Oct  4 18:34 image1-1.jpeg
-rw-r--r--  1 asarandi  october    60021 Oct  4 18:34 leila-portrait.png
-rw-r--r--  1 asarandi  october   588744 Oct  4 18:34 psprawka_23-1.jpg
-rw-r--r--  1 asarandi  october     7136 Oct  4 18:34 samasource-logo.png
-rw-r--r--  1 asarandi  october      783 Oct  4 18:34 slack.svg
-rw-r--r--  1 asarandi  october     3293 Oct  4 18:34 snapchat-logo.png
-rw-r--r--  1 asarandi  october     2028 Oct  4 18:34 techcrunch-logo.png
-rw-r--r--  1 asarandi  october   942209 Oct  4 18:34 tesla_panel_23.jpg
-rw-r--r--  1 asarandi  october     3937 Oct  4 18:34 the-newyorktimes-logo.png
-rw-r--r--  1 asarandi  october     2935 Oct  4 18:34 twitter-logo.png
-rw-r--r--  1 asarandi  october      545 Oct  4 18:34 twitter.svg
-rw-r--r--  1 asarandi  october     2580 Oct  4 18:34 venturebeat-logo.png
-rw-r--r--  1 asarandi  october  1292275 Oct  4 18:34 woj_keynote_20.jpg
-rw-r--r--  1 asarandi  october      704 Oct  4 18:34 youtube-play.svg
e1z4r13p9%


 */


function curl_get($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch);
	return ($data);
}

if ($argc > 1) {
	$url = $argv[1];
}

if (($html = curl_get($url)) === false) {
	exit("Failed to get page: '$url'\n");
}

$url_array = parse_url($url);
$folder = $url_array['host'];
if (!file_exists($folder))
	mkdir($folder);
chdir($folder);

$images = [];
preg_match_all('/<img.*?src="(.+?)"/i', $html, $images, PREG_PATTERN_ORDER);
if (isset($images[1])) {
	foreach ($images[1] as $img) {
		if ($img[0] == '/') {	//when <img src="/myimage.jpg">; we need shema, host
			$img = $url_array['scheme'] . '://' . $url_array['host'] . $img;
		}
		$filename = basename($img);
		if (($data = curl_get($img)) === false) {
			echo "Failed to get image: $img\n";
		} else {
			file_put_contents($filename, $data);
		}
	}
} else {
	echo "Could not find any <img> tags\n";
}

?>
