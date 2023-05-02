$(document).ready(function(){
    $('.main-slider').slick({
        dots: true,
        arrows: false,
        infinite: true,
        speed: 300,
        adaptiveHeight: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    dots:false
                }
            },
        ]
    });
});
