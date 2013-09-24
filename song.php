<?php

require('config.php');
require('ip_block.php');

// oh lawds... get the music!

if (!isset($_GET['a']) || trim($_GET['a']) == '') {
	die('no artist given');
}

if (!isset($_GET['t']) || trim($_GET['t']) == '') {
	die('no track given');
}

$selected_artist = strtolower(trim($_GET['a']));
$selected_track = strtolower(trim($_GET['t']));

// load the music library
$library_xml = simplexml_load_file($library_file);
$tracks = $library_xml->dict->dict;
unset($tracks->key);

$the_track = array(); // will hold all the track

foreach ($tracks->dict as $wut) {
	$is_mp3 = false;
	$new_track = array();
	$new_track['artist'] = ''.$wut->string[1].'';
	$new_track['title'] = ''.$wut->string[0].'';
	foreach ($wut->string as $string) {
		if (strpos($string, 'file://') !== false) {
			if (substr(strtolower($string), -4) == '.mp3') {
				$is_mp3 = true;
			}
			$new_track['file'] = str_replace($itunes_file_location_base, $nodejs_playback_url_base, $string);
		}
	}
	if (strtolower($new_track['artist']) == $selected_artist && strtolower($new_track['title']) == $selected_track && $is_mp3) {
		$the_track = $new_track;
		break;
	}
}

if (count($the_track) == 0) {
	die('could not find that track');
}

?>
<!doctype html>
<html>
<head>
<title><?php echo $the_track['artist']; ?> - <?php echo $the_track['title']; ?></title>
<link rel="stylesheet" href="radio.css" />
</head>
<body>
<div id="song-page">
<h1><?php echo $the_track['artist']; ?> - <?php echo $the_track['title']; ?></h1>
<audio id="player" src="<?php echo $the_track['file']; ?>" autoplay controls></audio>
<p>It don't get no simpler than this.</p>
<p><a href="<?php echo $app_path; ?>">go back to <?php echo $radio_name; ?></a></p>
</div>
</body>
</html>