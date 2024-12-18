import { wait } from "../functions/dom.js";

/**
 * Permet de rajouter la navigation tactile pour le carousel
 */
export class CarouselTouchPlugin {
    /** @type {boolean} */
    #isMoving = false;

    /**
     * @param {Carousel} carousel
     */
    constructor(carousel) {
        carousel.container.addEventListener("dragstart", (e) =>
            e.preventDefault()
        );
        carousel.container.addEventListener(
            "mousedown",
            this.startDrag.bind(this),
            { passive: false }
        );
        carousel.container.addEventListener(
            "touchstart",
            this.startDrag.bind(this)
        );

        window.addEventListener("mousemove", this.drag.bind(this));
        window.addEventListener("touchmove", this.drag.bind(this), {
            passive: false,
        });

        window.addEventListener("touchend", this.endDrag.bind(this));
        window.addEventListener("mouseup", this.endDrag.bind(this));
        window.addEventListener("touchcancel", this.endDrag.bind(this));

        carousel.debounce(carousel.container, "touchend");
        carousel.debounce(carousel.container, "mouseup");

        this.carousel = carousel;

        // IMPORTANT !! Event to disable href in case of a slide
        this.carousel.container.querySelectorAll(".js-href").forEach((link) => {
            link.addEventListener("click", (e) => {
                if (this.#isMoving) return e.preventDefault();
            });
        });
    }

    /**
     * Démarre le déplacement au touché
     * @param {MouseEvent|TouchEvent} e
     */
    startDrag(e) {
        e.preventDefault();
        if (this.#isMoving) return;
        if (e.touches) {
            if (e.touches.length > 1) {
                return;
            } else {
                e = e.touches[0];
            }
        }
        this.carousel.activateClickStatus();
        this.origin = { x: e.screenX, y: e.screenY };
        this.carousel.disableTransition();
        this.width = this.carousel.containerWidth;
        // this.carousel.activateClickStatus()
    }

    /**
     * Déplacement
     * @param {MouseEvent|TouchEvent} e
     */
    drag(e) {
        e.preventDefault();
        if (this.origin) {
            const pressionPoint = e.touches ? e.touches[0] : e;
            const translate = {
                x: pressionPoint.screenX - this.origin.x,
                y: pressionPoint.screenY - this.origin.y,
            };
            if (e.touches && Math.abs(translate.x) > Math.abs(translate.y)) {
                if (e.cancelable) e.preventDefault();
                e.stopPropagation();
            }
            const baseTranslate =
                (this.carousel.currentItem * -100) / this.carousel.items.length;
            this.lastTranslate = translate;
            this.carousel.translate(
                baseTranslate + (100 * translate.x) / this.width
            );
            this.#isMoving = true;
        }
    }

    /**
     * Fin du déplacement
     * @param {MouseEvent|TouchEvent} e
     */
    async endDrag(e) {
        e.preventDefault();
        if (this.origin && this.lastTranslate) {
            this.carousel.enableTransition();
            if (
                Math.abs(this.lastTranslate.x / this.carousel.carouselWidth) >
                0.2
            ) {
                // Save current item position
                const currentPosition = this.carousel.currentItem;
                console.log("Jai demandé dans le endrag classique");
                // Move to this item
                this.lastTranslate.x < 0
                    ? this.carousel.next()
                    : this.carousel.prev();

                // IMPORTANT !! If no changes we move back to the old position to avoid bugs
                if (currentPosition === this.carousel.currentItem) {
                    this.carousel.goToItem(this.carousel.currentItem);
                    console.log(
                        "Je demande le reset du mouvement dans le touch"
                    );
                }
            } else {
                // No changes, go back to the old position
                console.log("Jai demandé dans le endrag en else");
                this.carousel.goToItem(this.carousel.currentItem);
            }
        }

        // Resets all movements
        this.origin = null;
        this.lastTranslate = null;

        // IMPORTANT !! To avoid click on href conflicts
        await wait(200);

        this.#isMoving = false;
    }
}
