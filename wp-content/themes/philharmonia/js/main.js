(function($) {

    $(function() {

        $('.actSlider').owlCarousel({
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            autoplay:true,
            autoplayTimeout:4000,
            autoplayHoverPause:false,
            margin:0,
            nav:true,
            loop:true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1000:{
                    items:1
                }
            }
        });

        $('.actSliderFooter').owlCarousel({
            autoplay:true,
            autoplayTimeout:400000,
            autoplayHoverPause:false,
            margin:0,
            nav:true,
            loop:true,
            autoWidth:true,
            responsive:{
                0:{
                    items:1,
                    autoWidth:false,
                    autoplayTimeout:4000,
                    loop:true
                },
                767:{
                    items:3,
                    autoplayTimeout:4000,
                    autoWidth:false
                },
                991:{
                    items:4,
                    autoWidth:false,
                    autoplayTimeout:4000,
                    loop:true
                },
                1300:{
                    items:7,
                    autoWidth:true,
                    loop:false
                }
            }
        });

        var limit     = $(window).height()/4,
            backToTop = $('.back-to-top');

        $(window).scroll(function () {
            if ( $(this).scrollTop() > limit ) {
                backToTop.fadeIn();
            } else {
                backToTop.fadeOut();
            }
        });

        // scroll body to 0px on click
        backToTop.click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 1500);
            return false;
        });

        $('.play_button').click(function () {
            $('.video_container .poster').remove()
            $('.play_button').remove()
            var dataVideoUrl = $('.video_container').data('youtube-src')
            var iframeUrl = '<iframe width="100%" height="555px" src="'+ dataVideoUrl +'?rel=0&amp;autoplay=1" frameborder="0" allowfullscreen></iframe>';
            $('.video_container').append(iframeUrl)
        });

        var timerId = setInterval(function() {
            var heightBlock = $('.ourEvent img').height();
            $('.concertBlockText').css('height', heightBlock - 2 );
            $( window ).resize(function() {
                var heightBlock = $('.ourEvent img').height();
                $('.concertBlockText').css('height', heightBlock - 2 );
            });
        }, 20);

        $.h5Validate.addPatterns({
            mailUser: /^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/
        });
        $('form').h5Validate();

        function leftMenu() {
            var widthWindow = $("body").width();
            if (widthWindow < 767) {
                $(".icon-menu").click(function() {
                    $(".menuActive").toggleClass("menuWidth");
                    $("body").toggleClass("bodyWidth");
                });
            }
        }
        function searchLine() {
            $('.sendSearch').click(function(){
                $(".searchRow").toggleClass("widthSearch");
                $(".sendSearch").toggleClass("widthSendSearch");
                $(".inputSearch").focus();
            });
        }

        leftMenu();
        searchLine();

    });//document ready

})(jQuery);



