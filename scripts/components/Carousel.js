// import { CarouselTouchPlugin } from "./CarouselTouchPlugin.js"
// import { CarouselHoverPlugin } from "./CarouselHoverPlugin.js"
// import { YoutubePlayer } from "./YoutubePlayerPlugin.js"
import { createElement, debounce, importThisModule, wait, waitAndFail } from "../functions/dom.js"


export class Carousel
{

    /**
     * This callback type is called `requestCallback` and is displayed as a global symbol.
     * @callback moveCallback
     * @param {number} index
     */
    /** @type {Array} In order to save the inital HTML elements before manipulation for future use */
    initialItemsArray = []
    /** @type {HTMLElement} */
    #paginationButton
    /** @type {Boolean} */
    #click = false
    /** @type {Boolean} */
    #scrolling = false
    /** @type {String} */
    #status
    /** @type {Boolean} */
    #hovered = false
    /** @type {Number} */
    #currentTime
    /** @type {HTMLElement} Boutton "précédent" du carousel */
    #prevButton
    /** @type {HTMLElement} Boutton "suivant" du carousel */
    #nextButton
    /** @type {Number} */
    #endTime = 0
    /** @type {Number} */
    #startTime = 0
    /** @type {Boolean} */
    #reverseAnimation
    /** 
     * @type {IntersectionObserver}
     */
    #observer
    /** @type {Boolean} */
    #intersect
    /** @type {Number} Threshold for intersection obs */
    #ratio = .6
    /** @type {Object} Intersection Observer options */
    #options = {
        root: null,
        rootMargin: '0px',
        threshold: this.#ratio
    }
    /**
     * Intersection Obs Handler -
     * When intersect, calls the main function
     * and modify animation and loading bar behavior -
     * In order to avoid too many callstacks, 50ms debouncer
     * is in place (not mandatory) -
     * @type {IntersectionObserverCallback}
     */
    #intersectHandler = debounce( (entries) => {
        entries.forEach(entry => {
            if (entry.intersectionRatio > this.#ratio & !this.#intersect) {
                this.#intersect = true
                this.#animate()
                this.#autoSlide()
                this.#showLoadingBar()
                this.startTime
                return
            } else {
                !this.#reverseAnimation ? this.#bubbleAnimation() : null
                this.#intersect = false
                this.#hovered = false
                return
            }
        })
        return
    }, 50)

    /**
     * @type {ResizeObserver}
     */
    #resizeObserver
    /**
     * Resize Obs Handler -
     * When resizing, calls the restyle function
     * and recalculate the appearence of the carousel -
     * In order to avoid too many callstacks, 50ms debouncer
     * is in place (mandatory) -
     * @type {ResizeObserverCallback}
     */
    #handleResize = debounce( (entries, observer) => {
        entries.forEach(entry => {
            let mobile = window.innerWidth < 800
            if (mobile !== this.#isMobile) {
                this.#isMobile = mobile
                this.setStyle()
                this.#moveCallbacks.forEach(cb => cb(this.currentItem))
                return
            }
        })
        return
    }, 50)

    /** @type {Array.Callback} */
    #moveCallbacks = []
    /** @type {Boolean} */
    #isMobile = false
    /** @type {HTMLElement} */
    #loadingBar
    /** @type {Number} */
    #offset = 0
    /** @type {Array} Promises array */
    #resolvedPromisesArray = []
    // #eventAction
    #myIndex
    /** @type {Boolean} */
    #reverseMode = false
    /** @type {Array} Youtube API players array */
    #player = []
    /** @type {Boolean} */
    #alreadyHovered
    /** @type {Number} */
    #alreadyHoveredStartTime = 0
    /** @type {Number} */
    #alreadyHoveredEndTime = 0
    #case
    static #isInternalConstructing = false;


    /**
     * @param {HTMLElement} element 
     * @param {Object} options 
     * @param {Object} [options.slidesToScroll=1] Permet de définir le nombre d'éléments à faire défiler 
     * @param {Object} [options.visibleSlides=1] Permet de définir le nombre d'éléments visibles dans un slide 
     * @param {boolean} [options.loop=false] Permet de définir si l'on souhaite boucler en fin de slide
     * @param {boolean} [options.pagination=false] Permet de définir une pagination
     * @param {boolean} [options.navigation=true] Permet de définir la navigation
     * // IMPORTANT !! : si INFINITE = true : l'option loop DOIT ETRE FALSE
     * @param {boolean} [options.infinite=false]
     * @param {boolean} [options.automaticScrolling=true] Permet de définir le scrolling automatique - crer aussi un indicateur de temps avant chaques slides
     * @param {Object} [options.autoSlideDuration=3000] Permet de définir le délai entre chaque auto scroll - par défaut : 3s
     * @param {Object} [options.afterClickDelay=10000] Permet de définir un délai après intéraction de l'utilisateur - par défaut : 10s
     * // IMPORTANT !! : si GRID = true : l'affichage mobile aura alors 2 items par ligne
     * // Le Carousel Touch Plugin n'étant utile que sur l'axe "X", il ne sera pas actif -
     * @param {boolean} [options.grid=false] Permet de définir un affichage de type "GRID" - par défaut : false
     */
    constructor(element, options = {}) {
        console.log('je tente la création');
        if (!Carousel.#isInternalConstructing) {
            throw new TypeError("Carousel PrivateConstructor is not constructable");
        }
        Carousel.#isInternalConstructing = false
        console.log('Carousel initialisé');

        this.element = element
        this.options = Object.assign({}, {
            slidesToScroll: 1,
            visibleSlides: 1,
            loop: false,
            pagination: false,
            navigation: true,
            infinite: false,
            automaticScrolling: true,
            autoSlideDuration: 3000,
            afterClickDelay: 10000,
            grid: false
        }, options)
        this.currentItem = 0

        if (options.loop && options.infinite) {
            throw new Error(`Vous ne pouvez pas être à la fois en boucle ET en infini`)
        }
        let children = [].slice.call(element.children)
        // this.initialItemsArray = children
        // Modification du DOM
        this.root = createElement('div', {class: 'carousel'})
        this.container = createElement('div', {class: 'carousel__container'})
        this.root.setAttribute('tabindex', 0)
        this.root.append(this.container)
        this.element.append(this.root)
        this.items = children.map(child => {
            const item = this.#constructNewCarouselItem(child)
            return item
        })

        if (this.options.infinite) {
            this.#offset = this.#slidesToScroll + this.#visibleSlides
            if (this.#offset > children.length) {
                console.error(`Vous n'avez pas assez d'éléments dans le carousel`, element);
            }
            this.items = [
                ...this.items.slice(this.items.length - this.#offset).map(item => item.cloneNode(true)),
                ...this.items,
                ...this.items.slice(0, this.#offset).map(item => item.cloneNode(true))
            ]
            this.goToItem(this.#offset, false)

            this.items.forEach(item => {
                this.container.append(item)
            })
        }

        this.setStyle()

        if (this.options.navigation && !this.options.grid) {
            this.#createNavigation()
        }
        if (this.options.pagination && !this.options.grid) {
            this.#createPagination()
        }
        // Evènements
        this.#moveCallbacks.forEach(cb => cb(this.currentItem))
        if (this.options.automaticScrolling) {
            this.observe(this.element)
        }
        this.#onWindowResize()
        // window.addEventListener('resize', this.#onWindowResize.bind(this))
        this.root.addEventListener('keyup', e => this.#accessibilityKeys(e))
        if (this.options.infinite) {
            this.container.addEventListener('transitionend', this.#resetInfinite.bind(this))
        }
        // if (this.options.automaticScrolling) {
        // Plugins loader
        this.#loadModules()
        //     // new CarouselHoverPlugin(this)
        //     // CarouselHoverPlugin.create
        // }
        // this.#player = new YoutubePlayer(this)

        // if (!this.options.grid) new CarouselTouchPlugin(this)
    }

    static create(element, options = {}) {
        Carousel.#isInternalConstructing = true
        const instance = new Carousel(element, options)
        return instance
    }

    /**
     * Charge les plugins quand nécessaires seulement -
     * Les plugins : Hover / Touch / Youtube iFrame
     */
    #loadModules() {
        try {
            if (this.options.automaticScrolling) {
                importThisModule('CarouselHoverPlugin', this)
                // new CarouselHoverPlugin(this)
                // CarouselHoverPlugin.create
            }
            importThisModule('YoutubePlayerPlugin', this)
            if (!this.options.grid) {
                importThisModule('CarouselTouchPlugin', this)
            }
        } catch (error) {
            console.log(error)
        }
    }

    /**
     * Désactive la transition du conteneur
     */
    disableTransition() {
        this.container.style.transition = 'none'
    }

    /**
     * Réactive la transition du conteneur
     */
    enableTransition() {
        this.container.style.transition = ''
    }

    /**
     * Défini le status sur "clicked" et active les status click & scrolling
     * Le promise Array est réinitialisé
     */
    activateClickStatus() {
        this.#status = 'clicked'
        this.#resolvedPromisesArray = []
        this.#scrolling = true
        this.#click = true
    }

    /**
     * Permet l'accessibilité
     * @param {KeyboardEvent} e 
     */
    #accessibilityKeys(e) {
        if (e.key === 'Right' || e.key === 'ArrowRight') {
            this.next()
        } 
        if (e.key === 'Left' || e.key === 'ArrowLeft') {
            this.prev()
        }
    }

    /**
     * Applique les bonnes dimensions aux éléments du carousel
     * Permet de prendre en compte une grille
     */
    setStyle() {
        this.#moveCallbacks.forEach(cb => cb(this.currentItem))
        let ratio = this.items.length / this.#visibleSlides
        // this.container.style.width = (100) + "%"
        
        this.options.grid === true ?
            this.container.style.width = "100%" :
            this.container.style.width = (ratio * 100) + "%"
        // this.container.style.display = 'flex'
        // this.container.style.flexDirection = 'row'
        // this.container.style.flexWrap = 'wrap'
        // this.container.style.gridTemplateColumns = 'repeat(5, 1fr)'
        // this.container.style.display = 'flex'


        // this.container.style.height = (ratio * 100)
        this.items.forEach(item => {
            this.options.grid === true ?
                item.style.width = (100 / this.#visibleSlides)+ "%" :
                item.style.width = ((100 / this.#visibleSlides) / ratio) + "%"
            
            // item.style.width = "20%"
            //
        })
    }

    /**
     * Force l'animation de la loadingBar
     */
    #showLoadingBar() {
        if (this.#loadingBar) {
            this.#loadingBar.style.animationPlayState === 'paused' ? this.#loadingBar.style.animationPlayState = 'running' : null
            this.#loadingBar.style.display = 'none'
            this.#loadingBar.style.display = 'block'
        }
    }

    /**
     * @param {NodeListOf.<HTMLElement>} elements 
     */
    observe(elements) {
        if (this.#observer) {
            this.#observer.unobserve(elements)
            this.#observer.disconnect()
            this.#intersect = false
        }
        if (this.options.automaticScrolling) {
            this.#observer = new IntersectionObserver(this.#intersectHandler, this.#options)
            this.#observer.observe(elements)
        }
        return
    }

    /**
     * Permet de remettre à défaut les options de la loadingBar
     * au cas où elles auraient été reverse après une intersection
     * @returns
     */
    #animate() {
        if (this.#loadingBar && this.#reverseAnimation && this.#intersect || this.#status === 'inverseComplete') {
            this.#reverseAnimation = false
            this.#loadingBar.classList.remove('carousel__pagination__loadingBar--fade')
            this.#loadingBar.removeAttribute('style')
            return
        }
    }

    /**
     * Reverse l'animation des bulles quand les items n'intersectent pas à l'écran
     * @returns 
     */
    async #bubbleAnimation() {
        if (!this.#reverseAnimation && this.#loadingBar) {
            try {
                this.#reverseAnimation = true
                this.#status = 'inverseAnimation'
                this.#loadingBar.classList.add('carousel__pagination__loadingBar--fade')
                this.#loadingBar.style.animationDirection = 'reverse'
                this.#resolvedPromisesArray.push(await wait(2000, "je souhaite voir l'animation en reverse"))

                const r = await this.getStates
                if (r.status === 'rejected') {
                    throw new Error(`Promesse ${r.reason} non tenable`, {cause: r.reason})
                }
                this.#loadingBar.style.display = 'none' 
                this.#status = 'inverseComplete'
            } catch (error) {
                null
            }
        }
        return
    }
    
    /**
     * Passe un array de promesse définit
     */
    get getStates() {
        return this.#promiseState(this.#resolvedPromisesArray)
    }

    /**
     * Permet de RACE un array de promesse en retournant 
     * le status et la value/reason associée à la promesse qui arrive en première
     * @param {Promise} promise 
     * @returns 
     */
    #promiseState(promise) {
        const pendingState = { status: "pending" };
        
        return Promise.race(promise, pendingState)
            .then(
                (value) =>
                    value === pendingState ? value : { status: "fulfilled", value },
                (reason) => ({ status: "rejected", reason }),
        )
    }
    
    /**
     * Fonction principale de l'auto-scrolling
     * @returns 
     */
    async #autoSlide() {
        console.log('callback')

        if (this.#scrolling || !this.#intersect) return

        this.#alreadyHovered ? this.startTime : null
        try {

            if (this.#click) {
                this.#resolvedPromisesArray.push(await waitAndFail(100, "j'ai clic"))
                array = this.#resolvedPromisesArray.length 
            }

            if (this.#hovered && this.#status === 'hoveredCompleted' || this.#status === 'hovered' && !this.#status === 'clickComplete'  ) {
                this.#hovered = false
                this.#resolvedPromisesArray.push(await wait(this.#currentTime, "J'ai demandé un slide après un hover"))
            } else {
                this.#resolvedPromisesArray.push(await wait(this.#autoSlideDuration, "J'ai demandé un slide normal"))
            }

            let array = this.#resolvedPromisesArray.length
            const r = await this.getStates

            if (r.status === 'rejected') {
                throw new Error(`Promesse ${r.reason} non tenable`, {cause: r.reason})
            }

            this.#currentTime = 0

            if (!this.#click || this.#status === 'hoveredCompleted' || this.#status === 'canResume') {
                this.#scrolling = true
                this.#onFulfilled(array)
            }
            return
        } catch (error) {
            this.#onReject()
        }
    }
    
    /**
     * Permet de passer au next Slide si les conditions sont réunies
     * @param {[number]} arrayLength 
     * @returns 
     */
    #onFulfilled(arrayLength) {
        if (!this.#click && this.#intersect && !this.#reverseAnimation) {
            this.#scrolling ? this.#scrolling = false : null

            this.#case = null
            if (arrayLength <= this.#resolvedPromisesArray.length && this.#reverseMode) this.prev()
            if (arrayLength <= this.#resolvedPromisesArray.length && !this.#reverseMode) this.next()

            this.#resolvedPromisesArray = []
            this.#status = 'completed'

            if (this.#status === 'completed') return this.observe(this.element)
        }
        this.#scrolling ? this.#scrolling = false : null
        return
    }

    /**
     * Permet de blocker le script en cas de clic utilisateur sur les boutons
     * Il reviendra à la fonction 
     * @returns 
     */
    #onReject() {
        if (this.#click) {
            this.#resolvedPromisesArray = []
            this.#click = false
            this.#scrolling ? this.#scrolling = false : null
            this.#status = 'clickComplete'
            if (this.#status === 'clickComplete') return this.observe(this.element)
        }
        return
    }
    
    /**
     * Crer les flèches de navigation
     */
    #createNavigation() {
        this.#nextButton = createElement('div', {
            class: 'carousel__next'
        })
        this.#prevButton = createElement('div', {
            class: 'carousel__prev'
        })

        this.root.append(this.#nextButton)
        this.root.append(this.#prevButton)
        this.#createEventListenerFromClick(this.#nextButton, 'click', 'next', true, this.next.bind(this))
        this.#createEventListenerFromClick(this.#prevButton, 'click', 'prev', true, this.prev.bind(this))
        this.debounce(this.#nextButton, 'next')
        this.debounce(this.#prevButton, 'prev')

        if (this.options.loop === true || this.options.infinite === true) return
        this.#onMove(index => {
            if (index === 0) {
                this.#prevButton.classList.add('carousel__prev--hidden')
                this.#prevButton.disabled = true
            } else {
                this.#prevButton.classList.remove('carousel__prev--hidden')
                this.#prevButton.disabled = false
            }

            if (this.items[this.currentItem + this.#visibleSlides] === undefined) {
                this.#nextButton.classList.add('carousel__next--hidden')
                this.#nextButton.disabled = true
                this.#reverseMode = true
            } else {
                this.#nextButton.classList.remove('carousel__next--hidden')
                this.#nextButton.disabled = false
            }
        })
    }

    /**
     * Crer un timer
     */
    get startTime() {
        return this.#startTime = performance.now()
    }

    /**
     * Crer un timer secondaire après un premier hover
     */
    get startTimeAlreadyHovered() {
        return this.#alreadyHoveredStartTime = performance.now()
    }

    /**
     * Crer un timer
     */
    get endTime() {
        return this.#endTime = performance.now()
    }

    /**
     * Crer un timer secondaire après un premier hover
     */
    get endTimeAlreadyHovered() {
        return this.#alreadyHoveredEndTime = performance.now()
    }

    /**
     * Permet de vérifier le temps entre le hover et le début du timer
     */
    get currentTime() {
        let time
        time = this.#endTime - this.#startTime
        if (this.#alreadyHovered && !this.#click) {
            time = this.#currentTime - (this.#alreadyHoveredEndTime - this.#alreadyHoveredStartTime)
            return this.#currentTime = time
        } 
        if (this.#case === 2) {
            return this.#currentTime = this.#autoSlideDuration + this.afterClickDelay - time
        }
        return this.#currentTime = this.#autoSlideDuration - time
    }
    
    /**
     * Permet de créer un EventListener contenant un CustomEvent
     * @param {HTMLElement} object 
     * @param {EventListenerOptions} eventToListen 
     * @param {CustomElementConstructor} customEvent 
     * @param {number} animationDelay 
     * @function funct une fonction associée à l'évènement
     * @param {FunctionStringCallback} args Les arguments de la fonction si nécessaire
     */
    #createEventListenerFromClick(object, eventToListen , customEvent, animationDelay = false, funct = null, args) {
        object.addEventListener(eventToListen, (e) => {
            if (funct) funct(args)
            this.activateClickStatus()
            let newEvent = new CustomEvent(`${customEvent}`, {
                bubbles: false,
                detail: this.e
            }, {once: true})
            object.dispatchEvent(newEvent)
            animationDelay ? this.getAnimationDelay : null
        })
    }

    /**
     * Debounce le clic de la pagination ou des boutons droite et gauche
     * @param {HTMLElement} object 
     * @param {AddEventListenerOptions} event 
     * @fires [debounce] <this.afterClickDelay>
     */
    debounce(object, event) {
        object.addEventListener(event, debounce( () => {
            let array = this.#resolvedPromisesArray.length
            if (this.#status === 'clicked' || this.#click && this.#intersect) {
                if (array > this.#resolvedPromisesArray.length) {
                    this.#resolvedPromisesArray = []
                    return
                } else {
                    this.#scrolling = false
                    return this.observe(this.element)
                }
            }
        }, (this.afterClickDelay)))
    }

    /**
     * Permet de modifier la durée d'animation de la loadingBar
     * @param {number} duration 
     */
    #delayAnimation(duration) {
        if (this.#loadingBar && this.#intersect) {
            this.#showLoadingBar()
            this.#animate()
            this.#loadingBar.style.animationDuration = `${duration}ms`
        }
    }

    /**
     * @returns {@function | delayAnimation}
     */
    get getAnimationDelay() {
        if (!this.#click) {
            return this.#delayAnimation(this.#autoSlideDuration)
        }
        return this.#delayAnimation(this.afterClickDelay + this.#autoSlideDuration)
    }

    /**
     * Crer les boutons de pagination
     * @param {number} i 
     */
    #paginate(i) {
        this.#paginationButton = createElement('div', {class: 'carousel__pagination__button'})
            this.#createEventListenerFromClick(this.#paginationButton, 'click', 'paginationButton', true, this.goToItem.bind(this), i + this.#offset)
            this.pagination.append(this.#paginationButton)
            this.buttons.push(this.#paginationButton)
            this.debounce(this.#paginationButton, 'paginationButton')
    }

    /**
     * Crer la pagination dans le DOM
     */
    #createPagination() {
        this.pagination = createElement('div', {class: 'carousel__pagination'})
        if (this.options.automaticScrolling) {
            this.#loadingBar = createElement('div', {class: 'carousel__pagination__loadingBar'})
        }
        this.buttons = []
        this.root.append(this.pagination)
        if (!this.options.infinite) {
            for (let i = 0; i < this.items.length / this.#visibleSlides; i++) {
                this.#paginate(i)
            }
        } else {
            for (let i = 0; i < this.items.length - 2 * this.#offset; i = i + this.#slidesToScroll) {
                this.#paginate(i)
            }
        }
        this.buttons.push(this.#paginationButton)
        let activeButton
        this.#onMove(index => {
            let count = this.items.length - 2 * this.#offset
            
            if (this.options.infinite) {
                activeButton = this.buttons[Math.floor((index % count) / this.#slidesToScroll) ]
            } else {
                activeButton = this.buttons[Math.round(index / this.#slidesToScroll)]
            }

            if (activeButton) {
                this.buttons.forEach(button => {
                    button.classList.remove('carousel__pagination__button--active')
                    this.#loadingBar ? this.#loadingBar.remove() : null
                })
                activeButton.classList.add('carousel__pagination__button--active')
                this.#loadingBar ? activeButton.append(this.#loadingBar) : null
                this.getAnimationDelay
                this.#intersect ? this.startTime : null
            }
        })
    }

    next() {
        this.#alreadyHovered = false
        this.goToItem(this.currentItem + this.#slidesToScroll)
    }

    prev() {
        this.#alreadyHovered = false
        this.goToItem(this.currentItem - this.#slidesToScroll)
    }

    /**
     * Déplace le carousel vers l'élément ciblé
     * @param {number} index 
     * @param {boolean} [animation = true]
     */
    goToItem(index, animation = true) {
        if (index < 0) {
            let ratio = Math.floor(this.items.length / this.#slidesToScroll)
            let modulo = this.items.length % this.#slidesToScroll
            if (this.options.loop) {
                
                if (index + this.#slidesToScroll !== 0) {
                    index = 0
                }
                if (ratio - modulo === this.#slidesToScroll && modulo !== 0) {
                    index = this.items.length - this.#visibleSlides
                    this.#myIndex = 1
                } else if (ratio + modulo === this.#visibleSlides && ratio === this.#slidesToScroll) {
                    index = this.items.length - this.#visibleSlides
                    this.#myIndex = 2
                } else if (ratio - modulo !== this.#slidesToScroll) {
                    if (index + this.#slidesToScroll !== 0) {
                        index = 0
                    } else {
                        index = this.items.length - this.#visibleSlides
                    }
                } else {
                    for (let i = 0; i < this.items.length; i = i + this.#slidesToScroll) {
                        index = i
                    }
                }
            } else {
                return this.#reverseMode = false
            }
        } else if ((index >= this.items.length) || (this.items[this.currentItem + this.#visibleSlides] === undefined) && index > this.currentItem) {
            if (this.options.loop && !this.#reverseMode) {
                index = 0
            } else {
                return
            }
        } else if (index + this.#slidesToScroll > this.items.length) {
            index = this.items.length - this.#visibleSlides
        }
        let translateX = index * (-100 / this.items.length)
        if (!animation) {
            this.disableTransition()
        }
        this.translate(translateX)
        // Force Repaint
        this.container.offsetHeight
        // End of Force Repaint
        if (!animation) {
            this.enableTransition()
        }
        this.currentItem = index
        this.#moveCallbacks.forEach(cb => cb(index))
    }

    /**
     * Déplace le container pour donner l'impression d'un slide infini
     */
    #resetInfinite() {
        if (this.currentItem <= this.#slidesToScroll) {
            this.goToItem(this.currentItem + (this.items.length - 2 * this.#offset), false)
            return
        } 
        if (this.currentItem >= this.items.length - this.#offset) {
            this.goToItem(this.currentItem - (this.items.length - 2 * this.#offset), false)
            return
        }
        return
    }

    /** @param {moveCallback} */
    #onMove(callback) {
        this.#moveCallbacks.push(callback)
    }
    
    /**
     * Permet de définir un reStyle en fonction 
     * du changement de la taille de lafenêtre
     */
    #onWindowResize() {
        this.#resizeObserver = new ResizeObserver(this.#handleResize)
        this.#resizeObserver.observe(this.root)
    }

    translate(percent) {
        this.container.style.transform = 'translate3d('+ percent + '%,  0, 0)'
    }

    /** @returns {number} */
    get #slidesToScroll() {
        return this.#isMobile ? 1 : this.options.slidesToScroll
    }

    /** @returns {number} */
    get #visibleSlides() {
        return this.#isMobile ? (this.options.grid ? 2 : 1) : this.options.visibleSlides
    }

    /** @returns {number} */
    get #autoSlideDuration() {
        return this.options.autoSlideDuration
    }

    /** @returns {@param | options.afterClickDelay} */
    get afterClickDelay() {
        return this.options.afterClickDelay
    }

    /** @returns {number} */
    get containerWidth() {
        return this.container.offsetWidth
    }

    /** @returns {number} */
    get carouselWidth() {
        return this.root.offsetWidth
    }

    /**
     * Rajoute un item à l'initialArray pour pouvoir
     * l'insérer dynamiquement dans le Carousel -
     * Transforme l'élément en paramètre puis l'ajoute au carousel -
     * Si une vidéo est trouvée, elle sera automatiquement transformée en iFrame -
     * @param {HTMLElement} item Une nouvelle carte à display
     */
    appendToContainer(item) {
        // Sauvegarde de l'item
        // this.initialItemsArray.push(item)
        // Fin de sauvegarde
        // const newItem = createElement('div', {class: 'carousel__item'})
        // newItem.append(item)
        // this.container.append(newItem)
        const newItem = this.#constructNewCarouselItem(item)
        this.items.push(newItem)
        // for (let i = 0; i < this.items.length - 2 * this.#offset; i = i + this.#slidesToScroll) {
        //     this.#paginate(i)
        // }

        // this.#player.deleteIFrames
        // this.#player.APIReady
        this.#player = importThisModule('YoutubePlayerPlugin', this)
        // this.setStyle()
    }

    /**
     * Construit un nouvel élément de carrousel,
     * l'ajoute au conteneur et le sauvegarde dans le tableau initial -
     * @param {HTMLElement} item L'élément à ajouter au carrousel -
     * @returns {HTMLElement} L'élément ajouté au conteneur -
     */
    #constructNewCarouselItem(item) {
        // Sauvegarde de l'item dans l'Array initial du constructeur
        this.initialItemsArray.push(item)
        // Fin de sauvegarde
        const newItem = createElement('div', { class: 'carousel__item' })
        newItem.append(item)
        this.container.append(newItem)
        return newItem
    }

    get getScrollingStatus() {
        return this.#scrolling
    }

    /** @param {Boolean} status */
    set setScrollingStatus(status) {
        this.#scrolling = status
    }

    get getClickStatus() {
        return this.#click
    }

    set setClickStatus(status) {
        this.#click = status
    }

    get getPromiseArray() {
        return this.#resolvedPromisesArray
    }

    /**
     * Permet de réinitialiser l'array de promesses -
     * @param {Array} array
     */
    set setPromiseArray(array) {
        this.#resolvedPromisesArray = array
    }

    get getLoadingBar() {
        return this.#loadingBar
    }

    set setLoadingBar(status) {
        this.#loadingBar = status
    }

    get getStatus() {
        return this.#status
    }

    /** @param {String} status */
    set setStatus(status) {
        this.#status = status
    }

    get getHoverStatus() {
        return this.#hovered
    }

    /** @param {Boolean} status */
    set setHoverStatus(status) {
        this.#hovered = status
    }

    get getVideoPlayer() {
        return this.#player
    }

    get getIntersectStatus() {
        return this.#intersect
    }

    // get getEventAction() {
    //     return this.#eventAction
    // }

    get isAlreadyHovered() {
        return this.#alreadyHovered
    }

    /** @param {Boolean} status */
    set thisIsAlreadyHovered(status) {
        return this.#alreadyHovered = status
    }

    get isCase() {
        return this.#case
    }

    set setCase(status) {
        return this.#case = status
    }

    get restyle() {
        return this.setStyle()
    }
}