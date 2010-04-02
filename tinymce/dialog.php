<?php
global $eD2kLSButton;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $eD2kLSButton->str['title'] ?></title>
<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<script type="text/javascript" src="<?php bloginfo('url'); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<script type="text/javascript">//<![CDATA[
var ed2klsDialog = {
	init : function(ed) {
		tinyMCEPopup.resizeToInnerSize();
	},
	add : function(content) {
		var ed = tinyMCEPopup.editor;
		content = this.clean(content,"<br />");
		content = "[ed2k]<br />" + content +"<br />[/ed2k]";
		tinyMCEPopup.execCommand("mceInsertContent", false, content);
		tinyMCEPopup.close();
		ed.focus();
	},
	clean : function(str,rplmt) {
		str = str.replace(/(?!^)(ed2k:\/\/\|file\|.+?\|\/(?!\|))/gim, rplmt + "$1").replace(/(ed2k:\/\/\|file\|.+?\|\/(?!\|))(?!$)/gim, "$1" + rplmt);
		str = str.replace(new RegExp("(" + rplmt.replace(/\//g, "\\/") + "){2,}", "g"), rplmt);
		return str;
	},
	cancel : function(content) {
		tinyMCEPopup.close();
	},
};
tinyMCEPopup.onInit.add(ed2klsDialog.init, ed2klsDialog);
//]]></script>
<style type='text/css'>
body, textarea, input.mceButton {
	font-size: 13px!important;
}
#wrapper {
	width: 96%;
	margin: 1em auto;
}
.elsdiv {
	margin: 0.5em 0;
}
#ed2klsTa {
	width: 100%;
}
#ok, #clean {
	margin-right: 2em;
}
#buttonsline {
	text-align: center;
}
</style>
<base target="_self" />
</head>
<body style="display: none">
	<div id="wrapper">
		<div id="noteline" class="elsdiv">
			<label for="ed2klsTa"><?php echo $eD2kLSButton->str['enter'] ?></label>
		</div>
		<div id="taline" class="elsdiv">
			<textarea rows="20" cols="85" class="" id="ed2klsTa"></textarea>
		</div>
		<div id="buttonsline" class="elsdiv">
			<input type="button" value="<?php echo $eD2kLSButton->str['ok'] ?>" id="ok" class="mceButton" onclick="ed2klsDialog.add(document.getElementById('ed2klsTa').value);">
			<input type="button" value="<?php echo $eD2kLSButton->str['clean'] ?>" id="clean" class="mceButton" onclick="document.getElementById('ed2klsTa').value=ed2klsDialog.clean(document.getElementById('ed2klsTa').value,'\n');">
			<input type="button" value="<?php echo $eD2kLSButton->str['cancel'] ?>" id="cancel" class="mceButton" onclick="ed2klsDialog.cancel();">
		</div>
	</div>
</body>
</html>
