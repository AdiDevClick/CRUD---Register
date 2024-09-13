import { fetchJSON } from "../functions/api.js"
import { createElement, debounce, wait, waitAndFail } from "../functions/dom.js"
import { resetURL } from "../functions/url.js"
import { closeMenu } from "../script.js"
import { Carousel } from "./Carousel.js"
import { Toaster } from "./Toaster.js"

export class SearchBar
{
    /** @type {NodeListOf<HTMLElement>} */
    #loader
    /** @type {HTMLElement} */
    #input 
    /** @type {String} */
    #url
    // #url = '../recipes/Process_PreparationList.php'
    /** @type {Boolean} */
    #isSentAlready = false
    /** @type {Array} */
    #searchResults = []
    /** @type {boolean} */
    #loading = false
    /** @type {Boolean} */
    #isDeleted = false
    /** @type {Boolean} */
    #isCreated = false
    /** @type {Boolean} */
    #intersect = false
    /** @param {String} endpoint */
    #endpoint
    /** @type {Object} */
    #elements
    /** @type {HTMLTemplateElement} */
    #template
    /** @type {HTMLElement} */
    #target
    /** @typedef  {HTMLElement} HTMLMainElement */
    #wrapper
    /** @type {HTMLElement} */
    #container
    /** @type {Number} */
    #page = 1
    /** @type {Number} */
    #limit
    /** @type {HTMLFormElement} HTMLFormElement */
    #searchForm
    /** @type {Object} */
    #content = {}
    /** @type {Location} */
    #oldUrl = window.location.origin+window.location.pathname
    /** @type {Location} */
    #newUrl
    #script
    /** @type {AbortController} */
    // #controller
    /** 
     * Options pour le loader infini
     * @type {IntersectionObserver} 
     */
    #observer
    /** @type {Number} Threshold intersection ratio */
    #ratio = .6
    /** 
     * ATTENTION !! delay and trackVisibility are mandatory
     * @type {Object} Intersection options
     */
    #options = {
        delay: 100,
        root: null,
        rootMargin: '0px',
        threshold: this.#ratio,
        trackVisibility: true
    }
    /**
     * Intersection Obs Handler -
     * When intersect, calls the main function -
     * In order to avoid too many callstacks, 50ms debouncer
     * is in place (not mandatory) -
     * @type {IntersectionObserverCallback}
     */
    #handleIntersect = debounce( (entries, observer) => {
        entries.forEach(entry => {
            if (entry.intersectionRatio > this.#ratio && !this.#intersect) {
                this.#intersect = true
                this.#loadMore()
            }
            this.#intersect = false
        })
        return
    }, 50)
    
    /** @type {Object} Carousel class */
    #carousel = {}

    /**
     * @param {HTMLElement} element
     * @param {Object} options
     */
    constructor(element, options = {}) 
    {
        this.#loader = element
        this.options = Object.assign({}, {
            debouncing: true,
            debounceDelay: 1000,
            canBeEmpty: false,
            // whichInputCanBeEmpty: ['step_3', 'step_4', 'step_5', 'step_6', 'file'],
            // isSpecialCharactersAllowed: false,
            // whichInputAllowSpecialCharacters: ['Mot de Passe', 'Mot de Passe de confirmation', 'Email', 'file'],
        }, options)
        
        this.#searchForm = document.querySelector(element.dataset.form)
        
        this.#target = createElement("div", {
            class: "searched-recipes",
            id: "carousel1"
        })

        // this.#target = document.querySelector(element.dataset.target)
        this.#endpoint = this.#searchForm.dataset.endpoint
        this.#limit = element.dataset.limit
        this.#elements = JSON.parse(element.dataset.elements)
        this.#template = document.querySelector(element.dataset.template)
        this.#container = createElement('section', {
            class: "container"
        })
        this.#wrapper = document.querySelector('.wrapper')
        this.#content.innerHTML = this.#wrapper.innerHTML
        // this.#content.innerHTML = JSON.stringify(this.#wrapper.innerHTML)
        // this.#content = this.#wrapper.innerHTML
        // localStorage.setItem('forwardContent', JSON.stringify(this.#content))
        // localStorage.setItem('forwardContent', JSON.stringify(this.#content))

        this.#searchForm.addEventListener('submit', e => {
            e.preventDefault()
            // this.#newSearch(e)
            // this.#input = e.target
        })
        this.#searchForm.addEventListener('input', debounce((e) => {
            this.#newSearch(e)
            this.#input = e.target
        }, (this.options.debounceDelay)))
        
        window.addEventListener('DOMContentLoaded', () => {
            this.#observer = new IntersectionObserver(this.#handleIntersect, this.#options)
            if (window.location.href.toString().includes('recherche')) {
                this.#observer.observe(this.#loader)
                this.#loader.dataset.libraryNameObserverType = true
            } else {
                this.#loader.remove()
                this.#observer.unobserve(this.#loader)
            }
            // this.#observer.root.style.border = "26px solid #44aa44";
        }, {once: true})
        
        /**
         * Si la touche précédente est utilisée, la page précédente sera rechargée
         * et le contenu actuel sera sauvegardé -
         * Si la touche suivante est utilisée, la page sera réaffiché avec le contenu précédemment
         * sauvegardé qui sera reconstruit -
         * @param {PopStateEvent} e 
         */
        window.onpopstate = (e) => {
            e.preventDefault()
            if (window.location.hash === '#username' || '#') {
                closeMenu(e)
                return
            }
            if (history && (window.location.origin+window.location.pathname === this.#oldUrl)) {
                this.#content.innerContent = []
                const XMLS = new XMLSerializer()
                if (this.#carousel.initialItemsArray > 0) {
                    this.#carousel.initialItemsArray.forEach(element => {
                        const inp_xmls = XMLS.serializeToString(element)
                        this.#content.innerContent.push(inp_xmls)
                    })
                }
                this.#content.input = this.#input.id
                this.#content.newUrl = this.#newUrl
                this.#content.searchResultsLength = this.#searchResults.length

                this.#content.params = {}

                for (const [key, value] of this.#url.searchParams) {
                    this.#content.params[key] = value
                }
                localStorage.setItem('forwardContent', JSON.stringify(this.#content))
                this.#observer.unobserve(this.#loader)
                location.reload()
            }
            if (history !== null && (window.location.origin+window.location.pathname !== this.#oldUrl)) {
                const content = localStorage.getItem('forwardContent')
                this.#content = JSON.parse(content)

                this.#newUrl = this.#content.newUrl
                this.#page = this.#content.params._page
                this.#limit = this.#content.params._limit
                this.#input = document.querySelector(`#${this.#content.input}`)
                // this.#input.value = this.#content.params.query
                this.#carousel = this.#content.Carousel

                this.#content.innerHTML = localStorage.getItem('forwardContent')
                this.#createOrUpdateNewUrl(
                    'create',
                    this.#content.params.query,
                    "0",
                    this.#page,
                    this.#limit
                )

                this.#recreateWrapperContent('Rechercher une recette')
                this.#deleteTargetContent()
                this.#content.innerContent.forEach(element => {
                    this.#target.insertAdjacentHTML("beforeend", element)
                })

                this.#onReady("1")
            }
            console.log('object')
        }

        //           //
        //   RELOAD  //
        //   TO DO   //
        //    NEXT   //
        //           //
        window.addEventListener('beforeunload', (e) => {
            if (window.location.href.toString().includes('recherche')) {
            // if (window.location.href.toString().includes('search')) {
                // e.preventDefault()
                this.#content.innerContent = []
                const XMLS = new XMLSerializer()
                this.#carousel.initialItemsArray.forEach(element => {
                    const inp_xmls = XMLS.serializeToString(element)
                    this.#content.innerContent.push(inp_xmls)
                })
                this.#content.input = this.#input.id
                this.#content.newUrl = this.#newUrl
                this.#content.searchResultsLength = this.#searchResults.length

                this.#content.params = {}

                for (const [key, value] of this.#url.searchParams) {
                    this.#content.params[key] = value
                }

                localStorage.setItem('forwardContent', JSON.stringify(this.#content))
                // location.reload()
                // console.log('je suis dans le beforeunload')
                // this.#content.innerHTML = this.#wrapper.innerHTML
                // this.#content.newUrl = this.#newUrl
                // localStorage.setItem('saved_search_results', JSON.stringify(this.#content))
                // localStorage.setItem('saved_search_query', this.#url.searchParams)
                
                // history.replaceState({}, document.title, window.location.origin+'/recherche')
                // location.reload()
                
            }
        })


        // if (sessionStorage.getItem("is_reloaded")) alert('Reloaded!');

        // if (window.performance) {
        //     console.log(performance.getEntriesByType("navigation")[0].type)

        // }

        // window.addEventListener('beforeunload', (e) => {
        //     // e.preventDefault()
        //     // console.log(e)
        //         // return window.location.href = 'index.php'
        //     window.location.href = 'http://127.0.0.1/recettes/index.php'
        //     e.target.location = 'http://127.0.0.1/recettes/index.php'

        //     // if (performance.getEntriesByType("navigation")[0].type) {
        //     //     e.preventDefault()
        //     //     console.log(e.currentTarget.closed = true)
        //     //     return window.location.href = 'index.php'
        //     history.pushState({}, document.title, this.#oldUrl)
                
        //     //     // return true
        //     // }
        //     console.log(this.#oldUrl)
        //     // e.preventDefault()
        //     window.location.reload()
        // if (performance.getEntriesByType("navigation")[0].type === 'reload') {
            //     //     e.preventDefault()
            // console.log(this.#newUrl)
            //     //     return window.location.href = 'index.php'
                // window.history.pushState({}, document.title, "https://127.0.0.1/recettes/index.php")
            // window.location.reload()
                    
            //     //     // return true
        // }
        // })
        //     return null
            // e.returnValue = 'reload'
            // if ((performance.getEntriesByType("navigation")[0].type) === 'reload') {
            //     // e.preventDefault()
                
            //     history.replaceState({}, document.title, this.#newUrl)
            //     history.pushState({}, document.title, this.#oldUrl)
            //     window.location.href = 'index.php'
            //     console.log('testest')
            //     e.target.location = 'http://127.0.0.1/recettes/index.php'
            // // return true
            //     if (e.currentTarget.confirm) {
            //         console.log('testest2')
            //         console.log(e.currentTarget.confirm())
            //         // window.removeEventListener('beforeunload', (e))
            //         e.target.location = 'http://127.0.0.1/recettes/index.php'
            //         const test = e.currentTarget.confirm()
            //         if (test) {
            //             e.preventDefault()
            //             console.log(test.value)
            //         } else {
            //             console.log(test)
            //             window.location.reload(true)

            //             window.open("exit.html", "Thanks for Visiting!");
            //             e.currentTarget.confirm()
            //         }
            //     }
            // }
            // window.onbeforeunload = null
            // e.preventDefault()
        // })

        // if (confirm()) {
        //     confirm().close()
        // }

        // window.addEventListener('beforeunload', debounce( (e) => {
        //     console.log(e)
        //     e.preventDefault()

        //     // if (window.location.origin+window.location.pathname === this.#newUrl) {
        //     //     console.log('object')
        //     //     window.location.href = 'index.php'
        //     //     window.location.reload(true)
        //     // }
        // }, 500))
        // window.onbeforeunload = (e) => {
        //     console.log('je demande le reload')
        //     if (window.location.origin+window.location.pathname === this.#newUrl) {
        //         // beforeUnloadHandler 
        //         // e.preventDefault()
        //         // while(true) {
        //             // if (window.onbeforeunload != null) {
        //                 // window.onbeforeunload = null
        //                 debounce (() => { 
        //                     console.log('object')
        //                 }, 1000)
        //                 console.log('je reload')
        //                 // history.replaceState({}, document.title, this.#newUrl)
        //                 // history.pushState({}, document.title, this.#oldUrl)

        //                 // resetURL(this.#newUrl)
        //                 // history.replaceState({}, document.title, this.#oldUrl)
        //                 // window.history.go(this.#oldUrl)
        //                 // window.location.reload(true)

        //                 // window.location.assign(this.#oldUrl)
        //                 // window.location.replace("https://http://127.0.0.1/recettes/index.php");
        //                 window.location.href = 'index.php'
        //                 window.location.reload(true)

                        

                        

        //                 // e.preventDefault()

        //                 // location.reload()
        //                 // return e.returnValue = true
        //             // }
        //         // }
                
        //             // return true
        //         // console.log(this.#content.innerHTML)
        //         // window.location.assign(this.#oldUrl)
        //         // history.replaceState({}, document.title, this.#newUrl)
                

        //         // history.pushState({}, document.title, this.#oldUrl)
        //         // console.log(e.srcElement.documentElement)
        //         // console.log(document.documentElement)
        //         // document.documentElement = e.srcElement.documentElement
        //         // document.replaceChild(
        //         //     document.importNode(e.srcElement.documentElement, true),
        //         //     document.documentElement
        //         // )


        //         // const content = localStorage.getItem('forwardContent')
        //         // this.#content = JSON.parse(content)
        //         // this.#wrapper.innerHTML = this.#content.innerHTML


        //         // this.#content.innerHTML = localStorage.getItem('forwardContent')
        //         // this.#wrapper.innerHTML = this.#content.innerHTML
        //     } 
        // }

        // window.onbeforeunload = (e) => {
        //     if (window.location.origin+window.location.pathname === this.#newUrl) {
        //         console.log('test')
        //         location.href = 'index.php'
        //         location.reload(true);
        //     }
        // }
    }

    /**
     * Récupère la valeur entrée par l'utilisateur dans la barre de recherche
     * puis affiche une nouvelle URL dans la barre de navigation -
     * Lorsqu'une nouvelle recherche est solicitée, le Main Wrapper sera vidé
     * pour recréer le contenu de la recherche
     * @param {InputEvent} e 
     */
    #newSearch(e) {
        e.preventDefault()

        let data = new FormData(this.#searchForm)
        this.#input = data.get('query')

        if (!window.location.href.toString().includes('recherche')) history.pushState({}, document.title, window.location.origin+'/recettes/recherche/')
        if (this.#oldUrl !== window.location.origin+window.location.pathname) this.#newUrl = window.location.origin+window.location.pathname

        this.#createOrUpdateNewUrl('create', this.#input, 1)

        // this.#controller = new AbortController()
        this.#wrapper.addEventListener('animationend', (e) => {
            if (e.animationName === 'fadeOut') {
                this.#recreateWrapperContent('Rechercher une recette')
                this.#deleteTargetContent()
            }
        }, {once: true})
        this.#isCreated = false

        if (!this.#isDeleted || !this.#isCreated && this.#isDeleted) {
            this.#wrapper.classList.add('hidden')
        }
    }

    /**
     * Crer une URL et passe les searchParams si elles ont été enregistrés/donnés
     * @param {String} create
     * @param {String} query
     * @param {Number} reset
     * @param {Number} page
     * @param {Number} limit
     * @returns 
     */
    #createOrUpdateNewUrl(create = '', query = null, reset = null, page = null, limit = null) {
        if (create === 'create') this.#url = new URL(this.#endpoint)
        if (query) this.#url.searchParams.set('query', query)
        if (reset) this.#url.searchParams.set('_reset', reset)
        if (page) this.#url.searchParams.set('_page', page)
        if (limit) this.#url.searchParams.set('_limit', limit)
        return
    }

    /**
     * Permet de créer le contenu du main wrapper
     * @param {String} titleText Titre de la page
     * @returns {Boolean}
     */
    #recreateWrapperContent(titleText) {
        if (!this.#isDeleted) {
            this.#wrapper.innerHTML = ''

            this.#wrapper.appendChild(this.#container)

            const title = createElement('h1', {
                class: 'title'
            })
            title.innerText = titleText

            this.#container.prepend(title)
            this.#container.appendChild(this.#target)

            this.#wrapper.classList.remove('hidden')
            this.#isDeleted = true
        }
        return this.#isDeleted
    }

    /**
     * Supprime tous les éléments du conteneur du Carousel
     * et recrer le loader -
     * L'observer sera relancé pour vérifier si le loader intersect
     * @returns {Boolean} #isCreated
     */
    #deleteTargetContent() {
        if (!this.#isCreated && this.#isDeleted) {
            
            this.#target.innerHTML = ''
            this.#container.append(this.#loader)

            this.#loader.classList.remove('hidden')

            this.#loading = false
            this.#isCreated = true

            if (this.#content.searchResultsLength !== 0) {
                this.#observer.observe(this.#loader)
            } else {
                this.#loader.remove()
                new Toaster('Tout votre contenu a été chargé' ,'Succès')
            }
        }
        return this.#isCreated
    }

    /**
     * MAIN FUNCTION -
     * Fetch les données depuis la DataBase puis les ajoute au DOM
     * et utilise le créateur de Carousel pour les afficher au format GRID -
     * Les nouvelles données reçues de la DataBase seront directement envoyées
     * au Carousel par sa fonction appendToContainer() et rajouté à son array this.items
     * @returns
     */
    async #loadMore() {
        // console.log('j suis rentré et je commence le script => ', ' \\\n // loading => ' + this.#loading, ' \\\n // isCreated => ' + this.#isCreated, ' \\\n // intersect ? => ' + this.#intersect)
        if (this.#loading || !this.#isCreated || !this.#intersect) {
            // console.log('je peux pas rentrer => ', ' \\\n // loading => ' + this.#loading, ' \\\n // isCreated => ' + this.#isCreated, ' \\\n // intersect ? => ' + this.#intersect)
            return
        }
        this.#loading = true
        try {
            this.#createOrUpdateNewUrl('update', null, null, this.#page, this.#limit)
            this.#searchResults = await fetchJSON(this.#url)

            if (this.#searchResults.length <= 0) {
                this.#resetStatusAndDestroyObs(this.#loader, 'Tout le contenu a été chargé')
                return
            }
            this.#searchResults.forEach(result => {
                const elementTemplate = this.#template.content.firstElementChild.cloneNode(true)
                elementTemplate.setAttribute('id', result.recipe_id)
                for (const [key, selector] of Object.entries(this.#elements)) {
                    if (key === 'img_path' && result[key]) {
                        elementTemplate.querySelector(selector).src = this.#url.origin+/recettes/+result[key]
                    } else if (key === 'img_path' && result[key] === null || undefined) {
                        elementTemplate.querySelector(selector).src = this.#url.origin+/recettes/+'img/img1.jpeg'
                    } else {
                        elementTemplate.querySelector(selector).innerText = result[key]
                    }
                    if (key === 'href') elementTemplate.querySelector(selector).href = this.#url.origin+'/recettes/recipes/read.php?id='+result.recipe_id
                }
                
                if (this.#url.searchParams.get('_reset') === '0') {
                    this.#carousel.appendToContainer(elementTemplate)
                } else {
                    this.#target.append(elementTemplate)
                }
            })
            document.documentElement.classList.add('search-loaded')
            this.#onReady(this.#url.searchParams.get('_reset'))
            this.#url.searchParams.set('_reset', 0)

            // this.#wrapper.classList.remove('hidden')

            this.#page++
            // if (this.#input.value) this.#input.value = ''
            // this.#loading = false
            // this.#controller.abort()

            // IMPORTANT !!
            // Force the observer to reset in some cases where
            // the loader appears but it's state cannot update
            // this.#observer.unobserve(this.#loader)
            // this.#observer.observe(this.#loader)
            this.#disconnectObserver(this.#loader)
            // End of Force
            // console.log('jsuis arrivé à la fin du script => ', ' \\\n // loading => ' + this.#loading, ' \\\n // isCreated => ' + this.#isCreated, ' \\\n // intersect ? => ' + this.#intersect)
        } catch (error) {
            // console.log('g une error => ', ' // loading => ', ' \n // loading => ' + this.#loading, ' \n // isCreated => ' + this.#isCreated, ' \n // intersect ? => ' + this.#intersect)
            this.#loader.style.display = 'none'
            // const alert = alertMessage(error.message)
            // this.#target.insertAdjacentElement(
            //     'beforeend',
            //     alert
            // )
            // alert.addEventListener('close', () => {
            //     this.#loading = false
            //     this.#loader.style.removeProperty('display')
            //     // alert.addEventListener('close', (e))
            // }, {once: true})
            this.#loading = false
            this.#isCreated = false
            new Toaster(error, 'Erreur')
            this.#loader.style.removeProperty('display')
        }
    }

    /**
     * Crer le Carousel avec un affichage type GRID -
     * Si l'updateStyle est passé et est à 0, le Carousel ne sera pas recréé
     * et son getter restyle sera appellé pour styliser les nouveaux éléments
     * envoyés par la DataBase -
     * @param {*} restyleNumber 
     */
    #onReady(restyleNumber) {
        const updateStyle = (restyleNumber === '0') ? true : false

        if (!updateStyle) {
            this.#carousel = new Carousel(document.querySelector('#carousel1'), {
                visibleSlides: 3,
                automaticScrolling: false,
                loop: false,
                pagination: false,
                grid: true
            })
            return
        } else {
            this.#carousel.restyle
            return
        }
    }

    // /**
    //  * Réinitialise les états puis supprime le loader lié à l'observer -
    //  * Display un Toaster contenant le message de succès après avoir chargé
    //  * tous les éléments disponibles dans la DataBase -
    //  * ----------
    //  * Si aucun observer n'a été trouvé ou n'a jamais été créé, il sera créé
    //  * puis observera l'élément attaché -
    //  * @param {NodeListOf.<HTMLElement>} elements
    //  * @param {String} message
    //  * @returns
    //  */
    // #observe(elements, message = null) {
    //     if (this.#observer) {
    //         this.#observer.unobserve(elements)
    //         this.#observer.disconnect()
    //         this.#intersect = false
    //         if (this.#loading) {
    //             this.#input.value = ''
    //             this.#loading = false
    //         }
    //         if (this.#isCreated) this.#isCreated = false
    //         this.#page = 1
    //         this.#loader.remove()
    //         if (this.#wrapper.classList.contains('hidden')) this.#wrapper.classList.remove('hidden')
    //         if (document.documentElement.classList.contains('search-loaded')) document.documentElement.classList.remove('search-loaded')
    //         message ? new Toaster(message, 'Succès') : null
    //     } else {
    //         this.#observer = new IntersectionObserver(this.#handleIntersect, this.#options)
    //         this.#observer.observe(elements)
    //     }
    //     return
    // }
    /**
     * Réinitialise les états puis supprime le loader lié à l'observer -
     * Display un Toaster contenant le message de succès après avoir chargé
     * tous les éléments disponibles dans la DataBase -
     * @param {NodeListOf.<HTMLElement>} elements
     * @param {String} message
     * @returns 
     */
    #resetStatusAndDestroyObs(elements, message = null) {
        if (this.#observer) {
            if (this.#isCreated) this.#isCreated = false
            this.#page = 1
            this.#loader.remove()
            // if (this.#wrapper.classList.contains('hidden')) this.#wrapper.classList.remove('hidden')
            if (document.documentElement.classList.contains('search-loaded')) document.documentElement.classList.remove('search-loaded')
            message ? new Toaster(message, 'Succès') : null
        }
        this.#disconnectObserver(elements)
        return
    }

    /**
     * Réinitialise quelques propriétés -
     * Si aucun observer n'a été trouvé il l'élément attaché sera observé -
     * @param {HTMLElement} obs
     * @returns
     */
    #disconnectObserver(obs) {
        if (this.#observer) {
            this.#intersect = false
            if (this.#wrapper.classList.contains('hidden')) this.#wrapper.classList.remove('hidden')
            if (this.#loading) {
                this.#input.value = ''
            }
            this.#loading = false
            this.#observer.unobserve(obs)
            this.#observer.disconnect()
        }
        this.#observer.observe(obs)
        return
    }

    // set setUpdateAdress(url) {
    //     this.#url = url
    // }
}