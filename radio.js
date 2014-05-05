
/*

	BROWSER RADIO actual functionality via jQuery~~~

*/

// the URL to where the song.php file is
var song_url_base = 'http://localhost/radio/song.php';

// for use with random song selection
function randomInt(min, max) { // inclusive
	if (max == undefined) { // assume it's between 0 and whatever
		return Math.floor(Math.random() * (min + 1));
	} else {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}
}

// hold onto all the tracks available
var all_tracks = [];
var last_tracks = []; // hold onto the track history
var current_song_index = undefined; // what song is currently playing

// play song at a given index
function playSongAtIndex(index) {
	$('#loading').show();
	current_song_index = index * 1;
	//console.log('playing: ');
	//console.log(all_tracks[current_song_index]);
	$('div.song').removeClass('playing');
	$('#now-playing').html(all_tracks[current_song_index].artist + ' - ' + all_tracks[current_song_index].title);
	$('#player').attr('src', all_tracks[current_song_index].file.replace(/&/g, '%26'));
	document.getElementById('player').play();
	$('div.song[data-id="'+index+'"]').addClass('playing');
	$('a#current-song-link').attr('href', song_url_base + '?a=' + all_tracks[current_song_index].artist + '&t=' + all_tracks[current_song_index].title);
	$('#loading').hide();
}

// play random song, duh
function playRandomSong() {
	var randomsong_index = randomInt(all_tracks.length);
	if (current_song_index != undefined) {
		last_tracks.push(current_song_index); // save the last song
	}
	playSongAtIndex(randomsong_index);
}

// next song
function nextPlz() {
	if ($('#random-shuffle').prop('checked')) {
		playRandomSong();
	} else {
		playSongAtIndex(current_song_index + 1);
	}
}

// previous song
function prevPlz() {
	if ($('#random-shuffle').prop('checked')) {
		if (last_tracks.length == 0) {
			return;
		}
		playSongAtIndex(last_tracks.pop());
	} else {
		playSongAtIndex(current_song_index - 1);
	}
}

// load a new playlist of songs to the interface
function loadSongs(tracks) {
	$('#loading').show();
	all_tracks = tracks;
	last_tracks = []; // reset last tracks
	$('#num-songs').html(all_tracks.length);
	$('#list').html('');
	for (var i = 0; i < all_tracks.length; i++) {
		if (all_tracks[i] != undefined && all_tracks[i] != null) {
			var song = all_tracks[i];
			$('#list').append('<div data-id="'+i+'" class="song">'+song.artist+' - '+song.title+'</div>');
		}
	}
	$('div.song').click(function() {
		playSongAtIndex($(this).attr('data-id'));
	});
	$('#loading').hide();
}

// song's over -- wat do?
function songEnded() {
	if ($('#continuous-play').prop('checked')) {
		if ($('#random-shuffle').prop('checked')) {
			playRandomSong();
		} else {
			playSongAtIndex(current_song_index + 1);
		}
	}
}

$(document).ready(function() {
	
	// start with ALL THE TRACKS...
	$.get('list.php', function(tracklist) {
		loadSongs(tracklist);
	}, 'json');
	
	// get ALL THE PLAYLISTS...
	$.get('playlists.php', function(playlists) {
		for (var i = 0; i < playlists.length; i++) {
			$('select#playlists').append('<option value="'+playlists[i].id+'">'+playlists[i].name+'</option>');
		}
	}, 'json');
	
	// when you select a playlist item, change the current tracklist
	$('select#playlists').change(function() {
		$('#loading').show();
		var sort_option = '';
		if ($('#resort-box').prop('checked')) {
			sort_option = '&sort';
		}
		var selected_playlist = $(this).val();
		if (selected_playlist == 0) {
			$.get('list.php', function(tracklist) {
				loadSongs(tracklist);
			}, 'json');
		} else {
			$.get('playlist.php?id=' + selected_playlist + sort_option, function(tracks) {
				loadSongs(tracks);
			}, 'json');
		}
	});
	
	// resort the music
	$('#resort-box').click(function() {
		$('#loading').show();
		var sort_option = '';
		if ($('#resort-box').prop('checked')) {
			sort_option = '&sort';
		}
		var selected_playlist = $('select#playlists').val();
		if (selected_playlist == 0) {
			$.get('list.php', function(tracklist) {
				loadSongs(tracklist);
			}, 'json');
		} else {
			$.get('playlist.php?id=' + selected_playlist + sort_option, function(tracks) {
				loadSongs(tracks);
			}, 'json');
		}
	});
	
	$('#random-song-btn').click(playRandomSong);
	$('#prev-song-btn').click(prevPlz);
	$('#next-song-btn').click(nextPlz);
	
	$(document).keydown(function(e) {
		if (e.keyCode == 32) {
			e.preventDefault(); // stop spacebar from scrolling so we can use it for play/pause
		}
	});
	
	$(document).keyup(function(e) {
		// keyboard commands!
		switch (e.keyCode) {
			case 32: // space
			e.preventDefault();
			if (document.getElementById('player').paused) {
				document.getElementById('player').play();
			} else {
				document.getElementById('player').pause();
			}
			break;
			case 78: // n
			nextPlz();
			break;
			case 80: // p
			prevPlz();
			break;
			case 82: // r
			playRandomSong();
			break;
			default:
			//console.log(e.keyCode);
		}
	});
	
});

// on song end, fire off a function call in case we want more music
document.getElementById('player').addEventListener('ended', songEnded);
