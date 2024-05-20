/**
 * Permet de rajouter la navigation tactile pour le drawer
 */
export class DrawerTouchPlugin {

    /** @type {HTMLElement} */
    #recipe
    /** @type {HTMLElement} */
    #card
    /** @type {HTMLElement} */
    #steps
    /** @type {HTMLElement} */
    #showDrawerButton
    /** @type {Boolean} */
    #isOpened = false
    /** @type {Boolean} */
    #isFullyOpened = false
    /** @type {Boolean} */
    #isScrolledAtTop = false
    /** @type {HTMLElement} */
    #drawerBarButton
    /** @type {HTMLElement} */
    #closeButton
    /** @type {Object} */
    #savedPosition = {}
    /** @type {Boolean} */
    #shouldReset = false
    /** @type {Number} */
    #savedTranslateY

    /**
     * @param {Recipe Card} container
     */
    constructor(container) {
        this.container = container

        this.#steps = this.container.querySelector('.form-recipe')
        this.#recipe = this.container.querySelector('.show_drawer')
        this.#card = this.container.querySelector('.recipe')
        this.drawer = this.container.querySelector('.drawer')
        this.#showDrawerButton = this.container.querySelector('.opening_drawer_button')
        this.#drawerBarButton = this.container.querySelector('.drawer__button')
        this.#closeButton = this.container.querySelector('.drawer__close')
        // this.drawer.addEventListener('dragstart', e => e.preventDefault())
        this.#recipe.addEventListener('scroll', this.#onScroll.bind(this))

        this.#card.addEventListener('dragstart', e => e.preventDefault())

        // this.drawer.addEventListener('mousedown', this.startDrag.bind(this), {passive: true})
        this.#card.addEventListener('mousedown', this.startDrag.bind(this), {passive: true})
        // this.drawer.addEventListener('touchstart', this.startDrag.bind(this), {passive: true})
        this.#card.addEventListener('touchstart', this.startDrag.bind(this), {passive: true})

        window.addEventListener('mousemove', this.drag.bind(this))
        window.addEventListener('touchmove', this.drag.bind(this))
        
        window.addEventListener('touchend', this.endDrag.bind(this))
        window.addEventListener('mouseup', this.endDrag.bind(this))
        window.addEventListener('touchcancel', this.endDrag.bind(this))

        this.#showDrawerButton.addEventListener('click', this.#onOpen.bind(this))
        this.#closeButton.addEventListener('click', this.#onClose.bind(this))
        this.#steps.addEventListener('click', this.#onClose.bind(this))
    }

