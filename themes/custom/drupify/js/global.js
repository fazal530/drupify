/**
 * @file
 * Global utilities.
 *
 */
(function ($, Drupal, once) {

  'use strict';

  Drupal.behaviors.drupify = {
    attach: function (context, settings) {

  
  $(document).ready(function(){
    if (typeof AOS !== 'undefined') {
      AOS.init();
    }
  });

  if (typeof $.camelCase !== 'function') {
    $.camelCase = function (string) {
      return string.replace(/^-ms-/, 'ms-').replace(/-([a-z])/g, function (all, letter) {
        return letter.toUpperCase();
      });
    };
  }

  if (typeof $.type !== 'function') {
    $.type = function (obj) {
      if (obj == null) {
        return obj + '';
      }
      return typeof obj === 'object' || typeof obj === 'function'
        ? Object.prototype.toString.call(obj).slice(8, -1).toLowerCase()
        : typeof obj;
    };
  }

  if (typeof $.fn.owlCarousel === 'function' && !$.fn.owlCarousel.classicProPatched) {
    var originalOwlCarousel = $.fn.owlCarousel;
    var patchedOwlCarousel = function () {
      var args = arguments;

      if (typeof args[0] === 'object' || typeof args[0] === 'undefined') {
        var $newCarousels = this.filter(function () {
          return !$(this).data('owl.carousel');
        });

        if ($newCarousels.length) {
          originalOwlCarousel.apply($newCarousels, args);
        }

        return this;
      }

      return originalOwlCarousel.apply(this, args);
    };

    patchedOwlCarousel.Constructor = originalOwlCarousel.Constructor;
    patchedOwlCarousel.classicProPatched = true;
    $.fn.owlCarousel = patchedOwlCarousel;
  }
  else if (typeof $.fn.owlCarousel !== 'function') {
    $.fn.owlCarousel = function () {
      return this.addClass('owl-carousel-fallback');
    };
  }

  //Header
  $("#header-2 .nav-item.dropdown .nav-link").addClass("dropdown-toggle");
  //Header 2 color chanage
  var links_count = $('.header.active .nav-item.dropdown .nav-link').length
  console.log(links_count);
      if(links_count >= 7){
        $(".page-node-57 .header.active .nav-item.dropdown .nav-link").slice(0,3).addClass("darker-link")
      }
      if(links_count == 6){
        $(".page-node-57 .header.active .nav-item.dropdown .nav-link").slice(0,2).addClass("darker-link")
      }
      if(links_count == 5){
        $(".page-node-57 .header.active .nav-item.dropdown .nav-link").first().addClass("darker-link")
      }
//Active Class PerPage
  //  $(".page-node-57 .header").removeClass("active");
  //  $(".page-node-57 #header-2.header").addClass("active");

  $(function() {
    if (typeof $.fn.tooltip === 'function') {
      $('[data-bs-toggle="tooltip"]', context).tooltip();
    }
    else if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip === 'function') {
      $('[data-bs-toggle="tooltip"]', context).each(function () {
        bootstrap.Tooltip.getOrCreateInstance(this);
      });
    }
  });
  $( once("header-nav-toggle" , "#header-1 .navbar-toggler")).click(function(){
    $(".header-1-menu").toggleClass("header-1-block");
  });
  $( once("header-nav-close", ".header-1-menu .close .close-icon")).click(function(){
    $(".header-1-menu").toggleClass("header-1-block");
  });


  $( once("search", ".search-btn .btn")).click(function(){
    $(".search-btn .search-overlay").toggleClass("search-block");
  });
  $( once("hamburger", ".hamburger-btn")).click(function(){
    $(".hamburger-menu").toggleClass("hamburger-block");
  });


  $(function () {
    $(document).scroll(function () {
      var $nav = $("#header-1 .navigation-sticky");
      $nav.toggleClass("fixed-top", $(this).scrollTop() > 10);
    });
  });
  $(function () {
    $(document).scroll(function () {
      var $nav = $("#header-2 .navigation-sticky");
      $nav.toggleClass("fixed-top", $(this).scrollTop() > 10);
    });
  });
  $(function () {
    $(document).scroll(function () {
      var $nav = $("#header-3 .navigation-sticky");
      $nav.toggleClass("fixed-top", $(this).scrollTop() > 10);
    });
  });


  $(".dropdown-menu a.drop-toggle").on("click", function (e) {
    if (!$(this).next().hasClass("show")) {
      $(this)
        .parents(".dropdown-menu")
        .first()
        .find(".show")
        .removeClass("show");
    }
    var $subMenu = $(this).next(".dropdown-menu");
    $subMenu.toggleClass("show");
    $(this).parent("li").toggleClass("show");
    $(this)
      .parents("li.nav-item.dropdown.show")
      .on("hidden.bs.dropdown", function (e) {
        $(".dropdown-menu .show").removeClass("show");
      });
    return false;
  });

  //Slider

  $('.home-banner-1 .carousel-inner .carousel-item').first().addClass('active');  
  $('.home-banner-3 .carousel-inner .carousel-item').first().addClass('active'); 

  //Team
  $(".style-1").owlCarousel({
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: false,
    loop: true,
    nav: false,
    margin: 10,
    dots: false,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 3,
      },
      1000: {
        items: 5,
      },
    },
  });
  $(".style-2").owlCarousel({
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: false,
    loop: true,
    nav: false,
    margin: 10,
    dots: false,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 1,
      },
    },
  });
  $(".style-3").owlCarousel({
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: false,
    loop: true,
    nav: false,
    margin: 30,
    dots: true,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 2,
      },
      1000: {
        items: 3,
      },
    },
  });

  $(".team-carousel").owlCarousel({
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: false,
    loop: true,
    nav: false,
    margin: 30,
    dots: true,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 2,
      },
      1000: {
        items: 3,
      },
    },
  });

  $(".style-4").owlCarousel({
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: false,
    loop: true,
    nav: false,
    margin: 30,
    dots: true,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 2,
      },
      1000: {
        items: 3,
      },
    },
  });

