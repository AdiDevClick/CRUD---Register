import { fetchTemplate } from "../functions/api.js"
import { createElement, wait, alertMessage } from "../functions/dom.js"
import { ToasterTouchPlugin } from "./ToasterTouchPlugin.js"


// document.querySelectorAll('.toast').forEach(toaster => {
//     new Toaster(toaster)
// })


export class Toaster {

    /** @type {string} */
    #message
    /** @type {string} */
    #type
    /** @type {HTMLDivElement} */
    #alert
    /** @type {HTMLElement} */
    #progressBar
    /** @type {HTMLButtonElement} */
    #closeBtn
    #elements
    /** @type {HTMLTemplateElement} */
    #template
    #dataEndpoint

    /**
     * Construit une alerte en prenant en compte un message et un type d'erreur
     * 'Success' ou bien 'Erreur'
     * le type 'Erreur' est créé de base si non spécifié
     * 
     * Dans sa version actuelle, l'alerte a besoin de chercher le template d'alerte
     * Il faudra donc l'inclure quelque part dans le DOM au préalable
     * 
     * La fonction désactivée '#fetchTemplate()' permet de créer dynamiquement sur le DOM ce template
     * Mais le constructeur peine à y accéder
     * @param {string} message 
     * @param {string} type 
     */
    constructor(message, type = 'Erreur') {
        this.#message = message
        this.#type = type
        if (!this.toasterContainer) {
            this.#createNewToasterContainer()
            this.toasterContainer = document.querySelector('.toast-container')
            // this.#fetchTemplate(this.#dataEndpoint)
            // this.#fetchTemplate('../templates/toaster_template.html')
        }
        
        this.#dataEndpoint = this.toasterContainer.dataset.endpoint
        this.#elements = JSON.parse(this.toasterContainer.dataset.elements)
        this.#template = document.querySelector(this.toasterContainer.dataset.template)
        
        this.#alert = this.#template.content.firstElementChild.cloneNode(true)
        
        this.#progressBar = this.#alert.querySelector(this.toasterContainer.dataset.progressbar)
        this.#closeBtn = this.#alert.querySelector(this.toasterContainer.dataset.closebtn)
        
        this.#showAlert()
        this.#appendAlert()

        this.#alert.addEventListener('animationend', e => {
            this.#removeAlert(e)
        })
    
        this.#closeBtn.addEventListener('click', e => {
            this.#closingAnimation(e)
        }, {once: true})

        new ToasterTouchPlugin(this)
    }

    // async #getFetchedTemplate() {
    //     const template = await fetchTemplate(this.#dataEndpoint, this.toasterContainer.dataset.template)
    //     document.body.append(template)
    //     return template
    // }

    /**
     * Permet de créer une div sur le DOM comprenant des dataset
     * pour target les élements nécessaires à l'alerte
     */
    #createNewToasterContainer() {
        const toasterDivContainer = createElement('div', {
            class: 'toast-container',
            role: 'alert',
            'data-template': '#alert-layout',
            'data-progressbar': '.progress',
            'data-elements': '{"title": ".text-1", "body": ".text-2"}',
            'data-closebtn': '.toggle_btn-box',
            'data-endpoint': './templates/toaster_template.html'
        })
        document.body.append(toasterDivContainer)
    }

    /**
     * Insère l'alerte au conteneur préalablement créé
     * Cette alerte sera toujours fixée en haut à droite de l'écran
     * Si le conteneur n'existe pas, le script sera bloqué
     * @returns 
     */
    #appendAlert() {
        if (this.toasterContainer) {
            this.toasterContainer.insertAdjacentElement(
                'beforeend',
                this.#alert
            )
        } else {
            return
        }
    }

    /**
     * Permet de switch entre une alerte "success" ou bien "Erreur"
     * L'alerte d'Erreur est parametrée par défaut
     */
    #showAlert() {
        this.#alert.classList.add('active')
        this.#closeBtn.classList.add('open')
        this.#alert.classList.add(this.#type)
        if (this.#type === 'Success') {
            this.#alert.querySelector('i').classList.add('fa-check')
        } else {
            this.#alert.querySelector('i').classList.add('fa-info')
        }
        this.#progressBar.classList.add('active')
        this.#alert.querySelector('.text-1').innerText = this.#type
        this.#alert.querySelector('.text-2').innerText = this.#message
    }

    /**
     * Supprime l'alerte et son conteneur après 5s -
     * Une animation sera jouée en fin de délai
     */
    async #removeAlert(e) {
        await wait(5000)
        this.#alert.classList.remove('active')
        await wait(300)
        this.#progressBar.classList.remove('active')
        this.#alert.remove()
        this.toasterContainer.remove()
    }

    /**
     * Joue une animation puis ferme et supprime l'alerte -
     * Sera appliquée en cas de clic sur le bouton de fermeture
     * @param {EventInit} e 
     */
    async #closingAnimation(e) {
        e.preventDefault()
        this.#alert.classList.remove('active')
        this.#alert.classList.add('close')
        await wait(300)
        this.#progressBar.classList.remove('active')
        this.#alert.addEventListener('animationend', () => {
            this.#alert.remove()
            this.toasterContainer.remove()
        })
    }

    // async #fetchTemplate(url) {
    //     const target = this.toasterContainer.dataset.template
    //     const loadedTemplate = document.querySelector(target)
    //     try {
    //         if (!loadedTemplate) {
    //             const response = await fetch(url)
    //             if (!response.ok) {
    //                 throw new Error(`HTTP Error! Status: ${response.status}`)
    //             }
    //             const htmlElement = document
    //                 .createRange()
    //                 .createContextualFragment(await response.text())
    //                 .querySelector(target)
    //             if (!htmlElement) {
    //                 throw new Error(`L'élement ${target} n'existe pas à l'adresse : ${url}`)
    //             }
    //             this.#template = htmlElement
    //             document.body.append(htmlElement)
    //             return htmlElement
    //         }
    //         this.#template = loadedTemplate
    //         return loadedTemplate
    //     } catch (error) {
    //         const alert = alertMessage(error.message)
    //         const container = document.querySelector('.toast-container')
    //         container.insertAdjacentElement(
    //             'beforeend',
    //             alert
    //         )
    //     }
    // }
}