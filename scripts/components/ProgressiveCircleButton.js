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
    /** @type {HTMLInputElement} */
    #input
    /** @type {HTMLLabelElement} */
    #label
    /** @type {SVGCircleElement} */
    #circle
    /** @type {SVGPathElement} */
    #arrow
    /** @type {SVGTextElement} */
    #shareText
    /** @type {Number} */
    #progress = 0
    /** @type {Number} */
    #radius
    /** @type {MutationObserver} */
    #mutationObserver
    /** @type {MutationCallback} */
    #handleMutation = (mutationsList, observers) => {
        mutationsList.forEach(mutation => {
            if (mutation.attributeName === 'aria-valuenow') {
                this.#oldMutationValue = mutation.oldValue
            }
        })
    }
    /** @type {Number} */
    #oldMutationValue

    /**
     * @constructor
     * @param {String} positionClass - Définit la position par CSS - ex : 'right-corner'
     * @param {String} [options.flip="none"] - Default = "none". Définit la direction de la flèche du innerCircle.
     * Utilisez le string en liaison du CSS; ici, 'flip' permettra de la retourner
     * @param {String} [options.progressStart=0] - Default = 0. Permet de définir un #progress sur
     * une valeur définie lors de la création.
     */
    constructor(positionClass, options = {}) {
        this.options = Object.assign ({}, {
            progressStart: 0,
            flip: 'none'
        }, options)

        this.#progress = this.options.progressStart
        // Main element container
        this.#buttonContainer = document.querySelector('#circular-progress-button').content.firstElementChild.cloneNode(true)
        // this.#buttonContainer = this.#buttonTemplate

        // Setting IDs & Class
        this.#setIDs(positionClass)
        this.#addClassToElement(positionClass)

        // Elements
        this.#input = this.#buttonContainer.querySelector('input')
        this.#label = this.#buttonContainer.querySelector('label')
        this.#circle = this.#buttonContainer.querySelector('.progress-bar-color')
        this.#arrow = this.#buttonContainer.querySelector('.arrow')
        this.#shareText = this.#buttonContainer.querySelector('.share')

        // Defaults the shareText to hidden state
        this.#shareText.style.display = 'none'

        // Sets if the inner arrow should be left or right. flip = left / none = right
        this.#label.dataset.arrowflip = this.options.flip
        
        // Setting values attributes on the Label & SVG
        this.#label.setAttribute('role', 'progressbar')
        this.#label.setAttribute('aria-live', 'polite')

        const width = this.#buttonContainer.querySelector('svg').width.baseVal.value
        
        this.#radius = Math.round((width / 2) - 2)
        this.#circle.r.baseVal.value = this.#radius
        // this.#template = document.querySelector(element.data.template)

        // Calculation
        this.#updateProgression()

        this.#setStyle()

        // Events
        // window.addEventListener('DOMContentLoaded', e => {
        this.#mutationObserver = new MutationObserver(this.#handleMutation)
        this.#mutationObserver.observe(this.#buttonContainer, { attributes: true, subtree: true, attributeOldValue: true })
        // })
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
            if (child.htmlFor) child.htmlFor = child.htmlFor+'-'+id
            if (child.name) child.name = child.name+'-'+id
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
     * Retourne l'input associée au template
     * @returns {HTMLInputElement}
     */
    get input() {
        return this.#input
    }

    /**
     * @param {number} setting
     * @returns
     */
    set dashArray(setting) {
        return this.#dashArray = setting
    }

    /**
     * Permet de modifier dynamiquement la progression en modifiant le dashOffset du SVG
     * @param {number} progress
     * @returns
     */
    set progressBar(progress) {
        this.#progress = progress
        this.#updateProgression()
        this.#setStyle()
    }

    /**
     * Récupère la oldValue du mutateur
     * @returns
     */
    get oldOffsetValue() {
        return this.#oldMutationValue
    }

    /**
     * Permet de cacher / montrer le texte du SVG
     * pour laisser place à la flèche
     */
    set showText(status) {
        return this.#shareText.style.display = status
    }

    /**
     * Permet de cacher / montrer la flèche du SVG
     * pour laisser place au texte
     */
    set showArrow(status) {
        return this.#arrow.style.display = status
    }

    /**
     * Récupère l'actuelle progression
     * @returns
     */
    get getCurrentProgress() {
        return this.#progress
    }
}