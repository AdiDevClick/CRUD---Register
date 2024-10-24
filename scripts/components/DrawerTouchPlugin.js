import { appendToAnotherLocation, restoreToDefaultPosition, unwrap } from "../functions/dom.js"

/**
 * Permet de rajouter la navigation tactile pour le drawer
 */
export class DrawerTouchPlugin {

    /**
     * This callback type is called `requestCallback` and is displayed as a global symbol.
     * @callback moveCallback
     * @param {number} index
     */
    #moveCallbacks = []
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
    /** @type {Boolean} */
    #isMobile = false
    /** @type {Boolean} */
    #isTablet = false
    /** @type {Boolean} */
    #isDesktop = false
    /** @type {Number} */
    #index
    /** @type {String} */
    #clickedElement
    /** @type {HTMLElement} */
    #grid
    /** @type {AbortController} */
    #controller
    /** @type {MutationObserver} */
    #observer
    /** @type {String} */
    #mutationOldValue
    /** @type {MutationCallback} */
    #handleMutation = (mutationsList, observer) => {
        mutationsList.forEach(mutation => {
            if (mutation.attributeName === 'class' && mutation.target.classList.contains('mobile')) {
                this.#mutationOldValue = mutation.oldValue
                appendToAnotherLocation('#recipe_creation')
                this.#openListeners()
                return
            } else if (mutation.attributeName === 'class' && this.#mutationOldValue !== '' && !mutation.target.classList.contains('mobile')) {
                this.#closeListeners()
                this.#mutationOldValue = ''
                const elementsToUnwrap = [
                    '.img_preview',
                    '#submit-recipe'
                ]
                const section = document.querySelector('#recipe_creation')
                // const section = document.querySelector('#recipe_creation_all_resolutions')

                restoreToDefaultPosition(section, '.card')

                document.querySelector('.show_drawer').insertAdjacentElement('beforebegin', document.querySelector('.js-append-to-drawer'))
                elementsToUnwrap.forEach(element => {
                    section.append(document.querySelector(element))
                })
            }
        })
    }

    /**
     * @param {HTMLElement} container
     */
    constructor(container) {
        this.container = container
        this.#grid = this.container.querySelector('.contact-grid')
        this.#drawerBarButton = this.container.querySelector('.drawer__button')
        
        this.#onWindowResize()
        this.#checkDisplay()
        
        if (this.#isMobile) {
            this.#openListeners()
        }
        // this.drawer.addEventListener('mousedown', this.startDrag.bind(this), {passive: true})
        // this.drawer.addEventListener('touchstart', this.startDrag.bind(this), {passive: true})
        // if (this.#isTablet) {
            // this.#closeButton.addEventListener('click', this.#onClose.bind(this))
            // this.#steps.addEventListener('click', this.#onOpen.bind(this))
        // }
        // this.#steps.addEventListener('click', this.#onClose.bind(this))
        // this.#card.addEventListener('click', this.#onClose.bind(this))

        // Evènements
        this.#moveCallbacks.forEach(cb => cb(this.#index))

        // window.addEventListener("DOMContentLoaded", (e) => {
            this.#observer = new MutationObserver(this.#handleMutation)
            this.#observer.observe(this.container, { attributeOldValue: true})
        // })
        window.addEventListener('resize', this.#onWindowResize.bind(this))
    }

    #openListeners() {
        this.#controller = new AbortController()

        this.#card = this.container.querySelector('.recipe')
        this.#steps = this.container.querySelector('.form-recipe')
        this.#showDrawerButton = this.container.querySelector('.opening_drawer_button')
        this.drawer = this.container.querySelector('.drawer')
        this.#recipe = this.container.querySelector('.show_drawer')
        this.#closeButton = this.container.querySelector('.drawer__close')

        this.#card.addEventListener('scroll', this.#onScroll.bind(this), {signal: this.#controller.signal})
        // this.#recipe.addEventListener('scroll', this.#onScroll.bind(this), {signal: this.#controller.signal})

        this.#card.addEventListener('dragstart', e => e.preventDefault())
        this.#card.addEventListener('mousedown', this.startDrag.bind(this), {passive: true, signal: this.#controller.signal})
        this.#card.addEventListener('touchstart', this.startDrag.bind(this), {passive: true, signal: this.#controller.signal})
        window.addEventListener('mousemove', this.drag.bind(this), {signal: this.#controller.signal})
        window.addEventListener('touchmove', this.drag.bind(this), {signal: this.#controller.signal})
        window.addEventListener('touchend', this.endDrag.bind(this), {signal: this.#controller.signal})
        window.addEventListener('mouseup', this.endDrag.bind(this), {signal: this.#controller.signal})
        window.addEventListener('touchcancel', this.endDrag.bind(this), {signal: this.#controller.signal})
        this.#showDrawerButton.addEventListener('click', this.#onOpen.bind(this), {signal: this.#controller.signal})
        this.#closeButton.addEventListener('click', this.#onClose.bind(this), {signal: this.#controller.signal})
        this.#steps.addEventListener('click', this.#onOpen.bind(this), {signal: this.#controller.signal})
    }

    #closeListeners() {
        if (this.#isMobile != this.#isMobile) this.#controller.abort("Fermeture de tous les listeners")
        this.#resetStatusAndStyle()
    }

    /**
     * Permet d'attribuer le status de mobile/tablette/desktop
     */
    #checkDisplay() {
        this.#onMove(index => {
            if (index === 0) {
                this.#card?.classList.remove('open')
                this.#card?.removeAttribute('style')
                this.#steps?.classList.contains('card') ? this.#steps.classList.remove('card') : null
                this.#isMobile = true
                this.#isTablet = false
                this.#isDesktop = false
            }
            if (index === 1) {
                this.#card?.classList.remove('opened')
                this.#card?.removeAttribute('style')
                this.#steps?.classList.contains('card') ? null : this.#steps?.classList.add('card')
                this.#isMobile = false
                this.#isTablet = true
                this.#isDesktop = false
            }
            if (index === 2) {
                this.#card?.classList.remove('opened')
                this.#card?.classList.remove('open')
                this.#card?.removeAttribute('style')
                this.#steps?.classList.contains('card') ? null : this.#steps?.classList.add('card')
                this.#isMobile = false
                this.#isTablet = false
                this.#isDesktop = true
            }
        })
    }

    #onOpen(e) {
        this.#showDrawerButton.removeEventListener('click', this.#onOpen.bind(this))
        this.#steps.removeEventListener('click', this.#onOpen.bind(this))
        
        if (this.#isMobile && e.currentTarget !== this.#steps) {
            this.#card.style.display = 'block'
            // IMPORTANT in case of reset
            this.#recipe.scrollTo(50, 0)
            this.#disableScrollBehavior()

            this.drawer.style.display = 'block'
            this.#closeButton.removeAttribute('style')
            if (e.currentTarget === this.#showDrawerButton) {
                // Utiliser la fonction de vibration des appareils compatibles
                if ("vibrate" in navigator) {
                    navigator.vibrate(20)
                } else {
                    console.log('Vibration API not supported')
                }
                this.#clickedElement = 'card'
                this.#card.classList.add('open')
                this.translate('-80')
                // Force Repaint
                this.container.offsetHeight
                // End of Force Repaint
                this.#showDrawerButton.classList.remove('show')
                this.#showDrawerButton.classList.add('hidden')

                this.#card.addEventListener('transitionend', e => {
                    this.#isOpened = true
                    this.#card.classList.remove('open')
                    this.#card.classList.add('opened')
                    this.#card.removeAttribute('style')
                    this.#drawerBarButton.style.display = 'block'
                }, {once: true})
                this.#steps.addEventListener('click', this.#onClose.bind(this), {once : true})
            }
        }
        if (this.#isTablet) {
            // this.#drawerBarButton.removeAttribute('style')
            // this.#drawerBarButton.style.display = 'none'
            // if (this.#card.classList.contains('open')) {
            //     if (e.currentTarget === this.#card) {
            //         return
            //     }
            // } else if (e.currentTarget === this.#card && !this.#card.classList.contains('open')) {
            //     return
            // }
            if (!this.#steps.classList.contains('opened')) {
                
                if (e.currentTarget === this.#steps && !this.#card.classList.contains('open')) {
                    // console.log('test')
                    this.#clickedElement = 'steps'
                    this.#steps.classList.add('open')
                    this.#steps.style.animation = 'scaleOutSteps 0.5s forwards'
                    this.#grid.style.animation = 'gridContraction 0.5s forwards'
                    this.#steps.addEventListener('animationend', e => {
                        this.#isOpened = true
                        this.#steps.removeAttribute('style')
                        this.#steps.classList.remove('open')
                        this.#steps.classList.add('opened')
                        // this.#disableScrollBehavior()
                    }, {once: true})
                    this.#card.addEventListener('click', this.#onClose.bind(this))
                }
            }
        }
    }

    /**
     * Réinitialise tous les status et styles préalablement appliqués
     */
    #resetStatusAndStyle() {
        this.#drawerBarButton.classList.contains('fullyOpened') ? this.#drawerBarButton.classList.remove('fullyOpened') : null
        this.#card?.classList.contains('open') ? this.#card.classList.remove('open') : null
        this.#card?.classList.contains('opened') ? this.#card.classList.remove('opened') : null
        this.#card?.classList.contains('fullyOpened') ? this.#card.classList.remove('fullyOpened') : null
        this.#card?.classList.contains('hidden') ? this.#card.classList.remove('hidden') : null
        this.#card?.removeAttribute('style')
        this.#steps?.removeAttribute('style')
        this.#isOpened ? this.#isOpened = false : null
        this.#isFullyOpened ? this.#isFullyOpened = false : null
        // this.#isScrolledAtTop ? this.#isScrolledAtTop = false : null
        // this.#isFullyOpened ? this.#isFullyOpened : null
        // console.log(this.#showDrawerButton)
        this.drawer.style.display === 'block' ? this.drawer.removeAttribute('style') : null

        this.#showDrawerButton?.classList.contains('hidden') ? this.#showDrawerButton.classList.remove('hidden') : null
        this.#showDrawerButton?.classList.add('show')
        // this.#closeButton.style.display = 'none'
        this.#isScrolledAtTop = false
        this.#enableScrollBehavior()
    }

    /**
     * Permet de désactiver le scroll sur le root
     */
    #disableScrollBehavior() {
        document.documentElement.style.overflow = 'hidden'
        // document.documentElement.style.overflowX = 'hidden'
        document.documentElement.style.overscrollBehavior = 'none'
    }

    /**
     * Permet de réactiver le scroll sur le root
     */
    #enableScrollBehavior() {
        document.documentElement.removeAttribute('style')
        this.#isScrolledAtTop = false
    }

    #onScroll(e) {
        if (e.target.scrollTop === 0) {
            this.#isScrolledAtTop = true
        } else {
            this.#isScrolledAtTop = false
        }
    }

    #onClose(e) {
        // this.#steps.removeEventListener('click', this.#onClose.bind(this))
        this.#card.removeEventListener('click', this.#onClose.bind(this))
        // this.#closeButton.removeEventListener('click', this.#onClose.bind(this))
        if (this.#isMobile) {
            if (!this.#card.classList.contains('opened')) return

            this.#card.style.animation = 'slideToBottom 0.5s forwards'
            this.#card.classList.add('hidden')
            this.#card.addEventListener('animationend', () => {
                this.#resetStatusAndStyle()
            }, {once: true})
        }

        // if (this.#isMobile && !this.#card.classList.contains('opened') || e.currentTarget !== this.#closeButton) return
        // if (this.#isTablet && !this.#card.classList.contains('open')) return
        // if (this.#isMobile) this.#card.style.animation = 'slideToBottom 0.5s forwards'
        // if (this.#isTablet) this.#card.style.animation = 'scaleOut 0.5s reverse forwards'
        // if (this.#isTablet && (e.currentTarget === this.#steps || e.currentTarget === this.#closeButton)) this.#card.style.animation = 'scaleOutReverse 0.5s reverse forwards'
        if (this.#isTablet) {
            if (this.#card.classList.contains('open')) {
                if (e.currentTarget === this.#steps || e.currentTarget === this.#closeButton) {
                    // console.log('je suis ici')
                    this.#card.style.animation = 'scaleOut 0.5s reverse forwards'
                    this.#card.addEventListener('animationend', e => {
                        // console.log(e)
                        if (e.animationName === 'scaleOut') {
                            // console.log('dans le animationend ici')
                        // if (e.animationName === 'slideToRight' || 'slideToBottom') {
                            // this.#card.classList.remove('open')
                            // this.#card.classList.remove('opened')
                            // this.#card.classList.remove('fullyOpened')
                            // this.#isOpened = false
                            // this.#isFullyOpened = false
                            // this.#card.removeAttribute('style')
                            // this.#showDrawerButton.classList.remove('hidden')
                            // this.#showDrawerButton.classList.add('show')
                            // this.#drawerBarButton.classList.remove('fullyOpened')
                            // this.#enableScrollBehavior()
                            // this.drawer.style.display = 'none'
                            this.#closeButton.style.display = 'none'

                            this.#resetStatusAndStyle()
                        }
                    }, {once: true})
                }
            } else if (this.#steps.classList.contains('opened')) {
                if (e.currentTarget === this.#card) {
                    this.#steps.style.animation = 'scaleOutSteps 0.5s reverse forwards'
                    this.#grid.style.animation = 'gridContraction 0.5s reverse forwards'
                    // console.log('je suis là')
                    this.#steps.addEventListener('animationend', e => {
                        // console.log(e)
                        // console.log('je suis dans la animationend')
                        if (e.animationName === 'scaleOutSteps') {
                            // console.log('mon animation devrait etre terminée')
                            this.#steps.classList.remove('opened')
                            this.#card.classList.remove('open')
                            this.#isOpened = false
                            this.#steps.removeAttribute('style')
                            this.#grid.removeAttribute('style')
                            this.#enableScrollBehavior()
                            // document.documentElement.removeAttribute('style')
                        }
                    }, {once: true})
                }
            } else {
                return
            }
        }
        // if (this.#isTablet && (e.currentTarget === this.#steps || e.currentTarget === this.#closeButton)) this.#card.style.animation = 'slideToRight 0.5s forwards'
        // if (this.#isTablet && e.currentTarget === this.#card) this.#steps.style.animation = 'scaleOutSteps 0.5s reverse forwards'
        
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
        if (this.#steps.classList.contains('opened')) return
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
        if (this.#isMobile) {
            this.#card.classList.contains('opened') ? null : this.#card.classList.add('open')
        }
        if (this.#isTablet) {
            if (!this.#card.classList.contains('open') && !this.#steps.classList.contains('opened')) {
                // this.#disableScrollBehavior()
                // console.log('je suis tablette')
                // this.translate('0', '5', '550px')`

                // this.#card.style.scale = '1.3'
                // this.#card.style.animation = 'scaleOut 1s'
                this.#card.style.animation = 'scaleOut 0.5s forwards'
                // this.#card.style.transition = 'width 1s both ease-in-out'
                // this.translate('0', '10', '550px')
                // this.translate('-10', '-20', '550px')
                // this.#card.style.height = '100%'
                // this.#card.style.position = 'fixed'
                this.#card.addEventListener('animationend', e => {
                    // console.log('mon animation est pas terminée')
                    this.#card.classList.add('open')
                    this.drawer.style.display = 'block'
                    this.#closeButton.removeAttribute('style')
                    this.#drawerBarButton.style.display = 'none'
                    // this.translate('0', '5', '550px')

                    this.#card.removeAttribute('style')
                    this.#card.style.width = '550px'
                }, {once: true})
                // this.#card.addEventListener('transitionend', e => {
                //     // this.#card.classList.add('open')
                //     this.drawer.style.display = 'block'
                //     this.#drawerBarButton.style.display = 'none'
                // })
                this.#steps.addEventListener('click', this.#onClose.bind(this), {once: true})
            }
        }
        this.disableTransition()
        
        // this.#showDrawerButton.classList.add('hidden')
        // Sauvegarde de la witdh et height du conteneur
        this.height = this.#card.offsetHeight
        this.width = this.#card.offsetWidth
    }

    /**
     * Déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    drag(e) {
        // this.#card.style.overscrollBehavior = 'none'
        if (this.#isTablet) return
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

            // Saving initial position in case the user do not fully slide
            if (this.#isOpened && !this.#savedPosition.y) {
                this.#saveLastTranslate(this.lastTranslate)
            }
            // !! IMPORTANT !! : Allows the user to move the card during his interaction
            if (this.#isMobile) this.translate(100 * translate.y / this.height)
            // Force Repaint
            this.container.offsetHeight
            // End of Force Repaint
            // if (this.#isTablet) this.translate(100 * translate.x / this.width)
        }
    }

    /**
     * Permet de déplacer le conteneur visuellement en fonction des point de pression
     * @param {Number} percent 
     */
    translate(percentY, percentX = '0', width = null) {
        // this.#card.style.transform = 'translate3d(0,'+ percent + '%, 0)'
        let element
        if (this.#clickedElement === 'card') element = this.#card
        if (this.#clickedElement === 'steps') element = this.#steps
        element.style.transform = 'translate3d('+ percentX +'%,'+ percentY + '%, 0)'
        if (width) {
            element.style.width = width
        }
    }

    /**
     * Désactive la transition du conteneur
     */
    disableTransition() {
        this.#card.style.transition = 'none'
        // this.#card.style.overflowY = 'hidden'
        // this.#card.style.overflowX = 'hidden'
        this.#card.style.zIndex = '1000'
        // this.#card.style.position = 'fixed'
    }

    /**
     * Active la transition du conteneur
     */
    enableTransition() {
        this.#card.style.overflowY = 'auto'
        // this.#card.style.overflowX = 'auto'
        // this.drawer.style.animation = ''
        this.#card.style.animation = null

        // this.drawer.style.animation = 'slideToTop 1s forwards'
        this.#card.style.transition = ''
        // !this.#isOpened ? this.card.style.position = 'absolute' : this.card.style.position = 'fixed'
    }

    /**
     * Défini à quelle zone du top sera collé l'élément
     * @param {string} top 
     */
    style(top) {
        this.#card.style.top = top
    }

    /**
     * Fin du déplacement
     * @param {MouseEvent|TouchEvent} e
     */
    async endDrag(e) {
        if (!this.#isMobile) return
        if (this.origin && this.lastTranslate) {
            let translateY = Math.abs(this.lastTranslate.y / this.drawerHeigth)
            let translateX = Math.abs(this.lastTranslate.x / this.drawerWidth)
            // Force Repaint
            this.container.offsetHeight
            // End of Force Repaint
            this.enableTransition()

            // Au-delà de 10 points, l'alerte activera l'animation fadeout
            if (translateY > 0.10) {
                // let savedTranslateY = this.lastTranslate.y
                // if (this.lastTranslate.y < 0 || this.lastTranslate.y > 0) {
                // From half opened slide to bottom and disappear
                if (this.#isOpened && !this.#isFullyOpened && this.lastTranslate.y > 0 && this.#savedTranslateY !== this.lastTranslate.y) {
                // if (this.#isOpened && this.lastTranslate.y > 0 && !this.#isFullyOpened) {
                    this.#savedTranslateY = this.lastTranslate.y
                    this.#card.style.animation = 'slideToBottom 0.5s forwards'
                    this.#card.classList.add('hidden')
                    this.#card.addEventListener('animationend', () => {
                        this.#card.classList.remove('open')
                        this.#card.classList.remove('opened')
                        this.#card.classList.remove('hidden')
                        this.#card.removeAttribute('style')
                        this.#card.style.display = 'none'
                        this.#isOpened = false
                        this.#steps.removeAttribute('style')
                        this.#showDrawerButton.classList.remove('hidden')
                        this.#showDrawerButton.classList.add('show')
                        this.drawer.removeAttribute('style')

                        this.#enableScrollBehavior()
                        console.log('je ferme')
                    }, {once: true})
                }
                // From fully opened slide to half opened slide
                if (this.#isMobile && this.#isFullyOpened && this.lastTranslate.y > 0) {
                    this.#savedTranslateY = this.lastTranslate.y
                    this.#drawerBarButton.classList.contains('fullyOpened') ? this.#drawerBarButton.classList.remove('fullyOpened') : null
                    this.#card.style.animation = 'slideFromTop 0.5s forwards'
                    this.#card.addEventListener('animationend', (e) => {
                        this.#card.removeAttribute('style')
                        this.#card.classList.remove('fullyOpened')
                        this.#isFullyOpened = false
                    }, {once: true})
                    console.log('je retourne au centre')
                }
                // Half opened slide
                // if (this.#isMobile && this.lastTranslate.y < 0 && !this.#isOpened) {
                //     this.#card.style.animation = 'slideToTop 0.5s forwards'
                //     this.#card.addEventListener('animationend', e => {
                //         this.#isOpened = true
                //         console.log(this.#savedPosition)
                //         this.#card.removeAttribute('style')
                //         this.#card.classList.remove('open')
                //         this.#card.classList.add('opened')
                //     }, {once: true})
                //     console.log('jouvre')
                //     this.#saveLastTranslate(this.lastTranslate)
                // }
                // Fully open slide
                if (this.#isMobile && this.lastTranslate.y < 0 && !this.#isFullyOpened) {
                    this.#card.style.animation = 'slideToFullTop 0.5s forwards'
                    this.#card.addEventListener('animationend', e => {
                        this.#isFullyOpened = true
                        this.#card.removeAttribute('style')
                        this.#card.classList.add('fullyOpened')
                        this.#drawerBarButton.classList.add('fullyOpened')
                    }, {once: true})
                    this.#saveLastTranslate(this.lastTranslate)
                }
            } else {
                // Restore position
                this.translate(this.#savedPosition.y / this.height)
                this.#card.style.transitionDuration = "0.3s"
            }
            // this.#controller.abort()
        }
        this.origin = null
    }

    /**
     * Permet de sauvegarder la position X et Y dans un object { x: value, y: value }
     * Il utilise le lastTranslate préalablement créé avec le this.origin
     * Cette fonction est utilisée pour remettre la carte à son point d'
     * @param {Object} lastTranslate
     */
    #saveLastTranslate(lastTranslate) {
        for (const [key, value] of Object.entries(lastTranslate)) {
            this.#savedPosition[key] = value
        }
    }
    
    /**
     * Permet de spécifier le type de display en fonction 
     * du changement de la taille de la fenêtre
     */
    #onWindowResize() {
        // debugger
        let mobile = window.innerWidth <= 576
        let tablet = window.innerWidth <= 996 && window.innerWidth > 576
        let desktop = window.innerWidth > 996
        if (mobile !== this.#isMobile) {
            this.#isMobile = mobile
            // this.setStyle()
            // appendToAnotherLocation('#recipe_creation_all_resolutions')

            this.#index = 0
            this.container.classList.add('mobile')
            this.#card?.classList.remove('open')
            this.#card?.classList.remove('opened')
            this.#moveCallbacks.forEach(cb => cb(this.#index))
        } 
        if (tablet !== this.#isTablet) {
            this.#isTablet = tablet
            const elementsToUnwrap = [
                '.img_preview',
                '#submit-recipe'
            ]
            // pour revenir à defaut
            // unwrap('.card')
        
            // const section = document.querySelector('#recipe_creation_all_resolutions')
            // document.querySelector('.show_drawer').insertAdjacentElement('beforebegin', document.querySelector('.js-append-to-drawer'))
            // elementsToUnwrap.forEach(element => {
            //     section.append(document.querySelector(element))
            // })
            // this.setStyle()
            this.#index = 1
            if (this.container.classList.contains('mobile')) this.container.classList.remove('mobile')
            this.#card?.classList.remove('open')
            this.#card?.classList.remove('opened')
            this.#moveCallbacks.forEach(cb => cb(this.#index))
        }
        if (desktop !== this.#isDesktop) {
            this.#isDesktop = desktop
            this.#isTablet = tablet
            const elementsToUnwrap = [
                '.img_preview',
                '#submit-recipe'
            ]
            // pour revenir à defaut
            // unwrap('.card')
        
            // const section = document.querySelector('#recipe_creation_all_resolutions')
            // document.querySelector('.show_drawer').insertAdjacentElement('beforebegin', document.querySelector('.js-append-to-drawer'))
            // elementsToUnwrap.forEach(element => {
            //     section.append(document.querySelector(element))
            // })
            if (this.container.classList.contains('mobile')) this.container.classList.remove('mobile')

            // this.setStyle()
            this.#index = 2
            this.#card?.classList.remove('open')
            this.#card?.classList.remove('opened')
            this.#moveCallbacks.forEach(cb => cb(this.#index))
        }
    }

    /** @param {moveCallback} */
    #onMove(callback) {
        this.#moveCallbacks.push(callback)
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

    get resetStates() {
        this.#card.style.animation = 'slideToBottom 0.5s forwards'
        this.#card.addEventListener('animationend', () => {
            this.#resetStatusAndStyle()
        }, {once: true})
        return
    }    
}