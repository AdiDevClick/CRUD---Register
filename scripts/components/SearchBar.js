import { fetchJSON } from "../functions/api.js"
import { createElement, debounce, wait, waitAndFail } from "../functions/dom.js"
import { resetURL } from "../functions/url.js"
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
    #newUrl
    #script
    /** @type {AbortController} */
    #controller
    /** 
     * Options pour le loader infini
     * @type {IntersectionObserver} 
     */
    #observer
    /** @type {Number} */
    #ratio = .6
    #options = {
        delay: 100,
        root: null,
        rootMargin: '0px',
        threshold: this.#ratio,
        trackVisibility: true
    }
    #handleIntersect = (entries, observer) => {
        entries.forEach(entry => {
            console.log('Il ma ete demande dobserver')
            // console.log('je suis dans le entry => ', ' \n // loading => ' + this.#loading, ' \n // isCreated => ' + this.#isCreated, ' \n // intersect ? => ' + this.#intersect)

            if (entry.isIntersecting) {
                console.log('ça intersect')
            }
            // if (entry.intersectionRatio <= this.#ratio) {
            if (entry.intersectionRatio > this.#ratio) {
            // if (entry.boundingClientRect) {
            // console.log('g le bon ratio => ', ' \n // loading => ' + this.#loading, ' \n // isCreated => ' + this.#isCreated, ' \n // intersect ? => ' + this.#intersect)
                console.log('je suis dans lobs')
                this.#intersect = true
                this.#loadMore()
                // console.log(entry.boundingClientRect)
                // console.log(this.#loading)
                // console.log(this.#isCreated)
                // console.log(this.#isDeleted)
                
            // } else {
            }

                console.log('le ratio est pas bon')
                this.#intersect = false
                
            // }
            // }
            // do {
            //     console.log('je suis dans lobs')
            //     this.#intersect = true
            //     this.#loadMore()
            // } if (entry.intersectionRatio > this.#ratio)
            // this.#intersect = false
        })
        // return
    }
    /** @type {Object} */
    #carousel = {}
    /** @type {String} */
    #isSearchIncludedInUrl = window.location.href.toString().includes('search')

    constructor(element, options = {}) {
        // localStorage.removeItem('forwardContent');
        // this.#oldUrl = this.#oldUrl !== this.#newUrl
        // if (this.#oldUrl !== this.#newUrl ) this.#oldUrl = this.#newUrl

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
        this.#target.innerText = 'Carousel 1'
        // this.#target.classList.add('searched-recipes')
        // this.#target = document.querySelector(element.dataset.target)
        this.#endpoint = this.#searchForm.dataset.endpoint
        this.#limit = element.dataset.limit
        this.#elements = JSON.parse(element.dataset.elements)
        this.#template = document.querySelector(element.dataset.template)
        this.#container = createElement('section', {
            class: "container"
        })
        // this.#container.classList.add('container')
        this.#wrapper = document.querySelector('.wrapper')
        this.#content.innerHTML = this.#wrapper.innerHTML
        // this.#content.innerHTML = JSON.stringify(this.#wrapper.innerHTML)
        // this.#content = this.#wrapper.innerHTML
        // localStorage.setItem('forwardContent', JSON.stringify(this.#content))
        // localStorage.setItem('forwardContent', JSON.stringify(this.#content))

        // console.log(this.#observer)

        this.#searchForm.addEventListener('submit', e => {
            e.preventDefault()
            // this.#newSearch(e)
            // this.#input = e.target
        })
        this.#searchForm.addEventListener('input', debounce((e) => {
            this.#newSearch(e)
            this.#input = e.target
        }, (this.options.debounceDelay)))
        // if (this.#loader) {
            window.addEventListener('DOMContentLoaded', () => {
                console.log('lancement du DOM, je lance lobs')
                this.#observer = new IntersectionObserver(this.#handleIntersect, this.#options)
                // debugger
                // this.#observe(this.#loader)
                if (this.#isSearchIncludedInUrl) {
                // if (window.location.href.toString().includes('search')) {
                    this.#observer.observe(this.#loader)
                    this.#loader.dataset.libraryNameObserverType = true
                } else {
                    this.#loader.remove()
                }
                
                // this.#observer.root.style.border = "26px solid #44aa44";
            }, {once: true})
        // }
        
        window.onpopstate = (e) => {
            e.preventDefault()
            if (history && (window.location.origin+window.location.pathname === this.#oldUrl)) {
                // this.#content.push(this.#wrapper.innerHTML)
                // this.#content.push(this.#newUrl)
                console.log('1 => -----------------')
                console.log(this.#content)
                console.log(this.#content.innerHTML)
                console.log('-----------------')

                // this.#content.innerHTML = this.#wrapper.innerHTML
                
                this.#content.innerContent = []

                this.#carousel.initialItemsArray.forEach(element => {
                    this.#content.innerContent.push(element.outerHTML)
                })

                // this.#content.push(this.#wrapper.innerHTML)
                // this.#content.push(this.#newUrl)

                this.#content.newUrl = this.#newUrl
                this.#content.carousel = this.#carousel
                // this.#content.push(this.#url.searchParams)
                // this.#content.params['params'] = this.#url.searchParams
                this.#content.params = {}
                // this.#content.params.push(this.#url.searchParams)

                for (const [key, value] of this.#url.searchParams) {
                    this.#content.params[key] = value
                }
                localStorage.setItem('forwardContent', JSON.stringify(this.#content))
                // localStorage.setItem('forwardContent', this.#content)
                // this.#content['test'].push(this.#wrapper.innerHTML)
                // this.#content.push(this.#content.newUrl)
                // localStorage.setItem('forwardContent', JSON.stringify(toSave))
                // localStorage.setItem('forwardContent', JSON.stringify(this.#content))
                // document.querySelector('head').append(this.#script)
                // history.pushState({}, document.title, this.#newUrl)
                this.#observer.unobserve(this.#loader)
                location.reload()
                console.log('cest le back')

                // console.log(this.#content)

                // this.#observe(this.#loader)
            }
            if (history !== null && (window.location.origin+window.location.pathname !== this.#oldUrl)) {
                // this.#content = localStorage.getItem('forwardContent')
                const content = localStorage.getItem('forwardContent')
                // let content = JSON.parse(this.#content)
                this.#content = JSON.parse(content)

                this.#newUrl = this.#content.newUrl
                this.#wrapper.innerHTML = this.#content.innerContent

                this.#page = this.#content.params._page
                this.#limit = this.#content.params._limit
                this.#input = this.#content.params.query
                this.#carousel = this.#content.carousel
                // this.#loadMore()
                // this.#newUrl = content.newUrl
                // this.#wrapper.innerHTML = content.innerHTML
                this.#content.innerHTML = localStorage.getItem('forwardContent')
                // this.#newUrl = this.#content.newUrl
                // this.#wrapper.innerHTML = this.#content.innerHTML
                this.#createOrUpdateNewUrl(
                    'create', this.#input, 1,
                    this.#page, this.#limit
                )

                this.#isCreated = true
                
                this.#intersect = true

                console.log(this.#content.params)
                console.log('cest le go')
                console.log(this.#isCreated)
                // console.log(this.#carousel)
                // this.#onReady("1")
                this.#loadMore()
                // this.#observer.observe(this.#loader)
            }
        }

        window.addEventListener('beforeunload', (e) => {
            if (this.#isSearchIncludedInUrl) {
            // if (window.location.href.toString().includes('search')) {
                // e.preventDefault()
                this.#content.innerHTML = this.#wrapper.innerHTML
                this.#content.newUrl = this.#newUrl
                localStorage.setItem('saved_search_results', JSON.stringify(this.#content))
                localStorage.setItem('saved_search_query', this.#url.searchParams)
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
     * @param {InputEvent} e 
     */
    #newSearch(e) {
        e.preventDefault()
        // debugger
        // const wrapper = document.querySelector('.wrapper')
        // const container = document.querySelector('.container')

        // document.querySelector('.container').remove()
        // const container = document.createElement('section')
        // container.classList.add('container')
        // console.log(container)
        // wrapper.append(container)
        // const hero = document.querySelector('.hero')
        // if (this.#loading) {
        //     return
        // }
        // if (this.#loading) {
        //     // console.log(this.#loader)
        //     // wrapper.append(this.#loader)
        //     // document.querySelector('.searched-recipes').append(this.#loader)
        //     wrapper.append(this.#loader)
        //     // this.#loader = this.#loader
        //     // this.#observer.observe(this.#loader)
        //     this.#loading = false
        // }
        // this.#loading = true
        // const form = e.target
        let data = new FormData(this.#searchForm)
        this.#input = data.get('query')
        
        // window.location.replace('recherche')
        // window.location.hash = 'recherche'
        // console.log(this.#newUrl)
        // console.log(window.location.href)
        if (!this.#isSearchIncludedInUrl) history.pushState({}, document.title, 'search/')
        // if (!window.location.href.toString().includes('search')) history.pushState({}, document.title, 'search/')
        // if (!window.location.href.toString().includes('recherche')) history.pushState({}, document.title, window.location.pathname+'/recherche/')
        if (this.#oldUrl !== window.location.origin+window.location.pathname) this.#newUrl = window.location.origin+window.location.pathname
        
        // window.history.pushState({}, document.title, this.#oldUrl)

        // console.log('old => ', this.#oldUrl)
        // console.log('new si différente => ', this.#newUrl)

        this.#createOrUpdateNewUrl('create', this.#input, 1)

        // this.#url = new URL(this.#endpoint)
        
        // this.#url.searchParams.set('query', this.#input)
        // this.#url.searchParams.set('_reset', 1)


        // url.searchParams.set('_page', this.#page)
        // url.searchParams.set('_limit', this.#limit)
        // resetURL('register.php', 'failed', urlParams)
        // debugger
        this.#controller = new AbortController()
        this.#wrapper.addEventListener('animationend', (e) => {
            if (e.animationName === 'fadeOut') {
                // console.log(e)
                // console.log('les stats en fin danim => ', ' \\\n // loading => ' + this.#loading, ' \\\n // isCreated => ' + this.#isCreated, ' \\\n // intersect ? => ' + this.#intersect, ' \\\n // is deleted ? => ' + this.#isDeleted)


                if (!this.#isDeleted) {
                    this.#wrapper.innerHTML = ''
                    this.#wrapper.appendChild(this.#container)
                    const title = createElement('div', {
                        class: 'title'
                    })
                    title.innerText = 'MA RECHERCHE'
                    this.#container.prepend(title)
                    this.#container.appendChild(this.#target)
                    this.#wrapper.classList.remove('hidden')
                    console.log('jai normalement delete')
                    this.#isDeleted = true
                    console.log('les stats en fin de delete anim => ', ' \\\n // loading => ' + this.#loading, ' \\\n // isCreated => ' + this.#isCreated, ' \\\n // intersect ? => ' + this.#intersect, ' \\\n // is deleted ? => ' + this.#isDeleted)
                    
                }
                if (!this.#isCreated && this.#isDeleted) {
                    this.#target.innerHTML = ''
                    this.#container.append(this.#loader)
                    this.#loader.classList.remove('hidden')
                    this.#isCreated = true
                    this.#loading = false
                    console.log('je lappend')
                    console.log('les stats en fin de creation anim => ', ' \\\n // loading => ' + this.#loading, ' \\\n // isCreated => ' + this.#isCreated, ' \\\n // intersect ? => ' + this.#intersect, ' \\\n // is deleted ? => ' + this.#isDeleted)
                    // this.#loadMore()
                    this.#observer.observe(this.#loader)
                }
                console.log('les stats en fin danim => ', ' \\\n // loading => ' + this.#loading, ' \\\n // isCreated => ' + this.#isCreated, ' \\\n // intersect ? => ' + this.#intersect, ' \\\n // is deleted ? => ' + this.#isDeleted)
                // this.#observer.observe(this.#loader)
                // console.log(this.#loader.dataset)
            }
        })
        // }, {once: true})

        // const queryString = document.location
        // const url = queryString.origin+'/recettes/recipes/Process_PreparationList.php'
        // const urlParams = new URLSearchParams(queryString.search)
        // urlParams.set('query', data.get('query'))
        // document.querySelector('.container').createElement('div').classList.add('searched-recipes').prepend()
        // document.querySelector('.container').append(this.#loader)
        // this.#target.classList.add('searched-recipes')

        // this.#observer.observe(this.#loader)
        // console.log('je demande la création => ', ' \n // deleted => ' + this.#isDeleted, ' \n // isCreated => ' + this.#isCreated, ' \n // intersect ? => ' + this.#intersect)

        this.#isCreated = false

        if (!this.#isDeleted || !this.#isCreated && this.#isDeleted) {
            this.#wrapper.classList.add('hidden')
        }
    }

    #createOrUpdateNewUrl(create = '', query = null, reset = null, page = null, limit = null) {
        if (create === 'create') this.#url = new URL(this.#endpoint)
        if (query) this.#url.searchParams.set('query', query)
        if (reset) this.#url.searchParams.set('_reset', reset)
        if (page) this.#url.searchParams.set('_page', page)
        if (limit) this.#url.searchParams.set('_limit', limit)
    }

    async #loadMore() {
        // console.log(this.#observer)

        console.log('j suis rentré et je commence le script => ', ' \\\n // loading => ' + this.#loading, ' \\\n // isCreated => ' + this.#isCreated, ' \\\n // intersect ? => ' + this.#intersect)
        if (this.#loading || !this.#isCreated || !this.#intersect) {
            console.log('je peux pas rentrer => ', ' \\\n // loading => ' + this.#loading, ' \\\n // isCreated => ' + this.#isCreated, ' \\\n // intersect ? => ' + this.#intersect)
            return
            // return this.#observer.observe(this.#loader)
        }
        this.#loading = true
        console.log('je suis sensé afficher le content')
        // const url = new URL(this.#endpoint)
        // if (this.#url !== undefined) {
            // console.log(this.#url)
        // this.#target.innerHTML = ''
        // }
        // url.searchParams.set('query', data.get('query'))
        try {
            
            // const id = createElement('div', {
            //     id: 'carousel1'
            // })
            // this.#container.prepend(title)
            // this.#container.append(id)
            // this.#wrapper.removeEventListener('animationend')
            this.#createOrUpdateNewUrl('update', null, null, this.#page, this.#limit)
            console.log(this.#url)
            // this.#url.searchParams.set('_page', this.#page)
            // this.#url.searchParams.set('_limit', this.#limit)
            
            this.#searchResults = await fetchJSON(this.#url)

            if (this.#searchResults.length <= 0) {
                console.log("done")
                // this.#disconnectObserver('Tout le contenu a été chargé')
                this.#observe(this.#loader, 'Tout le contenu a été chargé')
                return
            }
            // this.#url.searchParams.set('_reset', 0)
            // console.log(this.#url)
            console.log(this.#searchResults)
            this.#searchResults.forEach(result => {
                const elementTemplate = this.#template.content.firstElementChild.cloneNode(true)
                elementTemplate.setAttribute('id', result.recipe_id)
                for (const [key, selector] of Object.entries(this.#elements)) {
                    // console.log(key, ' ' +selector)
                    // console.log(elementTemplate.querySelector(selector))
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
                    // console.log(this.#url.searchParams.get('_reset'))
                    this.#carousel.appendToContainer(elementTemplate)
                    // console.log('je demande a append le carousel')
                } else {
                    // console.log(this.#url.searchParams.get('_reset'))
                    this.#target.append(elementTemplate)
                    // console.log('je demande a append n,ormalement')
                }
            })
            document.documentElement.classList.add('search-loaded')
            
            // if (window.readyState !== 'loading') {
            // debugger
            this.#onReady(this.#url.searchParams.get('_reset'))
            // }
            this.#url.searchParams.set('_reset', 0)

            // window.addEventListener('DOMContentLoaded', onReady)

            this.#wrapper.classList.remove('hidden')

            this.#page++
            this.#input.value = ''
            this.#loading = false
            this.#controller.abort()

            // IMPORTANT !! 
            // Force observer to reset in some cases where 
            // the loader appears but it's state cannot update
            this.#observer.unobserve(this.#loader)
            // End of Force

            this.#observer.observe(this.#loader)

            // this.#observe(this.#loader)
            // this.#observer.unobserve(this.#loader)
            console.log('jsuis arrivé à la fin du script => ', ' \\\n // loading => ' + this.#loading, ' \\\n // isCreated => ' + this.#isCreated, ' \\\n // intersect ? => ' + this.#intersect)
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

    #onReady(restyleNumber) {
        const updateStyle = (restyleNumber === '0') ? true : false
        
        if (!updateStyle) {
            // console.log(restyle)
            // console.log(updateStyle)
            console.log('je demande à créer le carousel')
            this.#carousel = new Carousel(document.querySelector('#carousel1'), {
                visibleSlides: 3,
                automaticScrolling: false,
                loop: false,
                // infinite: true,
                pagination: false,
                afterClickDelay: 1000,
                grid: true
            })
        } else {
            console.log("le carousel est deja créé, je restyle")
            this.#carousel.restyle
            console.log(this.#observer)

        }
    }

    /**
     * @param {NodeListOf.<HTMLElement>} elements
     */
    #observe(elements, message = null) {
        if (this.#observer) {
            this.#observer.unobserve(elements)
            this.#observer.disconnect()
            this.#intersect = false
            if (this.#loading) {
                this.#input.value = ''
                this.#loading = false
            }
            if (this.#isCreated) this.#isCreated = false
            this.#page = 1
            this.#loader.remove()
            if (this.#wrapper.classList.contains('hidden')) this.#wrapper.classList.remove('hidden')
            if (document.documentElement.classList.contains('search-loaded')) document.documentElement.classList.remove('search-loaded')
            message ? new Toaster(message, 'Succès') : null
        } else {
            this.#observer = new IntersectionObserver(this.#handleIntersect, this.#options)
            this.#observer.observe(elements)
        }
        return
    }

    #disconnectObserver(message) {
        this.#loading = false
        this.#isCreated = false
        this.#input.value = ''
        this.#observer.disconnect()
        this.#loader.remove()
        new Toaster(message, 'Succès')

        // this.#observer.unobserve(this.#loader)
        
        // throw new Error(message)
    }

    // set setUpdateAdress(url) {
    //     this.#url = url
    // }
}