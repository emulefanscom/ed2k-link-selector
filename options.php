<style type="text/css">
#icon-ed2k {
	background: url("<?php echo constant('ED2KLS_URL'); ?>/img/emulecollection.png") no-repeat scroll 0 0 transparent;
}
.elsopt-wrap, .elsopt-wrap code {
	font-size: 13px;
}
.elsopt-wrap h3 {
	font-size: 1.4em;
	padding: 0.8em 0 0.4em;
	margin: 0.8em 0;
	border-top: 1px solid #DFDFDF;
}
input.elsopt-txt {
	width: 32em;
}
#elsopt-width, #elsopt-fontsize {
	width: 10em;
}
#elsopt-opt-all select {
	width: 10em;
}
#elsopt-opt-all #elsopt-lang {
	width: 20em;
}
#elsopt-opt-all th, #elsopt-opt-all td {
	text-align: left;
	vertical-align: top;
	padding: 0.7em;
}
.elsopt-help:hover {
	cursor: pointer;
	text-decoration: underline;
}
.elsopt-submitdiv {
	float: left;
}
#elsopt-default, #elsopt-uninstall-chk {
	margin-left: 2.3em;
}
#elsopt-demo {
	clear: both;
}
#elsopt-announce {
	background: #FCD0D0;
	border: 1px solid #FA8686;
	font-weight: bold;
	margin: 1em;
	padding: 1em 2em;
	-moz-border-radius: 6px;
	-khtml-border-radius: 6px;
	-webkit-border-radius: 6px;
	border-radius: 6px;
}
#elsopt-opt-all ul {
	margin: 0.4em 0 0;
	list-style: circle inside none;
}
#elsopt-opt-all ul ul {
	margin-left: 2.3em;
	list-style-type: disc;
}
</style>
<script type="text/javascript">//<![CDATA[
function elsInfoToggle(str) {
	var target = document.getElementById("elsopt-info-" + str)
	if (!target) {
		return false;
	}
	if (target.style.display == "block") {
		if (typeof jQuery == "undefined") {
			target.style.display = "none";
		} else {
			jQuery(target).slideUp(300);
		}
	} else {
		if (typeof jQuery == "undefined") {
			target.style.display = "block";
		} else {
			jQuery(target).slideDown(300);
		}
	}
	return false;
}
function ed2klsStatToggle(val) {
	document.getElementById("elsopt-stat").disabled = (val == "true") ? false : true;
}
function ed2klsUninstall(checked) {
	var el = document.getElementById("elsopt-uninstall");
	if (checked == true) {
		el.setAttribute("onclick", "");
	} else {
		el.setAttribute("onclick", "return false");
	}
}
//]]></script>

<div class="wrap elsopt-wrap">
	<?php screen_icon('ed2k'); ?>
	<h2><?php echo __('eD2k Link Selector Options', 'ed2kls'); ?></h2>
	<?php echo '<a href="http://emulefans.com/wordpress-ed2k-link-selector/" target="_blank" title="' . __('Homepage for eD2k Link Selector WordPress Plugin', 'ed2kls') . '">' . __('Plugin Home', 'ed2kls') . '</a>
	<span class="el-s-sep">|</span>
	<a href="http://www.emule-project.net/" target="_blank" title="' . __('eMule Official Site', 'ed2kls') . '">' . __('eMule Official', 'ed2kls') . '</a>
	<span class="el-s-sep">|</span>
	<a href="http://emulefans.com/" target="_blank" title="' . __('emulefans.com [eMule Fans Chinese Blog]', 'ed2kls') . '">' . __('emulefans.com', 'ed2kls') . '</a>
	<span class="el-s-sep">|</span>
	<a href="http://www.emule-mods.de/" target="_blank" title="' . __('eMule-Mods.de [eMule Mods Site]', 'ed2kls') . '">eMule-Mods.de</a>'; ?>
