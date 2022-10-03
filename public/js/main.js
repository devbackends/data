(function($){
  // toggle search bar
  $('.js-toggle-search').on('click', function(e) {
    e.preventDefault();
    var $this = $(this);

    $this.toggleClass('active');

    $('.searchbar').toggle();

    $(document).on('click', function(e) {
      if ($(e.target).closest('.vc-small-screen').length == 0) {
        $('.searchbar').hide();
        $this.removeClass('active');
      }
    });
  });

  // toggle mobile nav
  $('.js-toggle-nav').on('click', function(e) {
    e.preventDefault();
    var $this = $(this);

    $this.toggleClass('active');

    $('.nav-container').toggleClass('active');

    $(document).on('click', function(e) {
      if ($(e.target).closest('.nav-container .wrapper').length == 0 && $(e.target).closest('.js-toggle-nav').length == 0) {
        $('.nav-container').removeClass('active');
        $this.removeClass('active');
      }
    });
  });

  $('.js-close-nav').on('click', function(e) {
    e.preventDefault();

    $('.js-toggle-nav').toggleClass('active');
    $('.nav-container').toggleClass('active');
  });

  // toggle user nav
  $('.js-toggle-user-nav').on('click', function(e) {
    e.preventDefault();
    var $this = $(this);

    $('.js-user-nav').toggleClass('active');

    $(document).on('click', function(e) {
      if ($(e.target).closest('.customer-sidebar').length == 0 && $(e.target).closest('.js-toggle-user-nav').length == 0) {
        $('.js-user-nav').removeClass('active');
      }
    });
  });

  // product list slider
  $('.js-product-slider').flexslider({
    animation: "slide",
    slideshow: false,
    itemWidth: 227,
    itemMargin: 15,
    minItems: 1,
    move: 1,
    customDirectionNav: $(".js-product-slider-nav a")
  });

  // compare list slider
  $('.js-compare-slider').flexslider({
    animation: "slide",
    slideshow: false,
    itemWidth: 291,
    minItems: 1,
    move: 1,
    controlNav: false,
    customDirectionNav: $(".js-compare-slider-nav a")
  });

})(jQuery)