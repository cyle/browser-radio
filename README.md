# BROWSER RADIO~~~

This makes your iTunes music library accessible via a web browser, complete with next/prev/random, playlists, sorting, and more!

## Requirements

- an iTunes library full of music
- a web server service installed on your local machine (for example, OS X with Apache and PHP enabled)
- PHP 5.3+
- Node.js v0.10+
- npm
- the "express" node.js module v0.3+

## Installation

First of all, put the code in a web-accessible place.

Next, you need to edit a few config variables. Rename `config.example.php` to `config.php` and edit it for the front-end interface options, `play.js` for the node.js playback options, and `radio.js` for some more front-end configuration. You must edit all three files with your path info and whatnot. The variables to edit are clearly commented near the top of each file.

The `play.js` file is for node.js and requires the `express` plugin to be installed, as in run `npm install express` in the same directory as `play.js`.

## Usage

Run the `play.js` file by opening a terminal, going to wherever you dropped the files, and running `node play.js`. The app will listen on port 1337 (by default) for any incoming music playback requests, and send along the requested file accordingly. It has basic protection against using relative paths to grab stuff you don't want people to access.

If you want to keep it going for a long time, you may want to use a program like [forever](https://github.com/nodejitsu/forever) or [pm2](https://github.com/Unitech/pm2) to manage it in the background.

One that's running, just go to wherever you dropped the files via a browser. It should automatically parse your iTunes library and show a big huge list of files. Enjoy!

## Notes

This was built and tested on Mac OS Lion and Mountain Lion. I haven't tested it at all on Windows.

I'm completely unclear on whether "file sharing" like this is considered illegal. It's a grey area. This works a lot like how local area network iTunes Library Sharing already works, which isn't illegal, but combined with the ease of Spotify in the browser. I'm not sure what to think. In `config.php` you will see that you have to restrict usage to certain IP ranges, most often your local network only, much the same way iTunes Library Sharing already works. I can guarantee that it's not a good idea to share your iTunes library over the entire internet.

## Known Bugs

The playlist dropdown may be huge, I know. And for some reason certain song paths won't work, but I'm trying to figure it out. Also, there may be an issue playing high-bitrate mp3s. Again, not sure why.

## Features To Add

- sorting options
	- sort by artist and then album name and then track number ascending?
	- sort by artist and then track name ascending?
- search for tracks/artists