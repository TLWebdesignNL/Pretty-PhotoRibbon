/**
 * @copyright  (C) 2022 TLWebdesign <https://www.tlwebdesign.nl>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
document.addEventListener("DOMContentLoaded", () => {
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

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

    // Scroll a ribbon from one position to another, without animation when the user prefers reduced motion
    function scrollRibbon(container, from, to) {
        if (reducedMotion.matches) {
            container.scrollLeft = to;
        } else if (isSafari) {
            smoothHorizontalScrolling(container, 600, to - from, from);
        } else {
            container.scrollTo({
                left: to,
                top: 0,
                behavior: "smooth"
            });
        }
    }

    // Open the modal slideshow at the photo whose ribbon tile was activated
    document.querySelectorAll(".modal[id^=prettyRibbonModal]").forEach((modal) => {
        modal.addEventListener("show.bs.modal", (event) => {
            const trigger = event.relatedTarget;

            if (!trigger || trigger.dataset.photoIndex === undefined) {
                return;
            }

            const index = parseInt(trigger.dataset.photoIndex, 10);

            modal.querySelectorAll(".carousel-inner > .carousel-item").forEach((item, i) => {
                item.classList.toggle("active", i === index);
            });

            modal.querySelectorAll(".carousel-indicators > [data-bs-slide-to]").forEach((indicator, i) => {
                indicator.classList.toggle("active", i === index);

                if (i === index) {
                    indicator.setAttribute("aria-current", "true");
                } else {
                    indicator.removeAttribute("aria-current");
                }
            });
        });
    });

    document.querySelectorAll("div[id^=prettyRibbonCarousel]").forEach((carousel) => {
        const inner = carousel.querySelector(".carousel-inner");
        const items = Array.from(carousel.querySelectorAll(".carousel-item"));

        // Skip a ribbon without slides (e.g. a template override that renders an empty carousel)
        if (!inner || items.length === 0) {
            return;
        }

        const nextControl = carousel.querySelector(".carousel-control-next");
        const prevControl = carousel.querySelector(".carousel-control-prev");
        const isWide = window.matchMedia("(min-width: 768px)").matches;
        let visibleItems = parseInt(carousel.dataset.itemsVisible, 10);

        if (!visibleItems || visibleItems < 1) {
            visibleItems = 4;
        }

        let scrollPosition = 0;
        let itemWidth = 0;
        let maxScroll = 0;

        if (isWide) {
            itemWidth = items[0].offsetWidth;
            maxScroll = Math.max(0, inner.scrollWidth - itemWidth * visibleItems);

            const goTo = (target) => {
                target = Math.min(Math.max(0, target), maxScroll);
                scrollRibbon(inner, scrollPosition, target);
                scrollPosition = target;
            };

            if (nextControl) {
                nextControl.addEventListener("click", () => {
                    if (scrollPosition < maxScroll) {
                        goTo(scrollPosition + itemWidth);
                    }
                });
            }

            if (prevControl) {
                prevControl.addEventListener("click", () => {
                    if (scrollPosition > 0) {
                        goTo(scrollPosition - itemWidth);
                    }
                });
            }

            // Keep a keyboard-focused tile in view and the tracked position in sync
            inner.addEventListener("focusin", (event) => {
                const item = event.target.closest(".carousel-item");
                const index = items.indexOf(item);

                if (index < 0 || itemWidth === 0) {
                    return;
                }

                const first = Math.round(scrollPosition / itemWidth);
                let target = scrollPosition;

                if (index < first) {
                    target = index * itemWidth;
                } else if (index > first + visibleItems - 1) {
                    target = (index - visibleItems + 1) * itemWidth;
                }

                target = Math.min(Math.max(0, target), maxScroll);
                inner.scrollLeft = target;
                scrollPosition = target;
            });
        } else {
            carousel.classList.add("slide");
        }

        // AUTOPLAY: only when enabled, there is something to advance, and the user has not asked for reduced motion
        const toggle = carousel.parentElement.querySelector(".prettyRibbonToggle");
        const canAdvance = isWide ? maxScroll > 0 : items.length > 1;

        if (carousel.dataset.autoplay !== "1" || !canAdvance || reducedMotion.matches) {
            return;
        }

        let interval = parseInt(carousel.dataset.autoplayInterval, 10);

        if (!interval || interval < 1000) {
            interval = 5000;
        }

        let autoplayTimer = null;
        let stoppedByUser = false;

        const stopAutoplay = () => {
            if (autoplayTimer) {
                window.clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        };

        const runAutoplay = () => {
            if (isWide && scrollPosition >= maxScroll) {
                scrollRibbon(inner, scrollPosition, 0);
                scrollPosition = 0;

                return;
            }

            if (nextControl) {
                nextControl.click();
            }
        };

        const restartAutoplay = () => {
            stopAutoplay();

            if (!stoppedByUser) {
                autoplayTimer = window.setInterval(runAutoplay, interval);
            }
        };

        carousel.addEventListener("mouseenter", stopAutoplay);
        carousel.addEventListener("mouseleave", restartAutoplay);
        carousel.addEventListener("focusin", stopAutoplay);
        carousel.addEventListener("focusout", restartAutoplay);

        if (nextControl) {
            nextControl.addEventListener("click", restartAutoplay);
        }

        if (prevControl) {
            prevControl.addEventListener("click", restartAutoplay);
        }

        // Visible stop/start button (WCAG 2.2.2); its label switches between "Stop" and "Start"
        if (toggle) {
            const stopLabel = toggle.querySelector(".prettyRibbonToggle-stop");
            const startLabel = toggle.querySelector(".prettyRibbonToggle-start");

            toggle.hidden = false;
            toggle.addEventListener("click", () => {
                stoppedByUser = !stoppedByUser;

                if (stopLabel && startLabel) {
                    stopLabel.hidden = stoppedByUser;
                    startLabel.hidden = !stoppedByUser;
                }

                restartAutoplay();
            });
        }

        restartAutoplay();
    });
});
