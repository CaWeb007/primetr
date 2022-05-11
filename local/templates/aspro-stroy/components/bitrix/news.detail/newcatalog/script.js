$(document).ready(function(){
	if($('.catalog.detail').closest('div[id^=comp_]')){
		$('.content-md').closest('.maxwidth-theme').css('max-width', '100%').find('.content-md').css({'padding-left': 0, 'padding-right': 0});
		InitFlexSlider();
		$('.flexslider .item').css('opacity', 1);
	}
});
