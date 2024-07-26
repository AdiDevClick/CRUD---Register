import { createNewElements, wait } from "./functions/dom.js"

// document.querySelectorAll('.toast').forEach(toaster => {
//     new Toaster(toaster)
// })


class Toaster {

    #toasterContainer
    #message
    #type
    #alert
    #progressBar
    #closeBtn
    #elements

    constructor(message, type = 'Erreur', element = null) {
        this.#toasterContainer = element
        this.#message = message
        this.#type = type
        if (!this.#toasterContainer) {
            this.#createNewToasterContainer()
        }
        this.#alert = document.querySelector(this.#toasterContainer.dataset.template).content.firstElementChild.cloneNode(true)
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
        const toasterDivContainer = createNewElements('div', {
            class: 'toast-container',
            role: 'alert',
            'data-template': '#alert-layout',
            'data-progressBar': '.progress',
            'data-elements': '{"title": ".text-1", "body": ".text-2"}',
            'data-closeBtn': '.toggle_btn-box'
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
}        



    /** 
 * @param {string} message 
 * @param {string} type 
 * @returns {HTMLElement}
 */
// function alertMessage(message, type = 'Erreur') {
//  alertMessage(message, type = 'Erreur') {
    // let toasterDivContainer = document.querySelector('.toast-container')
    
    // const alert = document.querySelector('#alert-layout').content.firstElementChild.cloneNode(true)
    // const progress = alert.querySelector('.progress')
    // const closeIcon = alert.querySelector('.toggle_btn-box')
    // if (!toasterDivContainer) {
    //     toasterDivContainer = createElement('div', {
    //         class: 'toast-container',
    //         role: 'alert'
    //     })
    //     document.body.append(toasterDivContainer)
    // }
    // if (alert) {
    //     wait(50)
    //         .then(() => alert.classList.add('active'))
    //     closeIcon.classList.add('open')
    //     alert.classList.add(type)
    //     if (type === 'Success') {
    //         alert.querySelector('i').classList.add('fa-check')
    //     } else {
    //         alert.querySelector('i').classList.add('fa-info')
    //     }
    //     progress.classList.add('active')
    //     alert.querySelector('.text-1').innerText = type
    //     alert.querySelector('.text-2').innerText = message

        // wait(5000)
        //     .then(() => alert.classList.remove('active'))

        // wait(5300)
        //     .then(() => {
        //         progress.classList.remove('active')
        //         alert.remove()
        //         toasterDivContainer.remove()
        //     })
        
        // closeIcon.addEventListener('click', e => {
        //     e.preventDefault()
        //     alert.classList.remove('active')
        //     alert.classList.add('close')
            
        //     wait(300)
        //         .then(() => progress.classList.remove('active'))
        //     closeIcon.dispatchEvent(new CustomEvent('delete'))
        //     alert.addEventListener('animationend', () => {
        //         alert.remove()
        //         toasterDivContainer.remove()
        //     })
            
        // }, {once: true})
        // return alert
    // } else return
// }
