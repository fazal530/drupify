// Demo Code
(function ($, Drupal, once) {
  'use strict';
    Drupal.behaviors.color_panel = {
      attach: function(context, settings) {
        var default_primary= $(once('colorPBehaviour',':root',context)).css("--primary-color");
        var default_secondary= $(once('colorSBehaviour',':root',context)).css("--secondary-color");
        $(once('readyBehave', document,context)).ready(function() {  
          if (typeof(Storage) !== "undefined") {
            // Retrieve
            var primary_color = sessionStorage.getItem("pt-theme-primary-color");
            var secondary_color = sessionStorage.getItem("pt-theme-secondary-color");

            if(primary_color !== "undefined" && secondary_color !== "undefined"){
              $(':root').css('--primary-color',primary_color);
              $(':root').css('--secondary-color',secondary_color);
              $('.item-color[data-primary_color="'+primary_color+'"]').filter( $( "[data-secondary_color='"+secondary_color+"']" )).addClass('active');
            }
          }
        });
        $(once('color_panel','.pt-skins-panel .control-panel', context)).click(function(){
            if($(this).parents('.pt-skins-panel').hasClass('active')){
                $(this).parents('.pt-skins-panel').removeClass('active');
                $('.pt-skins-panel .control-panel .fa').addClass('fa-cog fa-spin');
              $('.pt-skins-panel .control-panel .fa-cog').removeClass('far fa-times');
                
            }
            else{
              $(this).parents('.pt-skins-panel').addClass('active');  
              $('.pt-skins-panel .control-panel .fa').removeClass('fa-cog fa-spin');
              $('.pt-skins-panel .control-panel .fa').addClass('far fa-times');

            } 
        });


          $(once('resetBehavior','#pt-reset-color', context)).click(function(){
               $(':root').css('--primary-color',default_primary);
               $(':root').css('--secondary-color',default_secondary);
               if (typeof(Storage) !== "undefined") {
                  sessionStorage.setItem("pt-theme-primary-color", default_primary);
                  sessionStorage.setItem("pt-theme-secondary-color", default_secondary);
                }
          });

          $('.pt-skins-panel .item-color').click(function(){
              if($(this).data('primary_color')){
                var category = $(this).data('category');
                var primary_color = $(this).data('primary_color');
                var secondary_color = $(this).data('secondary_color');
                $('.pt-skins-panel .item-color').removeClass('active');
                $(this).addClass('active');
                $(':root').css('--primary-color',$(this).data('primary_color'));
                $(':root').css('--secondary-color',$(this).data('secondary_color'));
                if (typeof(Storage) !== "undefined") {
                  sessionStorage.setItem("pt-theme-primary-color", $(this).data('primary_color'));
                  sessionStorage.setItem("pt-theme-secondary-color", $(this).data('secondary_color'));
                }
              }
          });

          // Header style change on load
          // Header style On change
          // $('#item_list').on('change', function() {
          //   $("#loader").css('display', 'block');
          //   // $("#page_content").css('display', 'none');
          //   if(this.value == 'header-3'){
          //     $('#main-wrapper').addClass('header_3_active');
          //   }
          //   else {
          //     $('#main-wrapper').removeClass('header_3_active');
          //   }
          //   if(this.value == 'header-4'){
          //     $('#main-wrapper').addClass('header_5_active');
          //   }
          //   else {
          //     $('#main-wrapper').removeClass('header_5_active');
          //   }
          //   $(".nav_header").attr('id', this.value);
          //   $('.header_type').removeClass('active');
          //   var current_header_id = this.value.substr(this.value.indexOf("-") + 1);
          //   $('.header_type.header-'+current_header_id).addClass('active');

          //     if (typeof(Storage) !== "undefined") {
          //       sessionStorage.setItem("pt-theme-header", this.value);
          //     }
          //   $("option[value=" + this.value + "]", this).attr("selected", true).siblings().removeAttr("selected")
          //     var myVar = setTimeout(showPage, 1000);
          // });  
          // function showPage() {
          //   $("#loader").css('display', 'none');
          //   // $("#page_content").css('display', 'block');
          // }

          //Header style On change
          $('#item_list').on('change', function() {
            /*Header*/
            $("#loader").css('display', 'block');
            $('header').removeClass('active');
            var current_header_id = this.value.substr(this.value.indexOf("-") + 1);
            console.log(current_header_id)
            $('#header-'+current_header_id).addClass('active');
            $(this.value).addClass('active');
            $("option[value=" + this.value + "]", this).attr("selected", true).siblings().removeAttr("selected")
            var myVar = setTimeout(showPage, 1000);
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
          });
          function showPage() {
            $("#loader").css('display', 'none');
            // $("#page_content").css('display', 'block');
          } 
       

          //checkbox
          $('#Check1').click(function(){
            if($(this).is(":checked")){
              $('#header-1 .navigation-sticky, #header-2 .navigation-sticky, #header-3 .navigation-sticky').addClass('sticky-nav');
            }
            else if($(this).is(":not(:checked)")){
              $('#header-1 .navigation-sticky, #header-2 .navigation-sticky, #header-3 .navigation-sticky').removeClass('sticky-nav');
            }
          });
          if($('#Check1').is(":checked")){
            $('#header-1 .navigation-sticky, #header-2 .navigation-sticky, #header-3 .navigation-sticky').addClass('sticky-nav');
          }
          else if($(this).is(":not(:checked)")){
            $('#header-1 .navigation-sticky, #header-2 .navigation-sticky, #header-3 .navigation-sticky').removeClass('sticky-nav');
          }
          $('#Check2').click(function(){
              if($(this).is(":checked")){edit-sticky
                  $('#page-loader').addClass('active');
              }
              else if($(this).is(":not(:checked)")){
                  $('#page-loader').removeClass('active');

              }
          });
          if($('#Check2').is(":checked")){
              $('#page-loader').addClass('active');
          }
          else if($(this).is(":not(:checked)")){
              $('#page-loader').removeClass('active');

          }

    }
  };

  document.onreadystatechange = function () {
    var state = document.readyState
    if (state == 'complete') {
           document.getElementById('interactive');
           document.getElementById('page-loader').style.visibility="hidden";
    }
  }

  // var e = document.getElementById("edit-header-variation");
  //         var currentHome = e.value;
  //         $('#'+currentHome).addClass('active');
  //go to top
  // var mybutton = document.getElementById("back-to");
  // // When the user scrolls down 100px from the top of the document, show the button
  // window.onscroll = function() {scrollFunction()};
  // function scrollFunction() {
  //   if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
  //     mybutton.style.display = "block";
  //   } else {
  //     mybutton.style.display = "none";
  //   }
  // }
  /*end*/

})(jQuery, Drupal, once);

// Demo Code