    #onOpen(e) {
        this.#card.classList.add('open')
        this.translate('-80')
        this.#showDrawerButton.removeEventListener('click', this.#onOpen.bind(this))
        this.#card.addEventListener('transitionend', e => {
            this.#isOpened = true
            this.#card.removeAttribute('style')
            this.#card.classList.remove('open')
            this.#card.classList.add('opened')
            this.#showDrawerButton.classList.remove('show')
            this.#showDrawerButton.classList.add('hidden')
        }, {once: true})
    }

    #onScroll(e) {
        if (e.target.scrollTop === 0) {
            this.#isScrolledAtTop = true
        } else {
            this.#isScrolledAtTop = false
        }
    }

    #onClose(e) {
        this.#steps.removeEventListener('click', this.#onClose.bind(this))
        this.#closeButton.removeEventListener('click', this.#onClose.bind(this))
        this.#card.style.animation = 'slideToBottom 1s forwards'
        this.#card.addEventListener('animationend', e => {
            this.#card.classList.remove('opened')
            this.#card.classList.remove('fullyOpened')
            this.#isOpened = false
            this.#isFullyOpened = false
            this.#card.removeAttribute('style')
            this.#showDrawerButton.classList.remove('hidden')
            this.#showDrawerButton.classList.add('show')
            this.#drawerBarButton.classList.remove('fullyOpened')
        }, {once: true})
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
        if (e.touches) {
            // Permet de ne prendre en compte qu'un seul point d'appui
            if (e.touches.length > 1) {
                return 
            } else {
                e = e.touches[0]
            }
        }
        if (this.#isFullyOpened && !this.#isScrolledAtTop) {
            return
        }
        this.origin = {x: e.screenX, y: e.screenY}
        // this.card.classList.add('open')
        // console.log(e.pageY)
        // console.log(this.#card.scrollTop)
        // console.log(window.scrollY)
        // console.log(this.#card.getBoundingClientRect())
        // console.log(this.#card.clientHeight)
        // this.test = this.#card.scrollHeight + Math.round(this.#card.scrollTop) === this.#card.clientHeight
        this.#card.classList.contains('opened') ? null : this.#card.classList.add('open')
        
        this.disableTransition()
        
        // this.#showDrawerButton.classList.add('hidden')
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
            const pressionPoint = e.touches ? e.touches[0] : e
            // Calcul du point d'appuis de l'axe X et Y en fonction du point d'origine
            let translate = {x: pressionPoint.screenX - this.origin.x, y: pressionPoint.screenY - this.origin.y}
            if (e.touches && Math.abs(translate.x) > Math.abs(translate.y)) {
                if (e.cancelable) e.preventDefault()
                e.stopPropagation()
            }
            const offsets = this.#card.getBoundingClientRect()
            offsets.x = translate.x
            if (this.#isOpened && this.#savedPosition !== translate) {
                // translate = this.#savedPosition
                // console.log(offsets)
                // console.log(offsets.top, offsets.left, offsets.bottom, offsets.right)
                // this.drawer.classList.add('hidden')
                // this.#isOpened = false
            }

            if (translate.y < 0 && offsets.top <= 0) return
            this.lastTranslate = translate
            this.origin
            this.translate(100 * translate.y / this.height)
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
        this.#card.style.transition = 'none'
        this.#card.style.overflowY = 'hidden'
        this.#card.style.overflowX = 'hidden'
        this.#card.style.zIndex = '1000'
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
        this.#card.style.top = top
    }

    /**
     * Fin du déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    async endDrag(e) {
        if (this.origin && this.lastTranslate) {
            let translateY = Math.abs(this.lastTranslate.y / this.drawerHeigth)
            // Force Repaint
            this.container.offsetHeight
            // End of Force Repaint
            this.enableTransition()

            //Au-delà de 20 points, l'alerte activera l'animation fadeout
            if (translateY > 0.10) {
                // let savedTranslateY = this.lastTranslate.y
                // if (this.lastTranslate.y < 0 || this.lastTranslate.y > 0) {
                if (this.#isOpened && !this.#isFullyOpened && this.lastTranslate.y > 0 && this.#savedTranslateY !== this.lastTranslate.y) {
                // if (this.#isOpened && this.lastTranslate.y > 0 && !this.#isFullyOpened) {
                    this.#savedTranslateY = this.lastTranslate.y
                    this.#card.style.animation = 'slideToBottom 1s forwards'
                    this.#card.classList.add('hidden')
                    this.#card.addEventListener('animationend', () => {
                        this.#card.classList.remove('open')
                        this.#card.classList.remove('opened')
                        this.#card.classList.remove('hidden')
                        this.#card.removeAttribute('style')
                        this.#isOpened = false
                        this.#steps.removeAttribute('style')
                        this.#showDrawerButton.classList.remove('hidden')
                        this.#showDrawerButton.classList.add('show')
                    }, {once: true})
                }
                if (this.#isFullyOpened && this.lastTranslate.y > 0) {
                    this.#savedTranslateY = this.lastTranslate.y
                    this.#drawerBarButton.classList.contains('fullyOpened') ? this.#drawerBarButton.classList.remove('fullyOpened') : null
                    this.#card.style.animation = 'slideFromTop 1s forwards'
                    this.#card.addEventListener('animationend', (e) => {
                        this.#card.removeAttribute('style')
                        this.#card.classList.remove('fullyOpened')
                        this.#isFullyOpened = false
                    }, {once: true})
                }
                if (this.lastTranslate.y < 0 && !this.#isOpened) {
                    this.#card.style.animation = 'slideToTop 1s forwards'
                    this.#card.addEventListener('animationend', e => {
                        this.#isOpened = true
                        this.#card.removeAttribute('style')
                        this.#card.classList.remove('open')
                        this.#card.classList.add('opened')
                    }, {once: true})
                    for (const [key, value] of Object.entries(this.lastTranslate)) {
                        this.#savedPosition[key] = value
                    }
                }
                if (this.lastTranslate.y < 0 && !this.#isFullyOpened) {
                    this.#card.style.animation = 'slideToFullTop 1s forwards'
                    this.#card.addEventListener('animationend', e => {
                        this.#isFullyOpened = true
                        this.#card.removeAttribute('style')
                        this.#card.classList.add('fullyOpened')
                        this.#drawerBarButton.classList.add('fullyOpened')
                    }, {once: true})
                    for (const [key, value] of Object.entries(this.lastTranslate)) {
                        this.#savedPosition[key] = value
                    }
                }
            }
        }
        this.origin = null
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