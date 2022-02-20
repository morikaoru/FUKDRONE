(function ($, CodeMirror) {
  'use strict';

  $(document).ready(function(){
    var targetId = "single_custom_css",
        options = {
          lineNumbers: true,
          mode: "css",
          tabSize: 2,
        };
    if ( $('.wpacc_editor_dark').length > 0 )
      options.theme = "tomorrow-night-bright";
    if ( $('#main_custom_css').length > 0 )
      targetId = "main_custom_css";
    var cssCodeMirror = CodeMirror.fromTextArea(document.getElementById(targetId), options);
  });

})(jQuery, CodeMirror);
