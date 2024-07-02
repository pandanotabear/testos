var ws_video = null;
var ws_video_connected = false;
var jmuxer = null;
var current_host = null;
var video_interval = null;
var connecting = false;
var currentTime = 0;

$(document).ready(function() {
	StartPlayer(document.location.host);
});


function StartPlayer(host) {
	if (host == "") return;
	if (host == null) return;
	if (host == undefined) return;

	current_host = host;
	if (video_interval != null) clearInterval(video_interval);
	video_interval = setInterval(RunPlayer, 1000);
	RunPlayer();
}

function RunPlayer() {

	if (ws_video_connected) return;

	if (connecting) return;
	connecting = true;

	if (jmuxer != null) jmuxer = null;

	if (ws_video != null) {
		try { ws_video.close(); } catch {}
		ws_video = null;
	}

	var uri = "ws://" + current_host + ":8080";

    jmuxer = new JMuxer({
		node: 'video_element',
		mode: 'video',
		flushingTime: 100,
		fps: 30,
		debug: false,
		onError: function(data) {
			jmuxer.reset();
		}
	});

	ws_video = new WebSocket(uri);
	ws_video.binaryType = 'arraybuffer';

	ws_video.addEventListener('message',function(event) {
		jmuxer.feed({
			video: new Uint8Array(event.data)
		});
	});

	ws_video.addEventListener('error', function(e) {
		onVideoDisconnected();
	});

	ws_video.addEventListener('close', function(e) {
		onVideoDisconnected();
	});

	ws_video.addEventListener('open', function(e) {
		onVideoConnected();
	});

	document.querySelector('#video_element').addEventListener('timeupdate',  evt => {
		var video = document.querySelector('#video_element');
		currentTime = video.currentTime;
		//console.log("timeupdate " + video.currentTime);
	});

	document.querySelector('#video_element').addEventListener('play',  evt => { 
		console.log('playback started');
	});

	document.querySelector('#video_element').addEventListener('pause',  evt => { 
		console.log('playback paused');

		var video = document.querySelector('#video_element');
		video.currentTime = currentTime - 0.01;
        video.play();
	});


	connecting = false;
}


function onVideoConnected() {
	ws_video_connected = true;
}

function onVideoDisconnected() {
	try { ws_video.close(); } catch (e) {}
	ws_video = null;
	ws_video_connected = false;
}
