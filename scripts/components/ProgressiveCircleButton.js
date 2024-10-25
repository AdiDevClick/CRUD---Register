import { createElement } from "../functions/dom.js"

export class ProgressiveCircleButton {
    
    #template
    #button
    /** @type {Number} */
    #buttonTemplate
    /** @type {Number} */
    #dashArray
    /** @type {Number} */
    #dashOffset
    /** @type {String} */
    #flipArrowData
    #label
    /** @type {SVGCircleElement} */
    #circle
    /** @type {Number} */
    #progress = 0
    /** @type {Number} */
    #radius

    constructor(position, flip='none') {
        this.#button = document.querySelector('#circular-progress-button').content.firstElementChild.cloneNode(true)
        // this.#button = this.#buttonTemplate

        this.#setIDs(position)
        this.#addClassToElement(position)

        // Setting values attributes on the SVG
        this.#label = this.#button.querySelector('label')
        this.#circle = this.#button.querySelector('.progress-bar-color')
        // Sets if the inner arrow should be left or right. flip = left / none = right
        this.#label.dataset.arrowflip = flip
        this.#label.setAttribute('role', 'progressbar')
        this.#label.setAttribute('aria-live', 'polite')

        const width = this.#button.querySelector('svg').width.baseVal.value
        
        this.#radius = Math.round((width / 2) - 1)
        this.#circle.r.baseVal.value = this.#radius
        // this.#template = document.querySelector(element.data.template)

        this.#updateProgression()
        this.#setStyle()
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
     * 
     */
    #setStyle() {
        this.#circle.style.setProperty('--data-percentage', this.#dashArray)
        this.#circle.style.setProperty('--data-dashoffset', this.#dashOffset)
    }

    /**
     * 
     */
    #updateProgression() {
        this.#label.setAttribute('aria-valuenow', this.#progress)

        this.#dashArray = this.#calculateDasharray(this.#radius)
        this.#circle.setAttribute('stroke-dasharray', this.#dashArray)

        this.#dashOffset = this.#calculateDashoffset(this.#progress, this.#dashArray)
        this.#circle.setAttribute('stroke-dashoffset', this.#dashOffset)
    }

    /**
     * Ajoute une classe au boutton
     * @param {String} className
     */
    #addClassToElement(className) {
        this.#button.classList.add(className)
    }

    // #setProgression(progress) {
    //     this.#button.classList.add(className)
    // }

    /**
     * Ajoute un string à l'ID original de l'élément pour le rendre unique
     * @param {String} id
     */
    #setIDs(id) {
        this.#button.querySelectorAll('[id]').forEach(child => {
            child.id = child.id+'-'+id
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
     * Calcule le SVG dash offset en fonction de la circonférence du cercle
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
        return this.#button
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