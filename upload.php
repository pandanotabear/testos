<?php
	$newFileName = '/tmp/cosmostreamer-ng.csuf';
	if (move_uploaded_file($_FILES['file']['tmp_name'], $newFileName)) {
		system("/opt/Cosmostreamer-NG/update.sh $newFileName >> /dev/null 2>&1 &");
		echo 'ok';
	} else {
		echo 'fail';
	}
?>