<?php
$condition = 0;
if ( isset($_POST['elsopt-uninstall']) && isset($_POST['elsopt-uninstall-chk']) ) {
	$delSuccess = delete_option('ed2kls_options');
	if($delSuccess) {
		$condition = 1;
	} else {
		$condition = 2;
	}
} else {
	$oldOptions = get_option('ed2kls_options');
	if (empty($oldOptions) || $oldOptions['dbversion'] != constant('ED2KLS_DBVERSION')) {
		$condition = 2;
	}
}
if ($condition == 1) {
		$deactivateUrl = 'plugins.php?action=deactivate&amp;plugin=ed2k-link-selector/ed2k-link-selector.php';
		if (function_exists('wp_nonce_url')) { 
			$deactivateUrl = wp_nonce_url($deactivateUrl, 'deactivate-plugin_ed2k-link-selector/ed2k-link-selector.php');
		}
		echo '<div id="elsopt-announce">';
		printf(__('Uninstalled successfully. Please click <a href="%1$s">here</a> to disable the plugin. Click the "%2$s" button to restore default settings and reuse the plugin.', 'ed2kls'), $deactivateUrl, __('Restore Default', 'ed2kls'));
		echo '</div>';
} elseif ($condition == 2) {
		echo '<div id="elsopt-announce">';
		printf(__('Failed to read from the database. Please go to <a href="%1$s">Plugin Page</a> to reinstall the plugin, or click the "%2$s" button below.', 'ed2kls'), 'plugins.php?plugin_status=all', __('Restore Default', 'ed2kls'));
		echo '</div>';
}
?>
	<div id="elsopt-opt-all">
		<h3><?php echo __('Settings', 'ed2kls'); ?></h3>