//Testimonial
$(".testimonial-sliding-1").owlCarousel({
  autoplay: true,
  autoplayTimeout: 5000,
  autoplayHoverPause: false,
  loop: true,
  nav: false,
  margin: 30,
  dots: true,
  responsiveClass: true,
  responsive: {
    0: {
      items: 1,
    },
    600: {
      items: 1,
    },
    1000: {
      items: 1,
    },
  },
});
$(".testimonial-sliding-2").owlCarousel({
  autoplay: true,
  autoplayTimeout: 5000,
  autoplayHoverPause: false,
  loop: true,
  nav: false,
  margin: 0,
  dots: false,
  responsiveClass: true,
  responsive: {
    0: {
      items: 1,
    },
    600: {
      items: 2,
    },
    1000: {
      items: 3,
    },
  },
});
$(".testimonial-sliding-3").owlCarousel({
  autoplay: true,
  autoplayTimeout: 5000,
  autoplayHoverPause: false,
  loop: true,
  nav: false,
  margin: 20,
  dots: true,
  responsiveClass: true,
  responsive: {
    0: {
      items: 1,
    },
    600: {
      items: 1,
    },
    1000: {
      items: 1,
    },
  },
});


$(".other-projects .image-sliding").owlCarousel({
  autoplay: true,
  autoplayTimeout: 5000,
  autoplayHoverPause: false,
  loop: true,
  nav: false,
  margin: 30,
  dots: true,
  responsiveClass: true,
  responsive: {
    0: {
      items: 1,
    },
    600: {
      items: 2,
    },
    1000: {
      items: 3,
    },
  },
});

$(".testimonials-slide-2").owlCarousel({
  autoplay: true,
  autoplayTimeout: 5000,
  autoplayHoverPause: false,
  loop: true,
  nav: false,
  margin: 0,
  dots: true,
  responsiveClass: true,
  responsive: {
    0: {
      items: 1,
    },  
    600: {
      items: 2,
    },
    1000: {
      items: 3,
    },
  },
});


//home-banner-1
var count = $('.home-banner-1 .carousel-inner .carousel-item').length + 1
$(document).ready(function(){
  var currentIndex = $('.home-banner-1 .carousel-inner .carousel-item.active').index();
  var currentIndex_next = $('.home-banner-1 .carousel-inner .carousel-item.active').index();
  $(' .home-banner-1 #carouselExampleControls').bind('slid.bs.carousel', function(e) {
    currentIndex = $('.home-banner-1 .carousel-inner .carousel-item.active').index();
    currentIndex_next = $('.home-banner-1 .carousel-inner .carousel-item.active').index()+2;
    if (currentIndex_next == count){
      $('.home-banner-1 .carousel-control-next .next_active').html('00');
    }else{
      $('.home-banner-1 .carousel-control-next .next_active').html('0'+currentIndex_next);
    }
    $('.home-banner-1 .carousel-control-prev .prev_active').html('0'+currentIndex);
  });
});

$(".home-banner-2 .image-slider").owlCarousel({
  autoplay: true,
  autoplayTimeout: 5000,
  autoplayHoverPause: false,
  loop: true,
  nav: false,
  margin: 20,
  dots: true,
  responsiveClass: true,
  responsive: {
    0: {
      items: 1,
    },
    600: {
      items: 1,
    },
    1000: {
      items: 1,
    },
  },
});




