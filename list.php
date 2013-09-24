<?php

require('config.php');
require('ip_block.php');

// oh lawds... get the music!

// load the itunes music library
$library_xml = simplexml_load_file($library_file);
$tracks = $library_xml->dict->dict;
unset($tracks->key);

$all_tracks = array(); // will hold all the tracks

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
	if ($is_mp3) {
		$all_tracks[] = $new_track;
	}
}

// sort the tracks by artist name and then by song title
foreach ($all_tracks as $key => $row) {
    $artists[$key]  = strtolower($row['artist']);
    $track_names[$key] = strtolower($row['title']);
}
array_multisort($artists, SORT_ASC, $track_names, SORT_ASC, $all_tracks);

//echo '<pre>'.print_r($all_tracks, true).'</pre>';

// if we want just a random track, get a random track
if (isset($_GET['random'])) {
	$random_track = $all_tracks[array_rand($all_tracks)];
	echo 'you should listen to '.print_r($random_track, true);
} else {
	echo json_encode($all_tracks); // otherwise send along the whole list
}

?>
