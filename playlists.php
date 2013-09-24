<?php

require('config.php');
require('ip_block.php');

// get playlists

// load the music library
$library_xml = simplexml_load_file($library_file);

$playlists_xml = $library_xml->dict->array->dict;

$playlists = array();

foreach ($playlists_xml as $playlist) {
	if ($playlist->string[0] == 'Library' || $playlist->string[0] == 'Music' || $playlist->string[0] == 'Purchased') {
		continue;
	} else {
		$new_playlist = array();
		//echo '<pre>'.print_r($playlist, true).'</pre>';
		$new_playlist['id'] = ''.$playlist->string[1].'';
		if (isset($playlist->string[2]) && trim($playlist->string[2]) != '') {
			$new_playlist['parent_id'] = ''.$playlist->string[2].'';
		} else {
			$new_playlist['parent_id'] = null;
		}
		$new_playlist['name'] = ''.$playlist->string[0].'';
		$playlists[] = $new_playlist;
	}
}

//echo '<pre>'.print_r($playlists, true).'</pre>';
echo json_encode($playlists);

?>