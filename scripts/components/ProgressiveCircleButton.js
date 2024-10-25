import { createElement } from "../functions/dom.js"

export class ProgressiveCircleButton {
    
    // #template
    /** @type {HTMLElement} */
    #buttonContainer
    /** @type {Number} */
    // #buttonTemplate
    /** @type {Number} */
    #dashArray
    /** @type {Number} */
    #dashOffset
    /** @type {String} */
    // #flipArrowData
    /** @type {HTMLLabelElement} */
    #label
    /** @type {SVGCircleElement} */
    #circle
    /** @type {Number} */
    #progress = 0
    /** @type {Number} */
    #radius
    /** @type {MutationObserver} */
    #mutationObserver
    #handleMutation = (mutationsList, observers) => {
        mutationsList.forEach(mutation => {
            if (mutation.attributeName === 'aria-valuenow') {
                console.log(mutation)
        }
        })
    }

    /**
     * @constructor
     * @param {String} positionClass - Définit la position par CSS - ex : 'right-corner'
     * @param {String} [flip="none"] - Définit la direction de la flèche du innerCircle, default : "none"
     */
    constructor(positionClass, flip="none") {
        // Main element container
        this.#buttonContainer = document.querySelector('#circular-progress-button').content.firstElementChild.cloneNode(true)
        // this.#buttonContainer = this.#buttonTemplate

        // Setting IDs & Class
        this.#setIDs(positionClass)
        this.#addClassToElement(positionClass)

        // Elements
        this.#label = this.#buttonContainer.querySelector('label')
        this.#circle = this.#buttonContainer.querySelector('.progress-bar-color')

        // Sets if the inner arrow should be left or right. flip = left / none = right
        this.#label.dataset.arrowflip = flip
        
        // Setting values attributes on the Label & SVG
        this.#label.setAttribute('role', 'progressbar')
        this.#label.setAttribute('aria-live', 'polite')

        const width = this.#buttonContainer.querySelector('svg').width.baseVal.value
        
        this.#radius = Math.round((width / 2) - 1)
        this.#circle.r.baseVal.value = this.#radius
        // this.#template = document.querySelector(element.data.template)

        // Calculation
        this.#updateProgression()

        this.#setStyle()

        // Events
        window.addEventListener('DOMContentLoaded', e => {
            this.#mutationObserver = new MutationObserver(this.#handleMutation)
            this.#mutationObserver.observe(this.#buttonContainer, { attributes: true, subtree: true, attributeOldValue: true })
        })
    }

    /**
     * Permet de créer une div sur le DOM comprenant des dataset
     * pour target les élements nécessaires à l'alerte
     */
    #createNewToasterContainer() {
        const buttonDivContainer = createElement('div', {
            class: 'circular-progress-button-container'
        })
        document.body.append(buttonDivContainer)
    }

    /**
     * Ajoute les propriétés dynamique du CSS au cercle du SVG -
     * Renvoi aux propriétés du stepButton.css.
     * Une animation / transformation linéaire sera jouée
     */
    #setStyle() {
        this.#circle.style.setProperty('--data-percentage', this.#dashArray)
        this.#circle.style.setProperty('--data-dashoffset', this.#dashOffset)
    }

    /**
     * Calcule le dash offset pour permettre
     * d'utiliser this.#progress comme un pourcentage
     */
    #updateProgression() {
        // Aria setting
        this.#label.setAttribute('aria-valuenow', this.#progress)

        // Circumference
        this.#dashArray = this.#calculateDasharray(this.#radius)
        this.#circle.setAttribute('stroke-dasharray', this.#dashArray)

        // Offset start
        this.#dashOffset = this.#calculateDashoffset(this.#progress, this.#dashArray)
        this.#circle.setAttribute('stroke-dashoffset', this.#dashOffset)
    }

    /**
     * Ajoute une classe au boutton
     * @param {String} className
     */
    #addClassToElement(className) {
        this.#buttonContainer.classList.add(className)
    }

    // #setProgression(progress) {
    //     this.#buttonContainer.classList.add(className)
    // }

    /**
     * Ajoute un string à l'ID original de l'élément pour le rendre unique
     * @param {String} id
     */
    #setIDs(id) {
        this.#buttonContainer.querySelectorAll('[id]').forEach(child => {
            child.id = child.id+'-'+id
            if (child.for) child.for = child.for+'-'+id
            console.log(child.id)
        })
    }

    /**
     * Calcule la circonférence d'un rayon
     * @param {Number} radius
     * @returns {Number}
     */
    #calculateDasharray(radius) {
        return Math.PI * radius * 2
    }

    /**
     * Calcule et renvoi le SVG dash offset en fonction
     * de la circonférence du cercle
     * @param {Number} percentageShown
     * @param {Number} circumference
     * @returns {Number}
     */
    #calculateDashoffset(percentageShown, circumference) {
        return ( (100 - percentageShown) / 100 ) * circumference
    }

    /**
     * Retourne l'objet créé par la Classe
     * @returns {HTMLElement}
     */
    get button() {
        return this.#buttonContainer
    }

    /**
     * @param {number} setting
     * @returns
     */
    set dashArray(setting) {
        return this.#dashArray = setting
    }

    /**
     * Permet de modifier dynamiquement la progression
     * @param {number} progress
     * @returns
     */
    set dashOffset(progress) {
        this.#progress = progress
        // this.#label.setAttribute('aria-valuenow', this.#progress)
        this.#updateProgression()
        this.#setStyle()
    }
}