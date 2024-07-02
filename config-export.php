<?php
	$filename = $_GET['file'];

	switch ($filename) {
		case 'cosmostreamer.conf': break;
		case 'config.txt': break;
		default: die('forbidden');
	}

	$fullFilename = '/boot/' . $filename;

	header('Content-Type:application/octet-stream');
	header('Content-Length: ' . filesize($fullFilename));
	header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
	header('Content-Disposition: filename="' . $filename . '"');
	readfile($fullFilename);
?>