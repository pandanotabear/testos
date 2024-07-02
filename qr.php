<?php
	$filename = '/run/cosmostreamer/board_id_qr.png';

	header("Content-type: image/png");
	header('Expires: 0');
	header('Content-Length: ' . filesize($filename));

	readfile($filename);
?>