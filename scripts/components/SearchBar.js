import { fetchJSON } from "../functions/api.js"
import { createElement, debounce, wait } from "../functions/dom.js"
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
    /** @type {String} */
    #content
    /** @type {Location} */
    #newUrl = window.location
    #oldUrl
    #script
    /** 
     * Options pour le loader infini
     * @type {IntersectionObserver} 
     */
    #observer
    /** @type {Number} */
    #ratio = .3
    #options = {
        // delay: 100,
        // root: "26px solid #44aa44",
        rootMargin: '0px',
        threshold: this.#ratio
    }
    #handleIntersect = (entries, observer) => {
        entries.forEach(entry => {
            // console.log('je suis dans le entry => ', ' \n // loading => ' + this.#loading, ' \n // isCreated => ' + this.#isCreated, ' \n // intersect ? => ' + this.#intersect)

            if (entry.isIntersecting) {
                // console.log('ça intersect')
            }
            // if (entry.intersectionRatio <= this.#ratio) {
            if (entry.intersectionRatio > this.#ratio) {
            // if (entry.boundingClientRect) {
            // console.log('g le bon ratio => ', ' \n // loading => ' + this.#loading, ' \n // isCreated => ' + this.#isCreated, ' \n // intersect ? => ' + this.#intersect)
                this.#intersect = true
                this.#loadMore()
                // console.log(entry.boundingClientRect)
                // console.log(this.#loading)
                // console.log(this.#isCreated)
                // console.log(this.#isDeleted)
                return
            } else {
                this.#intersect = false
                return
            }
        })
        return
    }

    constructor(element, options = {}) {
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
            class: "element.dataset.target"
        })
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
        // this.#content = this.#wrapper.innerHTML

        this.#searchForm.addEventListener('submit', this.#newSearch.bind(this))
        this.#searchForm.addEventListener('input', debounce((e) => {
            this.#newSearch(e)
            this.#input = e.target
        }, (this.options.debounceDelay)))
        window.addEventListener('DOMContentLoaded', () => {
            this.#observer = new IntersectionObserver(this.#handleIntersect, this.#options)
            this.#observe(this.#loader)
            // this.#observer.observe(this.#loader)
            // this.#observer.root.style.border = "26px solid #44aa44";
        })
        window.onpopstate = (e) => {
            e.preventDefault()
            console.log(this.#content)
            // this.#wrapper.innerHTML = this.#content
            if (e.state === null) {
                
                // this.#wrapper.innerHTML = this.#content
                this.#content = this.#wrapper.innerHTML
                localStorage.setItem('forwardContent', this.#content)
                
                // document.querySelector('head').append(this.#script)
                // history.pushState({}, document.title, this.#newUrl)
                location.reload()
                console.log('cest le back')
                console.log(this.#content)
                // this.#observe(this.#loader)
            }
            if (e.state !== null) {
                console.log(this.#content)
                this.#content = localStorage.getItem('forwardContent')
                this.#wrapper.innerHTML = this.#content

                console.log('cest le go')

            }
        }
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
        this.#url = new URL(this.#endpoint)
        // window.location.replace('recherche')
        // window.location.hash = 'recherche'
        if (!window.location.href.toString().includes('recherche')) history.pushState({}, document.title, 'recherche/')
        
        this.#input = data.get('query')
        // url.searchParams.set('_page', this.#page)
        // url.searchParams.set('_limit', this.#limit)
        this.#url.searchParams.set('query', this.#input)
        this.#url.searchParams.set('_reset', 1)
        // resetURL('register.php', 'failed', urlParams)
        // debugger

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

        if (!this.#isDeleted) {
            // if (this.#wrapper.classList.contains('hidden')) this.#wrapper.classList.remove('hidden')
            this.#wrapper.classList.add('hidden')
            // this.#script = document.querySelector('script[data-name="typewritter"]')
            // if (this.#script) this.#script.remove()
            // this.#wrapper.addEventListener('transitionend', (e) => {
            this.#wrapper.addEventListener('animationend', (e) => {
                if (e.animationName === 'fadeOut') {
                    this.#wrapper.innerHTML = ''
                    this.#wrapper.appendChild(this.#container)
                    this.#container.appendChild(this.#target)
                    this.#wrapper.classList.remove('hidden')
                }
            }, {once: true})

            // container.innerHTML = ''
            // this.#wrapper.innerHTML = ''
            // // container.classList.add('container')
            // this.#wrapper.appendChild(this.#container)
            // this.#container.appendChild(this.#target)

            // document.querySelector('.container').remove()
            // const container = document.createElement('section')
            // container.classList.add('container')

            // this.#target.classList.add(this.#loader.dataset.target)
            // this.#target = this.#target
            // const hero = document.querySelector('.hero')
            // wrapper.addEventListener('animationend', () => {
            //     // this.#observer.unobserve(this.#loader)
            //     // container.innerHTML = ''
            //     hero.remove()
            //     container.append(this.#loader)
            //     container.append(this.#template)
            //     container.prepend(this.#target)
            //     // this.#observer.observe(this.#loader)
            //     wrapper.offsetHeight
            //     // wrapper.classList.remove('hidden')
            // })
            // hero.innesrHTML = ''
            // hero.innerHTML = ''
            // hero.remove()
            // delete hero
            this.#isDeleted = true
            // console.log('fin de delete')

            // container.append(this.#template)
            // container.prepend(this.#target)
            // const newContent = this.#template.content.firstElementChild.cloneNode(true)
            // newContent.forEach()
        }

        if (!this.#isCreated && this.#isDeleted) {
            // this.#wrapper.classList.contains('hidden') ? this.#wrapper.classList.remove('hidden') : null
            this.#wrapper.classList.add('hidden')
            this.#wrapper.addEventListener('animationend', (e) => {
                if (e.animationName === 'fadeOut') {
                    this.#target.innerHTML = ''
                    this.#container.append(this.#loader)
                    // wrapper.offsetHeight
                    this.#isCreated = true
                    this.#loading = false
                    // console.log('Je demande a append le loader')
                }
            }, {once: true})
            
            // await wait(200)

            
            // this.#observe(this.#loader)

            // console.log('fin de création')
        }
        // console.log('je viens de créer => ', ' // loading => ' + this.#loading, ' // isCreated => ' + this.#isCreated)

        // this.#observer.observe(this.#loader)

        // this.#observer.takeRecords()
        // console.log(this.#url)
        
        // const query = url.searchParams.get('query')
        // const query = urlParams.get('query')
        // urlParams.toString()

        // if (!this.#modifyFormDataValues(form, data)) return
        // try {
        //     if (!this.#isSentAlready) {
        //             this.#searchResults = await fetchJSON(url, {
        //             // this.#searchResults = await fetchJSON(url+'?query='+query, {
        //             method: 'GET'
        //         })
        //         if (this.#searchResults.length <= 0) {
        //             this.#disconnectObserver('Tout le contenu a été chargé')
        //             return
        //         }
        //         console.log(this.#searchResults)
        //     }
        //     this.#loading = false
        // } catch(e) {
        //     console.log(e)
        // }
    }

    async #loadMore() {
        // console.log('j suis rentré et je commence le script => ', ' \n // loading => ' + this.#loading, ' \n // isCreated => ' + this.#isCreated, ' \n // intersect ? => ' + this.#intersect)

        if (this.#loading || !this.#isCreated || !this.#intersect) {
            // console.log('je peux pas rentrer => ', ' \n // loading => ' + this.#loading, ' \n // isCreated => ' + this.#isCreated, ' \n // intersect ? => ' + this.#intersect)
            return
            // return this.#observer.observe(this.#loader)
        }
        this.#loading = true
        // const url = new URL(this.#endpoint)
        // if (this.#url !== undefined) {
            // console.log(this.#url)
        // this.#target.innerHTML = ''
        // }
        // url.searchParams.set('query', data.get('query'))
        try {
            this.#url.searchParams.set('_page', this.#page)
            this.#url.searchParams.set('_limit', this.#limit)

            this.#searchResults = await fetchJSON(this.#url)

            if (this.#searchResults.length <= 0) {
                // this.#disconnectObserver('Tout le contenu a été chargé')
                this.#observe(this.#loader, 'Tout le contenu a été chargé')
                return
            }
            this.#url.searchParams.set('_reset', 0)
            // console.log(this.#url)
            // console.log(this.#searchResults)
            this.#searchResults.forEach(result => {
                const elementTemplate = this.#template.content.firstElementChild.cloneNode(true)
                elementTemplate.setAttribute('id', result.recipe_id)
                for (const [key, selector] of Object.entries(this.#elements)) {
                    // console.log(key['img_path'], ' ' +selector)
                    // console.log(elementTemplate.querySelector(selector))
                    if (key === 'img_path' && result[key]) {
                        elementTemplate.querySelector(selector).src = this.#url.origin+/recettes/+result[key]
                    } else if (key === 'img_path' && result[key] === null || undefined) {
                         elementTemplate.querySelector(selector).src = this.#url.origin+/recettes/+'img/img1.jpeg'
                    } else {
                        elementTemplate.querySelector(selector).innerText = result[key]
                    }
                }
                this.#target.append(elementTemplate)
            })
            this.#wrapper.classList.remove('hidden')

            this.#page++
            this.#input.value = ''
            this.#loading = false
            // this.#observe(this.#loader)
            // console.log('jsuis arrivé à la fin du script => ', ' \n // loading => ' + this.#loading, ' \n // isCreated => ' + this.#isCreated, ' \n // intersect ? => ' + this.#intersect)
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
            message ? new Toaster(message, 'Succès') : null
        }
        this.#observer = new IntersectionObserver(this.#handleIntersect, this.#options)
        this.#observer.observe(elements)
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