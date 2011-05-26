/*
	Author: Constantin Boiangiu (constantin[at]php-help.ro)
	Copyrigh (c) 2011 - author
	License: MIT(http://www.opensource.org/licenses/mit-license.php) and GPL (http://www.opensource.org/licenses/gpl-license.php)
	Package: Wordpress Featured Articles Lite plugin
	Version: 1.0
	jQuery version: 1.4.4
	Uses: DOMMouseScroll by Brandon Aaron (http://brandonaaron.net)
*/
(function(A){A.fn.extend({FeaturedArticles:function(B){var C=A.extend({},A.fn.FeaturedArticles.defaults,B);return this.each(function(){$this=A(this);var E=typeof(FA_Lite_params)!="object"?{}:FA_Lite_params;var I=typeof(FA_Lite_footer_params)!="object"?{}:FA_Lite_footer_params;var F=A.extend(E,I);var H=$this.attr("id"),G=A.parseJSON(F[H].replace(/&quot;/g,'"'));if(G.slideDuration){G.slideDuration=parseFloat(G.slideDuration)*1000}if(G.effectDuration){G.effectDuration=parseFloat(G.effectDuration)*1000}var D=A.extend({},C,G);A(this).data("faSlider",D);A(this).start()})},changeSlide:function(B){var C=B.currentKey+1>=B.slides.length?0:B.currentKey+1;B.goToSlide(C)},stopSlider:function(){clearInterval(this.interval);this.interval=false},startSlider:function(){if(this.stopped){return}var B=this.settings();this.interval=setInterval(this.changeSlide,B.slideDuration||3000,this)},start:function(){this.slides=A(this).find(".FA_article");if(this.slides.length<2){return}var B=this;this.currentKey=0;this.interval=false;this.stopped=false;this.prepareSlides();this.prepareNavigation();this.prepareSideNavs();var C=this.settings();if(C.autoSlide){B.startSlider();A(this).mouseenter(function(){B.stopSlider()});A(this).mouseleave(function(){B.startSlider()})}if(!C.mouseWheelNav){return}A(this).mousewheel(function(E,F){E.preventDefault();if(F>0){var D=B.currentKey-1<0?B.slides.length-1:B.currentKey-1}else{if(F<0){var D=B.currentKey+1>B.slides.length-1?0:B.currentKey+1}}B.stopSlider();B.goToSlide(D,F)})},settings:function(B){var C=A(this).data("faSlider");return C},prepareSlides:function(){this.slides.css({position:"absolute",top:0,left:0,opacity:0,"z-index":1});A(this.slides[this.currentKey]).css({opacity:1,"z-index":100})},prepareNavigation:function(){this.navLinks=A(this).find(".FA_navigation a");if(this.navLinks.length<1){return}var B=this,C=B.settings();A.each(this.navLinks,function(D,E){var F=A(E).parent().find("span");if(F.length>0){A(E).mouseenter(function(G){A(F).css({display:"block",top:-25,opacity:0}).animate({opacity:1,top:-20})}).mouseleave(function(G){F.css({display:"none",opacity:0,top:-25})})}A(E).click(function(G){G.preventDefault();if(B.interval){B.stopSlider()}B.goToSlide(D);if(!C.stopSlideOnClick&&C.autoSlide){B.startSlider()}else{if(C.stopSlideOnClick&&C.autoSlide){B.stopped=true}}});if(D==B.currentKey){A(B.navLinks[D]).addClass("active")}})},prepareSideNavs:function(){var E=A(this).find(".FA_back"),B=A(this).find(".FA_next"),C=this,D=C.settings();if(E.length>0){A(E).click(function(G){G.preventDefault();var F=C.currentKey-1>=0?C.currentKey-1:C.slides.length-1;if(C.interval){C.stopSlider()}C.goToSlide(F,1);if(!D.stopSlideOnClick&&D.autoSlide){C.startSlider()}else{if(D.stopSlideOnClick&&D.autoSlide){C.stopped=true}}})}if(B.length>0){A(B).click(function(G){G.preventDefault();var F=C.currentKey+1<C.slides.length?C.currentKey+1:0;if(C.interval){C.stopSlider()}C.goToSlide(F);if(!D.stopSlideOnClick&&D.autoSlide){C.startSlider()}else{if(D.stopSlideOnClick&&D.autoSlide){C.stopped=true}}})}},goToSlide:function(C,D){if(C==this.currentKey){return}if(C<0||C>=this.slides.length){return}var B=D||-1,E=this.settings(),F=E.fadePosition=="left"?"left":"top";switch(F){case"top":A(this.slides[this.currentKey]).css({top:0,"z-index":1}).animate({opacity:0,top:B*E.fadeDist},{queue:false,duration:E.effectDuration});A(this.slides[C]).css({top:-B*E.fadeDist,"z-index":100}).animate({opacity:1,top:0},{queue:false,duration:E.effectDuration});break;case"left":A(this.slides[this.currentKey]).css({left:0,"z-index":1}).animate({opacity:0,left:B*E.fadeDist},{queue:false,duration:E.effectDuration});A(this.slides[C]).css({left:-B*E.fadeDist,"z-index":100}).animate({opacity:1,left:0},{queue:false,duration:E.effectDuration});break}if(this.navLinks.length>0){A(this.navLinks[this.currentKey]).removeClass("active");A(this.navLinks[C]).addClass("active")}this.currentKey=C}});A.fn.FeaturedArticles.defaults={slideDuration:5000,effectDuration:1000,fadeDist:null,fadePosition:null,stopSlideOnClick:false,autoSlide:false,mouseWheelNav:false}})(jQuery);jQuery(document).ready(function(){jQuery(".FA_slider").FeaturedArticles({})});