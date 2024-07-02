<?php
	header('Cache-Control: no-cache, no-store, must-revalidate');
	header('Pragma: no-cache');
	header('Expires: 0');

	$url = $_GET['file'];

	$path_parts = pathinfo($url);
	$extension = $path_parts['extension'];
	if ($extension != 'jpg' && $extension != 'png') die('Wrong file');

	$type = 'image/jpeg';
	header('Content-Type:' . $type);
	header('Content-Length: ' . filesize($url));

	header('Content-Disposition: filename="' . $path_parts['basename'] . '"');

	readfile($url);
?>
