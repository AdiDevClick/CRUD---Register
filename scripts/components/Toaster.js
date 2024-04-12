import { createElement, wait } from "../functions/dom.js"


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
    #template
    #dataEndpoint

    constructor(message, type = 'Erreur', element) {
        this.#toasterContainer = element
        this.#message = message
        this.#type = type
        if (!this.#toasterContainer) {
            this.#toasterContainer = this.#createNewToasterContainer()
            this.#fetchTemplate('./templates/toaster_template.html')
        }
        this.#template = document.querySelector(this.#toasterContainer.dataset.template)
        this.#alert = this.#template.content.firstElementChild.cloneNode(true)
        // this.#alert = document.querySelector('#alert-layout').content.firstElementChild.cloneNode(true)
        // this.#progressBar = alert.querySelector('.progress')
        this.#progressBar = this.#alert.querySelector(this.#toasterContainer.dataset.progressBar)
        this.#closeBtn = this.#alert.querySelector(this.#toasterContainer.dataset.closeBtn)
        this.#elements = JSON.parse(this.#toasterContainer.dataset.elements)

        this.#showAlert()
        this.#appendAlert()

        this.#alert.addEventListener('animationend', () => {
            this.#removeAlert()
        })

        this.#closeBtn.addEventListener('click', e => {
            this.#closingAnimation()
        }, {once: true})
    }

    #createNewToasterContainer() {
        const toasterDivContainer = createElement('div', {
            class: 'toast-container',
            role: 'alert',
            'data-template': '#alert-layout',
            'data-progressBar': '.progress',
            'data-elements': '{"title": ".text-1", "body": ".text-2"}',
            'data-closeBtn': '.toggle_btn-box',
            'data-endpoint': '../templates/toaster_template.html'
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

    #removeAlert() {
        // wait(5000)
        //     .then(() => alert.classList.remove('active'))
        

        // wait(5300)
        //     .then(() => {
        //         progress.classList.remove('active')
        //         alert.remove()
        //         toasterDivContainer.remove()
        //     })
        // this.#alert.addEventListener('animationend', () => {
            this.#alert.classList.remove('active')
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
        const loadedTemplate = this.#template
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
                document.body.append(htmlElement)
                return htmlElement
            }
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