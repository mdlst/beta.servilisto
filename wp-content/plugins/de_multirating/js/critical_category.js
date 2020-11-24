(function($,Views,Models,AE){
	Views.Critical = Backbone.View.extend({
        el:'.wrapper-critical',
        events:{
            'change .criteria_tax' : 'sync_critical',
            'click a.toggle-button' : 'update_rating'
        },
        initialize: function(options) {
        },
        sync_critical:function(event){

        	target = $(event.currentTarget);
			kq = target.val();
			item = target.parent('.critical_cate').find('input').val();
			console.log(item);
            console.log(kq);
            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'post',
                data: {
                    action: 'sync-critical',
                    tax: item,
                    critical:kq
                }
            })
        },
        update_rating:function(event){
            console.log($('input[name=enable_critical]').val());
            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'post',
                data: {
                    action: 'update_rating',
                    enable_critical : $('input[name=enable_critical]').val()
                }
            })
        }
    })
	$(document).ready(function(){
		$('.chosen').chosen({
            no_results_text: "Oops, nothing found here!",
            max_selected_options : parseInt(de_multirating_globals_critical.max_review_criterias)
        }); 
		new Views.Critical();
	})

})(jQuery, AE.Views, AE.Models, window.AE)