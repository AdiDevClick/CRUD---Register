/**
 * Permet de rajouter la navigation tactile pour le drawer
 */
export class DrawerTouchPlugin {

    #recipe
    #card
    #steps
    #isOpened = false
    #savedPosition = {}

    /**
     * @param {Recipe Card}  
     */
    constructor(container) {
        // console.log(container)
        this.container = container
        this.#steps = document.querySelector('.form-recipe')
        this.#recipe = container.querySelector('.show_drawer')
        this.#card = container.querySelector('.recipe')
        this.drawer = document.querySelector('.drawer')
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
        // this.card.classList.add('open')
        this.#card.classList.contains('opened') ? null : this.#card.classList.add('open')
        this.disableTransition()
        
        // Sauvegarde de la witdh du conteneur
        this.height = this.#card.offsetHeight
        this.width = this.#card.offsetWidth
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
            const offsets = this.#card.getBoundingClientRect()
            // console.log(this.#savedPosition)
            offsets.x = translate.x
            if (this.#isOpened && this.#savedPosition !== translate) {
                // translate = this.#savedPosition
                // console.log(offsets)
                // console.log(offsets.top, offsets.left, offsets.bottom, offsets.right)
                // this.drawer.classList.add('hidden')
                // this.#isOpened = false
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
        this.#card.style.transform = 'translate3d(0,'+ percent + '%, 0)'
    }

    /**
     * Désactive la transition du conteneur
     */
    disableTransition() {
        // this.#steps.style.position = 'sticky'
        // if (this.#isOpened) return
        // if (this.drawer.classList.contains('open')) {
        //     this.drawer.classList.add('hidden')
        // } else {
        //     this.drawer.classList.add('open')
        // }
        // this.card.removeAttribute('style')
        // this.card.style.animation = 'none'
        // this.drawer.style.animation = 'auto ease 0s 1 normal both paused none'
        this.#card.style.transition = 'none'
        this.#card.style.overflowY = 'hidden'
        this.#card.style.overflowX = 'hidden'
        this.#card.style.zIndex = '999'
        this.#card.style.position = 'fixed'
    }

    /**
     * Active la transition du conteneur
     */
    enableTransition() {
        this.#card.style.overflowY = 'auto'
        // this.drawer.style.animation = ''
        this.#card.style.animation = null

        // this.drawer.style.animation = 'slideToTop 1s forwards'
        this.#card.style.transition = ''
        // !this.#isOpened ? this.card.style.position = 'absolute' : this.card.style.position = 'fixed'
    }

    style(top) {
        console.log(top)
        // this.drawer.style.top = top+'%'
        this.#card.style.top = top
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
            this.container.offsetHeight
            // End of Force Repaint
            this.enableTransition()
            console.log(this.#isOpened)
            //Au-delà de 20 points, l'alerte activera l'animation fadeout
            if (Math.abs(this.lastTranslate.y / this.drawerHeigth) > 0.10) {
                // if (this.lastTranslate.y < 0 || this.lastTranslate.y > 0) {
                if (this.#isOpened && this.lastTranslate.y > 0) {
                    // this.style('-1500')
                    console.log('test')

                    // this.drawer.style.transform = 'translateY(0)'
                    // this.translate('0')
                    // this.drawer.style.animation = 'none'
                    // this.drawer.style.transition = 'none'
                    // this.drawer.classList.remove('open')
                    this.#card.style.animation = 'slideToBottom 1s forwards'
                    this.#card.classList.add('hidden')
                    // this.card.addEventListener('transitionend', e => {
                    this.#card.addEventListener('animationend', () => {
                        this.#card.classList.remove('open')
                        this.#card.classList.remove('opened')
                        this.#card.classList.remove('hidden')
                        this.#card.removeAttribute('style')
                        this.#isOpened = false
                        this.#steps.removeAttribute('style')
                    }, {once: true})
                    // this.drawer.style.transform = 'translate3d(0, 0, 0)'
                }
                if (this.lastTranslate.y < 0 && !this.#isOpened) {
                    console.log('test2')
                    // this.translate('-70')
                    this.#card.style.animation = 'slideToTop 1s forwards'
                    // this.card.addEventListener('transitionend', e => {
                        this.#card.addEventListener('animationend', e => {
                        this.#isOpened = true
                        // this.card.style.position = 'absolute'
                        this.#card.removeAttribute('style')
                        this.#card.classList.remove('open')
                        this.#card.classList.add('opened')
                    }, {once: true})

                    console.log(this.#card.getBoundingClientRect())
                    // this.drawer.style.transform  = "none"
                    for (const [key, value] of Object.entries(this.lastTranslate)) {
                        this.#savedPosition[key] = value
                    }
                    // this.style('850px')
                    // this.drawer.style.transform = 'translate3d(0, -75%, 0)!important'
                }
                // if (this.lastTranslate.y > 0) {
                    console.log(this.#isOpened)
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
        // this.#isOpened = false
        this.origin = null
        // this.origin = {x: e.screenX, y: e.screenY}
    }

    /**
     * Retour la dimension width du conteneur
     */
    get drawerWidth() {
        return this.#card.offsetWidth
    }
    /**
     * Retour la dimension height du conteneur
     */
    get drawerHeigth() {
        return this.#card.offsetHeight
    }
}