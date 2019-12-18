(function() {
  tinymce.create("tinymce.plugins.ExamplePlugin", {
    init : function(ed, url) {
      ed.addCommand("ed2klsDialog", function() {
        ed.windowManager.open({
          title: elsMceVar.title,
          body: {
            type: 'textbox',
            name: 'code',
            multiline: true,
            minWidth: 700,
            minHeight: 450,
            spellcheck: false,
            style: 'direction: ltr; text-align: left'
          },
          onSubmit: function (e) {
              ed.insertContent("[ed2k]" + e.data.code + "[/ed2k]");
          }
          });
      });

      ed.addButton("ed2kls", {
        title : elsMceVar.title,
        cmd : "ed2klsDialog",
        image : url + "/icon.gif"
      });
    },

    createControl : function(n, cm) {
      return null;
    }
  });

  tinymce.PluginManager.add("ed2kls", tinymce.plugins.ExamplePlugin);
})();