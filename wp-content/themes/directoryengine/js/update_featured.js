 (function($, Views, Models, AE) {
 	/**
 	 * Button in backend control view to update et_featured meta key
 	 */
 	Views.Backend_UpdateFeatured = Backbone.View.extend({
 		// el: '.backend-button-wrapper',
 		events: {
 			'click .backend-action-button': 'onclickButton'
 		},
 		initialize: function() {
 			var view = this;
 			view.actions = view.$el.find('.backend-action-button').attr('data-action');
 			view.blockUi = new AE.Views.BlockUi();
 		},
 		onclickButton: function() {
 			//call a function when click to this button
 			var view = this;
 			if (view.actions === 'de_update_et_featured_key') {
 				var data = {
 					action: 'de_get_list_to_update_et_fetured',
 				};
 				$.ajax({
 					type: 'get',
 					url: ae_globals.ajaxURL,
 					data: data,
 					beforeSend: function() {
 						view.blockUi.block(view.$el.find('button'));
 					},
 					success: function(resp) {
 						var data = {
 							action: 'de_update_et_fetured',
 							content: resp.data
 						};
 						$.ajax({
 							type: 'get',
 							url: ae_globals.ajaxURL,
 							data: data,
 							beforeSend: function() {},
 							success: function(resp) {
 								view.$el.find('.backend-button-notification').html(resp.msg).addClass('success');
 								view.blockUi.unblock();
 							}
 						});
 					}
 				});
 			}
 		}
 	});

 	/**
 	 * control backend Button view in backend to upgrade rating_score
 	 */
 	Views.Backend_UpdateRatingScore = Backbone.View.extend({
 		// el: '.backend-button-wrapper',
 		events: {
 			'click .backend-action-button': 'onclickButton'
 		},
 		initialize: function() {
 			var view = this;
 			view.actions = view.$el.find('.backend-action-button').attr('data-action');
 			view.blockUi = new AE.Views.BlockUi();
 		},
 		onclickButton: function() {
 			//call a function when click to this button
 			var view = this,
 				data = {
 					action: 'de_get_list_to_update_rating_score',
 				};
			$.ajax({
				type: 'get',
				url: ae_globals.ajaxURL,
				data: data,
				beforeSend: function() {
					view.blockUi.block(view.$el.find('button'));
				},
				success: function(resp) {
					var data = {
						action: 'de_update_rating_score',
						content: resp.data
					};
					$.ajax({
						type: 'get',
						url: ae_globals.ajaxURL,
						data: data,
						beforeSend: function() {},
						success: function(resp) {
							view.$el.find('.backend-button-notification').html(resp.msg).addClass('success');
							view.blockUi.unblock();
						}
					});
				}
			});
 		}
 	});

 	$(document).ready(function(){
 		new Views.Backend_UpdateFeatured({el : '.add-et-featured'});
 		new Views.Backend_UpdateRatingScore({el : '.add-rating_score'});
 	});

 })(jQuery, AE.Views, AE.Models, window.AE);