/**
 * Permet de rajouter la navigation tactile pour le carousel
 */
export class ToasterTouch {

    /**
     * @param {Toaster} toaster 
     */
    constructor(toaster) {
        this.alert = toaster.querySelector('.toast')

        // this.carousel = carousel
        toaster.addEventListener('dragstart', e => e.preventDefault())
        toaster.addEventListener('mousedown', this.startDrag.bind(this), {passive: false})
        toaster.addEventListener('touchstart', this.startDrag.bind(this))

        window.addEventListener('mousemove', this.drag.bind(this))
        window.addEventListener('touchmove', this.drag.bind(this), {passive: false})
        
        window.addEventListener('touchend', this.endDrag.bind(this))
        window.addEventListener('mouseup', this.endDrag.bind(this))
        window.addEventListener('touchcancel', this.endDrag.bind(this))
        
        // toaster.debounce(carousel.container, 'touchend')
        // toaster.debounce(carousel.container, 'mouseup')

        this.toaster = toaster
        console.log(this.alert)
    }

    /**
     * Démarre le déplacement au touché
     * @param {MouseEvent|TouchEvent} e 
     */
    startDrag(e) {
        console.log('je suis dans le start')

        if (e.touches) {
            if (e.touches.length > 1) {
                return 
            } else {
                e = e.touches[0]
            }
        }
        this.origin = {x: e.screenX, y: e.screenY}
        this.disableTransition()
        this.width = this.toaster.offsetWidth
        console.log(this.offsetWidth)
    }

    /**
     * Déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    drag(e) {
        if (this.origin) {
            console.log('je suis dans le drag')
            const pressionPoint = e.touches ? e.touches[0] : e
            const translate = {x: pressionPoint.screenX - this.origin.x, y: pressionPoint.screenY - this.origin.y}
            if (e.touches && Math.abs(translate.x) > Math.abs(translate.y)) {
                if (e.cancelable) e.preventDefault()
                e.stopPropagation()
            }
            this.lastTranslate = translate
            // const baseTranslate = this.carousel.currentItem * -100 / this.carousel.items.length
            this.translate(100 * translate.x / this.width)
            // this.translate(baseTranslate + 100 * translate.x / this.width)
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
            this.enableTransition()
            console.log(this.alert.width)
            if (Math.abs(this.lastTranslate.x / this.toasterWidth) > 0.20) {
                if (this.lastTranslate.x < 0 || this.lastTranslate.x > 0) {
                    // console.log(this.toaster.remove())
                    // this.alert.classList.remove('active')
                    this.alert.classList.add('close')
                    // this.toaster.remove()
                }
            }
        }
        this.origin = null
    }

    // get toasterWidth() {
    //     return this.offsetWidth
    // }
    get toasterWidth() {
        return this.toaster.offsetWidth
    }
}