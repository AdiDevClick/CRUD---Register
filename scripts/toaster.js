import { CarouselTouchPlugin } from "./components/CarouselTouchPlugin.js"

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
// function alertMessage(message, type = 'Erreur') {
function alertMessage(message, type = 'Erreur') {
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

async function wait(duration) {
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve(duration)
        }, duration)
    })
}

/**
 * @param {string} tagName 
 * @param {object} attributes 
 * @returns {HTMLElement}
 */
function createElement(tagName = 'div', attributes = {}) {
    const element = document.createElement(tagName)
    for (const [attribute, value] of Object.entries(attributes)) {
        if (value !== null) {
            element.setAttribute(attribute, value)
        }
    }
    return element
}

// const form = document.querySelector('form')
// form.addEventListener('submit', e => {
//     e.preventDefault()
//     console.log('object2')
//     // $script
//     // this.#onSubmit(e.currentTarget)
//     form.removeEventListener('submit', e)
// })

let message
let type
let errAlert = false
let alertToaster

const queryString = window.location
const urlParams = new URLSearchParams(queryString.search)
// const url = new URL
// console.log(url)

const error = urlParams.get('error')
if (error === 'invalid-input') {
    errAlert = true
    message = 'Veuillez modifier votre identifiant'
    resetURL('index.php', 'error')
}
// error === 'invalid-input' ? message = 'Veuillez modifier votre identifiant' : errAlert = false

const success = urlParams.get('success')
if (success === 'disconnected') {
    errAlert = true
    type = 'Success'
    message = 'Vous avez été déconnecté avec succès'
    resetURL('index.php', 'success')
}

if (success === 'recipe-shared') {
    errAlert = true
    type = 'Success'
    message = 'Votre recette vient d\'être partagée, elle semble excellente !'
    resetURL('index.php', 'success')
}

if (success === 'recipe-updated') {
    errAlert = true
    type = 'Success'
    message = 'Votre recette a bien été mise à jour!'
    resetURL('index.php', 'success')
}
// success === 'disconnected' ? message = 'Vous avez été déconnecté avec succès' : errAlert = false


const login = urlParams.get('login')
if (login === 'success') {
    errAlert = true
    type = 'Success'
    message = 'Vous êtes connecté avec succès'
    resetURL('index.php', 'login')
}
// login === 'success' ? message = 'Vous êtes connecté avec succès' : errAlert = false

const register = urlParams.get('register')
if (register === 'success') {
    errAlert = true
    type = 'Success'
    message = 'Votre compte a été enregistré avec succès, vous pouvez maintenant vous connecter'
    resetURL('index.php', 'register')
}

if (register === 'failed') {
    errAlert = true
    type = 'Erreur'
    message = 'Problème dans la création de votre compte, veuillez vérifier que tous les champs soient correctement remplis'
    resetURL('register.php', 'failed')
}

console.log(urlParams.has('email-invalid')); // fonctionne pas
console.log(urlParams.has('invalid-input')); // fonctionne pas
console.log(urlParams.has('error'));
//false

if (errAlert) {
    alertToaster = alertMessage(message, type)
    errAlert = false
    type = ''
}
const alertContainer = document.querySelector('.toast-container')
if (alertContainer) {
    alertContainer.insertAdjacentElement(
        'beforeend',
        alertToaster
    )
    document.querySelectorAll('.toast-container').forEach(toaster => {
        new CarouselTouchPlugin(toaster)
        console.log('toast')
    })
}

/**
 * Remove and replace the URL Parameter from the history
 * delete the success/failed param in case of a browser return)
 * @param {string} page 
 * @param {string} paramName 
 * @returns 
 */
function resetURL(page, paramName) {
    urlParams.delete(paramName)
    return window.history.replaceState({}, document.title, page);
}