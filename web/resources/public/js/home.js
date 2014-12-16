$( document ).ready(function() {
    var homeLink = $('#li-home');
    var homeNavBarLink = $('.navbar-brand');
    eventPreventer('click', homeNavBarLink);
    eventPreventer('click', homeLink);
    homeLink.addClass('active');
});