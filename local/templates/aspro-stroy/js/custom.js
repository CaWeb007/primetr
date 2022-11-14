$(document).ready(function(){
    // open badge on click read more 

$('.read-more-badge').click(function(){
    $(this).prev('.badge-text').fadeToggle(100);
    $(this).html($(this).html() == 'Подробнее...' ? 'Скрыть...' : 'Подробнее...');
});

$('.main .container:first-child').addClass('mobile-slider-container');

var text = $('.reviews-more-text');
$('.more-text').click(function(){
  $(this).parent().find('.reviews-more-text').toggle();
  $(this).html($(this).html() == 'читать полностью' ? 'скрыть...' : 'читать полностью');
});

    //Check to see if the window is top if not then display button
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.to-top').fadeIn();
        } else {
            $('.to-top').fadeOut();
        }
    });
    
    //Click event to scroll to top
    $('.to-top').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });

    // stars rate function 
    $('.rating a').on('click', function(){
      $('.rating span, .rating a').removeClass('active');

      $(this).addClass('active');
      $('.rating span').addClass('active');
    });

    // video
    $(function () {
        var $videoContainer = $('#video'),
            $videoControls = $('.video-control'),
            $videoControls1 = $('.demo-play'),
            $video = $('#myVideo')[0],
            $video1 = $('#myVideo1')[0];

        $videoControls.click(function () {
            if ($video.paused) {
                $video.play();
                $videoContainer.addClass('video-is-playing');
            } else {
                $video.pause();
                $videoContainer.removeClass('video-is-playing');
            }
        });






		$('.red-color.play-button').click(function () {
            if ($video.paused) {
                $video.play();
                $videoContainer.addClass('video-is-playing');

            } else {
                $video.pause();
                $videoContainer.removeClass('video-is-playing');

            }
        });

        $videoControls1.click(function(){
            if($video1.paused) {
                $video1.play();
					   setTimeout(function() {
          $('#carouselImage').carousel('pause'); 
        }, 500);
            } else {
                $video1.pause();
                $("#carouselImage").carousel();
            }
        });
        if ($video1)
            $video1.play( function() {
                setTimeout(function() {
              $('#carouselImage').carousel('pause');
            }, 500);
            });

        if ($video1)
            $video1.pause( function() {
                $("#carouselImage").carousel();
            });


    });

});

//change html inner by select option value
var changePrice = function() {
    var select = $('.select-label');
    var displayPrice = $('.price-now');
    var displayPriceBefore = $('.price-before');


   select.click(function(){
        var before = $(this).attr('data-valuea');
        var now = $(this).attr('data-valueb');
        displayPrice.text(now);
        displayPriceBefore.text(before);
    });
}

changePrice();

// slick slider

$(document).ready(function(){
  $('.reviews-row').slick({
    dots: false,
    arrows: true,
    prevArrow:"<button type='button' class='slick-prev slick-arrow'><img src='/local/templates/aspro-stroy/img/arrow.svg'></button>",
    nextArrow:"<button type='button' class='slick-next slick-arrow'><img src='/local/templates/aspro-stroy/img/arrow.svg'></button>",
    infinite: true,
    speed: 300,
    adaptiveHeight: false,
    slidesToShow: 3,
    slidesToScroll: 3,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          centerPadding:'20px',
          infinite: true,
          dots: false
        }
      },
      {
        breakpoint: 900,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });
});

