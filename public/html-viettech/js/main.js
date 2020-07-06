$(window).bind('load', function () {
    // if($(".sidebar-left-filter-wrap").length > 0){
    //     $(".sidebar-left-filter-wrap .card h5").click((e) => {
    //         if(!$(e.currentTarget).parent().next().hasClass('show')){
    //             $(e.currentTarget).addClass('chg');
    //         }else{
    //             $(e.currentTarget).removeClass('chg');
    //         }
    //     });
    // }

    if($('footer .user-support').length > 0){
        $('footer .user-support').click(function(){
            $('footer .social-button-content').slideToggle();
        });
    }
    if($(window).width() < 992){
        if($('footer .user-support').length > 0){
            $('footer .social-button-content').css("display", "none");
        }
        $(".mobile-menu .wrap-cate-has-child i").click(function(){
            if($(this).parent().next().css('display') == 'none'){
                $(this).attr("class", "fa fa-angle-up");
            }else{
                $(this).attr("class", "fa fa-angle-down");
            }
            $(this).parent().next().slideToggle();
        });
        $(".mobile-menu .close-menu").click(function(){
            $(".mobile-menu").css("left", "-80vw");
        });
        $(".header-top .nav-mobile").click(function(){
            $(".mobile-menu").css("left", "0");
        });

        $('.page_laptop .show_sort').click(function(){
            $(".box-head .modal-sort").removeClass('d-none');
            $(".overlay").removeClass('d-none');
        });

        $('.page_laptop .modal-sort li').click(function(){
            $('.page_laptop .modal-sort li').removeClass('active');
            $(this).addClass('active');
        })

        $('.page_laptop .close-modal-sort').click(function(){
            $(".box-head .modal-sort").addClass('d-none');
            $(".overlay").addClass('d-none');
        })
        $('.page_laptop .box-filter .child button').click(function(){
            $('.page_laptop .box-filter .dropdown-list').addClass('d-none');
            $('.page_laptop .box-filter button').removeClass('active');
            $(this).addClass('active');
            $(this).next().removeClass('d-none');
        })
        $('.page_laptop .box-filter .dropdown-list li').click(function(){
            $('.page_laptop .box-filter .dropdown-list li').removeClass('active');
            $(this).addClass('active');
        })

        $(document).mouseup(function(e) 
        {
            var container = $('.page_laptop .box-filter .dropdown-list');
        
            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) 
            {
                $('.page_laptop .box-filter .dropdown-list').addClass('d-none')
            }
        });
        

    }
    if($(".tra-gop").length > 0){
        $(".option-card").click(function(){
           shop.setGetParameter('index', 1);
            $(".option").removeClass("active")
            $(this).addClass('active')
            $(".processing .using-comp").addClass('d-none');
            $(".processing .using-card").removeClass('d-none');
        });
        $(".option-comp").click(function(){
            shop.setGetParameter('index', 0);
            $(".option").removeClass("active")
            $(this).addClass('active')
            $(".processing .using-card").addClass('d-none');
            $(".processing .using-comp").removeClass('d-none');
        });
    }
    if($(".page-accessory-detail").length > 0){
        if($(window).width() < 992){
            let faq_html = $('#faq').html();
            $('#faq').remove();
            $(".faq-mobile").attr('id', 'faq');
            $(".faq-mobile").html(faq_html);
        }
    }
    if($('.list_store').length > 0){
        $(".list_store .fancybox").fancybox();
    }

    var check_isShow = 0;
    // $('.js-show-menu-mobile').click(function () {
    //     $('.menu').addClass('is-show');
    // });
    $('.js-hide-menu').on('click', function () {
        if (check_isShow == 1) {
            check_isShow = 0;
            $('.mega-menu-drop.is-show').removeClass('is-show');
        } else {
            $('.menu').removeClass('is-show');
        }
    });

    $('.item-has-child >a').click(function () {
        check_isShow = 1;
        $(this).find('.mega-menu-drop').addClass('is-show');
    });

    $(document).mouseup(function (e) {
        var container = $(".menu");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $('.menu').removeClass('is-show');
        }
    });

    $(window).scroll(function () {
        $('.menu').removeClass('is-show');
    });

    $('.ft-view-more .show-info-mobile').on('click', function() {
        $('.foot-col-1').slideToggle();
    });

    var $carousel = $('.js-carousel'),
        $carouselIcons = ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'];

    function runnCarousel() {
        if (!$().owlCarousel) {
            console.log('carousel: owlCarousel plugin is missing.');
            return true;
        }
        if ($carousel.length > 0) {
            $carousel.each(function () {
                var elem = $(this),
                    carouselNav = elem.attr('data-arrows'),
                    carouselDots = elem.attr('data-dots') || true,
                    carouseldotsData = elem.attr('data-dotsData') || false,
                    carouselAutoPlay = elem.attr('data-autoplay') || false,
                    carouselAutoplayTimeout = elem.attr('data-autoplay-timeout') || 5000,
                    carouselAutoWidth = elem.attr('data-auto-width') || false,
                    resizeHeight = elem.attr('auto-height') || false,
                    carouseAnimateIn = elem.attr('data-animate-in') || false,
                    carouseAnimateOut = elem.attr('data-animate-out') || false,
                    carouselLoop = elem.attr('data-loop') || false,
                    carouselMargin = elem.attr('data-margin') || 0,
                    carouselVideo = elem.attr('data-video') || false,
                    carouselItems = elem.attr('data-items') || 4,
                    carouselItemsLg = elem.attr('data-items-lg') || Number(carouselItems),
                    carouselItemsMd = elem.attr('data-items-md') || Number(carouselItemsLg),
                    carouselItemsSm = elem.attr('data-items-sm') || Number(carouselItemsMd),
                    carouselItemsXs = elem.attr('data-items-xs') || Number(carouselItemsSm),
                    carouselItemsXxs = elem.attr('data-items-xxs') || Number(carouselItemsXs);
                if (carouselItemsMd >= 3) {
                    var carouselItemsSm = elem.attr('data-items-sm') || Number(2);
                }
                if (carouselItemsSm >= 2) {
                    var carouselItemsXs = elem.attr('data-items-xs') || Number(2);
                }
                if (carouselItemsXs >= 1) {
                    var carouselItemsXxs = elem.attr('data-items-xxs') || Number(1);
                }
                if (carouselNav == 'false') {
                    carouselNav = false;
                } else {
                    carouselNav = true;
                }
                if (carouselDots == 'false') {
                    carouselDots = false;
                } else {
                    carouselDots = true;
                }
                if (carouseldotsData == 'true') {
                    carouseldotsData = true;
                } else {
                    carouseldotsData = false;
                }
                if (carouselAutoPlay == 'false') {
                    carouselAutoPlay = false;
                }
                var t = setTimeout(function () {
                    elem.owlCarousel({
                        nav: carouselNav,
                        dots: carouselDots,
                        dotsData: carouseldotsData,
                        thumbs: true,
                        thumbsPrerendered: true,
                        navText: $carouselIcons,
                        autoplay: carouselAutoPlay,
                        autoplayTimeout: carouselAutoplayTimeout,
                        autoplayHoverPause: true,
                        autoWidth: carouselAutoWidth,
                        autoHeight: resizeHeight,
                        loop: carouselLoop,
                        margin: Number(carouselMargin),
                        smartSpeed: Number(1300),
                        video: carouselVideo,
                        animateIn: carouseAnimateIn,
                        animateOut: carouseAnimateOut,
                        video: true,
                        lazyLoad: true,
                        videoWidth: true,
                        videoHeight: true,
                        onInitialize: function (event) {
                            // setTimeout(function () {
                            elem.addClass("owl-carousel owl-theme");
                            //    }, 1000);
                        },
                        responsive: {
                            0: {
                                items: Number(carouselItemsXxs)
                            },
                            480: {
                                items: Number(carouselItemsXs)
                            },
                            768: {
                                items: Number(carouselItemsSm)
                            },
                            992: {
                                items: Number(carouselItemsMd)
                            },
                            1200: {
                                items: Number(carouselItemsLg)
                            }
                        }
                    });
                }, 0);
            });
        }
    }
    runnCarousel();
    $(document).ready(function () {

        var sync1 = $("#sync1");
        var sync2 = $("#sync2");
        var slidesPerPage = 6; //globaly define number of elements per page
        var syncedSecondary = true;

        if ($(window).width() > 768) {
            sync1.owlCarousel({
                items: 1,
                slideSpeed: 5000,
                nav: false,
                autoplay: true,
                dots: false,
                loop: true,
                margin: 10,
                lazyLoad: true,
                responsiveRefreshRate: 200,
                navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
            }).on('changed.owl.carousel', syncPosition);

            sync2
                .on('initialized.owl.carousel', function () {
                    sync2.find(".owl-item").eq(0).addClass("current");
                })
                .owlCarousel({
                    items: slidesPerPage,
                    dots: false,
                    nav: false,
                    loop: false,
                    autoplay:true,
                    smartSpeed: 200,
                    slideSpeed: 5000,
                    margin: 10,
                    lazyLoad: true,
                    slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
                    responsiveRefreshRate: 100
                }).on('changed.owl.carousel', syncPosition2);

            function syncPosition(el) {
                //if you set loop to false, you have to restore this next line
                //var current = el.item.index;

                //if you disable loop you have to comment this block
                var count = el.item.count - 1;
                var current = Math.round(el.item.index - (el.item.count / 2) - .5);

                if (current < 0) {
                    current = count;
                }
                if (current > count) {
                    current = 0;
                }

                //end block

                sync2
                    .find(".owl-item")
                    .removeClass("current")
                    .eq(current)
                    .addClass("current");
                var onscreen = sync2.find('.owl-item.active').length - 1;
                var start = sync2.find('.owl-item.active').first().index();
                var end = sync2.find('.owl-item.active').last().index();

                if (current > end) {
                    sync2.data('owl.carousel').to(current, 100, true);
                }
                if (current < start) {
                    sync2.data('owl.carousel').to(current - onscreen, 100, true);
                }
            }

            function syncPosition2(el) {
                if (syncedSecondary) {
                    var number = el.item.index;
                    sync1.data('owl.carousel').to(number, 100, true);
                }
            }

            sync2.on("click", ".owl-item", function (e) {
                e.preventDefault();
                var number = $(this).index();
                sync1.data('owl.carousel').to(number, 300, true);
            });
        } else {
            sync1.owlCarousel({
                items: 1,
                slideSpeed: 5000,
                nav: false,
                autoplay: true,
                dots: true,
                loop: true,
                margin: 10,
                lazyLoad: true,
                responsiveRefreshRate: 200,
                navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
            });
        }


        sync2.on("click", ".owl-item", function (e) {
            e.preventDefault();
            var number = $(this).index();
            sync1.data('owl.carousel').to(number, 300, true);
        });



        if ($('.stars .star').length > 0) {
            $('.stars .star').each(function () {
                let percent = parseInt($(this).attr('data-vote') / 5 * 100);
                percent == 90 ? percent += 2 : '';
                percent == 70 ? percent += 1 : '';

                let percent_css = percent.toString() + "%";
                $(this).css("width", percent_css);
            });
        }

        setTimeout(function () {
            if ($('#pb_loader').length > 0) {
                $('#pb_loader').removeClass('show');
            }
        }, 1000);

        /* Quantity */
        $('.plus').click(function () {
            $(this).prev().val(+$(this).prev().val() + 1);
        });
        $('.minus').click(function () {
            if ($(this).next().val() > 0) $(this).next().val(+$(this).next().val() - 1);
        });

        /* Tab */

        $(".comment-wrap-top-tab .nav-tabs a").click(function () {
            $(this).tab('show');
        });

        /* Show box Submit review */
        $('.modal-box-comment').hide();
        $('.box-send-evaluation').click(function () {
            $('.modal-box-comment').slideDown();
        });
        $('.actions .action-right .btn-cancel').click(function () {
            $('.modal-box-comment').slideUp();
        });

        /* CLICK SHOW ADMIN REPLY */

        // $('.box-reply').hide();
        // $('.reply a').click(function() {
        //     console.log($(this).nextSibling());
        //     $('.box-reply').slideDown();
        // });

        $('.filter-value img').click(function () {
            $(this).parent().hide();
        });

        $('.filter-box .delete img').click(function () {
            $('.filter-box').hide();
        });

        /* Filter price */
        $('.has-dropdowm').hide();
        $('.filter-sort').click(function () {
            $('.has-dropdowm').toggle();
        });

        $('li .has-icon').click(function () {
            $(this).addClass('active');
            $(this).parent().siblings().find('.has-icon').removeClass('active');
        });

        $('.has-icon').click(function () {
            var x = $(this).text();
            $('.sortText').html(x);
        });
    });

    /* smooth scroll */
    if($('.product-tab').length > 0){
        var scroll = new SmoothScroll('.product-tab a[href*="#"]');
    }


    /* Readmore */
    $('.nav-toggle').click(function () {
        var collapse_content_selector = $(this).attr('href');
        var toggle_switch = $(this);
        $(collapse_content_selector).toggle(function () {
            if ($(this).css('display') == 'none') {
                toggle_switch.html('Đọc thêm');
            } else {
                toggle_switch.html('Thu gọn');
            }
        });
    });

    $('.nav-toggle-comment').click(function () {
        var collapse_content_selector = $(this).attr('href');
        var toggle_switch = $(this);
        $(collapse_content_selector).toggle(function () {
            if ($(this).css('display') == 'none') {
                toggle_switch.html('Xem thêm');
            } else {
                toggle_switch.html('Thu gọn');
            }

        });

        $('.product-item-2 .star input').rating();
        $('.product-item-2-suggest .star input').rating();
        $('.box-rating .star input').rating();
    });

    if ($('.tab-product-slider').length > 0) {
        $('.tab-product-slider .tab-control a').click(function () {
            $('.tab-product-slider .tab-control a').removeClass('active');
            $(this).addClass('active');
            let tab = $(this).attr('data-tab');
            $(tab).show().siblings().hide();
        });
    }
    $('.head-mega-list-drop .drop-control').click(function () {
        $(this).toggleClass('active');
        $(this).parent().siblings().find('.content').hide();
        $(this).parent().find('.content').slideToggle(200);
    });

    (function ($) {
        $(document).ready(function () {
            var listLastVideo = $("#lastVideo ul li");
            listLastVideo.first().addClass("active");
            listLastVideo.each(function (index, element) {
                var $this = $(this);
                $this.on("click", function (e) {
                    listLastVideo.removeClass("active");
                    $this.addClass("active");
                    var iframeVideo = $("#lastVideoShow iframe");
                    var viewVideo = $("#lastVideoShow .fs-vds-view");
                    var timeVideo = $("#lastVideoShow .fs-vds-time");
                    var data_id = $this.data("video-id");
                    iframeVideo.attr("src", "https://www.youtube.com/embed/"+data_id+"?showinfo=0");
                    viewVideo.html($this.data("video-view") + " luợt xem");
                    timeVideo.html($this.data("video-date"));
                });
            });
        });
    })(window.jQuery);


    if ($('#time-input').length > 0) {
        $('#time-input').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    }
    if ($('#time_input_bank').length > 0) {
        $('#time_input_bank').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    }
    if ($('.popup-success').length > 0) {
        $('.js-close-success-main').click(function () {
            $('.popup-success').hide(200);
            $('body').css('overflow', 'auto');
        });
        $('.js-show-success-main').click(function () {
            $('.popup-success').show(200);
            $('body').css('overflow', 'hidden');
        });
    }
    $('.discount-item a').click(function () {
        $('.discount-item a').removeClass('active');
        $(this).addClass('active');
    });


    // handle home page responsive
    if ($('.right-banner').length > 0 && $(window).width() < 767) {
        let right_banner = $('.right-banner').html();
        $('.right-banner-mobile').append(right_banner);
    }

    if($('.main_compare_products'.length > 0)){
        const demo_dom = [['.prd-1 .demo-sync1', '.prd-1 .demo-sync2'], ['.prd-2 .demo-sync1', '.prd-2 .demo-sync2']];
        demo_dom.forEach(function(item){
            var sync1 = $(item[0]);
            var sync2 = $(item[1]);
            var slidesPerPage = 3; //globaly define number of elements per page
            var syncedSecondary = true;
        
            if ($(window).width() > 768) {
                sync1.owlCarousel({
                    items: 1,
                    slideSpeed: 2000,
                    nav: false,
                    autoplay: false,
                    dots: false,
                    loop: true,
                    margin: 10,
                    lazyLoad: true,
                    responsiveRefreshRate: 200,
                    navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
                }).on('changed.owl.carousel', syncPosition);
        
                sync2
                    .on('initialized.owl.carousel', function() {
                        sync2.find(".owl-item").eq(0).addClass("current");
                    })
                    .owlCarousel({
                        items: slidesPerPage,
                        dots: false,
                        nav: false,
                        smartSpeed: 200,
                        slideSpeed: 500,
                        margin: 10,
                        lazyLoad: true,
                        slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
                        responsiveRefreshRate: 100
                    }).on('changed.owl.carousel', syncPosition2);
        
                function syncPosition(el) {
                    //if you set loop to false, you have to restore this next line
                    //var current = el.item.index;
        
                    //if you disable loop you have to comment this block
                    var count = el.item.count - 1;
                    var current = Math.round(el.item.index - (el.item.count / 2) - .5);
        
                    if (current < 0) {
                        current = count;
                    }
                    if (current > count) {
                        current = 0;
                    }
        
                    //end block
        
                    sync2
                        .find(".owl-item")
                        .removeClass("current")
                        .eq(current)
                        .addClass("current");
                    var onscreen = sync2.find('.owl-item.active').length - 1;
                    var start = sync2.find('.owl-item.active').first().index();
                    var end = sync2.find('.owl-item.active').last().index();
        
                    if (current > end) {
                        sync2.data('owl.carousel').to(current, 100, true);
                    }
                    if (current < start) {
                        sync2.data('owl.carousel').to(current - onscreen, 100, true);
                    }
                }
        
                function syncPosition2(el) {
                    if (syncedSecondary) {
                        var number = el.item.index;
                        sync1.data('owl.carousel').to(number, 100, true);
                    }
                }
        
                sync2.on("click", ".owl-item", function(e) {
                    e.preventDefault();
                    var number = $(this).index();
                    sync1.data('owl.carousel').to(number, 300, true);
                });
            } else {
                sync1.owlCarousel({
                    items: 1,
                    slideSpeed: 2000,
                    nav: false,
                    autoplay: false,
                    dots: true,
                    loop: true,
                    margin: 10,
                    lazyLoad: true,
                    responsiveRefreshRate: 200,
                    navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
                });
            }
        
        
            sync2.on("click", ".owl-item", function(e) {
                e.preventDefault();
                var number = $(this).index();
                sync1.data('owl.carousel').to(number, 300, true);
            });
        });
       
    }
  

})
$(window).bind('load', function () {
    if ($('#product-tabs').length > 0) {
        $(function () {
            var locationProductTab = $('#product-tabs').offset().top;
            $(window).scroll(function () {
                var locationBody = $(window).scrollTop();
                if (locationBody >= locationProductTab) {
                    $('.product-tab').addClass('has-scroll-menu');
                } else {
                    $('.product-tab').removeClass('has-scroll-menu');
                }
            });
        });
    }


    // Gets the video src from the data-src on each button
    var $videoSrc;
    $('.video-btn').click(function () {
        $("#video").attr('src', "");
        $video_id = $(this).data("id-video");
        $videoSrc = "https://www.youtube.com/embed/"+$video_id+"?showinfo=0";
    });

    // when the modal is opened autoplay it  
    $('#myModal').on('shown.bs.modal', function (e) {

        // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
        $("#video").attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
    })
    // stop playing the youtube video when I close the modal
    $('#myModal').on('hide.bs.modal', function (e) {
        // a poor man's stop video
        $("#video").attr('src', $videoSrc);
    })

});

