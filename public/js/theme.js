//  js Document 

    // Created on : 14/10/2017. 
    // Theme Name : Faster .
    // Description: Faster - App Landing Page.
    // Version    : 1.1.
    // Author     : @creativeGigs.

"use strict";


// Prealoder
 function prealoader () {
   if ($('#loader').length) {
     $('#loader').fadeOut(); // will first fade out the loading animation
     $('#loader-wrapper').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
     $('body').delay(350).css({'overflow':'visible'});
  };
 }


// WOW animation 
function wowAnimation () {
  if($('.wow').length) {
    var wow = new WOW(
    {
      boxClass:     'wow',      // animated element css class (default is wow)
      animateClass: 'animated', // animation css class (default is animated)
      offset:       80,          // distance to the element when triggering the animation (default is 0)
      mobile:       true,       // trigger animations on mobile devices (default is true)
      live:         true,       // act on asynchronously loaded content (default is true)
    }
  );
  wow.init();
  }
}

// placeholder remove
function removePlaceholder () {
  if ($("input,textarea").length) {
    $("input,textarea").each(
            function(){
                $(this).data('holder',$(this).attr('placeholder'));
                $(this).on('focusin', function() {
                    $(this).attr('placeholder','');
                });
                $(this).on('focusout', function() {
                    $(this).attr('placeholder',$(this).data('holder'));
                });
                
        });
  }
}


// Scroll to top
function scrollToTop () {
  if ($('.scroll-top').length) {

    //Check to see if the window is top if not then display button
    $(window).on('scroll', function (){
      if ($(this).scrollTop() > 200) {
        $('.scroll-top').fadeIn();
      } else {
        $('.scroll-top').fadeOut();
      }
    });
    
    //Click event to scroll to top
    $('.scroll-top').on('click', function() {
      $('html, body').animate({scrollTop : 0},1500);
      return false;
    });
  }
}


// Theme-banner slider 
function BannerSlider () {
  var banner = $("#theme-main-banner");
  if (banner.length) {
    banner.camera({ //here I declared some settings, the height and the presence of the thumbnails 
      height: '1000px',
      pagination: false,
      navigation: false,
      thumbnails: false,
      playPause: false,
      pauseOnClick: false,
      autoPlay:true,
      hover: false,
      overlayer: true,
      loader: 'none',
      minHeight: '700px',
      time: 400000,
    });
  };
}


// JS tilt Effect
function hoverTilt () {
  var tiltBlock = $('.js-tilt');
  if(tiltBlock.length) {
    $('.js-tilt').tilt({
        glare: true,
        maxGlare: .3
    })
  }
}



// Testimonial Slider 
function testimonialSlider () {
  var tSlider = $ (".testimonial-slider");
  if(tSlider.length) {
      tSlider.owlCarousel({
        loop:true,
        nav:false,
        dots:true,
        autoplay:true,
        autoplayTimeout:4000,
        autoplaySpeed:1000,
        lazyLoad:true,
        items:1
    })
  }
}


// Apps Screenshot Slider
function screenshotSlider () {
  var cSlider = $ (".screenshoot-slider");
  if(cSlider.length) {
      cSlider.owlCarousel({
        loop:true,
        nav:false,
        dots:false,
        autoplay:true,
        autoplayTimeout:4000,
        smartSpeed:1200,
        lazyLoad:true,
        responsive:{
            0:{
                items:1
            },
            551:{
                items:2
            },
            768:{
                items:3
            },
            1200:{
                items:5
            }
        },
    })
  }
}

//Contact Form Validation
function contactFormValidation () {
  var activeForm = $('.form-validation');
  if(activeForm.length){
    activeForm.validate({ // initialize the plugin
      rules: {
        sub: {
          required: true
        },
        email: {
          required: true,
          email: true
        },
        message: {
          required: true
        }
      },
      submitHandler: function(form) {
                $(form).ajaxSubmit({
                    success: function() {
                        $('.form-validation :input').attr('disabled', 'disabled');
                        activeForm.fadeTo( "slow", 1, function() {
                            $(this).find(':input').attr('disabled', 'disabled');
                            $(this).find('label').css('cursor','default');
                            $('#alert-success').fadeIn();
                        });
                    },
                    error: function() {
                        activeForm.fadeTo( "slow", 1, function() {
                            $('#alert-error').fadeIn();
                        });
                    }
                });
            }
        });
  }
}


