<?php
require(dirname(__FILE__)."/../../../wp-blog-header.php");

header('Cache-Control: max-age=604800, public, must-revalidate');
header('Content-Type: application/javascript');

echo 'var ed2klsPath="' . constant('ED2KLS_URL') . '";
var ed2klsVar = {};
ed2klsVar.retry = "' . __('Loading not finished. Please retry.', 'ed2kls') . '";
ed2klsVar.shk = "' . __('Hide', 'ed2kls') . '";
ed2klsVar.exd = "' . __('Show', 'ed2kls') . '";
ed2klsVar.bytes = "' . __('Bytes', 'ed2kls') . '";
ed2klsVar.tb = "' . __('TB', 'ed2kls') . '";
ed2klsVar.gb = "' . __('GB', 'ed2kls') . '";
ed2klsVar.mb = "' . __('MB', 'ed2kls') . '";
ed2klsVar.kb = "' . __('KB', 'ed2kls') . '";
ed2klsVar.helpinfo = [
"' . __('You can use <a href=\"http://www.emule-project.net/home/perl/general.cgi?l=1&rm=download\">eMule</a> or its mod (see <a href=\"http://www.emule-mods.de/?mods=start\">Mod Page on emule-mods.de</a>) (Windows), <a href=\"http://www.amule.org/\">aMule</a>(Win, Linux, Mac), etc. to download eD2k links. See <a href=\"http://wiki.amule.org/index.php/Ed2k_links_handling\">eD2k Links Handling</a> for help.<br />eMuleCollection files contain a set of links intended to be downloaded. They can be managed by eMule.<br />Click and hold down SHIFT key to toggle multiple checkboxes.<br />Use filters to select.<br />View <a href=\"http://emule-fans.com/wordpress-ed2k-link-selector/\">eD2k Link Selector WordPress plugin HomePage</a> to find this plugin or contact the author.', 'ed2kls') . '",
"' . __('Name Filter helps you select files by their names or extensions. Case insensitive.<br />Symbols Usage:<br />AND: space(<code> </code>), <code>+</code>;<br />NOT: <code>-</code>;<br />OR: <code>|</code>;<br />Escape: two quote marks(<code>\"\"</code>);<br />Match at the start: <code>^</code>;<br />Match at the end: <code>$</code>.<br />e.g.<br /><code>emule|0.49c -exe</code> to select names that contain \"eMule\" and \"0.49c\" but not contain \"exe\";<br /><code>^emule 0.49c$</code> to select names started with \"emule\" and end with \"0.49c\";<br /><code>\"emule 0.49c\"</code> with quote marks to match exactly a \"emule 0.49c\", not a \"eMule fake 0.49c\".', 'ed2kls') . '",
"' . __('Size Filter helps you select files by their sizes.', 'ed2kls') . '"
];';
?>