$(document).ready(function(){
    $('.main-slider').slick({
        dots: true,
        arrows: false,
        infinite: true,
        speed: 1000,
        autoplaySpeed: 5000,
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
