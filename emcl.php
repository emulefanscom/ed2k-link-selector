<?php

function downloadEmcl($content, $name) {
	ob_start();
	header('Cache-Control: no-cache, must-revalidate');
	header('Content-Type: application/octetstream');
	header('Content-Disposition: attachment; filename="' . $name . '.emulecollection"');
	echo '# eMule Collection (Simple Text Format). Created by eD2k Link Selector WordPress Plugin (emule-fans.com)
';
	echo $content;
    ob_end_flush();
}

if ( isset($_POST['el-s-no']) && isset($_POST['el-s-chkbx-' . $_POST['el-s-no']])) {
	$content = '';
	$links = $_POST['el-s-chkbx-' . $_POST['el-s-no']];
	foreach ($links as $mylink) {
		$content .= $mylink . '
';
	}
	$name = explode('|', $content, 4);
	$name = $name[2];
	if (!preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"])) {
		$name = urldecode($name);
	}
	$name = $name . date('_ymdHi');
	downloadEmcl($content, $name);
} else {
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ../../..');
}

exit;

?>