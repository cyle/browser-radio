<?php

/*
	
	
	BROWSER RADIO~~~~


*/

require('config.php');
require('ip_block.php');

?>
<!doctype html>
<html>
<head>
<title><?php echo $radio_name; ?></title>
<link rel="stylesheet" href="radio.css" />
</head>
<body>
<div id="header">
	<h1><?php echo $radio_name; ?></h1>
	<p>Note: this currently only works in Chrome and Safari.</p>
	<p>Playlist: <select id="playlists"><option value="0">All Music</option></select> <label><input id="resort-box" type="checkbox" /> resort to artist/track?</label></p>
	<p><span id="num-songs">Dunno how many</span> songs, lol.</p>
	<p id="now-playing-text"><b>Now playing: <span id="now-playing">nothing, lol</span></b></p>
	<p><a target="_blank" id="current-song-link" href="#">Link to currently playing song &raquo;</a></p>
	<p><input type="button" value="&laquo; [p]rev" id="prev-song-btn" /> <input type="button" id="random-song-btn" value="play [r]andom song, lol" /> <input type="button" value="[n]ext &raquo;" id="next-song-btn" /></p>
	<p><label title="keep playing music when the song ends"><input type="checkbox" id="continuous-play" /> Continuous play?</label> <label title="every time a song ends or you hit NEXT, play a random song"><input type="checkbox" id="random-shuffle" /> Random shuffle?</label></p>
</div>
<div id="player-container"><audio id="player" style="width:100%" controls></audio></div>
<div id="loading">Loading... <img src="loading.gif" /></div>
<div id="list"></div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="radio.js"></script>
</body>
</html>
