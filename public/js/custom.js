/**
 * Created by mirage on 8/31/16.
 */

$('#side-menu').find('ul li a').click(function (e) {
    e.preventDefault();
    $('#side-menu').find('ul li').removeClass('active');
    $(this).closest('li').addClass('active');
    $('#ajax-container').load($(this).attr('href'));
});


$('#view-profile').click(function (e) {
    e.preventDefault();
    $('#side-menu').find('ul li').removeClass('active');
    $('#ajax-container').load($(this).attr('href'));
});


$( document ).ready(function() {
    $('#side-menu').find('ul li' ).first().find('a').click();
});
