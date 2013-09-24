<?php

/*

	the BROWSER RADIO config file -- php part of it
	
	remember to also set config options in play.js
	remmeber to also set config options in radio.js

*/

// the URL path to the index.php page
$app_path = '/radio/';

// name your station
$radio_name = 'RADIOOOO';

// where is your "iTunes Music Library.xml" file ?
$library_file = '/Users/yourname/Music/iTunes/iTunes Music Library.xml';

// what's the file path base inside your iTunes Library XML file?
// hint: you can usually figure this out by right-clicking a song in iTunes and clicking "Get Info"
//       the "Where" section at the bottom of the "Summary" tab has this path
$itunes_file_location_base = 'file://localhost/Users/yourname/Music/';

// what's the URL to the node.js play system that comes with this?
$nodejs_playback_url_base = 'http://localhost:1337/play?file=';

// IP range restrictions
$ip_range_restricts = array('192.168.0.0/16', '10.0.0.0/8');


?>