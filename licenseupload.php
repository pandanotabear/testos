<?php
	$newFileName = '/tmp/licenses.tar.gz';
	if (move_uploaded_file($_FILES['file']['tmp_name'], $newFileName)) {
		system("tar zxvf $newFileName --directory / >> /dev/null 2>&1");
		echo 'ok';
	} else {
		echo 'fail';
	}
?>