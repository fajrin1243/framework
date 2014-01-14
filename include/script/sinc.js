(function($) {	

	$(function()
	{
		//nice scroll anchor
		$('a[href^="#"]').on('click',function (e) {
			e.preventDefault();

			var target = this.hash,
			$target = $(target);

			$('html, body').stop().animate({
				'scrollTop': $target.offset().top
			}, 900, 'swing', function () {
				window.location.hash = target;
			});
		});
		
		
		
		
		/** Smooth Load Content 
		 * to use this jquery using class fade-content
		*/
		function isScrolledIntoView(elem)
		{
			var docViewTop = $(window).scrollTop();
			var docViewBottom = docViewTop + $(window).height();

			var elemTop = $(elem).offset().top;
			var elemBottom = elemTop + $(elem).height();

			return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
		}


		$(document).ready(function(){
			 $('.fade-content').each(function(){
				if(!isScrolledIntoView($(this))){
					$(this).addClass('hidden');
				}
			});

			
			$(document).on('scroll', function(){
				$('.hidden').each(function(){
					if(isScrolledIntoView($(this))){
						$(this).removeClass('hidden').css({ 'display' : 'none' }).fadeIn();
					}
				});
			});
		});
		
		/*End Smooth Load Content */		
	});
	
	

	
	
	
})(jQuery);