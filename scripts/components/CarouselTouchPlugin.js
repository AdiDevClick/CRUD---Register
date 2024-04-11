import { Carousel } from "./Carousel.js"

/**
 * Permet de rajouter la navigation tactile pour le carousel
 */
export class CarouselTouchPlugin {

    /**
     * @param {Carousel} carousel 
     */
    constructor(carousel) {
        // this.carousel = carousel
        if (carousel.getAttribute('role') === 'alert') {
            carousel.addEventListener('dragstart', e => e.preventDefault())
            carousel.addEventListener('mousedown', this.startDrag.bind(this), {passive: false})
            carousel.addEventListener('touchstart', this.startDrag.bind(this))
        } else {
            carousel.container.addEventListener('dragstart', e => e.preventDefault())
            carousel.container.addEventListener('mousedown', this.startDrag.bind(this), {passive: false})
            carousel.container.addEventListener('touchstart', this.startDrag.bind(this))
            carousel.debounce(carousel.container, 'touchend')
            carousel.debounce(carousel.container, 'mouseup')
        }

        window.addEventListener('mousemove', this.drag.bind(this))
        window.addEventListener('touchmove', this.drag.bind(this), {passive: false})
        
        window.addEventListener('touchend', this.endDrag.bind(this))
        window.addEventListener('mouseup', this.endDrag.bind(this))
        window.addEventListener('touchcancel', this.endDrag.bind(this))
        
        // carousel.debounce(carousel.container, 'touchend')
        // carousel.debounce(carousel.container, 'mouseup')

        if (carousel.getAttribute('role') === 'alert') {
            this.toaster = carousel
        }
        this.carousel = carousel
    }

    /**
     * Démarre le déplacement au touché
     * @param {MouseEvent|TouchEvent} e 
     */
    startDrag(e) {
        if (e.touches) {
            if (e.touches.length > 1) {
                return 
            } else {
                e = e.touches[0]
            }
        }
        if (this.toaster) {
            this.origin = {x: e.screenX, y: e.screenY}
            this.width = this.toaster.offsetWidth
        } else {
            this.carousel.activateClickStatus()
            this.origin = {x: e.screenX, y: e.screenY}
            this.carousel.disableTransition()
            this.width = this.carousel.containerWidth
            this.carousel.activateClickStatus()
        }
    }

    /**
     * Déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    drag(e) {
        if (this.origin) {
            const pressionPoint = e.touches ? e.touches[0] : e
            const translate = {x: pressionPoint.screenX - this.origin.x, y: pressionPoint.screenY - this.origin.y}
            if (e.touches && Math.abs(translate.x) > Math.abs(translate.y)) {
                if (e.cancelable) e.preventDefault()
                e.stopPropagation()
            }
            if (this.toaster) {
                this.toaster.translate(100 * translate.x / this.width)
            } else {
                const baseTranslate = this.carousel.currentItem * -100 / this.carousel.items.length
                this.lastTranslate = translate
                this.carousel.translate(baseTranslate + 100 * translate.x / this.width)
            }
        }
    }

    /**
     * @param {HTMLElement}
     */
    translate(percent) {
        this.toaster.style.transform = 'translate3d('+ percent + '%,  0, 0)'
    }

    /**
     * @param {HTMLElement}
     */
    disableTransition() {
        this.toaster.style.transition = 'none'
    }

    /**
     * @param {HTMLElement}
     */
    enableTransition() {
        this.toaster.style.transition = ''
    }

    /**
     * Fin du déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    async endDrag(e) {
        if (this.origin && this.lastTranslate) {
            this.carousel.enableTransition()
            if (Math.abs(this.lastTranslate.x / this.carousel.carouselWidth) > 0.2) {
                this.lastTranslate.x < 0 ? this.carousel.next() : this.carousel.prev()
            } else {
                this.carousel.goToItem(this.carousel.currentItem)
            }
        }
        this.toaster.enableTransition.bind(this)
        this.origin = null
    }
}