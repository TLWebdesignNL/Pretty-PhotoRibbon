/**
 * @copyright  (C) 2022 TLWebdesign <https://www.tlwebdesign.nl>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
document.addEventListener("DOMContentLoaded", () => {
    // SMOOTH SCROLLING FALLBACK FOR SAFARI BROWSERS
    function smoothHorizontalScrolling(e, time, amount, start) {
        var eAmt = amount / 100;
        var curTime = 0;
        var scrollCounter = 0;
        while (curTime <= time) {
            window.setTimeout(SHS_B, curTime, e, scrollCounter, eAmt, start);
            curTime += time / 100;
            scrollCounter++;
        }
    }
    function SHS_B(e, sc, eAmt, start) {
        e.scrollLeft = (eAmt * sc) + start;
    }
    // CHECK IF BROWSER IS SAFARI
    var ua = navigator.userAgent.toLowerCase();
    var isSafari = false;
    if (ua.indexOf("safari") != -1) {
        if (ua.indexOf("chrome") > -1) {
            isSafari = false;
        } else {
            isSafari = true;
        }
    }

    var prettyPhotoribbonCarousel = document.querySelector("div[id^=prettyRibbonCarousel]");
    if (window.matchMedia("(min-width: 768px)").matches) {
        var carouselWidth = prettyPhotoribbonCarousel.getElementsByClassName("carousel-inner")[0].scrollWidth;
        var itemWidth = prettyPhotoribbonCarousel.getElementsByClassName("carousel-item")[0].offsetWidth;
        var scrollPosition = 0;

        prettyPhotoribbonCarousel.children[2].addEventListener("click", function () {
            if (scrollPosition < carouselWidth - itemWidth * 4) {
                var temp = scrollPosition;
                scrollPosition += itemWidth;
                if (isSafari) {
                    smoothHorizontalScrolling(prettyPhotoribbonCarousel.children[0], 600, itemWidth, temp);
                    console.log("scripted scrolling");
                } else {
                    prettyPhotoribbonCarousel.children[0].scrollBy({ left: itemWidth, top: 0,behavior:"smooth"})
                    console.log("native scrolling");
                }
            }
        });

        prettyPhotoribbonCarousel.children[1].addEventListener("click", function () {
            if (scrollPosition > 0) {
                var temp = scrollPosition;
                scrollPosition -= itemWidth;
                if (isSafari) {
                    smoothHorizontalScrolling(prettyPhotoribbonCarousel.children[0], 600, scrollPosition - temp, temp);
                } else {
                    prettyPhotoribbonCarousel.children[0].scrollBy({ left: -itemWidth, top: 0,behavior:"smooth"})
                }
            }
        });
    } else {
        prettyPhotoribbonCarousel.addClass("slide");
    }
});
