(function() {
	tinymce.create("tinymce.plugins.ExamplePlugin", {
		init : function(ed, url) {
			ed.addCommand("ed2klsDialog", function() {
				ed.windowManager.open({
					file : elsMceVar.url + "/tinymce/dialog.php",
					width : 700,
					height : 450,
					inline : 1
				}, {
					plugin_url : url,
				});
			});

			ed.addButton("ed2kls", {
				title : elsMceVar.title,
				cmd : "ed2klsDialog",
				image : url + "/icon.gif"
			});

			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive("ed2kls", n.nodeName == "IMG");
			});
		},

		createControl : function(n, cm) {
			return null;
		},

		getInfo : function() {
			return {
				longname : "eD2k Link Selector",
				author : "tomchen1989",
				authorurl : "http://emulefans.com",
				infourl : "http://emulefans.com/wordpress-ed2k-link-selector/",
				version : "1.0.0"
			};
		}
	});

	tinymce.PluginManager.add("ed2kls", tinymce.plugins.ExamplePlugin);
})();