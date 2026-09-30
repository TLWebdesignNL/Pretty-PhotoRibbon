/**
 * @copyright  (C) 2022 TLWebdesign <https://www.tlwebdesign.nl>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// Must match the breakpoint in prettyphotoribbon.scss
const wideScreen = window.matchMedia("(min-width: 768px)");
const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

/**
 * One photo ribbon.
 *
 * Wide screens: all tiles sit in a horizontally scrolling row; the arrows, arrow keys and autoplay
 * scroll it one tile at a time. Narrow screens: a regular Bootstrap carousel showing one tile.
 * Geometry is measured when needed, so resizing, rotating or crossing the breakpoint just works.
 */
class PrettyRibbon {
    constructor(carousel) {
        this.carousel = carousel;
        this.inner = carousel.querySelector(".carousel-inner");
        this.items = [...this.inner.querySelectorAll(":scope > .carousel-item")];
        this.nextControl = carousel.querySelector(".carousel-control-next");
        this.prevControl = carousel.querySelector(".carousel-control-prev");
        this.toggle = carousel.parentElement.querySelector(".prettyRibbonToggle");

        // Target of a scroll that may still be animating, so quick repeated clicks add up
        this.pendingTarget = null;
        this.pendingUntil = 0;

        this.autoplayEnabled = false;
        this.timer = null;
        this.hovered = false;
        this.focused = false;
        this.stoppedByUser = false;

        const interval = parseInt(carousel.dataset.autoplayInterval, 10);
        this.interval = interval >= 1000 ? interval : 5000;

        this.nextControl?.addEventListener("click", () => this.step(1));
        this.prevControl?.addEventListener("click", () => this.step(-1));
        this.carousel.addEventListener("keydown", (event) => this.onKeydown(event));
        this.inner.addEventListener("focusin", (event) => this.reveal(event.target));
        wideScreen.addEventListener("change", () => this.applyMode());

        this.applyMode();
        this.setupAutoplay();
    }

    get isWide() {
        return wideScreen.matches;
    }

    get itemWidth() {
        return this.items[0].getBoundingClientRect().width;
    }

    get maxScroll() {
        return Math.max(0, this.inner.scrollWidth - this.inner.clientWidth);
    }

    applyMode() {
        // Bootstrap animates slides only with .slide, which is wanted on narrow screens only
        this.carousel.classList.toggle("slide", !this.isWide);
    }

    scrollTo(left) {
        const target = Math.min(Math.max(0, left), this.maxScroll);

        this.pendingTarget = target;
        this.pendingUntil = performance.now() + 700;
        this.inner.scrollTo({ left: target, behavior: reducedMotion.matches ? "auto" : "smooth" });
    }

    // Scroll one tile forwards (1) or backwards (-1); narrow screens are handled by Bootstrap
    step(direction) {
        if (!this.isWide) {
            return;
        }

        const width = this.itemWidth;

        if (width === 0) {
            return;
        }

        const from = this.pendingTarget !== null && performance.now() < this.pendingUntil
            ? this.pendingTarget
            : this.inner.scrollLeft;

        this.scrollTo(Math.round((from + direction * width) / width) * width);
    }

    onKeydown(event) {
        if (!this.isWide || (event.key !== "ArrowRight" && event.key !== "ArrowLeft")) {
            return;
        }

        this.step(event.key === "ArrowRight" ? 1 : -1);
        this.restartAutoplay();
    }

    // Keep a keyboard-focused tile fully in view
    reveal(element) {
        const item = element.closest(".carousel-item");

        if (!this.isWide || !item) {
            return;
        }

        const itemRect = item.getBoundingClientRect();
        const innerRect = this.inner.getBoundingClientRect();

        if (itemRect.left < innerRect.left) {
            this.inner.scrollLeft -= innerRect.left - itemRect.left;
        } else if (itemRect.right > innerRect.right) {
            this.inner.scrollLeft += itemRect.right - innerRect.right;
        }
    }

    // AUTOPLAY: only when enabled and the user has not asked for reduced motion
    setupAutoplay() {
        if (this.carousel.dataset.autoplay !== "1" || this.items.length < 2 || reducedMotion.matches) {
            return;
        }

        this.carousel.addEventListener("mouseenter", () => {
            this.hovered = true;
            this.restartAutoplay();
        });
        this.carousel.addEventListener("mouseleave", () => {
            this.hovered = false;
            this.restartAutoplay();
        });
        this.carousel.addEventListener("focusin", () => {
            this.focused = true;
            this.restartAutoplay();
        });
        this.carousel.addEventListener("focusout", (event) => {
            this.focused = this.carousel.contains(event.relatedTarget);
            this.restartAutoplay();
        });
        this.nextControl?.addEventListener("click", () => this.restartAutoplay());
        this.prevControl?.addEventListener("click", () => this.restartAutoplay());

        // Visible stop/start button (WCAG 2.2.2); its label switches between "Stop" and "Start"
        if (this.toggle) {
            const stopLabel = this.toggle.querySelector(".prettyRibbonToggle-stop");
            const startLabel = this.toggle.querySelector(".prettyRibbonToggle-start");

            this.toggle.hidden = false;
            this.toggle.addEventListener("click", () => {
                this.stoppedByUser = !this.stoppedByUser;

                if (stopLabel && startLabel) {
                    stopLabel.hidden = this.stoppedByUser;
                    startLabel.hidden = !this.stoppedByUser;
                }

                this.restartAutoplay();
            });
        }

        this.autoplayEnabled = true;
        this.restartAutoplay();
    }

    restartAutoplay() {
        if (!this.autoplayEnabled) {
            return;
        }

        window.clearInterval(this.timer);
        this.timer = null;

        if (!this.stoppedByUser && !this.hovered && !this.focused) {
            this.timer = window.setInterval(() => this.advance(), this.interval);
        }
    }

    advance() {
        if (!this.isWide) {
            // Bootstrap's data API slides (and wraps) the narrow carousel
            this.nextControl?.click();

            return;
        }

        if (this.maxScroll === 0) {
            return;
        }

        if (this.inner.scrollLeft >= this.maxScroll - 1) {
            this.scrollTo(0);
        } else {
            this.step(1);
        }
    }
}

// Open the modal slideshow at the photo whose ribbon tile was activated
function syncModal(modal) {
    modal.addEventListener("show.bs.modal", (event) => {
        const index = parseInt(event.relatedTarget?.dataset.photoIndex ?? "", 10);

        if (Number.isNaN(index)) {
            return;
        }

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
}

function init() {
    document.querySelectorAll(".modal[id^=prettyRibbonModal]").forEach(syncModal);

    document.querySelectorAll("div[id^=prettyRibbonCarousel]").forEach((carousel) => {
        // Skip a ribbon without slides (e.g. a template override that renders an empty carousel)
        if (carousel.querySelector(":scope > .carousel-inner > .carousel-item")) {
            new PrettyRibbon(carousel);
        }
    });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
} else {
    init();
}
