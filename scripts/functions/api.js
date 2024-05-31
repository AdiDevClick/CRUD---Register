import { alertMessage } from "./dom.js"

/**
 * Fetch API au format JSON
 * @param {string} url
 * @param {RequestInit & {json?: object}} options
 * @returns {Promise}
 */
export async function fetchJSON(url = '', options = {}) {
    const headers = {
        Accept: 'application/json',
        ...options.headers
    }
    if (options.json) {
        options.body = JSON.stringify(Object.fromEntries(options.json))
        headers['Content-Type'] = 'application/json; charset=UTF-8'
    }
    try {
        const response = await fetch(url, {...options, headers})
        if (!response.ok) {
            throw new Error('Impossible de récupérer les données serveur', {cause: response})
        }
        return response.json()
    } catch (error) {
        alertMessage(error.message)
    }
}

/**
 * Fetch API au format Text (récupérer une modale ou un template)
 * @param {string} url
 * @param {string} targt
 * @returns {Promise}
 */
export async function fetchTemplate(url, targt) {
    const target = targt
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
            // document.body.append(htmlElement)
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