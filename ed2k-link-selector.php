<?php

/*
Plugin Name:  eD2k Link Selector
Plugin URI:   http://emule-fans.com/wordpress-ed2k-link-selector/
Description:  Convert [ed2k] tag to a nice table to display eD2k (eMule) links. 将标签[ed2k]转换为一个显示eD2k链接并带有过滤选择器的表格。
Version:      1.1.6
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

define('ED2KLS_VERSION', '116');
define('ED2KLS_URL', WP_PLUGIN_URL . '/ed2k-link-selector');
define('ED2KLS_DBVERSION', '3');

$ed2klsnumber = 0;
$ed2klspage = 0;

if(!class_exists('eD2kLinkSelector')) {
	class eD2kLinkSelector {

		function eD2kLinkSelector() {
			add_action('init', array(&$this, 'textdomain'));
			add_action('wp_head', array(&$this, 'addHead'));
			add_action('wp_footer', array(&$this, 'addFooter'));
			add_filter('the_content', array(&$this, 'doShortcode'));
			add_filter('comment_text', array(&$this, 'doShortcodeCmt'));
		}

		function doShortcode($content) {
			if ( function_exists('add_shortcode') ) {
				add_shortcode( 'ed2k' , array(&$this, 'shortcodeEd2k') );
				add_shortcode( 'emule' , array(&$this, 'shortcodeEd2k') );
				$content = do_shortcode($content);
				remove_shortcode('ed2k');
				remove_shortcode('emule');
			}
			return $content;
		}

		function doShortcodeCmt($content) {
			if ( function_exists('add_shortcode') ) {
				add_shortcode( 'ed2k' , array(&$this, 'shortcodeEd2kCmt') );
				add_shortcode( 'emule' , array(&$this, 'shortcodeEd2kCmt') );
				$content = do_shortcode($content);
				remove_shortcode('ed2k');
				remove_shortcode('emule');
			}
			return $content;
		}

		function textdomain($locale = null) {
			if ( empty($locale) ) {
				global $eD2kLSOption;
				$option = $eD2kLSOption->readOptions();
				if ( !empty($option['lang']) ) {
					$locale = $option['lang'];
				} else {
					$locale = 'en_US';
				}
			}
			//$locale = 'en_US';
			$domain = 'ed2kls';
			load_textdomain ($domain, dirname (__FILE__) . '/lang/' . $domain . '-' . $locale . '.mo');
		}

		function addHead() {
			global $ed2klspage;
			$ed2klspage = 1;
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
				echo '
<!-- START of eD2k Link Selector -->
<div style="display:none;">
<div id="el-s-info-content-str-0" class="el-s-info-content-str">
' . __('You can use <a href="http://www.emule-project.net/home/perl/general.cgi?l=1&rm=download">eMule</a> or its mod (see <a href="http://www.emule-mods.de/?mods=start">Mod Page on emule-mods.de</a>) (Windows), <a href="http://www.amule.org/">aMule</a>(Win, Linux, Mac), etc. to download eD2k links. See <a href="http://wiki.amule.org/index.php/Ed2k_links_handling">eD2k Links Handling</a> for help.<br />eMuleCollection files contain a set of links intended to be downloaded. They can be managed by eMule.<br />Click and hold down SHIFT key to toggle multiple checkboxes.<br />Use filters to select.<br />View <a href="http://emule-fans.com/wordpress-ed2k-link-selector/">eD2k Link Selector WordPress plugin HomePage</a> to find this plugin or contact the author.', 'ed2kls') . '
</div>
<div id="el-s-info-content-str-1" class="el-s-info-content-str">
' . __('Name Filter helps you select files by their names or extensions. Case insensitive.<br />Symbols Usage:<br />AND: space(<code> </code>), <code>+</code>;<br />NOT: <code>-</code>;<br />OR: <code>|</code>;<br />Escape: two quote marks(<code>""</code>);<br />Match at the start: <code>^</code>;<br />Match at the end: <code>$</code>.<br />e.g.<br /><code>emule|0.49c -exe</code> to select names that contain "eMule" and "0.49c" but not contain "exe";<br /><code>^emule 0.49c$</code> to select names started with "emule" and end with "0.49c";<br /><code>"emule 0.49c"</code> with quote marks to match exactly a "emule 0.49c", not a "eMule fake 0.49c".', 'ed2kls') . '
</div>
<div id="el-s-info-content-str-2" class="el-s-info-content-str">
' . __('Size Filter helps you select files by their sizes.', 'ed2kls') . '
</div>
</div>
<script type="text/javascript">//<![CDATA[
var ed2klsPath="' . constant('ED2KLS_URL') . '";
var ed2klsVar = {};
ed2klsVar.retry = "' . __('Loading not finished. Please retry.', 'ed2kls') . '";
ed2klsVar.shk = "' . __('Hide', 'ed2kls') . '";
ed2klsVar.exd = "' . __('Show', 'ed2kls') . '";
ed2klsVar.bytes = "' . __('Bytes', 'ed2kls') . '";
ed2klsVar.tb = "' . __('TB', 'ed2kls') . '";
ed2klsVar.gb = "' . __('GB', 'ed2kls') . '";
ed2klsVar.mb = "' . __('MB', 'ed2kls') . '";
ed2klsVar.kb = "' . __('KB', 'ed2kls') . '";
//]]></script>
<script type="text/javascript" src="' . constant('ED2KLS_URL') . '/ed2kls.js?v=' . constant('ED2KLS_VERSION') .'"></script>
<!-- END of eD2k Link Selector -->
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

		function shortcodeEd2k( $atts = array(), $content = NULL, $code = 'ed2k' ) {

			if ( $content === NULL ) {
				return '';
			}

			global $eD2kLSOption, $ed2klspage;
			$option = $eD2kLSOption->readOptions();
			$myatts = shortcode_atts( $option, $atts );

			if ( !is_singular() && $myatts['forall'] == 'false' || $myatts['format'] == '2' || $ed2klspage === 0 ) {
				return $this->convert2anchor($content);
			}

			global $ed2klsnumber;
			$ed2klsnumber += 1;

			$myno = strval($ed2klsnumber);

			return $this->convert2table($content, $myatts, $myno);

		}

		function shortcodeEd2kCmt( $atts = array(), $content = NULL, $code = 'ed2k' ) {
			if ( $content === NULL ) {
				return '';
			}
			return $this->convert2anchor($content);
		}

		function convert2anchor( $content ) {
			$newcontent = '';

			$content = preg_replace (
			"/(?<!ed2k=)(?<!ed2k=[\"\'])(?<!href=)(?<!href=[\"\'])ed2k:\/\/\|file\|.+?\|\/(?!\|)/i",
			"\n\\0\n",
			$content
			);

			preg_match_all (
			"/^.+$/m",
			$content,
			$lines
			);

			foreach ($lines[0] as $myline) {
				preg_match (
				"/(?<!ed2k=)(?<!ed2k=[\"\'])(?<!href=)(?<!href=[\"\'])ed2k:\/\/\|(file)\|(.+?)\|\/(?!\|)/i",
				$myline,
				$matches
				);
				if (count($matches) != 0) {
					$url = $matches[0];
					$pieces = explode("|", $matches[2]);
					$name = urldecode($pieces[0]);
					$newcontent .= '<span class="el-s-linkline">ed2k: <a class="el-s-link" href="' . $url . '">' . $name . '</a></span><br />
';
				} else {
					$myline = preg_replace (
					"/<p>|<\/p>|<br\s\/>|<br\/>|<br>/i",
					"",
					$myline
					);
					$myline = trim($myline);
					if ($myline !== "") {
						$newcontent .= '<span class="el-s-desc">' . $myline . '</span><br />
';
					}
				}
			}

			return $newcontent;
		}

		function convert2table($content, $atts, $no) {

			$sizetot = 0;
			$num = 0;
			$extarray = array();
			$newcontent = '';
			if ( strtolower($atts['collection']) != "false" ) {
				$newcontent .= '
<form action="' . WP_PLUGIN_URL . '/ed2k-link-selector/emcl.php" method="POST" id="el-s-form-' . $no . '" onsubmit="return ed2kls.emclChk(\'' . $no . '\');">';
			}
			$newcontent .= '
<table class="el-s';
			if ( $atts['buttonstyle'] == 1 ) {
				$newcontent .= ' el-s-buttonimg';
			} else if ( $atts['buttonstyle'] == 2 ) {
				$newcontent .= ' el-s-buttonimg el-s-buttonimg2';
			}
			$newcontent .= '" id="el-s-' . $no . '" border="0" cellpadding="0" cellspacing="0" style="width:' . $atts['width'] . ';font-size:' . $atts['fontsize'] . ';">
	<thead class="el-s-thead">
		<tr><td colspan="2">
			<div class="el-s-titlebtn el-s-toright">
				<a id="el-s-help-' . $no . '" class="el-s-pseubtn el-s-hlp el-s-toright" title="' . __('Help', 'ed2kls') . '" onclick="ed2kls.help(\'' . $no . '\',0)">[?]</a><a id="el-s-exd-' . $no . '" class="el-s-pseubtn el-s-exd el-s-toright" title="' . __('Hide', 'ed2kls') . '" onclick="ed2kls.close(\'' . $no . '\')">[-]</a>
			</div>
			<strong>' . $atts['head'] . '</strong><noscript><br /><span style="color:red!important;">' . __('Please enable javascript in your browser to visit this page.', 'ed2kls') . '</span></noscript>
		</td></tr>
	</thead>
	<tfoot>
		<tr class="el-s-infotr"><td colspan="2">
			<div id="el-s-info-' . $no . '" class="el-s-info" style="display: none;">
				<a id="el-s-info-close-' . $no . '" class="el-s-pseubtn el-s-info-close el-s-toright" title="' . __('Close help info', 'ed2kls') . '" onclick="ed2kls.closeinfo(\'' . $no . '\')">[×]</a>
				<div id="el-s-info-desc-' . $no . '" class="el-s-info-desc">' . __('Help Info:', 'ed2kls') . '</div>
				<div id="el-s-info-content-' . $no . '" class="el-s-info-content"></div>
			</div>
		</td></tr>
		<tr class="el-s-bottom"><td colspan="2">
			<a id="el-s-help2-' . $no . '" title="' . __('Help', 'ed2kls') . '" onclick="ed2kls.help(\'' . $no . '\',0)">' . __('Help', 'ed2kls') . '</a>
			<span class="el-s-sep">|</span>
			<a href="http://www.emule-project.net/" target="_blank" title="' . __('eMule Official Site', 'ed2kls') . '">' . __('eMule Official', 'ed2kls') . '</a>
			<span class="el-s-sep">|</span>
			<a href="http://emule-fans.com/" target="_blank" title="' . __('eMule-fans.com [eMule Fans Chinese Blog]', 'ed2kls') . '">' . __('eMule-fans.com', 'ed2kls') . '</a>
			<span class="el-s-sep">|</span>
			<a href="http://www.emule-mods.de/" target="_blank" title="' . __('eMule-Mods.de [eMule Mods Site]', 'ed2kls') . '">eMule-Mods.de</a>
			<span class="el-s-sep">|</span>
			<a href="http://emule-fans.com/wordpress-ed2k-link-selector/" target="_blank" title="' . __('Homepage for eD2k Link Selector WordPress Plugin', 'ed2kls') . '">' . __('Plugin Home', 'ed2kls') . '</a>
		</td></tr>
	</tfoot>
	<tbody id="el-s-tb-' . $no . '">
';

			$content = preg_replace (
			"/(?<!ed2k=)(?<!ed2k=[\"\'])(?<!href=)(?<!href=[\"\'])ed2k:\/\/\|file\|.+?\|\/(?!\|)/i",
			"\n\\0\n",
			$content
			);

			preg_match_all (
			"/^.+$/m",
			$content,
			$lines
			);

			foreach ($lines[0] as $myline) {
				preg_match (
				"/(?<!ed2k=)(?<!ed2k=[\"\'])(?<!href=)(?<!href=[\"\'])ed2k:\/\/\|(file)\|(.+?)\|\/(?!\|)/i",
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
		<input type="checkbox" class="el-s-chkbx el-s-chkbx-ed2k" name="el-s-chkbx-' . $no . '[]" id="el-s-chkbx-' . $no . '-' . $num . '" value="' . $url . '" onclick="ed2kls.checkIt(\'' . $no . '\',event);" checked="checked" /><a class="el-s-dl" href="' . $url . '" ed2k="' . $url . '">' . $name . '</a>';
					if (strtolower($atts['stat']) != "false") {
						$newcontent .= '
		<a class="el-s-viewsrc" href="' . $atts['stat'] . $hash . '" target="_blank">' . __('Stat', 'ed2kls') . '</a>';
					}
					$newcontent .= '
	</td>
	<td class="el-s-right">' . $size . '</td>
</tr>';
				} else {
					$myline = preg_replace (
					"/<p>|<\/p>|<br\s\/>|<br\/>|<br>/i",
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
				<span class="el-s-area"><input type="checkbox" class="el-s-chkbx el-s-chkall" id="el-s-chkall-' . $no . '" onclick="ed2kls.checkAll(\'' . $no . '\',this.checked)" checked="checked" /><label class="el-s-chkall" for="el-s-chkall-' . $no . '">' . __('All', 'ed2kls') . '</label></span>';

			if ( (strtolower($atts['name']) != 'false' && $num >= 2) || strtolower($atts['name']) == 'true') {
				$newcontent .= '
				<span class="el-s-area el-s-area-label"><label class="el-s-namefilter" for="el-s-namefilter-' . $no . '">' . __('Name Filter', 'ed2kls') . '</label><a id="el-s-namefilterhelp-' . $no . '" class="el-s-pseubtn el-s-hlp" title="' . __('Help', 'ed2kls') . '" onclick="ed2kls.help(\'' . $no . '\',1)">[?]</a>:<input type="text" class="el-s-txt el-s-namefilter" id="el-s-namefilter-' . $no . '" onkeyup="ed2kls.filter(\'' . $no . '\')" />';

				$extarray = array_unique($extarray);

				foreach ($extarray as $myext) {
					$newcontent .= '
				<input type="checkbox" value="' . $myext . '" name="el-s-chktype-' . $no . '[]" class="el-s-chkbx el-s-chktype" id="el-s-chktype-' . $myext . '-' . $no . '" onclick="ed2kls.typeFilter(\'' . $no . '\',this.value,this.checked)" /><label class="el-s-filter" for="el-s-chktype-' . $myext . '-' . $no . '">' . strtoupper($myext) . '</label>';
				}

				$newcontent .= '</span>';
			}

			if ( (strtolower($atts['size']) != 'false' && $num >= 2) || strtolower($atts['size']) == 'true') {
				$newcontent .= '
				<span class="el-s-area el-s-area-label"><label class="el-s-sizefilter">' . __('Size Filter', 'ed2kls') . '</label><a id="el-s-sizefilterhelp-' . $no . '" class="el-s-pseubtn el-s-hlp" title="' . __('Help', 'ed2kls') . '" onclick="ed2kls.help(\'' . $no . '\',2)">[?]</a>:<select id="el-s-sizesymbol-' . $no . '-1" class="el-s-sel" onchange="ed2kls.filter(\'' . $no . '\')">
					<option selected="selected" value="1">&gt;</option>
					<option value="2">&lt;</option>
				</select><input type="text" class="el-s-txt el-s-sizefilter" id="el-s-sizefilter-' . $no . '-1" onkeyup="ed2kls.filter(\'' . $no . '\')" /><select id="el-s-sizeunit-' . $no . '-1" class="el-s-sel" onchange="ed2kls.filter(\'' . $no . '\')">
					<option selected="selected" value="1099511627776">' . __('TB', 'ed2kls') . '</option>
					<option value="1073741824">' . __('GB', 'ed2kls') . '</option>
					<option value="1048576">' . __('MB', 'ed2kls') . '</option>
					<option value="1024">' . __('KB', 'ed2kls') . '</option>
					<option value="1">' . __('Bytes', 'ed2kls') . '</option>
				</select>,<select id="el-s-sizesymbol-' . $no . '-2" class="el-s-sel" onchange="ed2kls.filter(\'' . $no . '\')">
					<option selected="selected" value="1">&gt;</option>
					<option value="2">&lt;</option>
				</select><input type="text" class="el-s-txt el-s-sizefilter" id="el-s-sizefilter-' . $no . '-2" onkeyup="ed2kls.filter(\'' . $no . '\')" /><select id="el-s-sizeunit-' . $no . '-2" class="el-s-sel" onchange="ed2kls.filter(\'' . $no . '\')">
					<option selected="selected" value="1099511627776">' . __('TB', 'ed2kls') . '</option>
					<option value="1073741824">' . __('GB', 'ed2kls') . '</option>
					<option value="1048576">' . __('MB', 'ed2kls') . '</option>
					<option value="1024">' . __('KB', 'ed2kls') . '</option>
					<option value="1">' . __('Bytes', 'ed2kls') . '</option>
				</select></span>';
			}

			$newcontent .= '
			</td>
			<td rowspan="2" class="el-s-right"><span id="el-s-totsize-' . $no . '">' . $sizetot .'</span><br />(<span id="el-s-totnum-' . $no . '">' . $num . '</span>' . __('Files', 'ed2kls') . ')</td>
		</tr>
		<tr class="el-s-buttontr">
			<td class="el-s-left">
				<input type="button" id="el-s-download-' . $no . '" class="el-s-button el-s-download" onclick="ed2kls.download(\'' . $no . '\')" title="' . __('Download', 'ed2kls') . '" value="' . __('Download', 'ed2kls') . '" />
				<input type="button" id="el-s-copylinks-' . $no . '" class="el-s-button el-s-copylinks" onclick="ed2kls.cb.iecopy(2,\'' . $no . '\')" title="' . __('Copy Links', 'ed2kls') . '" value="' . __('Copy Links', 'ed2kls') . '" />
				<input type="button" id="el-s-copynames-' . $no . '" class="el-s-button el-s-copynames" onclick="ed2kls.cb.iecopy(1,\'' . $no . '\')" title="' . __('Copy Names', 'ed2kls') . '" value="' . __('Copy Names', 'ed2kls') . '" />';
			if ( strtolower($atts['collection']) != "false" ) {
				$newcontent .= '
				<input type="hidden" value="' . $no . '" name="el-s-no"><input type="submit" id="el-s-submit-' . $no . '" class="el-s-button el-s-emcl" title="' . __('eMuleCollection', 'ed2kls') . '" value="' . __('eMuleCollection', 'ed2kls') . '" />';
			}
			$newcontent .= '
				<span class="el-s-copied" id="el-s-copied-' . $no . '" style="display:none;"><span class="el-s-yes">√</span>' . __('Copyed', 'ed2kls') . '</span>
			</td>
		</tr>
	</tbody>
</table>';
			if ( strtolower($atts['collection']) != "false" ) {
				$newcontent .= '
</form>';
			}
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
if(typeof edButtons!=="undefined")edButtons[edButtons.length]=new edButton("ed_ed2k","eD2k","[ed2k]\n","\n[/ed2k]","e");
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

if(!class_exists('eD2kLSOption')) {
	class eD2kLSOption {

		function eD2kLSOption() {
			add_action('init', array(&$this, 'updateOptions'), 9);
			register_activation_hook(__FILE__, array(&$this, 'optionInit'));
			register_deactivation_hook(__FILE__, array(&$this, 'deactive'));
			add_action('admin_menu', array(&$this, 'addOptionPage'));
			add_filter('plugin_action_links', array(&$this, 'addLinks'), 10, 2);
		}

		function addOptionPage() {
			if (function_exists('add_options_page')) {
				add_options_page(__('eD2k Link Selector Options', 'ed2kls'), __('eD2k Link Selector', 'ed2kls'), 'manage_options', 'ed2k-link-selector/options.php');
			}
		}

		function readOptions() {
			$options = get_option('ed2kls_options');
			if (empty($options) || $options['dbversion'] != constant('ED2KLS_DBVERSION')) {
				$options = $this->defaultOptions();
			}
			return $options;
		}

		function optionInit() {
			$options = get_option('ed2kls_options');
			if (empty($options)) {
				$options = $this->setDefaultOptions();
			} elseif ($options['dbversion'] != constant('ED2KLS_DBVERSION')) {
				$options = $this->mergeOptions();
			}
			return $options;
		}

		function defaultOptions() {
			$defOptions = array();
			$defOptions['dbversion'] = constant('ED2KLS_DBVERSION');
			$defOptions['lang'] = get_locale();
			$defOptions['head'] = __('eD2k Links', 'ed2kls');
			$defOptions['stat'] = 'http://ed2k.shortypower.org/?hash=';
			$defOptions['name'] = 'auto';
			$defOptions['size'] = 'auto';
			$defOptions['collection'] = 'true';
			$defOptions['width'] = '100%';
			$defOptions['fontsize'] = '13px';
			$defOptions['buttonstyle'] = '0';
			$defOptions['format'] = '1';
			$defOptions['forall'] = 'false';
			return $defOptions;
		}

		function mergeOptions() {
			load_plugin_textdomain('ed2kls', false, 'ed2k-link-selector/lang');
			$newOptions = $this->defaultOptions();
			$oldOptions = get_option('ed2kls_options');
			if (empty($oldOptions)) {
				add_option('ed2kls_options', $newOptions);
			} else {
				foreach ($newOptions as $name => $myNewOption) {
					$myOldOption = $oldOptions[$name];
					if (!empty($myOldOption)) {
						$newOptions[$name] = $myOldOption;
					}
				}
				update_option('ed2kls_options', $newOptions);
			}
			return $newOptions;
		}

		function setDefaultOptions() {
			load_plugin_textdomain('ed2kls', false, 'ed2k-link-selector/lang');
			$newOptions = $this->defaultOptions();
			if (get_option('ed2kls_options') === false) {
				add_option('ed2kls_options', $newOptions);
			} else {
				update_option('ed2kls_options', $newOptions);
			}
			return $newOptions;
		}

		function updateOptions() {
			if ( isset($_POST['elsopt-save']) ) {
				$newOptions = array();
				$defOptions = $this->defaultOptions();
				$newOptions['dbversion'] = constant('ED2KLS_DBVERSION');
				$newOptions['lang'] = $_POST['elsopt-lang'];
				$newOptions['head'] = trim($_POST['elsopt-head']) ? trim($_POST['elsopt-head']) : $defOptions['head'];
				if ( $_POST['elsopt-stat-if'] == 'true') {
					$newOptions['stat'] = trim($_POST['elsopt-stat']) ? trim($_POST['elsopt-stat']) : $defOptions['stat'];
				} else {
					$newOptions['stat'] = 'false';
				}
				$newOptions['name'] = $_POST['elsopt-name'];
				$newOptions['size'] = $_POST['elsopt-size'];
				$newOptions['collection'] = $_POST['elsopt-collection'];
				$newOptions['width'] = $_POST['elsopt-width'] ? $_POST['elsopt-width'] : $defOptions['width'];
				$newOptions['fontsize'] = $_POST['elsopt-fontsize'] ? $_POST['elsopt-fontsize'] : $defOptions['fontsize'];
				$newOptions['format'] = $_POST['elsopt-format'];
				$newOptions['buttonstyle'] = $_POST['elsopt-buttonstyle'];
				$newOptions['forall'] = $_POST['elsopt-forall'];
				update_option('ed2kls_options', $newOptions);
			}
			if ( isset($_POST['elsopt-default']) ) {
				$this->setDefaultOptions();
			}
		}

		function deactive() {
			if ( isset($_GET['elsdeldata']) && $_GET['elsdeldata'] = 'yes' ) {
				delete_option('ed2kls_options');
			}
		}

		function addLinks($links, $file) {
			if ( $file == 'ed2k-link-selector/ed2k-link-selector.php' ) {
				$deactivateUrl = 'plugins.php?action=deactivate&amp;plugin=ed2k-link-selector/ed2k-link-selector.php';
				if (function_exists('wp_nonce_url')) { 
					$deactivateUrl = wp_nonce_url($deactivateUrl, 'deactivate-plugin_ed2k-link-selector/ed2k-link-selector.php');
				}
				$links[] = '<a href="' . $deactivateUrl . '&elsdeldata=yes" title="' . __('Disable and delete the saved settings in the database', 'ed2kls') . '">' . __('Disable Cpltly', 'ed2kls') . '</a>';
				$links[] = '<a href="options-general.php?page=ed2k-link-selector/options.php">' . __('Settings', 'ed2kls') . '</a>';
			}
			return $links;
		}

	}
}

if(class_exists('eD2kLinkSelector')) {
	$eD2kLinkSelector = new eD2kLinkSelector();
}

if(class_exists('eD2kLSOption')) {
	$eD2kLSOption = new eD2kLSOption();
}

if(class_exists('eD2kLSButton')) {
	$eD2kLSButton = new eD2kLSButton();
}

?>