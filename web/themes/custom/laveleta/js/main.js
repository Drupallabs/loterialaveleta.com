
(function ($, Drupal) {
  'use strict';
  $(".colorlib-loader").fadeOut("slow");

  $('.js-gotop').on('click', function(event){
    event.preventDefault();
    $('html, body').animate({
      scrollTop: $('html').offset().top
    }, 500, 'easeInOutExpo');
    return false;
  });

  $(window).scroll(function(){
    var $win = $(window);
    if ($win.scrollTop() > 200) {
      $('.js-top').addClass('active');
    } else {
      $('.js-top').removeClass('active');
    }
  });

  var i = 0;
  $('.animate-box').waypoint( function( direction ) {

    if( direction === 'down' && !$(this.element).hasClass('animated-fast') ) {
      i++;
      $(this.element).addClass('item-animate');
      setTimeout(function(){
        $('body .animate-box.item-animate').each(function(k){
          var el = $(this);
          setTimeout( function () {
            var effect = el.data('animate-effect');
            if ( effect === 'fadeIn') {
              el.addClass('fadeIn animated-fast');
            } else if ( effect === 'fadeInLeft') {
              el.addClass('fadeInLeft animated-fast');
            } else if ( effect === 'fadeInRight') {
              el.addClass('fadeInRight animated-fast');
            } else {
              el.addClass('fadeInUp animated-fast');
            }
            el.removeClass('item-animate');
          },  k * 200, 'easeInOutExpo' );
        });
      }, 100);
    }
  } , { offset: '85%' } );
  
})(jQuery, Drupal);
