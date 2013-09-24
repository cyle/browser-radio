<?php

require('config.php');
require('ip_block.php');

// get a playlist

if (!isset($_GET['id']) || trim($_GET['id']) == '') {
	die('need a playlist ID');
}

$requested_playlist_id = trim($_GET['id']);

// load the music library
$library_xml = simplexml_load_file($library_file);

$tracks_xml = $library_xml->dict->dict;
unset($tracks_xml->key);

$all_tracks = array(); // will hold all the tracks

foreach ($tracks_xml->dict as $wut) {
	//echo '<pre>'.print_r($wut, true).'</pre>';
	$is_mp3 = false;
	$new_track = array();
	$track_id = $wut->integer * 1;
	//$new_track['id'] = $wut->integer * 1;
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
		$all_tracks[$track_id] = $new_track;
	}
}

//echo '<pre>'.print_r($all_tracks, true).'</pre>';

$playlists_xml = $library_xml->dict->array->dict;

$playlist_tracks = array();

foreach ($playlists_xml as $playlist) {
	if ($playlist->string[0] == 'Library' || $playlist->string[0] == 'Music' || $playlist->string[0] == 'Purchased') {
		continue;
	} else {
		$playlist_id = ''.$playlist->string[1].'';
		if ($playlist_id == $requested_playlist_id) {
			foreach ($playlist->array->dict as $track) {
				$playlist_tracks[] = $all_tracks[$track->integer * 1];
			}
		}
	}
}

if (isset($_GET['sort'])) {
	// sort the tracks by artist name and then by song title
	foreach ($playlist_tracks as $key => $row) {
	    $artists[$key]  = strtolower($row['artist']);
	    $track_names[$key] = strtolower($row['title']);
	}
	array_multisort($artists, SORT_ASC, $track_names, SORT_ASC, $playlist_tracks);
}

//echo '<pre>'.print_r($playlist_tracks, true).'</pre>';
echo json_encode($playlist_tracks);

?>