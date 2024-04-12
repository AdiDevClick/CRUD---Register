import { fetchTemplate } from "../functions/api.js"
import { createElement, wait, alertMessage } from "../functions/dom.js"


// document.querySelectorAll('.toast').forEach(toaster => {
//     new Toaster(toaster)
// })


export class Toaster {

    #toasterContainer
    #message
    #type
    #alert
    #progressBar
    #closeBtn
    #elements
    #template = []
    #dataEndpoint
    #test

    constructor(message, type = 'Erreur') {
        // this.#toasterContainer = element
        this.#message = message
        this.#type = type
        if (!this.#toasterContainer) {
            this.#createNewToasterContainer()
            this.#toasterContainer = document.querySelector('.toast-container')
            // this.#fetchTemplate(this.#searchURL())
            // this.#fetchTemplate('../templates/toaster_template.html')
        }
        
        // this.#alert = document.querySelector('#alert-layout').content.firstElementChild.cloneNode(true)
        // this.#progressBar = alert.querySelector('.progress')
        this.#dataEndpoint = this.#toasterContainer.dataset.endpoint
        
        // this.#getFetchedTemplate()
        // this.#template = this.#getFetchedTemplate()
        // this.#template = fetchTemplate(this.#dataEndpoint, this.#toasterContainer.dataset.template)
        // this.#getFetchedTemplate()
        // this.#getFetchedTemplate().then((t) => console.log(t))
        // this.#template = this.#getFetchedTemplate()
        this.#elements = JSON.parse(this.#toasterContainer.dataset.elements)
        this.#template = document.querySelector(this.#toasterContainer.dataset.template)
        
        this.#alert = this.#template.content.firstElementChild.cloneNode(true)
        this.#progressBar = this.#alert.querySelector(this.#toasterContainer.dataset.progressbar)
        this.#closeBtn = this.#alert.querySelector(this.#toasterContainer.dataset.closebtn)
        
        this.#showAlert()
        this.#appendAlert()

        this.#alert.addEventListener('animationend', e => {
            this.#removeAlert(e)
        })

        this.#closeBtn.addEventListener('click', e => {
            this.#closingAnimation(e)
        }, {once: true})
    }

    async #getFetchedTemplate() {
        const template = await fetchTemplate(this.#dataEndpoint, this.#toasterContainer.dataset.template)
            // .then((response) => {
            //     return this.#alert.push(response)
            // })
        document.body.append(template)
        console.log(template)
        console.log(template.content.firstElementChild.cloneNode(true))
        this.#template.push(template.content.firstElementChild.cloneNode(true))
        // this.#alert = template
    }

    get temp() {
        return 
    }

    #searchURL() {
        const url = new URL('toaster_template.html')
        return url
    }

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

    #appendAlert() {
        if (this.#toasterContainer) {
            this.#toasterContainer.insertAdjacentElement(
                'beforeend',
                this.#alert
            )
        } else {
            return
        }
    }

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

    async #removeAlert() {
        await wait(5000)
        //     .then(() => alert.classList.remove('active'))
        this.#alert.classList.remove('active')

        await wait(5300)
        //     .then(() => {
        //         progress.classList.remove('active')
        //         alert.remove()
        //         toasterDivContainer.remove()
        //     })
        // this.#alert.addEventListener('animationend', () => {
            // this.#alert.classList.remove('active')
            this.#progressBar.classList.remove('active')
            this.#alert.remove()
            this.#toasterContainer.remove()
        // })
    }

    #closingAnimation() {
        e.preventDefault()
        this.#alert.classList.remove('active')
        this.#alert.classList.add('close')
        
        wait(300)
            .then(() => progress.classList.remove('active'))
        // closeIcon.dispatchEvent(new CustomEvent('delete'))
        this.#alert.addEventListener('animationend', () => {
            this.#alert.remove()
            this.#toasterContainer.remove()
        })
    }

    async #fetchTemplate(url) {
        const target = this.#toasterContainer.dataset.template
        const loadedTemplate = document.querySelector(target)
        try {
            if (!loadedTemplate) {
                const response = await fetch(url)
                if (!response.ok) {
                    throw new Error(`HTTP Error! Status: ${response.status}`)
                }
                const htmlElement = document
                    .createRange()
                    .createContextualFragment(await response.text())
                    .querySelector(target)
                if (!htmlElement) {
                    throw new Error(`L'élement ${target} n'existe pas à l'adresse : ${url}`)
                }
                this.#template = htmlElement
                document.body.append(htmlElement)
                return htmlElement
            }
            this.#template = loadedTemplate
            return loadedTemplate
        } catch (error) {
            const alert = alertMessage(error.message)
            const container = document.querySelector('.toast-container')
            container.insertAdjacentElement(
                'beforeend',
                alert
            )
        }
    }
}