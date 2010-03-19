<?php

/*
Plugin Name:  eD2k Link Selector
Plugin URI:   http://emule-fans.com/wordpress-ed2k-link-selector/
Description:  Convert [ed2k] tag to a nice table to display eD2k (eMule) links. 将标签[ed2k]转换为一个显示eD2k链接并带有过滤选择器的表格。
Version:      1.0.0
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

class eD2kLinkSelector {

	function eD2kLinkSelector() {
		add_action('init', array(&$this, 'textdomain'));
		add_action('wp_head', array(&$this, 'addHeader'));
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

	function addHeader() {
		$cssUrl = WP_PLUGIN_URL . '/ed2k-link-selector/ed2kls.css';
		if ( file_exists(TEMPLATEPATH . '/ed2kls.css') ) {
			$cssUrl = get_bloginfo('template_url') . '/ed2kls.css';
		}
		echo '
<link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />
<script type="text/javascript">
var ed2klsMoviePath="' . WP_PLUGIN_URL . '/ed2k-link-selector/ZeroClipboard.swf";
var ed2klsexd={},ed2klsshk={},ed2klsretry={};
</script>
<script type="text/javascript" src="' . WP_PLUGIN_URL . '/ed2k-link-selector/ZeroClipboard.js"></script>
<script type="text/javascript" src="' . WP_PLUGIN_URL . '/ed2k-link-selector/ed2kls.js"></script>
';
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
		return '<a href="' . $url . '" ed2k="' . $url . '">' . $name . '</a>';
	}

	function shortcodeEd2k( $atts = array(), $content = NULL, $code ) {

		if ( $content === NULL ) {
			return '';
		}

		$myatts = shortcode_atts( array(
			'head' => __('eD2k Links', 'ed2kls'),
			'stat' => 'http://ed2k.shortypower.org/?hash=',
			'name' => 'true',
			'size' => 'true',
			'format' => '1',
			'forall' => 'false',
		//'lang' => 'zh_CN',//force to use another language
		), $atts );

		if ( !is_single() && !is_page() && $myatts['forall'] == 'false' || is_feed() || $myatts['format'] == '2' ) {
			$content = preg_replace_callback(
			"/ed2k:\/\/\|(file)\|(.+)\|\/(?!\|)/i",
			array(&$this, 'excerptRepCallback'),
			$content
			);
			return $content;
		}

		$myno = strval(mt_rand());
		$sizetot = 0;
		$num = 0;
		$extarray = array();
		$newcontent = '
<script type="text/javascript">
ed2klsretry[' . $myno . ']="' . __('Loading not finished. Please retry.', 'ed2kls') . '";
ed2klsshk[' . $myno . ']="' . __('Shrink', 'ed2kls') . '";
ed2klsexd[' . $myno . ']="' . __('Expand', 'ed2kls') . '";
</script>
<table class="el-s" id="el-s-' . $myno . '" border="0" cellpadding="0" cellspacing="0">
	<thead>
		<tr><td colspan="2">
			<div class="el-s-titlebtn el-s-toright">
				<a id="el-s-help-' . $myno . '" class="el-s-pseubtn el-s-hlp el-s-toright" title="' . __('Help', 'ed2kls') . '" onclick="els.help(\'' . $myno . '\',0)">[?]</a><a id="el-s-exd-' . $myno . '" class="el-s-pseubtn el-s-exd el-s-toright" title="' . __('Shrink', 'ed2kls') . '" onclick="els.close(\'' . $myno . '\')">[-]</a>
			</div>
			<strong>' . $myatts['head'] . '</strong><noscript><br /><span style="color:red!important;">' . __('Please enable javascript in your browser to visit this page.', 'ed2kls') . '</span></noscript>
		</td></tr>
	</thead>
	<tfoot>
		<tr class="el-s-infotr"><td colspan="2">
			<div id="el-s-info-' . $myno . '" class="el-s-info" style="display: none;">
				<a id="el-s-info-close-' . $myno . '" class="el-s-pseubtn el-s-info-close el-s-toright" title="' . __('Close help info', 'ed2kls') . '" onclick="els.closeinfo(\'' . $myno . '\')">[×]</a>
				<div id="el-s-info-desc-' . $myno . '" class="el-s-info-desc">' . __('Help Info:', 'ed2kls') . '</div>
				<div class="el-s-info-content">' . __('You can use <a href="http://www.emule-project.net/home/perl/general.cgi?l=1&rm=download">eMule</a> or its mod (see <a href="http://www.emule-mods.de/?mods=start">Mod Page on emule-mods.de</a>) (Windows), <a href="http://www.amule.org/">aMule</a>(Win, Linux, Mac), etc. to download eD2k links.  Click and hold down SHIFT key to toggle multiple checkboxes. Use filters to select. View <a href="http://emule-fans.com/wordpress-ed2k-link-selector/">eD2k Link Selector WordPress plugin HomePage</a> to find this plugin or contact the author.', 'ed2kls') . '</div>
				<div class="el-s-info-content">' . __('Name Filter helps you select files by their names or extensions. Case insensitive. Symbols Usage: AND: space(<code> </code>), <code>+</code>; NOT: <code>-</code>; OR: <code>|</code>; Escape: two quote marks(<code>""</code>); Match at the start: <code>^</code>; Match at the end: <code>$</code>. e.g. <code>emule|0.49c -exe</code> to select names that contain "eMule" and "0.49c" but not contain "exe"; <code>^emule 0.49c$</code> to select names started with "emule" and end with "0.49c"; <code>"emule 0.49c"</code> with quote marks to match exactly a "emule 0.49c", not a "eMule fake 0.49c".', 'ed2kls') . '</div>
				<div class="el-s-info-content">' . __('Size Filter helps you select files by their sizes.', 'ed2kls') . '</div>
			</div>
		</td></tr>
		<tr class="el-s-bottom"><td colspan="2">
			<a id="el-s-help2-' . $myno . '" title="' . __('Help', 'ed2kls') . '" onclick="els.help(\'' . $myno . '\',0)">' . __('Help', 'ed2kls') . '</a>
			<span class="el-s-sep">|</span>
			<a href="' . __('http://www.emule-project.net/', 'ed2kls') . '" target="_blank" title="' . __('eMule Official', 'ed2kls') . '">' . __('eMule Official', 'ed2kls') . '</a>
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
			"/ed2k:\/\/\|(file)\|(.+)\|\/(?!\|)/i",
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
		<input type="checkbox" class="el-s-chkbx" name="el-s-chkbx-' . $myno . '" id="el-s-chkbx-' . $myno . '-' . $num . '" value="' . $url . '" onclick="els.checkIt(\'' . $myno . '\',event);" checked="checked" /><a class="el-s-dl" href="' . $url . '" ed2k="' . $url . '">' . $name . '</a>';
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
				<span class="el-s-area"><input type="checkbox" class="el-s-chkbx el-s-chkall" id="el-s-chkall-' . $myno . '" onclick="els.checkAll(\'' . $myno . '\',this.checked)" checked="checked" /><label class="el-s-chkall" for="el-s-chkall-' . $myno . '">' . __('All', 'ed2kls') . '</label></span>';

		if (strtolower($myatts['name']) == "true") {
			$newcontent .= '
				<span class="el-s-area el-s-area-label"><label class="el-s-namefilter" for="el-s-namefilter-' . $myno . '">' . __('Name Filter', 'ed2kls') . '</label><a id="el-s-namefilterhelp-' . $myno . '" class="el-s-pseubtn el-s-hlp" title="' . __('Help', 'ed2kls') . '" onclick="els.help(\'' . $myno . '\',1)">[?]</a>:<input type="text" class="el-s-txt el-s-namefilter" id="el-s-namefilter-' . $myno . '" onkeyup="els.filter(\'' . $myno . '\')" />';

			$extarray = array_unique($extarray);

			foreach ($extarray as $myext) {
				$newcontent .= '
				<input type="checkbox" value="' . $myext . '" name="el-s-chktype-' . $myno . '" class="el-s-chkbx el-s-chktype" id="el-s-chktype-' . $myext . '-' . $myno . '" onclick="els.typeFilter(\'' . $myno . '\',this.value,this.checked)" /><label class="filter" for="el-s-chktype-' . $myext . '-' . $myno . '">' . strtoupper($myext) . '</label>';
			}

			$newcontent .= '</span>';
		}

		if (strtolower($myatts['size']) == "true") {
			$newcontent .= '
				<span class="el-s-area el-s-area-label"><label class="el-s-sizefilter">' . __('Size Filter', 'ed2kls') . '</label><a id="el-s-sizefilterhelp-' . $myno . '" class="el-s-pseubtn el-s-hlp" title="' . __('Help', 'ed2kls') . '" onclick="els.help(\'' . $myno . '\',2)">[?]</a>:<select id="el-s-sizesymbol-' . $myno . '-1" class="el-s-sel" onchange="els.filter(\'' . $myno . '\')">
					<option selected="selected" value="1">&gt;</option>
					<option value="2">&lt;</option>
				</select><input type="text" class="el-s-txt el-s-sizefilter" id="el-s-sizefilter-' . $myno . '-1" onkeyup="els.filter(\'' . $myno . '\')" /><select id="el-s-sizeunit-' . $myno . '-1" class="el-s-sel" onchange="els.filter(\'' . $myno . '\')">
					<option selected="selected" value="1099511627776">' . __('TB', 'ed2kls') . '</option>
					<option value="1073741824">' . __('GB', 'ed2kls') . '</option>
					<option value="1048576">' . __('MB', 'ed2kls') . '</option>
					<option value="1024">' . __('KB', 'ed2kls') . '</option>
					<option value="1">' . __('Bytes', 'ed2kls') . '</option>
				</select>,<select id="el-s-sizesymbol-' . $myno . '-2" class="el-s-sel" onchange="els.filter(\'' . $myno . '\')">
					<option selected="selected" value="1">&gt;</option>
					<option value="2">&lt;</option>
				</select><input type="text" class="el-s-txt el-s-sizefilter" id="el-s-sizefilter-' . $myno . '-2" onkeyup="els.filter(\'' . $myno . '\')" /><select id="el-s-sizeunit-' . $myno . '-2" class="el-s-sel" onchange="els.filter(\'' . $myno . '\')">
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
				<div id="el-s-download-' . $myno . '" class="el-s-button el-s-download" onclick="els.download(\'' . $myno . '\')">' . __('Download Selected', 'ed2kls') . '</div><div id="el-s-copynames-' . $myno . '" class="el-s-button el-s-copynames" onclick="els.cb.iecopy(1,\'' . $myno . '\')">' . __('Copy Selected Names', 'ed2kls') . '</div><div id="el-s-copylinks-' . $myno . '" class="el-s-button el-s-copylinks" onclick="els.cb.iecopy(2,\'' . $myno . '\')">' . __('Copy Selected Links', 'ed2kls') . '</div><span class="el-s-copied" id="el-s-copied-' . $myno . '" style="display:none;"><span class="el-s-yes">√</span>' . __('Copyed', 'ed2kls') . '</span>
			</td>
		</tr>
	</tbody>
</table>
';

		return $newcontent;

	}

}

if(class_exists('eD2kLinkSelector')) {
	$eD2kLinkSelector = new eD2kLinkSelector();
}

?>