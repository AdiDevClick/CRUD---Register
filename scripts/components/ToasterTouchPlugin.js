/**
 * Permet de rajouter la navigation tactile pour le toaster
 */
export class ToasterTouchPlugin {

    /**
     * @param {Toaster} toaster 
     */
    constructor(toaster) {
        this.alert = toaster.toasterContainer.querySelector('.toast')

        toaster.toasterContainer.addEventListener('dragstart', e => e.preventDefault())
        toaster.toasterContainer.addEventListener('mousedown', this.startDrag.bind(this), {passive: true})
        toaster.toasterContainer.addEventListener('touchstart', this.startDrag.bind(this), {passive: true})

        window.addEventListener('mousemove', this.drag.bind(this))
        window.addEventListener('touchmove', this.drag.bind(this))
        
        window.addEventListener('touchend', this.endDrag.bind(this))
        window.addEventListener('mouseup', this.endDrag.bind(this))
        window.addEventListener('touchcancel', this.endDrag.bind(this))

        this.toaster = toaster
    }

    /**
     * Démarre le déplacement au touché
     * @param {MouseEvent|TouchEvent} e 
     */
    startDrag(e) {
        if (e.touches) {
            // Permet de ne prendre en compte qu'un seul point d'appui
            if (e.touches.length > 1) {
                return 
            } else {
                e = e.touches[0]
            }
        }
        this.origin = {x: e.screenX, y: e.screenY}
        this.disableTransition()
        // Sauvegarde de la witdh du conteneur
        this.width = this.toaster.toasterContainer.offsetWidth
    }

    /**
     * Déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    drag(e) {
        if (this.origin) {
            const pressionPoint = e.touches ? e.touches[0] : e
            // Calcul du point d'appuis de l'axe X et Y en fonction du point d'origine
            const translate = {x: pressionPoint.screenX - this.origin.x, y: pressionPoint.screenY - this.origin.y}
            if (e.touches && Math.abs(translate.x) > Math.abs(translate.y)) {
                if (e.cancelable) e.preventDefault()
                e.stopPropagation()
            }
            this.lastTranslate = translate
            this.translate(100 * translate.x / this.width)
        }
    }

    /**
     * Permet de déplacer le conteneur visuellement en fonction des point de pression
     * @param {Number} percent 
     */
    translate(percent) {
        this.toaster.toasterContainer.style.transform = 'translate3d('+ percent + '%,  0, 0)'
    }

    /**
     * Désactive la transition du conteneur
     */
    disableTransition() {
        this.toaster.toasterContainer.style.transition = 'none'
    }

    /**
     * Active la transition du conteneur
     */
    enableTransition() {
        this.toaster.toasterContainer.style.transition = ''
    }

    /**
     * Fin du déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    async endDrag(e) {
        if (this.origin && this.lastTranslate) {
            this.enableTransition()
            //Au-delà de 20 points, l'alerte activera l'animation fadeout
            if (Math.abs(this.lastTranslate.x / this.toasterWidth) > 0.20) {
                if (this.lastTranslate.x < 0 || this.lastTranslate.x > 0) {
                    this.alert.classList.add('close')
                    this.alert.addEventListener('animationend', () => {
                        // this.alert.remove()
                        this.toaster.toasterContainer.remove()
                    }, {once: true})
                }
            }
        }
        this.origin = null
        window.removeEventListener('touchmove', this.drag)
    }

    /**
     * Retour la dimension width du conteneur
     */
    get toasterWidth() {
        return this.toaster.toasterContainer.offsetWidth
    }
}