/**
 * Permet de céer un élément avec les attributs de son choix
 * ex : createElement('li', {
 *      class: 'myClass',
 *      role: 'myRole'
 * })
 * La fonction permet de vérifier si la valeur est nulle :
 * ex : createElement('li', {
 *      class: 'myClass',
 *      role: 'myRole',
 *      checked: todo.completed ? '' : null,
 * })
 * @param {String} tagName - NodeListOf.<HTMLElement>
 * @param {Object} attributes 
 * @returns {HTMLElement}
 */
export function createNewElements(tagName, attributes = {}) {
    const element = document.createElement(tagName)
    for (const [attribute, value] of Object.entries(attributes)) {
        if (value !== null) {
            element.setAttribute(attribute, value)
        }
    }
    return element
}

/**
 * Debounce une fonction de manière Asynchrone
 * Il faut spécifier la duration -
 * Cette fonction permet aussi de prendre en compte 
 * les paramètres de la fonction debounced
 * @param {Function} funct 
 * @param {Number} duration
 * @fires [debounce]
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
 * Accepte une promesse
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
 * Rejette une promesse de force
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
 * Crer une alerte en fonction d'un template HTML  id "#alert-layout" 
 */

/**
 * Modifier le "type" pour changer la couleur de l'alerte
 */

/**
 * Les différents types de couleur :
 * 'Erreur' => rouge (par défaut si rien de mentionné)
 * 'Success' => bleu
 */

/**
 * Utiliser le selecteur ".toast-container" pour insérer le toast
 * Le script se chargera de le créer sur le DOM dans le cas où il n'existe pas
 */

/**
 * IMPORTANT :
 * Pour utiliser le toaster, il faut importer la fonction 'alertMessage'
 * Exemple : import { alertMessage } from "./functions/dom.js"
 * 
 * Pour le CSS : 
 * Importer le toaster.css dans le main css file
 */

/**
 * Exemple d'utilisation : 
 *   const alert = alertMessage(error.message)
 *   const alertContainer = document.querySelector('.toast-container')
 *   alertContainer.insertAdjacentElement(
 *       'beforeend',
 *       alert
 *   )
 */

/** 
 * @param {string} message 
 * @param {string} type 
 * @returns {HTMLElement}
 */
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