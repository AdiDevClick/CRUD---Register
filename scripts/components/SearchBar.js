import { fetchJSON } from "../functions/api.js"
import { createElement, debounce, wait } from "../functions/dom.js"
import { Toaster } from "./Toaster.js"

export class SearchBar
{
    /** @type {HTMLElement} */
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
    /** @type {boolean} */
    #isDeleted = false
    /** @type {boolean} */
    #isCreated = false
    /** @type {string} */
    #endpoint
    /** @type {object} */
    #elements
    /** @type {HTMLTemplateElement} */
    #template
    /** @type {HTMLElement} */
    #target
    /** @type {HTMLElement} */
    #wrapper
    /** @type {HTMLElement} */
    #container
    /** @type {Number} */
    #page = 1
    /** @type {number} */
    #limit
    /** @type {HTMLFormElement} */
    #searchForm
    /** 
     * Options pour le loader infini
     * @type {IntersectionObserver} 
     */
    #observer
    /** @type {Number} */
    #ratio = .3
    #options = {
        delay: 100,
        // root: "26px solid #44aa44",
        rootMargin: '0px',
        threshold: this.#ratio
    }
    #handleIntersect = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                console.log('ça intersect')
            }
            // if (entry.intersectionRatio <= this.#ratio) {
            if (entry.intersectionRatio > this.#ratio) {
            // if (entry.boundingClientRect) {
                this.#loadMore()
                // console.log(entry.boundingClientRect)
                // console.log(this.#loading)
                // console.log(this.#isCreated)
                // console.log(this.#isDeleted)
            }
        })
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
        this.#target = document.createElement('div')
        this.#target.classList.add('searched-recipes')
        // this.#target = document.createElement('div', {
        //     class: "element.dataset.target"
        // })
        // this.#target = document.querySelector(element.dataset.target)
        this.#endpoint = this.#searchForm.dataset.endpoint
        this.#limit = element.dataset.limit
        this.#elements = JSON.parse(element.dataset.elements)
        this.#template = document.querySelector(element.dataset.template)
        this.#container = document.createElement('section')
        this.#container.classList.add('container')
        this.#wrapper = document.querySelector('.wrapper')


        this.#searchForm.addEventListener('submit', this.#newSearch.bind(this))
        this.#searchForm.addEventListener('input', debounce((e) => {
            this.#newSearch(e)
            this.#input = e.target
        }, (this.options.debounceDelay)))
        window.addEventListener('DOMContentLoaded', () => {
            this.#observer = new IntersectionObserver(this.#handleIntersect, this.#options)
            this.#observer.observe(this.#loader)
            // this.#observer.root.style.border = "26px solid #44aa44";
        })
    }

    async #newSearch(e) {
        e.preventDefault()
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
        this.#input = data.get('query')
        // url.searchParams.set('_page', this.#page)
        // url.searchParams.set('_limit', this.#limit)
        this.#url .searchParams.set('query', this.#input)
        // debugger

        // const queryString = document.location
        // const url = queryString.origin+'/recettes/recipes/Process_PreparationList.php'
        // const urlParams = new URLSearchParams(queryString.search)
        // urlParams.set('query', data.get('query'))
        // document.querySelector('.container').createElement('div').classList.add('searched-recipes').prepend()
        // document.querySelector('.container').append(this.#loader)
        // this.#target.classList.add('searched-recipes')

        // this.#observer.observe(this.#loader)
        console.log(this.#loading)
        console.log(this.#isCreated)
        await wait(200)

        if (!this.#isDeleted) {
            const script = document.querySelector('script[data-name="typewritter"]')
            script.remove()
            // container.innerHTML = ''
            this.#wrapper.innerHTML = ''
            // container.classList.add('container')
            this.#wrapper.appendChild(this.#container)
            this.#container.appendChild(this.#target)

            // document.querySelector('.container').remove()
            // const container = document.createElement('section')
            // container.classList.add('container')

            // this.#target.classList.add(this.#loader.dataset.target)
            // this.#target = this.#target
            // const hero = document.querySelector('.hero')
            // wrapper.classList.add('hidden')
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
            console.log('fin de delete')

            // container.append(this.#template)
            // container.prepend(this.#target)
            // const newContent = this.#template.content.firstElementChild.cloneNode(true)
            // newContent.forEach()
        }

        if (!this.#isCreated && this.#isDeleted) {
            // container.innerHTML = ''
            this.#target.innerHTML = ''
            this.#container.append(this.#loader)
            // wrapper.offsetHeight
            this.#isCreated = true

            console.log('fin de création')
        }
        this.#observer.observe(this.#loader)

        // this.#observer.takeRecords()
        console.log(this.#observer)
        
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
        if (this.#loading || !this.#isCreated) {
            console.log('je peux pas rentrer => ', ' // loading => ' + this.#loading, ' // isCreated => ' + this.#isCreated)

            return this.#observer.observe(this.#loader)
        }
        this.#loading = true

        // const url = new URL(this.#endpoint)
        // if (this.#url !== undefined) {
            // console.log(this.#url)
        console.log(this.#url)
        // this.#target.innerHTML = ''
        // }
        // url.searchParams.set('query', data.get('query'))
        try {
            
            this.#url.searchParams.set('_page', this.#page)
            this.#url.searchParams.set('_limit', this.#limit)
            this.#searchResults = await fetchJSON(this.#url)

            if (this.#searchResults.length <= 0) {
                this.#disconnectObserver('Tout le contenu a été chargé')
                return
            }

            console.log(this.#searchResults)
            this.#searchResults.forEach(result => {
                const elementTemplate = this.#template.content.firstElementChild.cloneNode(true)
                elementTemplate.setAttribute('id', result.recipe_id)
                for (const [key, selector] of Object.entries(this.#elements)) {
                    console.log(key, ' ' +selector)
                    // console.log(elementTemplate.querySelector(selector))
                    // key === 'img_path' ? elementTemplate.querySelector(selector).src = result[key] : elementTemplate.querySelector(selector).innerText = result[key]
                    elementTemplate.querySelector(selector).innerText = result[key]
                }
                this.#target.append(elementTemplate)
            })
            this.#page++
            this.#input.value = ''
            this.#loading = false
        } catch (error) {
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
            new Toaster(error, 'Erreur')
            this.#loader.style.removeProperty('display')
            this.#loading = false
            this.#isCreated = false
        }
    }

    #disconnectObserver(message) {
        this.#loading = false
        this.#isCreated = false
        new Toaster(message, 'Succès')
        this.#observer.disconnect()
        this.#loader.remove()
        // this.#observer.unobserve(this.#loader)
        
        // throw new Error(message)
    }

    // set setUpdateAdress(url) {
    //     this.#url = url
    // }
}