import { appendToAnotherLocation, restoreToDefaultPosition } from "../../functions/dom.js"

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
    /**
     * Si une classe "mobile" est ajoutée ou retirée,
     * l'UI sera transformée.
     * @type {MutationCallback}
     * @param {MutationRecord} mutationsList
     * @param {MutationObserver} observer
     */
    #handleMutation = (mutationsList, observer) => {
        mutationsList.forEach(mutation => {
            if (mutation.attributeName === 'class' && mutation.target.classList.contains('mobile')) {
                const firstGroupElements = [
                    '.js-two',
                    '.js-three',
                    '.js-four'
                ]
                const elementsToShow = '.js-one'

                // Save the old mutation value
                this.#mutationOldValue = mutation.oldValue

                // Append content in predefined order
                appendToAnotherLocation('#recipe_creation', this.container)

                // Hide the tab index
                this.preparation.tabulation.style.display = 'none'

                // Start event listeners
                this.#openListeners()

                // When we enter mobile mode, display the hidden elements from firstGroupElements
                firstGroupElements.forEach(element => {
                    const target = this.container.querySelector(element)
                    this.#removeStyle(target, 'hidden')
                })

                // Additionnaly, we display the elementsToShow
                this.container.querySelectorAll(elementsToShow).forEach(element => {
                    this.#removeStyle(element, 'hidden')
                })
                return
            } else if (mutation.attributeName === 'class' &&
                this.#mutationOldValue !== '' &&
                !mutation.target.classList.contains('mobile') &&
                (mutation.target.classList.contains('desktop') ||
                mutation.target.classList.contains('tablet'))) {
                // 1 - Close listeners to avoid unecessary issues
                this.#closeListeners()
                this.#mutationOldValue = ''

                // 2 - Show the tab index
                this.preparation.tabulation.removeAttribute('style')

                // 3 - Targets to unwrap after reorder
                const elementsToUnwrap = [
                    '.js-four',
                    '.js-five',
                    '#submit-recipe'
                ]

                // 3.1 - Container for the targets to unwrap and reorder to
                const section = this.container.querySelector('#recipe_creation')

                // 4 - Reorder positions
                restoreToDefaultPosition(section, '.card')
                this.container.querySelector('.show_drawer').insertAdjacentElement('beforebegin', this.container.querySelector('.js-append-to-drawer'))

                // 5 - Unwrap the targets to the end of the section
                elementsToUnwrap.forEach(element => {
                    Array.from(this.container.querySelectorAll(element)).forEach(el => {
                        section.append(el)
                    })
                })

                // 6 - Verify which step the user was at in the previews desktop / tablet mode
                const currentStep = this.preparation.currentSubmitionStep-1

                // 7 - Retrieve datas for each steps
                const datas = this.preparation.datas
                const elementsToHide = datas.filter( ( t,i ) => i != currentStep)
                const elementsToShow = datas.filter( ( t,i ) => i == currentStep)

                // 8 - Hidding content depending on the saved current step
                for (const element of elementsToHide) {
                    if (element.class !== '.js-five') {
                        this.container.querySelectorAll(element.class).forEach(element => {
                            this.#hideElement(element)
                        })
                    }
                }
                for (const element of elementsToShow) {
                    this.container.querySelectorAll(element.class).forEach(element => {
                        this.#removeStyle(element, 'hidden')
                    })
                }
            }
        })
    }

    /**
     * @typedef {Object} Ingredient
     * @property {HTMLElement} gridContainer - L'élément HTML qui sert de conteneur de la grille.
     * @param {Ingredient} container
     */
    constructor(container) {
        this.preparation = container
        this.container = this.preparation.gridContainer
        this.#grid = this.container.querySelector('.contact-grid')
        this.#drawerBarButton = this.container.querySelector('.drawer__button')

        // Check if the class "mobile" is found on the container
        // Mutate the UI depending on the device
        this.#observer = new MutationObserver(this.#handleMutation)
        this.#observer.observe(this.container, { attributeOldValue: true})

        this.#onWindowResize()
        this.#checkDisplay()

        // Evènements
        this.#moveCallbacks.forEach(cb => cb(this.#index))
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

    /**
     * Supprime tous les EventListeners lorsque l'UI passe en mode tablette/desktop
     * Réinitialise tous les styles
     */
    #closeListeners() {
        if (this.#isMobile != this.#isMobile) this.#controller.abort("Fermeture de tous les listeners")
        this.#resetStatusAndStyle()
    }

    /**
     * Permet d'attribuer le status de mobile/tablette/desktop.
     * Index 0 = mobile
     * Index 1 = tablette
     * Index 2 = desktop
     * Ajoute ou supprime des classes pour que l'UI s'applique correctement.
     */
    #checkDisplay() {
        this.#onMove(index => {
            // Mobile true
            if (index === 0) {
                this.#removeStyle(this.#card, 'open')
                this.#steps?.classList.contains('card') ? this.#steps.classList.remove('card') : null  // not same
                this.#setDeviceType('isMobile', true)
            }
            // Tablet or Desktop true
            if (index === 1 || index === 2) {
                if (index === 1) {
                    this.#setDeviceType('isTablet', true)
                }
                if (index === 2) {
                    this.#setDeviceType('isDesktop', true)
                }
                this.#removeStyle(this.#card, ['open', 'opened'])
                this.#steps?.classList.contains('card') ? null : this.#steps?.classList.add('card')
            }
        })
    }

    /**
     * Applique la classe .hidden à un élément pour le cacher
     * Puis ajoute un style display='none'
     * pour laisser la fade out s'opérer
     * @param {HTMLElement} element 
     */
    #hideElement(element) {
        element.classList.add('hidden')
        element.style.display = 'none'
    }

    /**
     * Permet de vérifier le type de display en fonction
     * du changement de la taille de la fenêtre
     */
    #onWindowResize() {
        let mobile = window.innerWidth <= 576
        let tablet = window.innerWidth <= 996 && window.innerWidth > 576
        let desktop = window.innerWidth > 996


        if (mobile !== this.#isMobile) {
            this.#index = 0
            this.#moveCallbacks.forEach(cb => cb(this.#index))
        }

        if (tablet !== this.#isTablet) {
            this.#index = 1
            this.#moveCallbacks.forEach(cb => cb(this.#index))
        }

        if (desktop !== this.#isDesktop) {
            this.#index = 2
            this.#moveCallbacks.forEach(cb => cb(this.#index))
        }
    }

    /**
     * Supprime une classe d'un élément puis rajoute une nouvelle
     * @param {HTMLElement} element
     * @param {String} classToRemove
     * @param {String} classToAdd
     */
    #classRemoveFromAndAdd(element, classToRemove, classToAdd) {
        if (element) {
            element.classList.remove(classToRemove)
            element.classList.add(classToAdd)
        }
    }

    /**
     * Supprime un attribute de plusieurs élément à la fois
     * @param {Array} elements
     * @param {String} attributeName
     */
    #removeAttributeFrom(elements, attributeName) {
        elements.forEach(element => {
            if (element) {
                element.removeAttribute(attributeName)
            }
        })
    }

    /**
     * Supprime une classe ou une liste de classes puis supprime l'attribut 'style'
     * @param {HTMLElement} target
     * @param {String|Array<String>} className
     */
    #removeStyle(target, className) {
        className = Array.isArray(className) ? className : [className]
        if (target) {
            target.classList.remove(...className)
            target.removeAttribute('style')
        }
    }

    /**
     * Ajoute une classe au container principal en fonction du type de
     * device utilisé.
     * Lorsque l'utilisateur passe d'un type d'écran à un autre,
     * Le mutateur prendra à charge la modification de l'UI.
     * @param {String} device
     * @param {boolean} isActive
     */
    #setDeviceType(device, isActive) {
        this.#isMobile = this.#isTablet = this.#isDesktop = false
        this.container.classList.remove('mobile', 'desktop', 'tablet')

        if (isActive) {
            if (device === 'isMobile') {
                this.#isMobile = true
                this.container.classList.add('mobile')
            }
            if (device === 'isTablet') {
                this.#isTablet = true
                this.container.classList.add('tablet')
            }
            if (device === 'isDesktop') {
                this.#isDesktop = true
                this.container.classList.add('desktop')
            }
        }
    }

    /**
     * Ouvre le drawer avec une animation jusqu'à 80% de l'écran
     * @param {PointerEvent} e
     */
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
                this.#classRemoveFromAndAdd(this.#showDrawerButton, 'show', 'hidden')

                this.#card.addEventListener('transitionend', e => {

                    this.#isOpened = true

                    this.#card.classList.add('opened')
                    this.#removeStyle(this.#card, 'open')

                    this.#drawerBarButton.style.display = 'block'
                }, {once: true})
                this.#steps.addEventListener('click', this.#onClose.bind(this), {once : true})
            }
        }

        if (this.#isTablet) {
            if (!this.#steps.classList.contains('opened')) {
                
                if (e.currentTarget === this.#steps && !this.#card.classList.contains('open')) {
                    this.#clickedElement = 'steps'
                    this.#steps.classList.add('open')
                    this.#steps.style.animation = 'scaleOutSteps 0.5s forwards'
                    this.#grid.style.animation = 'gridContraction 0.5s forwards'
                    this.#steps.addEventListener('animationend', e => {

                        this.#isOpened = true

                        this.#removeStyle(this.#steps, 'open')
                        this.#steps.classList.add('opened')
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
        this.#removeStyle(this.#card, ['open', 'opened', 'fullyOpened', 'hidden'])
        this.#isOpened ? this.#isOpened = false : null
        this.#isFullyOpened ? this.#isFullyOpened = false : null
        // this.#isScrolledAtTop ? this.#isScrolledAtTop = false : null
        // this.#isFullyOpened ? this.#isFullyOpened : null
        this.#removeAttributeFrom([this.drawer, this.#steps], 'style')
        // this.drawer?.style.display === 'block' ? this.drawer?.removeAttribute('style') : null

        this.#classRemoveFromAndAdd(this.#showDrawerButton, 'hidden', 'show')
        this.#isScrolledAtTop = false
        this.#enableScrollBehavior()
    }

    /**
     * Permet de désactiver le scroll sur le root
     */
    #disableScrollBehavior() {
        document.documentElement.style.overflow = 'hidden'
        document.documentElement.style.overscrollBehavior = 'none'
    }

    /**
     * Permet de réactiver le scroll sur le root
     */
    #enableScrollBehavior() {
        document.documentElement.removeAttribute('style')
        this.#isScrolledAtTop = false
    }

    /**
     * Vérifie si la carte a été défilée tout en haut
     * @param {Event} e Scroll event
     */
    #onScroll(e) {
        e.target.scrollTop === 0 ? this.#isScrolledAtTop = true : this.#isScrolledAtTop = false
    }

    /**
     * Ferme le drawer en l'animant jusqu'à sa complète disparition
     * en bas de l'écran.
     * @param {PointerEvent} e
     * @returns
     */
    #onClose(e) {
        this.#card.removeEventListener('click', this.#onClose.bind(this))

        if (this.#isMobile) {
            if (!this.#card.classList.contains('opened')) return
            this.#card.style.animation = 'slideToBottom 0.5s forwards'
            this.#card.classList.add('hidden')
            this.#card.addEventListener('animationend', () => {
                this.#resetStatusAndStyle()
            }, {once: true})
        }

        if (this.#isTablet) {
            if (this.#card.classList.contains('open')) {
                if (e.currentTarget === this.#steps || e.currentTarget === this.#closeButton) {
                    this.#card.style.animation = 'scaleOut 0.5s reverse forwards'
                    this.#card.addEventListener('animationend', e => {
                        if (e.animationName === 'scaleOut') {
                            this.#closeButton.style.display = 'none'
                            this.#resetStatusAndStyle()
                        }
                    }, {once: true})
                }
            } else if (this.#steps.classList.contains('opened')) {
                if (e.currentTarget === this.#card) {
                    this.#steps.style.animation = 'scaleOutSteps 0.5s reverse forwards'
                    this.#grid.style.animation = 'gridContraction 0.5s reverse forwards'
                    this.#steps.addEventListener('animationend', e => {
                        if (e.animationName === 'scaleOutSteps') {
                            this.#card.classList.remove('open')
                            this.#removeStyle(this.#steps, 'opened')
                            this.#isOpened = false
                            this.#grid.removeAttribute('style')
                            this.#enableScrollBehavior()
                        }
                    }, {once: true})
                }
            } else {
                return
            }
        }
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

        if (this.#isMobile) {
            this.#card.classList.contains('opened') ? null : this.#card.classList.add('open')
        }

        if (this.#isTablet) {
            if (!this.#card.classList.contains('open') && !this.#steps.classList.contains('opened')) {
                this.#card.style.animation = 'scaleOut 0.5s forwards'
                this.#card.addEventListener('animationend', e => {
                    this.#card.classList.add('open')
                    this.drawer.style.display = 'block'
                    this.#drawerBarButton.style.display = 'none'
                    this.#removeAttributeFrom([this.#card, this.#closeButton], 'style')
                    this.#card.style.width = '550px'
                }, {once: true})
                this.#steps.addEventListener('click', this.#onClose.bind(this), {once: true})
            }
        }
        this.disableTransition()
        
        // Sauvegarde de la witdh et height du conteneur
        this.height = this.#card.offsetHeight
        this.width = this.#card.offsetWidth
    }

    /**
     * Déplacement
     * @param {MouseEvent|TouchEvent} e 
     */
    drag(e) {
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
        }
    }

    /**
     * Permet de déplacer le conteneur visuellement en fonction des point de pression
     * @param {Number} percent 
     */
    translate(percentY, percentX = '0', width = null) {
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
        this.#card.style.zIndex = '1000'
    }

    /**
     * Active la transition du conteneur
     */
    enableTransition() {
        this.#card.style.overflowY = 'auto'
        this.#card.style.animation = null
        this.#card.style.transition = ''
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
                // From half opened slide to bottom and disappear
                if (this.#isOpened && !this.#isFullyOpened && this.lastTranslate.y > 0 && this.#savedTranslateY !== this.lastTranslate.y) {
                    this.#savedTranslateY = this.lastTranslate.y
                    this.#card.style.animation = 'slideToBottom 0.5s forwards'
                    this.#card.classList.add('hidden')
                    this.#card.addEventListener('animationend', () => {
                        this.#removeStyle(this.#card, ['open', 'opened', 'hidden'])
                        this.#card.style.display = 'none'

                        this.#isOpened = false

                        this.#classRemoveFromAndAdd(this.#showDrawerButton, 'hidden', 'show')
                        this.#removeAttributeFrom([this.drawer, this.#steps], 'style')

                        this.#enableScrollBehavior()
                    }, {once: true})
                }
                // From fully opened slide to half opened slide
                if (this.#isMobile && this.#isFullyOpened && this.lastTranslate.y > 0) {
                    this.#savedTranslateY = this.lastTranslate.y
                    this.#drawerBarButton.classList.contains('fullyOpened') ? this.#drawerBarButton.classList.remove('fullyOpened') : null
                    
                    this.#card.style.animation = 'slideFromTop 0.5s forwards'
                    
                    this.#card.addEventListener('animationend', (e) => {
                        this.#removeStyle(this.#card, 'fullyOpened')

                        this.#isFullyOpened = false

                    }, {once: true})
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
        if (!this.#isMobile) return
        this.#card.style.animation = 'slideToBottom 0.5s forwards'
        this.#card.addEventListener('animationend', () => {
            this.#resetStatusAndStyle()
        }, { once: true } )
        return
    }

    /**
     * Récupère le conteneur HTML du constructeur
     * @return
     */
    // get _container() {
    //     return this.container
    // }
}