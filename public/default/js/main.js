$(function () {
    // b-main-nav
    const $mainNav = $(".b-main-nav");

    $(".b-navbar-toggler").click(function () {
        $("body").toggleClass("overflow-hidden");

        $mainNav.css("top", $(".b-header").innerHeight());

        setTimeout(() => {
            $mainNav
                .find(".dropdown-toggle:not([aria-expanded=true])")
                .trigger("click");
        }, 1);
    });

    $mainNav.on("hide.bs.dropdown", function (e) {
        if (e.clickEvent && window.matchMedia("(max-width: 1199px)").matches) e.preventDefault();
    });

    // Custom formatting
    $(".b-description table")
        .addClass("table-text")
        .wrap('<div class="table-responsive" />');

    // tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // js-select2
    const $select2 = $(".js-select2"),
        $select2Lg = $(".js-select2-lg");

    if ($select2.length) {
        $select2.select2({
            width: "100%",
            theme: "bootstrap4",
        });
    }

    if ($select2Lg.length) {
        $select2Lg.select2({
            theme: "bootstrap4",
            containerCssClass: "select2-lg",
        });
    }

    // js-priority-nav
    priorityNav.init({
        mainNavWrapper: "nav",
        mainNav: ".js-priority-nav",
        navDropdownClassName: "b-equipment__dropdown",
        navDropdownToggleClassName: "b-equipment__dropdown-toggle",
        navDropdownLabel: lang.more,
        navDropdownBreakpointLabel: lang.category,
        breakPoint: false,
    });

    // js-equipment-gallery
    const $equipmentGallery = $(".js-equipment-gallery");

    if ($equipmentGallery.length) {
        $equipmentGallery.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            loop: true,
            swipe: false,
            asNavFor: ".js-equipment-thumbs",
        });
    }

    // js-equipment-thumbs
    const $equipmentThumbs = $(".js-equipment-thumbs");

    if ($equipmentThumbs.length) {
        $equipmentThumbs.slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: ".js-equipment-gallery",
            dots: false,
            focusOnSelect: true,
            arrows: false,
            // verticalSwiping: true,
            // vertical: true,
            loop: true,
        });
    }

    const $header = $(".b-header"),
        $headerNewsCategory = $(".b-header__news-category");

    let trigger = true;

    $(window).scroll(function () {
        let scrollTop = $(this).scrollTop();

        if (scrollTop > 70) {
            $header.addClass("is-active");
            $headerNewsCategory
                .removeClass("btn-primary")
                .addClass("btn-light");

            if (trigger == true) {
                trigger = false;

                $header.hide().fadeIn();
            }
        } else {
            trigger = true;

            $header.removeClass("is-active");
            $headerNewsCategory
                .removeClass("btn-light")
                .addClass("btn-primary");
        }
    });

    // windResize
    function windResize() {
        fullWidth();

        let windowWidth = $(window).innerWidth(),
            containerWith = $("#content").innerWidth();

        // js-home-carousel
        const $homeCarousel = $(".js-home-carousel");

        if (
            window.matchMedia("(max-width: 991px)").matches &&
            $homeCarousel.length
        ) {
            if (!$homeCarousel.hasClass("owl-loaded")) {
                $homeCarousel
                    .removeClass("row")
                    .addClass("owl-carousel owl-theme")
                    .css({
                        marginRight:
                            -((windowWidth - containerWith) / 2) - 8 - 60,
                        width: "auto",
                    });
                $homeCarousel.children().removeClass();

                $homeCarousel.owlCarousel({
                    items: 4,
                    margin: 16,
                    nav: false,
                    dots: false,
                    loop: true,
                    responsive: {
                        0: {
                            items: 2,
                        },
                        576: {
                            items: 3,
                        },
                        768: {
                            items: 4,
                        },
                    },
                });
            }
        } else {
            if ($homeCarousel.hasClass("owl-loaded")) {
                $homeCarousel.owlCarousel("destroy");
                $homeCarousel
                    .addClass("row")
                    .removeClass("owl-carousel owl-theme")
                    .removeAttr("style");

                $homeCarousel.children().each(function () {
                    $(this).addClass($(this).data("col"));
                });
            }
        }

        // js-mobile-carousel
        function mobileCarousel(media = 767) {
            let $mobileCarousel = $(".js-mobile-carousel");

            if ((media = 991)) {
                $mobileCarousel = $(".js-mobile-carousel-md");
            }

            if (
                window.matchMedia("(max-width: " + media + "px)").matches &&
                $mobileCarousel.length
            ) {
                if (!$mobileCarousel.hasClass("owl-loaded")) {
                    $mobileCarousel
                        .removeClass("row")
                        .addClass("owl-carousel owl-theme");
                    $mobileCarousel.children().removeClass();

                    $mobileCarousel.owlCarousel({
                        items: 1,
                        nav: false,
                        dots: true,
                        loop: true,
                    });
                }
            } else {
                if ($mobileCarousel.hasClass("owl-loaded")) {
                    $mobileCarousel.owlCarousel("destroy");
                    $mobileCarousel
                        .addClass("row")
                        .removeClass("owl-carousel owl-theme");
                    $mobileCarousel
                        .children()
                        .addClass($mobileCarousel.children().data("col"));
                }
            }
        }

        mobileCarousel();
        mobileCarousel(991);

        // data-moved-block
        const $movedBlock = $("[data-moved-block]");

        $movedBlock.each(function () {
            let blockId = $(this).data("moved-block");

            if (window.matchMedia("(max-width: 991px)").matches) {
                $(this).appendTo(
                    '[data-moved-block-position-mobile="' + blockId + '"]'
                );
            } else {
                $(this).appendTo(
                    '[data-moved-block-position-pc="' + blockId + '"]'
                );
            }
        });

        // js-promotion-y
        if ($(".js-promotion-y").length) {
            $("#content").css(
                "padding-right",
                $(".js-promotion-y").innerWidth() -
                    (windowWidth - containerWith) / 2 +
                    10
            );

            if ($(".js-promotion-y").is(":hidden")) {
                $("#content").removeAttr("style");
            }
        }

        // Set max height by index by other columns
        $(".js-identical-height").each(function () {
            let $thisMain = $(this),
                $columns = $(this).children();

            $columns
                .first()
                .children()
                .each(function (index) {
                    let maxHeight = 0;

                    for ($i = 0; $i < $columns.length; $i++) {
                        let $thisElement = $(
                            ".b-news-base-item:eq('" + index + "'), .b-news-short-item:eq('" + index + "')",
                            $columns.eq($i)
                        );
                        let thisHeight = $thisElement
                            .height("auto")
                            .outerHeight();
                        maxHeight =
                            maxHeight > thisHeight ? maxHeight : thisHeight;
                    }

                    let $thisLine = $(
                        ".b-news-base-item:eq('" + index + "'), .b-news-short-item:eq('" + index + "')",
                        $columns
                    );

                    if (
                        $thisMain.data("type") == "mobile" && window.matchMedia("(min-width: 992px)").matches
                    ) {
                        $thisLine.removeAttr("style");
                    } else {
                        $thisLine.outerHeight(maxHeight);
                    }
                });
        });
    }

    windResize();

    $(window).resize(function () {
        windResize();
    });

    if (window.matchMedia("(max-width: 767px)").matches) {
        setTimeout(() => {
            $("*").removeClass("lazy");
        }, 3000);
    } else {
        $("*").removeClass("lazy");
    }
});

