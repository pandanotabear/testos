<?php
	$action = '';
	$media = '';
	if (isset($_GET['action'])) $action = $_GET['action'];
	if (isset($_GET['media'])) $media = $_GET['media'];

	$output_filename = '';
	switch ($media) {
		case 'splash': UploadSplash($action); break;
		case 'logo': UploadLogo($action); break;
		case 'intro': UploadIntro($action); break;
		case 'bgaudio': UploadBgAudio($action); break;
	}


	function UploadSplash($action) {
		$filename = '/boot/splash.png';
		switch ($action) {
			case "get":
				$path_parts = pathinfo($filename);
				header('Cache-Control: no-cache, no-store, must-revalidate');
				header('Pragma: no-cache');
				header('Expires: 0');
				header('Content-Type: image/png');
				header('Content-Length: ' . filesize($filename));
				header('Content-Disposition: filename="' . $path_parts['basename'] . '"');
				readfile($filename);
			break;

			case "set":
				system('mount -o rw,remount /boot');
				if (move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
					echo 'ok';
					system('killall -q omxivsplash1 omxivsplash2 > /dev/null 2>&1');
				} else {
					echo 'fail';
				}
				system('sync');
				system('mount -o ro,remount /boot');
			break;
		}
	}

	function UploadLogo($action) {
		$filename = '/boot/logo.png';
		switch ($action) {
			case "get":
				$path_parts = pathinfo($filename);
				header('Cache-Control: no-cache, no-store, must-revalidate');
				header('Pragma: no-cache');
				header('Expires: 0');
				header('Content-Type: image/png');
				header('Content-Length: ' . filesize($filename));
				header('Content-Disposition: filename="' . $path_parts['basename'] . '"');
				readfile($filename);
			break;

			case "set":
				system('mount -o rw,remount /boot');
				if (move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
					echo 'ok';
					system('killall -q omxivlogo1 omxivlogo2 omxivlogo3 > /dev/null 2>&1');
				} else {
					echo 'fail';
				}
				system('sync');
				system('mount -o ro,remount /boot');
			break;
		}
	}

	function UploadIntro($action) {
		switch ($action) {
			/*case "get":
				$path_parts = pathinfo($filename);
				header('Cache-Control: no-cache, no-store, must-revalidate');
				header('Pragma: no-cache');
				header('Expires: 0');
				header('Content-Type: video/mp4');
				header('Content-Length: ' . filesize($filename));
				header('Content-Disposition: filename="' . $path_parts['basename'] . '"');
				readfile($filename);
			break;*/

			case "set":
				system('mount -o rw,remount /boot');

				$filename = '';

				/// Check video file codec
				$output = shell_exec('gst-discoverer-1.0 ' . $_FILES['file']['tmp_name']);
				if (strpos($output, 'H.265') === false) {
				} else {
					$filename = '/boot/intro.h265.mp4';
				}
				if (strpos($output, 'H.264') === false) {
				} else {
					$filename = '/boot/intro.mp4';
				}
				if ($filename == '') {
					break;
				}

				if (file_exists($_FILES['file']['tmp_name'])) {
					@unlink($filename);
				}

				if (move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
					echo 'ok';
					system('killall -q gst-video-bg > /dev/null 2>&1');
				} else {
					echo 'fail';
				}
				system('sync');
				system('mount -o ro,remount /boot');
			break;
		}
	}

	function UploadBgAudio($action) {
		$filename = '/boot/bgaudio.mp3';
		switch ($action) {
			case "get":
				$path_parts = pathinfo($filename);
				header('Cache-Control: no-cache, no-store, must-revalidate');
				header('Pragma: no-cache');
				header('Expires: 0');
				header('Content-Type: audio/mp3');
				header('Content-Length: ' . filesize($filename));
				header('Content-Disposition: filename="' . $path_parts['basename'] . '"');
				readfile($filename);
			break;

			case "set":
				system('mount -o rw,remount /boot');
				if (move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
					echo 'ok';
					system('killall -q audioserver gst-audio-source-stub > /dev/null 2>&1');
				} else {
					echo 'fail';
				}
				system('sync');
				system('mount -o ro,remount /boot');
			break;
		}
	}
?>
