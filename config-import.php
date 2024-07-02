<?php
	//print_r($_FILES);

	$filename = $_GET['file'];

	switch ($filename) {
		case 'cosmostreamer.conf':
		break;

		case 'config.txt':
		break;

		default: die('forbidden');
	}

	$tempFilename = '/tmp/' . $filename . '.tmp';
	$fullFilename = '/boot/' . $filename;

	/// Upload file into temp dir
	if (move_uploaded_file($_FILES['file']['tmp_name'], $tempFilename)) {
	} else {
		die('Filed to save file');
	}

	/// Check file content
	$fileContent = file_get_contents($tempFilename);
	switch ($filename) {
		case 'cosmostreamer.conf':
			if (strpos($fileContent, 'Cosmostreamer NG config file') === false) die('Not a Cosmostreamer config file!');
		break;

		case 'config.txt':
			if (strpos($fileContent, '# For more options and information see') === false) die('Not a Raspberry Pi config file!');
		break;
	}

	system('mount -o rw,remount /boot');

	/// Backup existing file
	@copy($fullFilename, $fullFilename . '.save');

	if (rename($tempFilename, $fullFilename)) {
		echo 'ok';
	} else {
		echo 'fail';
	}

	system('sync');
	system('mount -o ro,remount /boot > /dev/null 2>&1');
?>