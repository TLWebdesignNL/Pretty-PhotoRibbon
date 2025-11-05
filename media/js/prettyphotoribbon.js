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

    var prettyPhotoribbonCarousels = document.querySelectorAll("div[id^=prettyRibbonCarousel]");

    for (let i = 0; i < prettyPhotoribbonCarousels.length; i++) {
        if (window.matchMedia("(min-width: 768px)").matches) {
            let carouselWidth = prettyPhotoribbonCarousels[i].getElementsByClassName("carousel-inner")[0].scrollWidth;
            let itemWidth = prettyPhotoribbonCarousels[i].getElementsByClassName("carousel-item")[0].offsetWidth;
            let scrollPosition = 0;
            let visibleItems = parseInt(prettyPhotoribbonCarousels[i].dataset.itemsVisible, 10);

            if (!visibleItems || visibleItems < 1) {
                visibleItems = 4;
            }

            let maxScroll = Math.max(0, carouselWidth - itemWidth * visibleItems);

            let nextControl = prettyPhotoribbonCarousels[i].querySelector(".carousel-control-next");
            let prevControl = prettyPhotoribbonCarousels[i].querySelector(".carousel-control-prev");

            if (nextControl) {
                nextControl.addEventListener("click", function () {
                    if (scrollPosition < maxScroll) {
                        let temp = scrollPosition;
                        scrollPosition += itemWidth;
                        if (isSafari) {
                            smoothHorizontalScrolling(prettyPhotoribbonCarousels[i].children[0], 600, itemWidth, temp);
                            console.log("scripted scrolling");
                        } else {
                            prettyPhotoribbonCarousels[i].children[0].scrollBy({
                                left: itemWidth,
                                top: 0,
                                behavior: "smooth"
                            });
                            console.log("native scrolling");
                        }
                    }
                });
            }

            if (prevControl) {
                prevControl.addEventListener("click", function () {
                    if (scrollPosition > 0) {
                        let temp = scrollPosition;
                        scrollPosition -= itemWidth;

                        if (isSafari) {
                            smoothHorizontalScrolling(prettyPhotoribbonCarousels[i].children[0], 600, scrollPosition - temp, temp);
                            console.log("scripted scrolling");
                        } else {
                            prettyPhotoribbonCarousels[i].children[0].scrollBy({
                                left: -itemWidth,
                                top: 0,
                                behavior: "smooth"
                            });
                            console.log("native scrolling");
                        }
                    }
                });
            }

            if (prettyPhotoribbonCarousels[i].dataset.autoplay === "1" && maxScroll > 0) {
                let interval = parseInt(prettyPhotoribbonCarousels[i].dataset.autoplayInterval, 10);

                if (!interval || interval < 1000) {
                    interval = 5000;
                }

                const scrollContainer = prettyPhotoribbonCarousels[i].children[0];
                let autoplayTimer = null;

                const stopAutoplay = function () {
                    if (autoplayTimer) {
                        window.clearInterval(autoplayTimer);
                        autoplayTimer = null;
                    }
                };

                const runAutoplay = function () {
                    if (scrollPosition >= maxScroll && scrollContainer) {
                        if (isSafari) {
                            smoothHorizontalScrolling(scrollContainer, 600, -scrollPosition, scrollPosition);
                        } else {
                            scrollContainer.scrollTo({
                                left: 0,
                                top: 0,
                                behavior: "smooth"
                            });
                        }

                        scrollPosition = 0;

                        return;
                    }

                    if (nextControl) {
                        nextControl.click();
                    }
                };

                const restartAutoplay = function () {
                    stopAutoplay();
                    autoplayTimer = window.setInterval(runAutoplay, interval);
                };

                restartAutoplay();

                prettyPhotoribbonCarousels[i].addEventListener("mouseenter", function () {
                    stopAutoplay();
                });

                prettyPhotoribbonCarousels[i].addEventListener("mouseleave", restartAutoplay);
                prettyPhotoribbonCarousels[i].addEventListener("focusin", function () {
                    stopAutoplay();
                });
                prettyPhotoribbonCarousels[i].addEventListener("focusout", restartAutoplay);

                if (nextControl) {
                    nextControl.addEventListener("click", restartAutoplay);
                }

                if (prevControl) {
                    prevControl.addEventListener("click", restartAutoplay);
                }
            }
        } else {
            prettyPhotoribbonCarousels[i].addClass("slide");
        }
    }
});