// Close suddess Alret
function closeSuccessAlert () {
  var closeButton = $ (".closeAlert");
  if(closeButton.length) {
      closeButton.on('click', function(){
        $(".alert-wrapper").fadeOut();
      });
      closeButton.on('click', function(){
        $(".alert-wrapper").fadeOut();
      })
  }
}


// partical JS Bg
function particalBGone () {
  if ($("#particles-js").length) {
    particlesJS("particles-js", {
        "particles": {
          "number": {
            "value": 70,
            "density": {
              "enable": true,
              "value_area": 800
            }
          },
          "color": {
            "value": "#ffffff"
          },
          "shape": {
            "type": "circle",
            "stroke": {
              "width": 0,
              "color": "#000000"
            },
            "polygon": {
              "nb_sides": 5
            },
            "image": {
              "src": "img/github.svg",
              "width": 100,
              "height": 100
            }
          },
          "opacity": {
            "value": 0.1,
            "random": false,
            "anim": {
              "enable": false,
              "speed": 1,
              "opacity_min": 0.2,
              "sync": false
            }
          },
          "size": {
            "value": 2,
            "random": true,
            "anim": {
              "enable": false,
              "speed": 40,
              "size_min": 0.1,
              "sync": false
            }
          },
          "line_linked": {
            "enable": true,
            "distance": 150,
            "color": "#ffffff",
            "opacity": 0.4,
            "width": 1
          },
          "move": {
            "enable": true,
            "speed": 4,
            "direction": "none",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
              "enable": false,
              "rotateX": 600,
              "rotateY": 1200
            }
          }
        },
        "interactivity": {
          "detect_on": "canvas",
          "events": {
            "onhover": {
              "enable": false,
              "mode": "grab"
            },
            "onclick": {
              "enable": true,
              "mode": "push"
            },
            "resize": true
          },
          "modes": {
            "grab": {
              "distance": 140,
              "line_linked": {
                "opacity": 1
              }
            },
            "bubble": {
              "distance": 400,
              "size": 40,
              "duration": 2,
              "opacity": 8,
              "speed": 3
            },
            "repulse": {
              "distance": 200,
              "duration": 0.4
            },
            "push": {
              "particles_nb": 4
            },
            "remove": {
              "particles_nb": 2
            }
          }
        },
        "retina_detect": true
      });


      /* ---- stats.js config ---- */

      var count_particles, stats, update;
      stats = new Stats;
      stats.setMode(0);
      stats.domElement.style.position = 'absolute';
      stats.domElement.style.left = '0px';
      stats.domElement.style.top = '0px';
      document.body.appendChild(stats.domElement);
      count_particles = document.querySelector('.js-count-particles');
      update = function() {
        stats.begin();
        stats.end();
        if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) {
          count_particles.innerText = window.pJSDom[0].pJS.particles.array.length;
        }
        requestAnimationFrame(update);
      };
      requestAnimationFrame(update);
  };
}

// partical JS Bg
function particalBGtwo () {
  if ($("#particles-js-two").length) {
    particlesJS("particles-js-two", {
        "particles": {
          "number": {
            "value": 7,
            "density": {
              "enable": true,
              "value_area": 800
            }
          },
          "color": {
            "value": "#fff"
          },
          "shape": {
            "type": "polygon",
            "stroke": {
              "width": 0,
              "color": "#fff"
            },
            "polygon": {
              "nb_sides": 6
            },
            "image": {
              "src": "img/github.svg",
              "width": 100,
              "height": 100
            }
          },
          "opacity": {
            "value": 0.03,
            "random": true,
            "anim": {
              "enable": false,
              "speed": 1,
              "opacity_min": 0.1,
              "sync": false
            }
          },
          "size": {
            "value": 160,
            "random": false,
            "anim": {
              "enable": true,
              "speed": 10,
              "size_min": 40,
              "sync": false
            }
          },
          "line_linked": {
            "enable": false,
            "distance": 200,
            "color": "#ffffff",
            "opacity": 1,
            "width": 2
          },
          "move": {
            "enable": true,
            "speed": 9,
            "direction": "none",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
              "enable": false,
              "rotateX": 600,
              "rotateY": 1200
            }
          }
        },
        "interactivity": {
          "detect_on": "canvas",
          "events": {
            "onhover": {
              "enable": false,
              "mode": "grab"
            },
            "onclick": {
              "enable": false,
              "mode": "push"
            },
            "resize": true
          },
          "modes": {
            "grab": {
              "distance": 400,
              "line_linked": {
                "opacity": 1
              }
            },
            "bubble": {
              "distance": 400,
              "size": 40,
              "duration": 2,
              "opacity": 8,
              "speed": 3
            },
            "repulse": {
              "distance": 200,
              "duration": 0.4
            },
            "push": {
              "particles_nb": 4
            },
            "remove": {
              "particles_nb": 2
            }
          }
        },
        "retina_detect": true
      });


      /* ---- stats.js config ---- */

      var count_particles, stats, update;
      stats = new Stats;
      stats.setMode(0);
      stats.domElement.style.position = 'absolute';
      stats.domElement.style.left = '0px';
      stats.domElement.style.top = '0px';
      document.body.appendChild(stats.domElement);
      count_particles = document.querySelector('.js-count-particles');
      update = function() {
        stats.begin();
        stats.end();
        if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) {
          count_particles.innerText = window.pJSDom[0].pJS.particles.array.length;
        }
        requestAnimationFrame(update);
      };
      requestAnimationFrame(update);
  };
}


