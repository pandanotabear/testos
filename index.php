<?php
	$config = LoadConfig('/run/cosmostreamer/config.conf');

	$content = file_get_contents('index.tpl.html');

	$content = str_replace('{IP}', $_SERVER['SERVER_ADDR'], $content);
	$content = str_replace('{RND}', rand(), $content);
	$content = str_replace('{APP_TYPE}', $config['app_type'], $content);

	if ($config['app_type'] == 9) {
		$content = str_replace('{APP_NAME}', 'StereoPi', $content);
		$content = str_replace('{BACKGROUND-IMG}', 'background-stereopi.jpg', $content);
		$content = str_replace('{HIDEFORSTEREOPISTYLE}', 'display:none;', $content);
	} else {
		$content = str_replace('{APP_NAME}', 'Cosmostreamer NG', $content);
		$content = str_replace('{BACKGROUND-IMG}', 'background-cs.jpg', $content);
		$content = str_replace('{HIDEFORSTEREOPISTYLE}', '', $content);
	}

	if ($config['app_type'] == 17) {
		$content = str_replace('{DJIFPVONLY}', '', $content);
	} else {
		$content = str_replace('{DJIFPVONLY}', 'display:none;', $content);
	}


	echo $content;


    function LoadConfig($filename) {
		if (!file_exists($filename)) return;

		$result = array();

		$lines = file($filename);

		foreach ($lines as $k => $v) {
			$line = trim($v);

			$parts = explode('=', $line);
			if (sizeof($parts) != 2) continue;

			$result[$parts[0]] = $parts[1];
		}

		return $result;
    }

?>
