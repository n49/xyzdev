jQuery(document).ready(function($){

	/* Mobile Menu */

	$('header .nav').meanmenu({
		meanScreenWidth: '768',
		meanMenuContainer: '.header .middle > .wrapper',
		removeElements: '.header .middle .sub-menu'
	});
});
