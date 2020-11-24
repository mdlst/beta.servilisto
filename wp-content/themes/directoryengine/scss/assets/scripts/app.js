app = {
	models: {},
	views: {},
	collections: {},
	clipboard: {},
	init: function() {
		apply = new app.views.Prototype();
	}
};

app = app || {};

app.views.Prototype = Backbone.View.extend({
	el: 'body',
	events: {
	},
	initialize: function() {
		var _this = this;
		$('.rate-it').raty();
		$('.sidenav li').on('click', function(){
			var id = $(this).attr('id');
			$('html, body').animate({
			    scrollTop: $('.block-'+id).offset().top - 85
			}, 50);
		});
		$('.pre-code').each(function(index) {
			var id = $(this).attr('id');
			app.clipboard[id] = $('#'+id).html();
		});
		$('[data-toggle="tooltip"]').tooltip();
		$('.btn-clipboard').on('click', function() {
			var id = $(this).attr('data-id');
			clipboard.copy(app.clipboard[id]);
		});
		$('.chosen-single').chosen();
		_this.Highlighter();
	},
	Highlighter: function() {
		SyntaxHighlighter.defaults['html-script'] = true;
		SyntaxHighlighter.defaults['gutter'] = false;
		SyntaxHighlighter.defaults['toolbar'] = false;
		SyntaxHighlighter.all();
	}
});


//== Initialize app
//
app.init();