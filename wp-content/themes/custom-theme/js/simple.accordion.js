jQuery(document).ready(function($){
    var allPanels = $('.accordion .content').hide();

    $('.accordion .title').click(function() {
        $(this).next().slideToggle();
        $(this).toggleClass('active');
        return false;
    });
});