$(".circle_percent").each(function() {
  var $this = $(this),
  $dataV = $this.data("percent"),
  $dataDeg = $dataV * 3.6,
  $round = $this.find(".round_per");
$round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");	
$this.append('<div class="circle_inbox"><span class="percent_text"></span></div>');
$this.prop('Counter', 0).animate({Counter: $dataV},
{
  duration: 2000, 
  easing: 'swing', 
  step: function (now) {
          $this.find(".percent_text").text(Math.ceil(now)+"%");
      }
  });
if($dataV >= 51){
  $round.css("transform", "rotate(" + 360 + "deg)");
  setTimeout(function(){
    $this.addClass("percent_more");
  },1000);
  setTimeout(function(){
    $round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");
  },1000);
} 
});




    // Tabs Style 1
    // $('.portfolio-tab .tabs-item').masonry({
    //   itemSelector: '.item',
    //   layoutMode: 'fitRows'
    // });
    $(".portfolio-tab .tabs-item").isotope({
      itemSelector: ".item",
      masonry: {
        columnWidth: 1
     }
    });
    $(".portfolio-tab .tabs-menu ul li").click(function () {
      $(".portfolio-tab .tabs-menu ul li").removeClass("active");
      $(this).addClass("active");
      var selector;
      selector = $(this).attr("data-filter");
      $(".portfolio-tab .tabs-item").isotope({
        filter: selector,
      });
      return false;
    });






    $('.tabs-item').magnificPopup({
      delegate: 'a',
      type: 'image',
      tLoading: 'Loading image #%curr%...',
      mainClass: 'mfp-img-mobile',
      gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0,1]
      }
    });
      //Our Values Style 2
      $(".our-value-progress").each(function() {
    
        var value = $(this).attr('data-value');
        var left = $(this).find('.progress-left .our-value-progress-bar');
        var right = $(this).find('.progress-right .our-value-progress-bar');
    
        if (value > 0) {
          if (value <= 50) {
            right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
          } else {
            right.css('transform', 'rotate(180deg)')
            left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
          }
        }
    
      })
    
      function percentageToDegrees(percentage) {
    
        return percentage / 100 * 360
    
      }
      
      //COMING SOON
      function getTimeRemaining(endtime) {
        var t = Date.parse(settings.custom_date) - Date.parse(new Date());
        var seconds = Math.floor((t / 1000) % 60);
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        var days = Math.floor(t / (1000 * 60 * 60 * 24));
        return {
          'total': t,
          'days': days,
          'hours': hours,
          'minutes': minutes,
          'seconds': seconds
          };
      }
      function initializeClock(id, endtime) {
        var clock = document.getElementById(id);
        var daysSpan = clock.querySelector('.days');
        var hoursSpan = clock.querySelector('.hours');
        var minutesSpan = clock.querySelector('.minutes');
        var secondsSpan = clock.querySelector('.seconds');
        function updateClock() {
          var t = getTimeRemaining(endtime);
          daysSpan.innerHTML = t.days;
          hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
          minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
          secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
          if (t.total <= 0) {
            clearInterval(timeinterval);
            document.getElementById("clockdiv").innerHTML = settings.custom_message_dateExpired;
          }
        }
        updateClock();
        var timeinterval = setInterval(updateClock, 1000);
        }
        var deadline = new Date(Date.parse(new Date()));
        if($("#clockdiv").length){
          initializeClock('clockdiv', deadline);
        }
      
      // $('.our-works-3.portfolio-grid-3-column.masonry-style-1.portfolio-tab.section .tabs-item .row div:nth-child(2), .our-works-3.portfolio-grid-3-column.masonry-style-1.portfolio-tab.section .tabs-item .row div:nth-child(6) ').find(".item-content").addClass('h-large'); 

        // Clipboard
        var elementCopy = document.getElementsByClassName("language-markup");
        if(typeof(elementCopy) != 'undefined' && elementCopy != null){ 
        var clipboard = new ClipboardJS('.clipboard');   
        clipboard.on('success', function (e) {
        e.trigger.textContent = 'Copied';
        window.setTimeout(function() {
          e.trigger.textContent = 'Copy to Clipboard';
        }, 8000);
        console.log(e);
        });
        clipboard.on('error', function (e) {
        console.log(e);
        });
        }

         //responsive dropdown - viewport
        /**
         * Save DOM selectors.
         */
         var $nav_menu = $('.navbarsExampleDefault');
         var $site_container = $('body');
         var $window = $(window);
         var window_size = $window.width();
         $nav_menu.find( once('openLeftBehavior', 'ul li',context)).hover(function() {
                 if ($(this).children('ul').length) {
                     // var menu_ul_left =$(this).offset().left + $(this).width();
                     var menu_ul_element = $(this).children('ul');
                     var menu_ul_offset = menu_ul_element.offset();
                     var menu_ul_left = menu_ul_offset.left;
                     var menu_ul_width = menu_ul_element.width();
                     var site_container_width = $site_container.width();
                     var site_container_left = $site_container.offset().left;
                     // alert(site_container_width + site_container_left);
                     var is_menu_ul_visible = (menu_ul_left + menu_ul_width <= site_container_width + site_container_left);
                     if (!is_menu_ul_visible) {                        $(this).children('ul').removeClass('open-left');
                         $(this).children('ul').addClass('open-left');
                     }
                 }
             },
         );

        // Comment Form Validation
        function validate(){
          var cnv = $('form.comment-form textarea').val();
          if (!$.trim(cnv)) {
            $(once('validation', 'form.comment-form textarea')).after('<span class="error-comment">* Add Comment before Submission</span>');
              return false;
          } else { return true; }
        }
        $(once('comment-validation', 'form.comment-form')).submit(validate);

    }
  };

})(jQuery, Drupal, once);
