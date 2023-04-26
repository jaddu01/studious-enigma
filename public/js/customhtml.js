// Custom Js 
$('.must-have-product-slider').slick({
  infinite: true,
  slidesToShow: 5,
  autoplay:false,
  arrows: true,
  dots:false,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1000,
      settings: {
        arrows: true,
        centerMode: false,
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: true,
        centerMode: false,
        slidesToShow: 1
      }
    }
  ]
});

$('.today-savar-product-slider').slick({
  infinite: true,
  slidesToShow: 6,
  autoplay:false,
  arrows: true,
  dots:false,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1000,
      settings: {
        arrows: true,
        centerMode: false,
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: true,
        centerMode: false,
        slidesToShow: 1
      }
    }
  ]
});

$('.most-searched-slider').slick({
  infinite: true,
  slidesToShow: 6,
  autoplay:false,
  arrows: true,
  dots:false,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1000,
      settings: {
        arrows: false,
        centerMode: false,
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: true,
        centerMode: false,
        slidesToShow: 1
      }
    }
  ]
});



// banner
$('.banner_slider').slick({
  infinite: true,
  slidesToShow: 1,
  autoplay:true,
  arrows:false,
  centerMode: false,
  dots:true,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        centerMode: false,
		dots:true,  
        slidesToShow: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: false,
		dots:true,  
        slidesToShow: 1
      }
    }
  ]
});


jQuery(document).ready(function(){
			//Accordion Nav
			jQuery('.mainNav').navAccordion({
				expandButtonText: '<i aria-hidden="true" class="fa fa-angle-down"></i>',  //Text inside of buttons can be HTML
				collapseButtonText: '<i aria-hidden="true" class="fa fa-angle-up"></i>'
			}, 
			function(){
				console.log('Callback')
			});
			jQuery(".menubar").click(function(){
				jQuery("body").toggleClass("mobilemenu_area");	
			});
			
			
		});







 $(".searchicon").click(function(){
 $(".top_social").toggleClass("addclssearch");	
 });

  $(".rightregister_btn").click(function(){
 $("body").toggleClass("register_visible");	
 }); 
 

/* py js 24 march start */

$('.add').click(function () {
    if ($(this).prev().val() < 100) {
      $(this).prev().val(+$(this).prev().val() + 1);
    }
});
$('.sub').click(function () {
    if ($(this).next().val() > 1) {
      if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
    }
});

/* py js 24 march end */

//Vijay 16-06-2020
$('.ads-slider').slick({
  infinite: true,
  slidesToShow: 3,
  autoplay:false,
  arrows: true,
  dots:false,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1000,
      settings: {
        arrows: true,
        centerMode: false,
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: true,
        centerMode: false,
        slidesToShow: 1
      }
    }
  ]
});