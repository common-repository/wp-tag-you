(function(window, document, $, undefined){

	window.wpBucket = {};
	
    wpBucket.parsor=function(selection,users_object){
    $.fn.atwho.debug = true
    var emojis = [
      "icon_arrow","icon_biggrin","icon_confused","icon_cool","icon_cry","icon_eek","icon_evil","icon_exclaim","icon_idea","icon_lol","icon_mad","icon_mrgreen","icon_netural","icon_question","icon_razz","icon_redface","icon_rolleyes","icon_sad","icon_smile","icon_surprised","icon_twisted","icon_wink"
    ];

    var names = $.map(users_object.success,function(value,i) {
      return {'id':value.id,'name':value.display_name,'avatar':value.avatar_url};
    });
    var emojis = $.map(emojis, function(value, i) {return {key: value, name:value}});

    var at_params = {
      at: "@",
      data: names,
      headerTpl: '<div class="atwho-header">Member List<small>↑&nbsp;↓&nbsp;</small></div>',
      insertTpl: '@${name}',
      displayTpl: "<li><img src='${avatar}'  height='30' width='30' /> <small>${name}</small></li>",
      limit: 200
    }
    var emoji_params = {
      at: ":",
      data: emojis,
      displayTpl: "<li>${name} <img src='./wp-includes/images/smilies/${key}.gif'  height='20' width='20' /></li>",
      insertTpl: ':${key}:',
      delay: 400
    }

    $editor = $(selection).atwho(at_params); //.atwho(emoji_params)
    $editor.caret('pos', 47);
    $editor.focus().atwho('run');
    emoji_params.insertTpl = "<img src='./wp-includes/images/smilies/${key}.gif'  height='20' width='20' />";
    },
	wpBucket.init = function() {

		// put your custom functions here
		console.log('Wp Bucket loaded');
        /*if(tagyou.settings.classes.length > 0){
          var classes_to_apply=[];
          var classes_arr = tagyou.settings.classes.split(',');
          for (var i = 0; i < classes_arr.length; i++) {
            classes_to_apply.push(classes_arr[i].trim());
          }
        }*/
            
        /*if(classes_to_apply.length <= 0){
          return false;
        }*/
        
        //var commentContainer=$(classes_to_apply.join());
        var commentContainer=$('#comment');
        var relation={
                'action':'fetch_all_user',
            };

            $.post(tagyou.ajaxurl,relation,function(res){
                var users_object=JSON.parse(res);
                 wpBucket.parsor(commentContainer,users_object);
            });

	}

	$(document).on( 'ready', wpBucket.init );

})(window, document, jQuery);