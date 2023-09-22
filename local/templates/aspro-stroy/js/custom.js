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


    $('form[name="aspro_stroy_callback"]').submit(function () {
        ym(45980856,'reachGoal','zadavat-vopros');
    })
});
BX.addCustomEvent(document, "b24-sitebutton-load", function (e){
    e.buttons.add({
        'id': 'my-whatsapp',
        'href': 'https://api.whatsapp.com/send/?phone=79149025931',
        'title': 'Whatsapp',
        'target': '_blank',
        'sort': 1000,
        'icon': '/images/whatsapp.png'
    });
});
$(document).ready(function () {
    const ORD = {
        init: function () {
            this.setEntities()
            this.setEvents()
        },
        setEvents: function () {
            this.ord.on('click', $.proxy(this.ordClickHandler, this))
            this.popupLink.on('click.copyOrdLink', $.proxy(this.copyLinkHandler, this))
        },
        setEntities: function () {
            this.ord = $('.ord-link')
            this.currentORD = {}
            this.popup = $('#bx_ord_popup')
            this.openClass = 'ord-open'
            this.linkCopied = 'link-copied'
            this.document = $(document)
            this.popupLink = this.popup.find('.copy-ord-link');
        },
        copyLinkHandler: function () {
            const input = this.currentORD.children('.ord-link-href')
            input.select()
            document.execCommand("copy")
            this.popupLink.addClass(this.linkCopied)
        },
        ordClickHandler: function (event) {
            this.currentORD = $(event.target)
            if (this.currentORD.hasClass(this.openClass))
                this.hideOrd()
            else
                this.showOrd()
        },
        showOrd: function (){
            if (this.currentORD.hasClass(this.openClass)) return false
            this.setOrdPopupPosition()
            this.currentORD.addClass(this.openClass)
            this.popup.show()
            this.document.on('mouseup.outerOrd', $.proxy(this.clickOuterOrdHandler, this))
        },
        hideOrd: function () {
            if (!this.currentORD.hasClass(this.openClass)) return false
            this.document.off('mouseup.outerOrd')
            this.currentORD.removeClass(this.openClass)
            this.popupLink.removeClass(this.linkCopied)
            this.popup.offset({top: 0, left: 0})
            this.popup.hide()
        },
        clickOuterOrdHandler: function (event) {
            if (this.currentORD.is(event.target)
                || this.currentORD.has(event.target).length !== 0
            ){
                return
            }
            if (!this.popup.is(event.target)
                && this.popup.has(event.target).length === 0
            ){
                this.hideOrd()
            }
        },
        setOrdPopupPosition: function () {
            let offset = this.currentORD.offset()
            let top = offset.top + this.currentORD.height() + 10
            const windowWidth = $(window).outerWidth()
            const popupWidth = this.popup.outerWidth()
            const ordWidth = this.currentORD.outerWidth()
            let left = offset.left - popupWidth / 2 + ordWidth / 2
            if (windowWidth < left + popupWidth + 20)
                left = windowWidth - popupWidth - 20;
            if (left < 0)
                left = 20
            this.popup.offset({top: top, left: left})
        }
    }
    ORD.init()
});