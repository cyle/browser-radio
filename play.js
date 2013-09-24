/*
	
	mp3 playback for your radio
	
	note: this logs song playback by default, so you may want to redirect its logs to /dev/null or something if you really don't care

*/

// what port to listen on, should NOT be the same as your web server
var radio_port = 1337;

// our radio station name
var radio_name = 'RADDIIIOOOO';

// the base directory where our music files live
// this should be the same as $itunes_file_location_base in config.php, 
// except this should be an actual path, not a file:// URL
var music_basedir = '/Users/yourname/Music/';

// end config here, everything else is just code

var fs = require('fs');
var express = require('express'); // we need dat express module to make this easier
var app = express();

function makeTS() {
	var d = new Date();
	var ts = '';
	ts = ''+((d.getMonth() < 9) ? '0': '')+(d.getMonth()+1)+'-'+((d.getDate() < 10) ? '0': '')+d.getDate()+'-'+d.getFullYear()+' '+((d.getHours() < 10) ? '0': '')+d.getHours()+':'+((d.getMinutes() < 10) ? '0': '')+d.getMinutes()+':'+((d.getSeconds() < 10) ? '0': '')+d.getSeconds()+'';
	return ts;
}

app.get('/', function(req, res) {
	var body = radio_name;
	res.setHeader('Content-Type', 'text/plain');
	res.setHeader('Content-Length', body.length);
	res.end(body);
});

app.get('/play', function(req, res) {
	//console.log(req);
	if (req.query.file == undefined) {
		// 500
		res.send(500, { error: 'You did not specify a file!' });
	} else if (fs.existsSync(music_basedir + req.query.file) == false) {
		// 404
		res.send(404);
	} else {
		if (req.query.file.indexOf('..') != -1) {
			res.send(500, { error: 'You cannot use relative paths!' });
		} else {
			// ok cool
			var filepath = music_basedir + req.query.file;
			var ip = req.headers['x-forwarded-for'] || req.connection.remoteAddress;
			console.log('' + makeTS() + ' [' + ip + '] is listening to ' + filepath);
			res.download(filepath);
		}
	}
});

app.listen(radio_port);
console.log(radio_name+', listening on port '+radio_port+'...');