var FA_dev = {
	start: function(){
		var s = $$('.FA_overall_container');
		var d = $$('.wpf-dev');
		
		d.each( function(el, i){
			el.injectInside(s[i]);
			var fx = new Fx.Morph(el, {'wait':false, 'duration':200});
			el.addEvents({
				'mouseenter': function(){
					fx.start({'width':150});
				},
				'mouseleave': function(){
					fx.start({'width':16});
				}
			});	
		})
	}
}
window.addEvent('domready', FA_dev.start);