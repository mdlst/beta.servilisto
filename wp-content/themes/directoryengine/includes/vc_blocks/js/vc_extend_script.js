(function($) {

	var panel 	= $('<div class="color-panel">'),
		icons 	= [
			'fa-camera', 'fa-taxi', 'fa-beer', 'fa-anchor', 'fa-cutlery', 'fa-exclamation-triangle',
			'fa-road', 'fa-wheelchair', 'fa-car', 'fa-truck',
			'fa-graduation-cap', 'fa-briefcase', 'fa-coffee', 'fa-book', 'fa-plane', 'fa-tachometer',
			'fa-gamepad', 'fa-building', 'fa-shopping-cart',
			'fa-video-camera', 'fa-tree', 'fa-rocket', 'fa-glass', 'fa-star'
		];

	$(document).on('focus','input.icon', function(){
		var $input = $(this);
		for (var i = icons.length - 1; i >= 0; i--) {
			var element = $('<div class="color-item" data="'+ icons[i] +'">').append('<i class="fa '+ icons[i] +'"></i>');

			// set event
			element.on('click', function(event){
				var val = $(event.currentTarget).attr('data');
				$input.val(val);
				panel.fadeOut('normal', function(){ $(this).html(''); });
			});
			panel.append(element);

		}
		panel.fadeIn();
		$input.parent().append(panel);
	});

})(jQuery);