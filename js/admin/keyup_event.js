jQuery(document).ready(function($) {

    // Create 'keyup_event' tinymce plugin
    tinymce.PluginManager.add('keyup_event', function(editor, url) {

        // Create keyup event
        editor.on('keyup', function(e) {
            $(this).addClass('active');
            var inp = String.fromCharCode(e.keyCode);
            if (!/[a-zA-Z0-9-_ ]/.test(inp)){
            return false;
            }


            // Get the editor content (html)
            get_ed_content = tinymce.activeEditor.getContent();
            // Do stuff here... (run get_editor_content() function)
            get_editor_content(get_ed_content);
        });
    });

    // This is needed for running the keyup event in the text (HTML) view of the editor
    $('#content').on('keyup', function(e) {
    
        var inp = String.fromCharCode(e.keyCode);
            if (!/[a-zA-Z0-9-_ ]/.test(inp)){
            return false;
            }
        // Get the editor content (html)
        get_ed_content = tinymce.activeEditor.getContent();
        // Do stuff here... (run get_editor_content() function)
        get_editor_content(get_ed_content);
    });

    // This function allows the script to run from both locations (visual and text)
    function get_editor_content(content) {
        var post_ID=$('#post_ID').val();
        
        var content2= strip(content);
    if (content.indexOf('@') > -1)
    {
        var afterAt = content2.substr(content.indexOf("@") + 1);
        if(afterAt.length>2){

            var relation={
                'action':'fetch_tagged_user',
                'user_name': afterAt,
                'post_id' : post_ID
            };

            $.post(ajaxurl,relation,function(res){

            });
        }
        
    }
        
    }

    function strip(html)
{
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}
});