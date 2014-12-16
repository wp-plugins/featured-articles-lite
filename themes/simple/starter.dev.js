;(function($){	
	$(document).ready( function(){
		$('.fa_slider_simple').FeaturedArticles({
			slide_selector 	: '.fa_slide',
			nav_prev 		: '.go-back',
			nav_next		: '.go-forward',
			nav_elem		: '.main-nav .fa-nav',
			
			effect	: false,
			
			begin	: load,
			before	: before,
			after	: after,
			stop	: stop,
			start	: start
		});		
	});
	
	var load = function(){
		var options = this.settings(),
			self = this;
		this.progressBar = $(this).find('.progress-bar');
		this.mouseOver;
	}
	
	var before = function(){
		var options = this.settings();
		this.progressBar.stop().css({'width':0});		
	}
	
	var after = function(){
		var options 	= this.settings(),
			duration 	= options.slide_duration;
		
		if( this.mouseOver || this.stopped || !options.auto_slide ){
			return;			
		}
		
		this.progressBar.css({width:0}).animate(
			{'width' : '100%'},
			{duration: duration, queue:false, complete: function(){
				$(this).css({'width':0});
			}
		});
	}
	
	var stop = function(){
		this.progressBar.stop().css({'width':0});
		this.mouseOver = true;			
	}
	
	var start = function(){
		this.mouseOver = false;
		if( this.animating() ){
			return;
		}
		
		var options 	= this.settings(),
			duration 	= options.slide_duration;		
		
		this.progressBar.css({width:0}).animate(
			{'width' : '100%'},
			{duration: duration, queue:false, complete: function(){
				$(this).css({'width':0});
			}
		});
	}
	
})(jQuery);	