// partical JS Bg
function particalBGthree () {
  if ($("#particles-js-three").length) {
    particlesJS("particles-js-three", {
        "particles": {
    "number": {
      "value": 100,
      "density": {
        "enable": true,
        "value_area": 800
      }
    },
    "color": {
      "value": "#fff"
    },
    "shape": {
      "type": "circle",
      "stroke": {
        "width": 0,
        "color": "#000000"
      },
      "polygon": {
        "nb_sides": 5
      },
      "image": {
        "src": "img/github.svg",
        "width": 100,
        "height": 100
      }
    },
    "opacity": {
      "value": 0.1,
      "random": true,
      "anim": {
        "enable": false,
        "speed": 1,
        "opacity_min": 0.1,
        "sync": false
      }
    },
    "size": {
      "value": 10,
      "random": true,
      "anim": {
        "enable": false,
        "speed": 40,
        "size_min": 0.1,
        "sync": false
      }
    },
    "line_linked": {
      "enable": false,
      "distance": 500,
      "color": "#ffffff",
      "opacity": 0.4,
      "width": 2
    },
    "move": {
      "enable": true,
      "speed": 3,
      "direction": "bottom",
      "random": false,
      "straight": false,
      "out_mode": "out",
      "bounce": false,
      "attract": {
        "enable": false,
        "rotateX": 600,
        "rotateY": 1200
      }
    }
  },
  "interactivity": {
    "detect_on": "canvas",
    "events": {
      "onhover": {
        "enable": true,
        "mode": "bubble"
      },
      "onclick": {
        "enable": true,
        "mode": "repulse"
      },
      "resize": true
    },
    "modes": {
      "grab": {
        "distance": 400,
        "line_linked": {
          "opacity": 0.5
        }
      },
      "bubble": {
        "distance": 400,
        "size": 4,
        "duration": 0.3,
        "opacity": 1,
        "speed": 3
      },
      "repulse": {
        "distance": 200,
        "duration": 0.4
      },
      "push": {
        "particles_nb": 4
      },
      "remove": {
        "particles_nb": 2
      }
    }
  },
  "retina_detect": true
      });


      /* ---- stats.js config ---- */

      var count_particles, stats, update;
      stats = new Stats;
      stats.setMode(0);
      stats.domElement.style.position = 'absolute';
      stats.domElement.style.left = '0px';
      stats.domElement.style.top = '0px';
      document.body.appendChild(stats.domElement);
      count_particles = document.querySelector('.js-count-particles');
      update = function() {
        stats.begin();
        stats.end();
        if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) {
          count_particles.innerText = window.pJSDom[0].pJS.particles.array.length;
        }
        requestAnimationFrame(update);
      };
      requestAnimationFrame(update);
  };
}


// DOM ready function
jQuery(document).on('ready', function() {
	(function ($) {
	   removePlaceholder ();
     scrollToTop ();
     wowAnimation ();
     hoverTilt ();
     BannerSlider ();
     testimonialSlider ();
     screenshotSlider ();
     contactFormValidation ();
     closeSuccessAlert ();
  })(jQuery);
});


// Window load function
jQuery(window).on('load', function () {
   (function ($) {
      prealoader ();
      particalBGone ();
      particalBGtwo ();
      particalBGthree ()
  })(jQuery);
 });
