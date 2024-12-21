import { debounce } from "../functions/dom.js";
import { Carousel } from "./Carousel.js";

/**
 * Permet de rajouter une fonction hoover qui prend en compte des videos
 */
export class CarouselHoverPlugin {
    // export class CarouselHoverPlugin extends Carousel
    /** @type {Boolean} */
    #hovered = false;
    /** @type {MouseEvent} */
    #eventAction;
    // static #isInternalConstructing = false;
    /**
     * @param {Carousel} carousel
     */
    constructor(carousel) {
        console.log("CarouselHoverPlugin initialisé");
        // if (!CarouselHoverPlugin.#isInternalConstructing) {
        //     throw new TypeError("PrivateConstructor is not constructable");
        // }
        // CarouselHoverPlugin.#isInternalConstructing = false
        // super()
        this.carousel = carousel;

        carousel.items.forEach((item) => {
            console.log(item);
            this.#createEventListenerFromMouse(
                item,
                "mousemove",
                "mouseDebounce",
                false,
                this.#onHover.bind(this)
            );
            this.#debounceMouse(item, "mouseDebounce");
            item.addEventListener("mouseleave", (e) => this.#onPointerOut(e));
            return;
        });
    }

    // static create() {
    //     CarouselHoverPlugin.#isInternalConstructing = true
    //     const instance = new CarouselHoverPlugin()
    //     return instance
    // }

    /**
     * Permet de pause l'animation lors d'un mouse hover
     * @param {PointerEvent} e
     */
    #onHover() {
        console.log("Je suis dans le onHover");
        if (this.carousel.isAlreadyHovered) {
            this.carousel.endTimeAlreadyHovered;
        }
        this.#hovered = true;
        this.carousel.setHoverStatus = true;
        this.carousel.endTime;
        this.carousel.setStatus = "hovered";
        if (this.carousel.getLoadingBar)
            this.carousel.getLoadingBar.style.animationPlayState = "paused";
    }

    /**
     * Relance l'animation quand le pointer est enlevé de l'item
     * @param {PointerEvent} e
     */
    #onPointerOut(e) {
        console.log("Je suis dans le onPointerOut");

        if (this.carousel.getStatus === "canResume") {
            this.carousel.setStatus = "hoveredCompleted";
            this.#hovered = false;
            if (this.carousel.getLoadingBar) {
                this.carousel.currentTime;
                this.carousel.getLoadingBar.style.animationPlayState =
                    "running";
                this.carousel.observe(this.carousel.element);
                this.carousel.thisIsAlreadyHovered = true;
                this.carousel.startTimeAlreadyHovered;
            }
        }
        return;
    }

    /**
     * Permet de créer un EventListener pour une action Souris contenant un CustomEvent
     * @param {HTMLElement} object - Objet à écouter
     * @param {EventListenerOptions} eventToListen - Evènement à trigger
     * @param {CustomElementConstructor} customEvent - Nom du CustomEvent à insérer
     * @param {Boolean} [animationDelay=false] - Si true, la loading bar sera delay . Default : false
     * @param {Function} [funct=null] - Une fonction associée à l'évènement
     * @param {FunctionStringCallback} [args=null] Les arguments de la fonction si nécessaire
     */
    #createEventListenerFromMouse(
        object,
        eventToListen,
        customEvent,
        animationDelay = false,
        funct = null,
        args = null
    ) {
        object.addEventListener(eventToListen, (e) => {
            if (this.carousel.getClickStatus) {
                this.carousel.setClickStatus = false;
                this.carousel.setScrollingStatus = false;
                this.#hovered = false;
                this.carousel.setCase = 2;
            }
            if (
                funct &&
                (!this.#hovered || !this.carousel.getClickStatus) &&
                this.carousel.getStatus !== "hovered"
            )
                funct(args);
            //default
            // if (funct && this.carousel.getStatus !== 'hovered') funct(args)
            // end default

            this.#eventAction = e.clientX;
            this.carousel.setPromiseArray = [];

            let newEvent = new CustomEvent(
                `${customEvent}`,
                {
                    bubbles: true,
                    detail: { e, object },
                },
                { once: true }
            );
            object.dispatchEvent(newEvent);

            animationDelay ? this.carousel.getAnimationDelay : null;
        });
    }

    /**
     * Debounce le hover
     * @param {HTMLElement} object
     * @param {AddEventListenerOptions} event
     * @fires [debounce] <this.#afterClickDelay>
     */
    #debounceMouse(object, event) {
        object.addEventListener(
            event,
            debounce((e) => {
                const mouseEvent = e.detail.e;
                let X = mouseEvent.clientX;
                let Y = mouseEvent.clientY;
                let mousePosition = X;
                console.log(!e.target === e.detail.object);
                if (
                    mousePosition !== this.#eventAction ||
                    this.carousel.getVideoPlayer.videoStatus === false
                ) {
                    return (mousePosition = X);
                }

                // this.carousel.setStatus = "hovered"
                //     ? (this.carousel.setStatus = "canResume")
                //     : null;
                this.carousel.setStatus = "canResume";
                console.log(e.target);
                console.log(e.detail.e);
                console.log(e.detail);
                console.log(e.detail.object);
                return this.#onPointerOut();
            }, this.carousel.afterClickDelay)
        );
    }

    get getHoverStatus() {
        return this.#hovered;
    }

    set setHoverStatus(status) {
        return (this.#hovered = status);
    }
}