<?php if ($condition == 0) { ?>
		<p><?php printf(__('Click "[?]" button for help. The settings below will apply globally. You can also set "%2$s" and "%3$s" for specific %1$s tag in the post.', 'ed2kls'), '<code>[ed2k]</code>', __('Tag Attribute', 'ed2kls'), __('Available Value(s)', 'ed2kls')); ?></p>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<table>
				<tr>
					<th><?php echo __('Language', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('lang');">[?]</a></th>
					<td>
						<select id="elsopt-lang" name="elsopt-lang" class="elsopt-opt">
							<option value="en_US"<?php if($oldOptions['lang'] == 'en_US'){echo ' selected="selected"';} ?>>English (en_US)</option>
							<option value="zh_CN"<?php if($oldOptions['lang'] == 'zh_CN'){echo ' selected="selected"';} ?>>简体中文 (Simp Chinese, zh_CN)</option>
							<option value="zh_TW"<?php if($oldOptions['lang'] == 'zh_TW'){echo ' selected="selected"';} ?>>繁體中文 (Trad Chinese, zh_TW)</option>
							<option value="fr_FR"<?php if($oldOptions['lang'] == 'fr_FR'){echo ' selected="selected"';} ?>>Français (French, fr_FR)</option>
						</select>
						<div id="elsopt-info-lang" class="elsopt-info" style="display:none;"><?php echo __('Plugin language.', 'ed2kls'); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php echo __('Title', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('head');">[?]</a></th>
					<td>
						<input type="text" id="elsopt-head" name="elsopt-head" value="<?php echo $oldOptions['head']; ?>" class="elsopt-opt elsopt-txt" />
						<div id="elsopt-info-head" class="elsopt-info" style="display:none;"><?php echo __('(For the "Table" format) The title.', 'ed2kls'); ?><ul><li><?php echo __('Tag Attribute', 'ed2kls'); ?>: <code>head</code></li><li><?php echo __('Available Value(s)', 'ed2kls'); ?>: <ul><li><code>[<?php echo __('Text', 'ed2kls'); ?>]</code></li></ul></li></ul></div>
					</td>
				</tr>
				<tr>
					<th><?php echo __('Stat Button', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('stat');">[?]</a></th>
					<td>
						<select id="elsopt-stat-if" name="elsopt-stat-if" class="elsopt-opt" onchange="ed2klsStatToggle(this.options[this.selectedIndex].value);">
							<option value="true"<?php if($oldOptions['stat'] != 'false'){echo ' selected="selected"';} ?>><?php echo __('Enable', 'ed2kls'); ?></option>
							<option value="false"<?php if($oldOptions['stat'] == 'false'){echo ' selected="selected"';} ?>><?php echo __('Disable', 'ed2kls'); ?></option>
						</select>
						<br />
						<input type="text" id="elsopt-stat" name="elsopt-stat" class="elsopt-opt elsopt-txt" value="<?php if($oldOptions['stat'] != 'false'){echo $oldOptions['stat'];} ?>"<?php if($oldOptions['stat'] == 'false'){echo ' disabled="disabled"';} ?> />
						<div id="elsopt-info-stat" class="elsopt-info" style="display:none;"><?php echo __('(For the "Table" format) Whether to use stat button, and which stat site.', 'ed2kls'); ?><ul><li><?php echo __('Tag Attribute', 'ed2kls'); ?>: <code>stat</code></li><li><?php echo __('Available Value(s)', 'ed2kls'); ?>: <ul><li><code>[<?php echo __('Stat site URL prefix', 'ed2kls'); ?>]</code>;</li><li><code>false</code>: <?php echo __('Disable', 'ed2kls'); ?></li></ul></li></ul></div>
					</td>
				</tr>
				<tr>
					<th><?php echo __('Name Filter', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('name');">[?]</a></th>
					<td>
						<select id="elsopt-name" name="elsopt-name" class="elsopt-opt">
							<option value="auto"<?php if($oldOptions['name'] == 'auto'){echo ' selected="selected"';} ?>><?php echo __('Auto', 'ed2kls'); ?></option>
							<option value="true"<?php if($oldOptions['name'] == 'true'){echo ' selected="selected"';} ?>><?php echo __('Enable', 'ed2kls'); ?></option>
							<option value="false"<?php if($oldOptions['name'] == 'false'){echo ' selected="selected"';} ?>><?php echo __('Disable', 'ed2kls'); ?></option>
						</select>
						<div id="elsopt-info-name" class="elsopt-info" style="display:none;"><?php echo __('(For the "Table" format) Whether to use name filter.', 'ed2kls'); ?><ul><li><?php echo __('Tag Attribute', 'ed2kls'); ?>: <code>name</code></li><li><?php echo __('Available Value(s)', 'ed2kls'); ?>: <ul><li><code>auto</code>: <?php echo __('Auto', 'ed2kls'); ?>;</li><li><code>true</code>: <?php echo __('Enable', 'ed2kls'); ?>;</li><li><code>false</code>: <?php echo __('Disable', 'ed2kls'); ?></li></ul></li></ul></div>
					</td>
				</tr>
				<tr>
					<th><?php echo __('Size Filter', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('size');">[?]</a></th>
					<td>
						<select id="elsopt-size" name="elsopt-size" class="elsopt-opt">
							<option value="auto"<?php if($oldOptions['size'] == 'auto'){echo ' selected="selected"';} ?>><?php echo __('Auto', 'ed2kls'); ?></option>
							<option value="true"<?php if($oldOptions['size'] == 'true'){echo ' selected="selected"';} ?>><?php echo __('Enable', 'ed2kls'); ?></option>
							<option value="false"<?php if($oldOptions['size'] == 'false'){echo ' selected="selected"';} ?>><?php echo __('Disable', 'ed2kls'); ?></option>
						</select>
						<div id="elsopt-info-size" class="elsopt-info" style="display:none;"><?php echo __('(For the "Table" format) Whether to use size filter.', 'ed2kls'); ?><ul><li><?php echo __('Tag Attribute', 'ed2kls'); ?>: <code>size</code></li><li><?php echo __('Available Value(s)', 'ed2kls'); ?>: <ul><li><code>auto</code>: <?php echo __('Auto. Disable if only 1 link, enable if > 1', 'ed2kls'); ?>;</li><li><code>true</code>: <?php echo __('Enable', 'ed2kls'); ?>;</li><li><code>false</code>: <?php echo __('Disable', 'ed2kls'); ?></li></ul></li></ul></div>
					</td>
				</tr>
				<tr>
					<th><?php echo __('eMuleCollection', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('collection');">[?]</a></th>
					<td>
						<select id="elsopt-collection" name="elsopt-collection" class="elsopt-opt">
							<option value="true"<?php if($oldOptions['collection'] == 'true'){echo ' selected="selected"';} ?>><?php echo __('Enable', 'ed2kls'); ?></option>
							<option value="false"<?php if($oldOptions['collection'] == 'false'){echo ' selected="selected"';} ?>><?php echo __('Disable', 'ed2kls'); ?></option>
						</select>
						<div id="elsopt-info-collection" class="elsopt-info" style="display:none;"><?php echo __('(For the "Table" format) Whether to show emulecollection button.', 'ed2kls'); ?><ul><li><?php echo __('Tag Attribute', 'ed2kls'); ?>: <code>collection</code></li><li><?php echo __('Available Value(s)', 'ed2kls'); ?>: <ul><li><code>true</code>: <?php echo __('Enable', 'ed2kls'); ?>;</li><li><code>false</code>: <?php echo __('Disable', 'ed2kls'); ?></li></ul></li></ul></div>
					</td>
				</tr>
				<tr>
					<th><?php echo __('Width', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('width');">[?]</a></th>
					<td>
						<input type="text" id="elsopt-width" name="elsopt-width" value="<?php echo $oldOptions['width']; ?>" class="elsopt-opt elsopt-txt" />
						<div id="elsopt-info-width" class="elsopt-info" style="display:none;"><?php echo __('(For the "Table" format) Width of the table.', 'ed2kls'); ?><ul><li><?php echo __('Tag Attribute', 'ed2kls'); ?>: <code>width</code></li><li><?php echo __('Available Value(s)', 'ed2kls'); ?>: <ul><li><code>[<?php echo __('Width, in %, px, pt or em', 'ed2kls'); ?>]</code></li></ul></li></ul></div>
					</td>
				</tr>
				<tr>
					<th><?php echo __('Font Size', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('fontsize');">[?]</a></th>
					<td>
						<input type="text" id="elsopt-fontsize" name="elsopt-fontsize" value="<?php echo $oldOptions['fontsize']; ?>" class="elsopt-opt elsopt-txt" />
						<div id="elsopt-info-fontsize" class="elsopt-info" style="display:none;"><?php echo __('(For the "Table" format) Font size of the table.', 'ed2kls'); ?><ul><li><?php echo __('Tag Attribute', 'ed2kls'); ?>: <code>fontsize</code></li><li><?php echo __('Available Value(s)', 'ed2kls'); ?>: <ul><li><code>[<?php echo __('Font size, in %, px, pt or em', 'ed2kls'); ?>]</code></li></ul></li></ul></div>
					</td>
				</tr>
				<tr>
					<th><?php echo __('Button Style', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('buttonstyle');">[?]</a></th>
					<td>
						<select id="elsopt-buttonstyle" name="elsopt-buttonstyle" class="elsopt-opt">
							<option value="0"<?php if($oldOptions['buttonstyle'] == '0'){echo ' selected="selected"';} ?>><?php echo __('Text only', 'ed2kls'); ?></option>
							<option value="1"<?php if($oldOptions['buttonstyle'] == '1'){echo ' selected="selected"';} ?>><?php echo __('Image and text', 'ed2kls'); ?></option>
							<option value="2"<?php if($oldOptions['buttonstyle'] == '2'){echo ' selected="selected"';} ?>><?php echo __('Image only', 'ed2kls'); ?></option>
						</select>
						<div id="elsopt-info-buttonstyle" class="elsopt-info" style="display:none;"><?php echo __('(For the "Table" format) Style of buttons.', 'ed2kls'); ?><ul><li><?php echo __('Tag Attribute', 'ed2kls'); ?>: <code>buttonstyle</code></li><li><?php echo __('Available Value(s)', 'ed2kls'); ?>: <ul><li><code>0</code>: <?php echo __('Text only', 'ed2kls'); ?>;</li><li><code>1</code>: <?php echo __('Image and text', 'ed2kls'); ?>;</li><li><code>2</code>: <?php echo __('Image only', 'ed2kls'); ?></li></ul></li></ul></div>
					</td>
				</tr>
				<tr>
					<th><?php echo __('Format', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('format');">[?]</a></th>
					<td>
						<select id="elsopt-format" name="elsopt-format" class="elsopt-opt">
							<option value="1"<?php if($oldOptions['format'] == '1'){echo ' selected="selected"';} ?>><?php echo __('Table', 'ed2kls'); ?></option>
							<option value="2"<?php if($oldOptions['format'] == '2'){echo ' selected="selected"';} ?>><?php echo __('Anchor', 'ed2kls'); ?></option>
						</select>
						<div id="elsopt-info-format" class="elsopt-info" style="display:none;"><?php echo __('Which format to use for content on single post and page.', 'ed2kls'); ?><ul><li><?php echo __('Tag Attribute', 'ed2kls'); ?>: <code>format</code></li><li><?php echo __('Available Value(s)', 'ed2kls'); ?>: <ul><li><code>1</code>: <?php echo __('Table', 'ed2kls'); ?>;</li><li><code>2</code>: <?php echo __('Anchor', 'ed2kls'); ?></li></ul></li></ul></div>
					</td>
				</tr>
				<tr>
					<th><?php echo __('For All', 'ed2kls'); ?><a class="elsopt-help" title="<?php echo __('Help', 'ed2kls'); ?>" onclick="elsInfoToggle('forall');">[?]</a></th>
					<td>
						<select id="elsopt-forall" name="elsopt-forall" class="elsopt-opt">
							<option value="true"<?php if($oldOptions['forall'] == 'true'){echo ' selected="selected"';} ?>><?php echo __('Yes', 'ed2kls'); ?></option>
							<option value="false"<?php if($oldOptions['forall'] == 'false'){echo ' selected="selected"';} ?>><?php echo __('No', 'ed2kls'); ?></option>
						</select>
						<div id="elsopt-info-forall" class="elsopt-info" style="display:none;"><?php echo __('Whether to apply on non-singular pages (pages which are not single posts or pages. Like front page and category pages).', 'ed2kls'); ?><ul><li><?php echo __('Tag Attribute', 'ed2kls'); ?>: <code>forall</code></li><li><?php echo __('Available Value(s)', 'ed2kls'); ?>: <ul><li><code>true</code>: <?php echo __('Yes', 'ed2kls'); ?>;</li><li><code>false</code>: <?php echo __('No', 'ed2kls'); ?></li></ul></li></ul></div>
					</td>
				</tr>
			</table>
			<div class="submit elsopt-submitdiv">
				<input class="button-primary" id="elsopt-save" name="elsopt-save" type="submit" value="<?php echo __('Save Options', 'ed2kls'); ?> »">
			</div>
		</form>
<?php } ?>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<div class="submit elsopt-submitdiv">
				<input class="button-primary" id="elsopt-default" name="elsopt-default" type="submit" value="<?php echo __('Restore Default', 'ed2kls'); ?> »">
			</div>
		</form>
<?php if ($condition == 0) { ?>
	</div>

	<div id="elsopt-demo">
		<h3><?php echo __('Demo', 'ed2kls'); ?></h3>
<?php
	global $eD2kLinkSelector;
	$content1 = 'ed2k://|file|eMule0.49c.zip|2868871|0F88EEFA9D8AD3F43DABAC9982D2450C|h=SQ7LUTYUSMDBP2TVE2M7T6VUBLU324KF|/';
	$content2 = 'ed2k://|file|emule0.49c-Xtreme7.2.7z|7124399|071F8D79E5BDC96208079ADE1C2443B0|h=THOGM3WRM4OGNCQHDQBS5JFFAUTQNZEU|/
Browser
ed2k://|file|Firefox%20Setup%20win%203.6%20en-US.exe|8327264|E35EBC236C3F254044CCAD73C81DAB2B|h=DDDKMVPBSYNTCVEU4UXBMSHHSPL34FOP|/';
	global $eD2kLSOption;
	$myatts = $eD2kLSOption->readOptions();
	global $ed2klsnumber;
	$ed2klsnumber = 1;
	echo $eD2kLinkSelector->addHead();
	echo $eD2kLinkSelector->convert2table($content1, $myatts, '1');
	echo '<br />';
	echo $eD2kLinkSelector->convert2table($content2, $myatts, '2');
	echo $eD2kLinkSelector->addFooter();
?>
	</div>
	<div id="elsopt-uninstall-all">
		<h3><?php echo __('Uninstall', 'ed2kls'); ?></h3>
		<p><?php echo __('Click uninstall button to delete the saved settings in the database.', 'ed2kls'); ?></p>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<div class="submit elsopt-submitdiv">
				<input class="button-primary" id="elsopt-uninstall" name="elsopt-uninstall" type="submit" value="<?php echo __('Uninstall', 'ed2kls'); ?> »" onclick="if(!document.getElementById('elsopt-uninstall-chk').checked){return false;}">
				<input type="checkbox" id="elsopt-uninstall-chk" name="elsopt-uninstall-chk" class="elsopt-chkbx" value="true"><label for="elsopt-uninstall-chk" class="elsopt-lb"><?php echo __('Confirm', 'ed2kls'); ?></label>
			</div>
		</form>
	</div>
</div>
<?php } ?>