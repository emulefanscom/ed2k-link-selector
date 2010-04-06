<?php

/*
 Plugin Name:  eD2k Link Selector
 Plugin URI:   http://emule-fans.com/wordpress-ed2k-link-selector/
 Description:  Convert [ed2k] tag to a nice table to display eD2k (eMule) links. 将标签[ed2k]转换为一个显示eD2k链接并带有过滤选择器的表格。
 Version:      1.1.2
 Author:       tomchen1989
 Author URI:   http://emule-fans.com/
 */

/*
 Copyright 2010 tomchen1989/emule-fans.com  (email : emulefanscom@gmail.com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

define('ED2KLS_VERSION', '1.1.2');
define('ED2KLS_URL', WP_PLUGIN_URL . '/ed2k-link-selector');

$ed2klsnumber = 0;

if(!class_exists('eD2kLinkSelector')) {
	class eD2kLinkSelector {

		function eD2kLinkSelector() {
			add_action('init', array(&$this, 'textdomain'));
			add_action('wp_head', array(&$this, 'addHead'));
			add_action('wp_footer', array(&$this, 'addFooter'));
			add_filter('the_content', array(&$this, 'doShortcode'));
		}

		function doShortcode($content) {
			if ( function_exists('remove_shortcode') ) {
				add_shortcode( 'ed2k' , array(&$this, 'shortcodeEd2k') );
				add_shortcode( 'emule' , array(&$this, 'shortcodeEd2k') );
				$content = do_shortcode($content);
				remove_shortcode('ed2k');
				remove_shortcode('emule');
			}
			return $content;
		}

		function textdomain() {
			$locale = get_locale();
			if ( empty($locale) ) {
				$locale = 'en_US';
			}
			//$locale = 'en_US';
			$domain = 'ed2kls';
			load_textdomain ($domain, dirname (__FILE__) . '/lang/' . $domain . '-' . $locale . '.mo');
		}

		function addHead() {
			$cssUrl = constant('ED2KLS_URL') . '/ed2kls.css?v=' . constant('ED2KLS_VERSION');
			if ( file_exists(TEMPLATEPATH . '/ed2kls.css') ) {
				$cssUrl = get_bloginfo('template_url') . '/ed2kls.css';
			}
			echo '
<link rel="stylesheet" type="text/css" href="' . $cssUrl . '" /><!-- eD2k Link Selector CSS -->
';
		}

		function addFooter() {
			global $ed2klsnumber;
			if ($ed2klsnumber >= 1) {
				echo $ed2klsnumber.'
<!-- START of eD2k Link Selector JavaScript -->
<script type="text/javascript" src="' . constant('ED2KLS_URL') . '/ed2klsvar.js.php?v=' . constant('ED2KLS_VERSION') .'"></script>
<script type="text/javascript" src="' . constant('ED2KLS_URL') . '/ed2kls.js?v=' . constant('ED2KLS_VERSION') .'"></script>
<!-- END of eD2k Link Selector JavaScript -->
';
			}
		}

		function formatSize($val) {
			$sep = 100;
			$unit = __('Bytes', 'ed2kls');
			if ($val >= 1099511627776) {
				$val = round($val / (1099511627776 / $sep)) / $sep;
				$unit = __('TB', 'ed2kls');
			} else if ($val >= 1073741824) {
				$val = round($val / (1073741824 / $sep)) / $sep;
				$unit = __('GB', 'ed2kls');
			} else if ($val >= 1048576) {
				$val = round($val / (1048576 / $sep)) / $sep;
				$unit = __('MB', 'ed2kls');
			} else if ($val >= 1024) {
				$val = round($val / (1024 / $sep)) / $sep;
				$unit = __('KB', 'ed2kls');
			}
			return $val . $unit;
		}

		function excerptRepCallback($matches) {
			$url = $matches[0];
			$pieces = explode("|", $matches[2]);
			$name = urldecode($pieces[0]);
			return '<a href="' . $url . '">' . $name . '</a>';
		}

		function shortcodeEd2k( $atts = array(), $content = NULL, $code ) {

			if ( $content === NULL ) {
				return '';
			}

			$myatts = shortcode_atts( array(
			'head' => __('eD2k Links', 'ed2kls'),
			'stat' => 'http://ed2k.shortypower.org/?hash=',
			'name' => 'auto',
			'size' => 'auto',
			'collection' => 'true',
			'format' => '1',
			'forall' => 'false',
			//'lang' => 'zh_CN',//force to use another language
			), $atts );

			$mypermalink = get_permalink();
			$mypermalink = split('://', $mypermalink, 2);
			$mypermalink = $mypermalink[1];

			if ( !is_single() && !is_page() && $myatts['forall'] == 'false' || is_feed() || $myatts['format'] == '2' || $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] != $mypermalink && (is_single() || is_page()) ) {
				$content = preg_replace_callback(
				"/ed2k:\/\/\|(file)\|(.+?)\|\/(?!\|)/i",
				array(&$this, 'excerptRepCallback'),
				$content
				);
				return $content;
			}

			global $ed2klsnumber;
			$ed2klsnumber += 1;

			$myno = strval($ed2klsnumber);
			$sizetot = 0;
			$num = 0;
			$extarray = array();
			$newcontent = '
<form action="' . WP_PLUGIN_URL . '/ed2k-link-selector/emcl.php" method="POST" id="el-s-form-' . $myno . '" onsubmit="return ed2kls.emclChk(\'' . $myno . '\');">
<table class="el-s" id="el-s-' . $myno . '" border="0" cellpadding="0" cellspacing="0">
	<thead>
		<tr><td colspan="2">
			<div class="el-s-titlebtn el-s-toright">
				<a id="el-s-help-' . $myno . '" class="el-s-pseubtn el-s-hlp el-s-toright" title="' . __('Help', 'ed2kls') . '" onclick="ed2kls.help(\'' . $myno . '\',0)">[?]</a><a id="el-s-exd-' . $myno . '" class="el-s-pseubtn el-s-exd el-s-toright" title="' . __('Hide', 'ed2kls') . '" onclick="ed2kls.close(\'' . $myno . '\')">[-]</a>
			</div>
			<strong>' . $myatts['head'] . '</strong><noscript><br /><span style="color:red!important;">' . __('Please enable javascript in your browser to visit this page.', 'ed2kls') . '</span></noscript>
		</td></tr>
	</thead>
	<tfoot>
		<tr class="el-s-infotr"><td colspan="2">
			<div id="el-s-info-' . $myno . '" class="el-s-info" style="display: none;">
				<a id="el-s-info-close-' . $myno . '" class="el-s-pseubtn el-s-info-close el-s-toright" title="' . __('Close help info', 'ed2kls') . '" onclick="ed2kls.closeinfo(\'' . $myno . '\')">[×]</a>
				<div id="el-s-info-desc-' . $myno . '" class="el-s-info-desc">' . __('Help Info:', 'ed2kls') . '</div>
				<div id="el-s-info-content-' . $myno . '" class="el-s-info-content"></div>
			</div>
		</td></tr>
		<tr class="el-s-bottom"><td colspan="2">
			<a id="el-s-help2-' . $myno . '" title="' . __('Help', 'ed2kls') . '" onclick="ed2kls.help(\'' . $myno . '\',0)">' . __('Help', 'ed2kls') . '</a>
			<span class="el-s-sep">|</span>
			<a href="http://www.emule-project.net/" target="_blank" title="' . __('eMule Official', 'ed2kls') . '">' . __('eMule Official', 'ed2kls') . '</a>
			<span class="el-s-sep">|</span>
			<a href="http://emule-fans.com/" target="_blank" title="' . __('eMule-fans.com [eMule Fans Chinese Blog]', 'ed2kls') . '">' . __('eMule-fans.com', 'ed2kls') . '</a>
			<span class="el-s-sep">|</span>
			<a href="http://www.emule-mods.de/" target="_blank" title="' . __('eMule-Mods.de [eMule Mods Site]', 'ed2kls') . '">eMule-Mods.de</a>
			<span class="el-s-sep">|</span>
			<a href="http://emule-fans.com/wordpress-ed2k-link-selector/" target="_blank" title="' . __('Homepage for eD2k Link Selector WordPress Plugin', 'ed2kls') . '">' . __('Plugin Home', 'ed2kls') . '</a>
		</td></tr>
	</tfoot>
	<tbody id="el-s-tb-' . $myno . '">
';

			preg_match_all (
			"/^.+$/m",
			$content,
			$lines
			);

			foreach ($lines[0] as $myline) {
				preg_match (
				"/ed2k:\/\/\|(file)\|(.+?)\|\/(?!\|)/i",
				$myline,
				$matches
				);
				if (count($matches) != 0) {
					$num += 1;
					if ($num%2) {
						$odd = 1;
					} else {
						$odd = 2;
					}
					$url = $matches[0];
					//$type = $matches[1];
					$pieces = explode("|", $matches[2]);
					$name = urldecode($pieces[0]);
					if (strrchr($name, '.')) {
						$ext = strtolower(trim(substr(strrchr($name, '.'), 1, 15)));
						array_push($extarray, $ext);
					}
					$size = $pieces[1];
					$sizetot += $size;
					$size = $this->formatSize($size);
					$hash = $pieces[2];
					$newcontent .= '
<tr class="el-s-tr' . $odd . '">
	<td class="el-s-left">
		<input type="checkbox" class="el-s-chkbx el-s-chkbx-ed2k" name="el-s-chkbx-' . $myno . '[]" id="el-s-chkbx-' . $myno . '-' . $num . '" value="' . $url . '" onclick="ed2kls.checkIt(\'' . $myno . '\',event);" checked="checked" /><a class="el-s-dl" href="' . $url . '" ed2k="' . $url . '">' . $name . '</a>';
					if (strtolower($myatts['stat']) != "false") {
						$newcontent .= '
		<a class="el-s-viewsrc" href="' . $myatts['stat'] . $hash . '" target="_blank">' . __('Stat', 'ed2kls') . '</a>';
					}
					$newcontent .= '
	</td>
	<td class="el-s-right">' . $size . '</td>
</tr>';
				} else {
					$myline = preg_replace (
					"/<p>|<\/p>|<br\s\/>|<br>/i",
					"",
					$myline
					);
					$myline = trim($myline);
					if ($myline !== "") {
						$newcontent .= '
<tr class="el-s-desctr">
	<td colspan="2">
'. $myline .'
	</td>
</tr>';
					}
				}

			}

			$sizetot = $this->formatSize($sizetot);

			$newcontent .= '
		<tr class="el-s-selecttr">
			<td class="el-s-left">
				<span class="el-s-area"><input type="checkbox" class="el-s-chkbx el-s-chkall" id="el-s-chkall-' . $myno . '" onclick="ed2kls.checkAll(\'' . $myno . '\',this.checked)" checked="checked" /><label class="el-s-chkall" for="el-s-chkall-' . $myno . '">' . __('All', 'ed2kls') . '</label></span>';

			if ( (strtolower($myatts['name']) != 'false' && $num >= 2) || strtolower($myatts['name']) == 'true') {
				$newcontent .= '
				<span class="el-s-area el-s-area-label"><label class="el-s-namefilter" for="el-s-namefilter-' . $myno . '">' . __('Name Filter', 'ed2kls') . '</label><a id="el-s-namefilterhelp-' . $myno . '" class="el-s-pseubtn el-s-hlp" title="' . __('Help', 'ed2kls') . '" onclick="ed2kls.help(\'' . $myno . '\',1)">[?]</a>:<input type="text" class="el-s-txt el-s-namefilter" id="el-s-namefilter-' . $myno . '" onkeyup="ed2kls.filter(\'' . $myno . '\')" />';

				$extarray = array_unique($extarray);

				foreach ($extarray as $myext) {
					$newcontent .= '
				<input type="checkbox" value="' . $myext . '" name="el-s-chktype-' . $myno . '[]" class="el-s-chkbx el-s-chktype" id="el-s-chktype-' . $myext . '-' . $myno . '" onclick="ed2kls.typeFilter(\'' . $myno . '\',this.value,this.checked)" /><label class="filter" for="el-s-chktype-' . $myext . '-' . $myno . '">' . strtoupper($myext) . '</label>';
				}

				$newcontent .= '</span>';
			}

			if ( (strtolower($myatts['size']) != 'false' && $num >= 2) || strtolower($myatts['size']) == 'true') {
				$newcontent .= '
				<span class="el-s-area el-s-area-label"><label class="el-s-sizefilter">' . __('Size Filter', 'ed2kls') . '</label><a id="el-s-sizefilterhelp-' . $myno . '" class="el-s-pseubtn el-s-hlp" title="' . __('Help', 'ed2kls') . '" onclick="ed2kls.help(\'' . $myno . '\',2)">[?]</a>:<select id="el-s-sizesymbol-' . $myno . '-1" class="el-s-sel" onchange="ed2kls.filter(\'' . $myno . '\')">
					<option selected="selected" value="1">&gt;</option>
					<option value="2">&lt;</option>
				</select><input type="text" class="el-s-txt el-s-sizefilter" id="el-s-sizefilter-' . $myno . '-1" onkeyup="ed2kls.filter(\'' . $myno . '\')" /><select id="el-s-sizeunit-' . $myno . '-1" class="el-s-sel" onchange="ed2kls.filter(\'' . $myno . '\')">
					<option selected="selected" value="1099511627776">' . __('TB', 'ed2kls') . '</option>
					<option value="1073741824">' . __('GB', 'ed2kls') . '</option>
					<option value="1048576">' . __('MB', 'ed2kls') . '</option>
					<option value="1024">' . __('KB', 'ed2kls') . '</option>
					<option value="1">' . __('Bytes', 'ed2kls') . '</option>
				</select>,<select id="el-s-sizesymbol-' . $myno . '-2" class="el-s-sel" onchange="ed2kls.filter(\'' . $myno . '\')">
					<option selected="selected" value="1">&gt;</option>
					<option value="2">&lt;</option>
				</select><input type="text" class="el-s-txt el-s-sizefilter" id="el-s-sizefilter-' . $myno . '-2" onkeyup="ed2kls.filter(\'' . $myno . '\')" /><select id="el-s-sizeunit-' . $myno . '-2" class="el-s-sel" onchange="ed2kls.filter(\'' . $myno . '\')">
					<option selected="selected" value="1099511627776">' . __('TB', 'ed2kls') . '</option>
					<option value="1073741824">' . __('GB', 'ed2kls') . '</option>
					<option value="1048576">' . __('MB', 'ed2kls') . '</option>
					<option value="1024">' . __('KB', 'ed2kls') . '</option>
					<option value="1">' . __('Bytes', 'ed2kls') . '</option>
				</select></span>';
			}

			$newcontent .= '
			</td>
			<td rowspan="2" class="el-s-right"><span id="el-s-totsize-' . $myno . '">' . $sizetot .'</span><br />(<span id="el-s-totnum-' . $myno . '">' . $num . '</span>' . __('Files', 'ed2kls') . ')</td>
		</tr>
		<tr class="el-s-buttontr">
			<td class="el-s-left">
				<input type="button" id="el-s-download-' . $myno . '" class="el-s-button el-s-download" onclick="ed2kls.download(\'' . $myno . '\')" value="' . __('Download', 'ed2kls') . '" />
				<input type="button" id="el-s-copylinks-' . $myno . '" class="el-s-button el-s-copylinks" onclick="ed2kls.cb.iecopy(2,\'' . $myno . '\')" value="' . __('Copy Links', 'ed2kls') . '" />
				<input type="button" id="el-s-copynames-' . $myno . '" class="el-s-button el-s-copynames" onclick="ed2kls.cb.iecopy(1,\'' . $myno . '\')" value="' . __('Copy Names', 'ed2kls') . '" />';
			if ( strtolower($myatts['collection']) != "false" ) {
				$newcontent .= '
				<input type="hidden" value="' . $myno . '" name="el-s-no"><input type="submit" id="el-s-submit-' . $myno . '" class="el-s-button el-s-emcl" value="' . __('eMuleCollection', 'ed2kls') . '" />';
			}
			$newcontent .= '
				<span class="el-s-copied" id="el-s-copied-' . $myno . '" style="display:none;"><span class="el-s-yes">√</span>' . __('Copyed', 'ed2kls') . '</span>
			</td>
		</tr>
	</tbody>
</table>
</form>
';

			return $newcontent;

		}

	}
}

if(!class_exists('eD2kLSButton')) {
	class eD2kLSButton {

		function eD2kLSButton() {
			add_action('init', array(&$this, 'addTinymce'));
			add_filter('admin_head', array(&$this, 'addQuicktag'));
		}

		function addQuicktag() {
			echo '
<script type="text/javascript">//<![CDATA[
if(edButtons)edButtons[edButtons.length]=new edButton("ed_ed2k","eD2k","[ed2k]\n","\n[/ed2k]","e");
//]]></script>
';
		}

		function addTinymce() {
			if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ){
				return;
			}
			if ( get_user_option('rich_editing') == 'true' ) {
				add_filter('admin_head', array(&$this, 'addTinymceJsVars'));
				add_filter('mce_external_plugins', array(&$this, 'addTinymcePlugin'));
				add_filter('mce_buttons', array(&$this, 'addTinymceButton'));
			}
		}

		function addTinymceJsVars() {
			echo '
<script type="text/javascript">//<![CDATA[
var elsMceVar = {
	title : "' . __('Add eD2k Links', 'ed2kls') . '",
	url : "' . constant('ED2KLS_URL') . '"
};
//]]></script>
';
		}

		function addTinymcePlugin($plugin_array) {
			$plugin_array['ed2kls'] = constant('ED2KLS_URL') . '/tinymce/editor_plugin.js';
			return $plugin_array;
		}

		function addTinymceButton($buttons) {
			array_push($buttons, 'separator', 'ed2kls');
			return $buttons;
		}

	}
}

if(class_exists('eD2kLinkSelector')) {
	$eD2kLinkSelector = new eD2kLinkSelector();
}

if(class_exists('eD2kLSButton')) {
	$eD2kLSButton = new eD2kLSButton();
}

?>