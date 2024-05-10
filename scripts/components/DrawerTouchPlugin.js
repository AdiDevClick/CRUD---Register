/**
 * Permet de rajouter la navigation tactile pour le drawer
 */
export class DrawerTouchPlugin {

    #isOpened = false
    #savedPosition = {}

    /**
     * @param {Drawer} drawer 
     */
    constructor(container) {
        // console.log(container)
        this.container = container
        this.card = container.querySelector('.show_drawer')
        this.drawer = container.querySelector('.recipe')
        this.drawer.addEventListener('dragstart', e => e.preventDefault())

        this.drawer.addEventListener('mousedown', this.startDrag.bind(this), {passive: true})
        this.drawer.addEventListener('touchstart', this.startDrag.bind(this), {passive: true})

        window.addEventListener('mousemove', this.drag.bind(this))
        window.addEventListener('touchmove', this.drag.bind(this))
        
        window.addEventListener('touchend', this.endDrag.bind(this))
        window.addEventListener('mouseup', this.endDrag.bind(this))
        window.addEventListener('touchcancel', this.endDrag.bind(this))
    }

    isDragging(e) {
        // e.preventDefault()
        console.log(e.currentTarget)
        // this.drawer.classList.contains('open') ? this.drawer.classList.add('hidden') : this.drawer.classList.add('open')
    }

    /**
     * Démarre le déplacement au touché
     * @param {MouseEvent|TouchEvent} e 
     */
    startDrag(e) {
        console.log(this.drawer.getBoundingClientRect())

        // this.style(this.#savedPosition.y)
        // this.style('-70')
        console.log('je suis dans le start')
        console.log(e)
        // this.drawer.classList.contains('open') ? this.drawer.classList.add('hidden') : this.drawer.classList.add('open')

        if (e.touches) {
            // Permet de ne prendre en compte qu'un seul point d'appui
            if (e.touches.length > 1) {
                return 
            } else {
                e = e.touches[0]
            }
        }
        this.origin = {x: e.screenX, y: e.screenY}
        this.drawer.classList.add('open')
        // this.drawer.classList.contains('open') ? this.drawer.classList.add('hidden') : this.drawer.classList.add('open')
        this.disableTransition()
        
        // Sauvegarde de la witdh du conteneur
        this.height = this.drawer.offsetHeight
        this.width = this.drawer.offsetWidth
    }

    /**
     * Déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    drag(e) {
        if (this.origin) {
            // console.log('origine 2 => '+this.height)
            const pressionPoint = e.touches ? e.touches[0] : e
            // Calcul du point d'appuis de l'axe X et Y en fonction du point d'origine
            let translate = {x: pressionPoint.screenX - this.origin.x, y: pressionPoint.screenY - this.origin.y}
            if (e.touches && Math.abs(translate.x) > Math.abs(translate.y)) {
                if (e.cancelable) e.preventDefault()
                console.log('ca devrait etre cancel')
                e.stopPropagation()
            }
            // this.card.style.display = 'block'
            // this.drawer.style.heigth = 'auto'
            // this.drawer.style.position = 'absolute'
            // if (translate.y !== translate.y - 1) {
            //     console.log('pas ok')
            //     translate.y = translate.y - 1
            // }
            const offsets = this.drawer.getBoundingClientRect()
            // console.log(this.#savedPosition)
            offsets.x = translate.x
            if (this.#isOpened && this.#savedPosition !== translate) {
                // translate = this.#savedPosition
                // console.log(offsets)
                // console.log(offsets.top, offsets.left, offsets.bottom, offsets.right)
                // this.drawer.classList.add('hidden')
                this.#isOpened = false
            }
            this.lastTranslate = translate
            
            this.origin
            this.translate(100 * translate.y / this.height)
            // this.translate(100 * translate.y / this.width)
        }
    }

    /**
     * Permet de déplacer le conteneur visuellement en fonction des point de pression
     * @param {Number} percent 
     */
    translate(percent) {
        this.drawer.style.transform = 'translate3d(0,'+ percent + '%, 0)'
    }

    /**
     * Désactive la transition du conteneur
     */
    disableTransition() {
        // if (this.#isOpened) return
        // if (this.drawer.classList.contains('open')) {
        //     this.drawer.classList.add('hidden')
        // } else {
        //     this.drawer.classList.add('open')
        // }
        // this.drawer.style.animation = 'none'
        // this.drawer.style.animation = 'auto ease 0s 1 normal both paused none'
        // this.drawer.style.transition = 'none'
    }

    /**
     * Active la transition du conteneur
     */
    enableTransition() {
        // this.drawer.style.animation = ''
        // this.drawer.style.animation = 'slideToTop 1s forwards'
        // this.drawer.style.transition = ''
    }

    style(top) {
        console.log(top)
        // this.drawer.style.top = top+'%'
        this.drawer.style.top = top
    }

    /**
     * Fin du déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    async endDrag(e) {
        console.log('je suis dans le end')
        if (this.origin && this.lastTranslate) {
            // console.log(this.lastTranslate)
            // Force Repaint
            // this.drawer.offsetHeight
        // End of Force Repaint
            this.enableTransition()
            //Au-delà de 20 points, l'alerte activera l'animation fadeout
            if (Math.abs(this.lastTranslate.y / this.drawerHeigth) > 0.20) {
                // if (this.lastTranslate.y < 0 || this.lastTranslate.y > 0) {
                if (this.lastTranslate.y < 0) {
                    console.log('test2')
                    // this.translate('-75%')
                    
                    // this.drawer.style.animation = 'slideToTop 1s forwards'

                    console.log(this.drawer.getBoundingClientRect())
                    // this.drawer.style.transform  = "none"
                    this.#isOpened = true
                    for (const [key, value] of Object.entries(this.lastTranslate)) {
                        this.#savedPosition[key] = value
                    }
                    // this.style('850px')
                    // this.drawer.style.transform = 'translate3d(0, -75%, 0)!important'
                }
                if (this.lastTranslate.y > 0) {
                    // this.style('-1500')
                    console.log('test')
                    // this.drawer.style.transform = 'translateY(18%)'
                    // this.translate('0')
                    // this.drawer.style.animation = 'none'
                    // this.drawer.style.transition = 'none'
                    // this.drawer.classList.remove('open')
                    // this.drawer.classList.add('hidden')
                    // this.drawer.style.animation = 'slideToBottom 1s forwards'
                    this.#isOpened = false
                    // this.drawer.style.transform = 'translate3d(0, 0, 0)'
                }
                // this.lastTranslate.y < 0 ? this.drawer.style.transform = 'translateY(-75%)' : this.drawer.style.transform = 'translateY(0)'
                    // this.drawer.classList.add('hidden')
                    // this.drawer.addEventListener('animationend', () => {
                    //     // this.alert.remove()
                    //     // this.drawer.remove()
                    // })
                // }
            }
        }
        console.log(e.target)
        // this.enableTransition()
        // this.disableTransition()

        this.origin = null
        // this.origin = {x: e.screenX, y: e.screenY}
    }

    /**
     * Retour la dimension width du conteneur
     */
    get drawerWidth() {
        return this.drawer.offsetWidth
    }
    /**
     * Retour la dimension height du conteneur
     */
    get drawerHeigth() {
        return this.drawer.offsetHeight
    }
}