function apply_coupon() {
    var coupon = $('#coupons').val();
        coupon = coupon.replace(/\s/g, '');
    if (coupon != '') {
        shop.ajax_popup('check-coupon', 'post', {coupon: coupon,}, function (json) {
            if (json.error == 1) {
                Swal.fire({
                    title: 'Thông báo',
                    text: json.msg,
                    type: 'warning',
                    confirmButtonText: 'Đồng ý',
                    confirmButtonColor: '#f37d26',
                });
            } else {
                Swal.fire({
                    title: 'Thông báo',
                    text: 'Áp dụng Coupon thành công!',
                    type: 'success',
                    confirmButtonText: 'Đồng ý',
                    confirmButtonColor: '#f37d26',
                }).then((result) => {
                    shop.setGetParameter('coupon_code', json.data.coupon_code);
                });
            }
        });
    }
}
$(document).mouseup(function(e)
{
    var container = $('.page_laptop .box-filter .dropdown-list');

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0)
    {
        $('.page_laptop .box-filter .dropdown-list').addClass('d-none')
    }
});

if ($('.back-to-top').length) {
    var scrollTrigger = 100, // px
        backToTop = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > scrollTrigger) {
                $('.back-to-top').show(100);
            } else {
                $('.back-to-top').hide(100);
            }
        };
    backToTop();
    $(window).on('scroll', function () {
        backToTop();
    });
    $('.back-to-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    });
}

// $('.pay-method .tab-control a').on('click', function () {
//     $('.pay-method-value').val($(this).attr('data-value'));
//     $(this).addClass('active').siblings().removeClass('active');
// });