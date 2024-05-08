/**
 * Permet de rajouter la navigation tactile pour le drawer
 */
export class DrawerTouchPlugin {

    /**
     * @param {Drawer} drawer 
     */
    constructor(drawer) {
        // this.drawer = drawer.querySelector('.toast')
        drawer.addEventListener('dragstart', e => e.preventDefault())
        drawer.addEventListener('mousedown', this.startDrag.bind(this), {passive: true})
        drawer.addEventListener('touchstart', this.startDrag.bind(this), {passive: true})

        window.addEventListener('mousemove', this.drag.bind(this))
        window.addEventListener('touchmove', this.drag.bind(this))
        
        window.addEventListener('touchend', this.endDrag.bind(this))
        window.addEventListener('mouseup', this.endDrag.bind(this))
        window.addEventListener('touchcancel', this.endDrag.bind(this))

        this.drawer = drawer
        console.log(this.drawer)
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
        this.width = this.drawer.offsetWidth
        console.log(this.width)
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
            this.translate(100 * translate.y / this.width)
        }
    }

    /**
     * Permet de déplacer le conteneur visuellement en fonction des point de pression
     * @param {Number} percent 
     */
    translate(percent) {
        this.drawer.style.transform = 'translate3d(0, '+ percent + '%, 0)'
    }

    /**
     * Désactive la transition du conteneur
     */
    disableTransition() {
        this.drawer.style.transition = 'none'
    }

    /**
     * Active la transition du conteneur
     */
    enableTransition() {
        this.drawer.style.transition = ''
    }

    /**
     * Fin du déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    async endDrag(e) {
        if (this.origin && this.lastTranslate) {
            this.enableTransition()
            //Au-delà de 20 points, l'alerte activera l'animation fadeout
            if (Math.abs(this.lastTranslate.y / this.drawerWidth) > 0.20) {
                if (this.lastTranslate.y < 0 || this.lastTranslate.y > 0) {
                    this.drawer.classList.add('hidden')
                    this.drawer.addEventListener('animationend', () => {
                        // this.alert.remove()
                        // this.drawer.remove()
                    })
                }
            }
        }
        this.origin = null
    }

    /**
     * Retour la dimension width du conteneur
     */
    get drawerWidth() {
        return this.drawer.offsetWidth
    }
}