// js-full-width
function fullWidth() {
    let windowWidth = $(window).width(),
        containerWith = $("#content").width(),
        $fullWith = $(".js-full-width");

    if (windowWidth > containerWith) {
        $fullWith.css({
            width: windowWidth,
            marginLeft: -((windowWidth - containerWith) / 2),
        });
    } else {
        $fullWith.removeAttr("style");
    }
}

window.addEventListener("busCache", function () {
    // js-scrollbar
    const myScrollbar = document.querySelector(".js-scrollbar");

    if (myScrollbar != null) {
        Scrollbar.init(myScrollbar, {
            overscrollEffect: "bounce",
            alwaysShowTracks: true,
        });
    }
});

// ajaxFormSubmit
function ajaxFormSubmit(formId) {
    let $thisForm = $("#" + formId),
        $formSubmit = $thisForm.find(['type="submit"']);

    $.ajax({
        url: $thisForm.attr("action"),
        method: "POST",
        data: new FormData($thisForm[0]),
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        beforeSend: function () {
            $formSubmit.prop("disabled", true);

            setTimeout(function () {
                $formSubmit.prop("disabled", false);
            }, 8000);
        },
        success: function (data) {
            if (data.errors) {
                let error = '<ul class="text-left mb-0">';

                $.each(data.errors, function (key, value) {
                    error += "<li>" + data.errors[key] + "</li>";
                });

                Swal.fire({
                    icon: "error",
                    title: "Error...",
                    html: error + "</ul>",
                });
            }

            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Success...",
                    text: data.success,
                });

                $thisForm[0].reset();
                $thisForm.parents(".modal").modal("hide");
            }

            if (data.redirect) {
                setTimeout(function () {
                    window.location = data.redirect;
                }, 1000);
            }

            $formSubmit.prop("disabled", false);
        },
    });
}

// showPrice
function showPrice(price, currency = "$") {
    if (!price) return "-";

    return `${currency}<strong class="text-${ price > 0 ? "primary" : "danger"} ml-1">${price.toFixed(2)}</strong>`;
}

// getCurrencyRate
function getCurrencyRate() {
    let currencyData = $(".js-currency-rate .dropdown-item.active:first");
    return currencyData.data("rate") ? currencyData.data("rate") : 1;
}

// getCurrencyName
function getCurrencyName() {
    let currencyData = $(".js-currency-rate .dropdown-item.active:first");
    return currencyData.text() ? currencyData.text() : "$";
}

// delay
const delay = (function () {
    let timer = 0;

    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

// js-currency-rate
$(".js-currency-rate .dropdown-item").on("click", function (e) {
    e.preventDefault();

    $(".js-currency-rate").find(".dropdown-toggle").text($(this).text());
    $(".js-currency-rate").find(".dropdown-item").removeClass("active");
    $(".js-currency-rate .dropdown-item:nth-child(" + ($(this).index() + 1) + ")").addClass("active");
});

function contactForm(token) {
    ajaxFormSubmit("contact-form");
}

function dataCenterForm(token) {
    ajaxFormSubmit("data-center-form");
}

function dataCenterSupport(token) {
    ajaxFormSubmit("data-center-support");
}

function equipmentForm(token) {
    ajaxFormSubmit("equipment-form");
}

function equipmentOrderForm(token) {
    ajaxFormSubmit("equipment-order-form");
}

function firmwareForm(token) {
    ajaxFormSubmit("firmware-form");
}

function subscribeForm(token) {
    ajaxFormSubmit("subscribe-form");
}
