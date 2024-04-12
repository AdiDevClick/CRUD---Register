/**
 * @param {NodeListOf.<HTMLElement>} tagName 
 * @param {object.<HTMLElement>} attributes 
 * @returns {HTMLElement}
 */
export function createElement(tagName, attributes = {}) {
    const element = document.createElement(tagName)
    for (const [attribute, value] of Object.entries(attributes)) {
        if (value !== null) {
            element.setAttribute(attribute, value)
        }
    }
    return element
}

/**
 * @param {Function} funct 
 * @param {Number} duration 
 * @returns {Function}
 */
export const debounce = function(funct, duration) {
    let timer
    return (...args) => {
        let context = this
        return new Promise((resolve) => {
            clearTimeout(timer)
            timer = setTimeout(() => {
                funct.apply(context, args)
                resolve(duration)
            }, duration)
        })
    }
}

/**
 * @param {number} duration 
 * @returns {Promise}
 */
export function wait(duration, message) {
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve(message)
        }, duration)
    })
}
/**
 * @param {number} duration 
 * @returns {Promise}
 */
export function waitAndFail(duration, message) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            reject(message)
        }, duration)
    })
}

/** 
 * @param {string} message 
 * @param {string} type 
 * @returns {HTMLElement}
 */
// function alertMessage(message, type = 'Erreur') {
export function alertMessage(message, type = 'Erreur') {
        let toasterDivContainer = document.querySelector('.toast-container')
        
        const alert = document.querySelector('#alert-layout').content.firstElementChild.cloneNode(true)
        const progress = alert.querySelector('.progress')
        const closeIcon = alert.querySelector('.toggle_btn-box')
        if (!toasterDivContainer) {
            toasterDivContainer = createElement('div', {
                class: 'toast-container',
                role: 'alert'
            })
            document.body.append(toasterDivContainer)
        }
        if (alert) {
            wait(50)
                .then(() => alert.classList.add('active'))
            closeIcon.classList.add('open')
            alert.classList.add(type)
            if (type === 'Success') {
                alert.querySelector('i').classList.add('fa-check')
            } else {
                alert.querySelector('i').classList.add('fa-info')
            }
            progress.classList.add('active')
            alert.querySelector('.text-1').innerText = type
            alert.querySelector('.text-2').innerText = message
    
            wait(5000)
                .then(() => alert.classList.remove('active'))
    
            wait(5300)
                .then(() => {
                    progress.classList.remove('active')
                    alert.remove()
                    toasterDivContainer.remove()
                })
            
            closeIcon.addEventListener('click', e => {
                e.preventDefault()
                alert.classList.remove('active')
                alert.classList.add('close')
                
                wait(300)
                    .then(() => progress.classList.remove('active'))
                closeIcon.dispatchEvent(new CustomEvent('delete'))
                alert.addEventListener('animationend', () => {
                    alert.remove()
                    toasterDivContainer.remove()
                })
                
            }, {once: true})
            return alert
        } else return
    }