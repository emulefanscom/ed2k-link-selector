tinymce.create("tinymce.plugins.ExamplePlugin",{init:function(e,n){e.addCommand("ed2klsDialog",function(){e.windowManager.open({file:elsMceVar.url+"/tinymce/dialog.php",width:700,height:450,inline:1},{plugin_url:n})}),e.addButton("ed2kls",{title:elsMceVar.title,cmd:"ed2klsDialog",image:n+"/icon.gif"}),e.onNodeChange.add(function(e,n,i){n.setActive("ed2kls","IMG"==i.nodeName)})},createControl:function(e,n){return null},getInfo:function(){return{longname:"eD2k Link Selector",author:"Tom Chen",authorurl:"https://emulefans.com",infourl:"https://emulefans.com/wordpress-ed2k-link-selector/",version:"2.0.0"}}}),tinymce.PluginManager.add("ed2kls",tinymce.plugins.ExamplePlugin);