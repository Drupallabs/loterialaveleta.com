(function ($, Drupal) {
  'use strict';
  var options = {};
  var effects = ['blind', 'clip', 'drop', 'explode', 'slide', 'bounce', 'fold', 'fade', 'highlight', 'puff', 'pulsate', 'scale', 'shake', 'size'];

  setInterval(function () {
    let selectedEffect = effects[Math.floor(Math.random() * effects.length)];

    // some effects have required parameters

    if (selectedEffect === "scale") {
      options = { percent: 50 };
    } else if (selectedEffect === "size") {
      options = { to: { width: 480, height: 480 } };
    }
    $(".bruja").effect(selectedEffect, options, 2800, callback);
  }, 20000);

  function callback() {
    setTimeout(function () {
      $(".effect").removeAttr("style").fadeIn(2000);
    }, 2000);
  };


  var x = 0,
    container = $('.bloque-botes'),
    items = container.find('article'),
    containerHeight = 0,
    numberVisible = 4,
    intervalSec = 1500;

  if (!container.find('article:first').hasClass("first")) {
    container.find('article:first').addClass("first");
  }

  items.each(function () {
    if (x < numberVisible) {
      containerHeight = containerHeight + $(this).outerHeight(true) + 15;
      x++;
    }
  });
  console.log(containerHeight);
  container.css({ height: containerHeight, overflow: "hidden" });


  if (intervalSec < 1500) {
    intervalSec = 1500;
  }

  var init = setInterval(
    function () {
      var firstItem = container.find('article.first').html();

      container.append('<article>' + firstItem + '</article>');
      firstItem = '';
      container.find('article.first').animate({ marginTop: "-137px" }, 1090, function () { 
        $(this).remove(); container.find('article:first').addClass("first"); });
    }, intervalSec);

  container.hover(function () {
    clearInterval(init);
  }, function () {
    init = setInterval(function () {
      var firstItem = container.find('article.first').html();

      container.append('<article>' + firstItem + '</article>');
      firstItem = '';
      container.find('article.first').animate({ marginTop: "-137px" }, 1090, function () { $(this).remove(); container.find('article:first').addClass("first"); });
    }, intervalSec);
  });
  
})(jQuery, Drupal);
