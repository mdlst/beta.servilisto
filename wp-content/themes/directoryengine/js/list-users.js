(function($, AE, Views, Models, Collections) {
	$(document).ready(function(){
		UserListItem = Views.PostItem.extend({
		 	tagName: 'li',
		 	className: 'user-list-item',
		 	template: _.template($('#de-user-item').html()),
		 	onItemBeforeRender: function() {
		 		// before render view
		 	},
		 	onItemRendered: function() {
		 		// after render view
		 	}
		});
		/* list view control user list*/
		 
		ListUsers = Views.ListPost.extend({
		 	tagName: 'ul',
		 	itemView: UserListItem,
		 	itemClass: 'user-list-item'
		});
	 	if( $('.list-user-page-info').length > 0 ){
	 		$('.list-user-page-info').each(function() {
		 		if( $(this).find('.userdata').length > 0 ){
		 			var userdata   = JSON.parse($(this).find('.userdata').html()),
		 				collection = new Collections.Users(userdata);
		 		} else {
		 			collection = new Collections.Users();
		 		}
		 		new ListUsers({
		 			el : $(this),	
		 			collection : collection ,
		 			itemView: UserListItem,
		 			itemClass: 'user-list-item'
		 		});
		 		/**
		 		 * init block control list blog
		 		 */
		 		new Views.BlockControl({
		 			collection: collection,
		 			el: $('.list-user-page-wrapper')
		 		});
		 	});
	 	}
	});

})(jQuery, window.AE, window.AE.Views, window.AE.Models, window.AE.Collections);	