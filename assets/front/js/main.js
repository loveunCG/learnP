/*
Copyright (c) 2016
[Custom JS Script]
Theme Name : Tutors - Jobs Directory Tutors HTML Template
Version    : 1.0
Author     : DigiSamaritan
Author URL : http://themeforest.net/user/digisamaritan
Support    : digisamaritan@gmail.com
*/
/*jslint browser: true*/
/*global $, jQuery, alert*/

/*--------------------------------------------------------------
TABLE OF CONTENTS:
----------------------------------------------------------------
# Document Ready
## Vars
## Page Pre Loading
## Owl Carousel - Top Rated Tutors Slider
## Owl Carousel - Testimonial Slider
## JRATE Star Rating
## Select2 Dropdown Picker
## Dashboard Offcanvas Menu
## Masonry Flex Grid
## Scrioll To Top
## Magnificpop Lightbox Gallery
## Magnificpop Video Gallery
## DataTables
## Table Column Highlighting
## Show-Hide More Text
## CounterUp - number counter
## File Upload - fileinput
## Marquee News Scroller
## Flatpickr - datetpicker
--------------------------------------------------------------*/


/* Document Ready */
jQuery(document).ready(function () {
    "use strict";

    /* Vars */
    var showChar,
        ellipsestext,
        moretext,
        lesstext,
        content,
        $dashboard_section,
        $popup_gallery;

    // Page Pre Loading
    $(window).load(function () { // makes sure the whole site is loaded
        $('#status').fadeOut(); // will first fade out the loading animation
        $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
        $('body').delay(350).css({
            'overflow': 'visible'
        });
    });

    //Owl Carousel - Top Rated Tutors Slider
    $(".toprated-slider").owlCarousel({
        items: 4,
        loop: true,
        nav: true,
        navText: [
            "<i class=' left-arrow'></i>",
            "<i class=' right-arrow'></i>"
        ],
        dots: false,

        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            992: {
                items: 3
            },
            1000: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });

    //Owl Carousel - Testimonial Slider
    $(".testimonial-slider").owlCarousel({
        items: 3,
        loop: true,
        nav: true,
        navText: [
            "<i class=' left-arrow'></i>",
            "<i class=' right-arrow'></i>"
        ],
        dots: false,

        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            992: {
                items: 2
            },
            1000: {
                items: 2
            },
            1200: {
                items: 3
            }
        }
    });

    /* JRATE Star Rating -- SVG based Rating jQuery plugin -- for docs rafy-fa plugin -- http://jacob87.github.io/raty-fa/ */
    $('.startRate').raty({
        score: 3
    });

    //Select2 Dropdown Picker -- jQuery replacement for Select boxes
    $(".select-picker").select2();
    $(".select-picker-default").select2({
        minimumResultsForSearch: Infinity
    });

    //Select2 Multi Select - Select2 can be used to quickly set up fields used for tagging.
    $('.tags').select2({
        data: [],
        tags: true,
        tokenSeparators: [','],
        placeholder: "Select from the list",
        closeOnSelect: true
    });
    
    /*select2 multiple */
    $(".multiple-tags").select2();
    
    /*Dashboard Profile Button - Worked as offcanvas menu icon when responsive */
    $('.offcanvas-btn').on('click', function (e) {
        $(this).toggleClass('btnborder').toggle();

        /*Offcanvas menu effect  - When offcanvas menu triggers dasboard panel have opacity */
        $(".dashboard-panel").toggleClass("ad-opacity");
    });

    //Dashboard Offcanvas Menu
    $('.offcanvas-btn').click(function () {
        $('.offcanvas').toggleClass('active');
    });
    $('[data-toggle="popover"]').popover();

    /* Flex-grid Boxes -Masonry JavaScript grid layout library */
    $('.grid').masonry({
        itemSelector: '.grid-item'
    });

    //Scroll To Top
    if ($('.back-to-top').length) {
        var scrollTrigger = 1000, // px
            backToTop = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('.back-to-top').addClass('show');
                } else {
                    $('.back-to-top').removeClass('show');
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

    /* close menu while outside of this container*/
    $dashboard_section = $('.dashboard-section');
    if ($dashboard_section.length) {
        $($dashboard_section).mouseup(function (e) {
            var container = $(".sidebar-offcanvas");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                $('.offcanvas').removeClass('active');
                $(".pb-breadcrumb,.dashboard-panel").removeClass("ad-opacity");
            }
        });
    }

    // Magnificpop Lightbox Gallery - open image gallery in popup window
    $popup_gallery = $('.popup-gallery');
    if ($popup_gallery.length) {
        $popup_gallery.magnificPopup({
            delegate: 'a',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1]
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
            },
            zoom: {
                enabled: true,
                duration: 300,
                opener: function (element) {
                    return element.find('img');
                }
            }
        });
    }

    // Magnificpop Video Gallery - plays videos  in a popup window
    $('.videopopUp').magnificPopup({
        type: 'iframe',
        iframe: {
            markup: '<div class="mfp-iframe-scaler">' +
                '<div class="mfp-close"></div>' +
                '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                '</div>', // HTML markup of popup, `mfp-close` will be replaced by the close button
            patterns: {
                youtube: {
                    index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
                    id: 'v=', // String that splits URL in a two parts, second part should be %id%
                    src: 'https://www.youtube.com/embed/%id%' // URL that will be set as a source for iframe.
                },
                vimeo: {
                    index: 'vimeo.com/',
                    id: '/',
                    src: 'https://player.vimeo.com/video/%id%'
                }
            },
            srcAction: 'iframe_src' // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".
        }
    });

    //DataTables - Table plug-in for jQuery supports Pagination, instant search and multi-column orderingsupports
    if ($('.report-table').length) {
        $('.report-table').DataTable({
            searching: false,
            "bLengthChange": false,
            "bInfo": false,
            "oLanguage": {
                "oPaginate": {
                    "sNext": '<i class="glyphicon glyphicon-arrow-right">',
                    "sPrevious": '<i class="glyphicon glyphicon-arrow-left">'
                }
            }
        });
    }

    //Table Column Highlighting - On hovering cell, hightleting the column
    $('.appointment-table td').hover(
        function () {
            var t = parseInt($(this).index(), 10) + 1;
            $('td:nth-child(' + t + ')').addClass('highlightd');
            $('th:nth-child(' + t + ')').addClass('highlighth');
        },

        function () {
            var t = parseInt($(this).index(), 10) + 1;
            $('td:nth-child(' + t + ')').removeClass('highlightd');
            $('th:nth-child(' + t + ')').removeClass('highlighth');
        }
    );

    /* Show-Hide More Text - automatically shorten text in a DIV and add "+" link. */
    showChar = 100;
    ellipsestext = "...";
    moretext = '<i class="glyphicon glyphicon-plus"></i>';
    lesstext = '<i class="glyphicon glyphicon-minus"></i>';
    $('.more').each(function () {
        content = $(this).html();
        if (content.length > showChar) {
            var c = content.substr(0, showChar),
                h = content.substr(showChar - 1, content.length - showChar),
                html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
            $(this).html(html);
        }
    });

    $(".morelink").click(function () {
        if ($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });

    //CounterUp - Number Counter which displays numerical data with animations
    var countmem = {
        useEasing: true, // toggle easing
        useGrouping: true, // 1,000,000 vs 1000000
        separator: ',', // character to use as a separator
        decimal: '.', // character to use as a decimal
        prefix: '',
        suffix: ''
    };
    if ($("#lessonsCount").length) {
        /* setup CountUp object - CountUp("myTargetElement", startVal, endVal, decimals, duration, options); */
        var countdemo = new CountUp("lessonsCount", 29354, 29439, 0, 100, countmem);
        countdemo.start();
    }

    //File Upload - file input with file preview for various files, offers multiple selection
    if ($("#file-upload").length) {
        $("#file-upload").fileinput();
    }

    //Marquee News Scroller - jQuery plugin to scroll the text like the old traditional marquee.
    if ($('.marquee').length) {
        $('.marquee').marquee({
            pauseOnHover: true,
            allowCss3Support: true,
            duration: 13000
        });
    }

    /* Flatpickr - lightweight datetimepicker & calendar */
    if ($('.calendar').length) {
        flatpickr(".calendar");
    }
});

/* Read File Input */
function readURL(input, id) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {

            input.style.width = '100%';
            $('#' + id)
                .attr('src', e.target.result);
            $('#' + id).fadeIn();
        };
        reader.readAsDataURL(input.files[0]);
    }
}