import { createElement } from "../functions/dom.js"

export class ProgressiveCircleButton {
    
    #r
    #template
    #button
    #width
    #buttonTemplate
    #dashArray
    #dashOffset
    /** @type {String} */
    #flipArrowData
    #label

    constructor(position, flip='none') {
        this.#button = document.querySelector('#circular-progress-button').content.firstElementChild.cloneNode(true)
        // this.#button = this.#buttonTemplate
        this.#label = this.#button.querySelector('label').dataset.arrowflip
        this.#label.setAttribute(dataset.arrowflip = flip)
        console.log(flip)
        console.log(this.#label)
        this.#width = this.#button.querySelector('svg').width.baseVal.value
        this.#r = Math.round((this.#width / 2) - 1)
        this.#button.classList.add(position)
        this.#width = this.#r
        // this.#template = document.querySelector(element.data.template)
        this.#dashArray = this.#calculateDasharray(this.#r)
        this.#dashOffset = this.#calculateDashoffset('15', this.#dashArray)

        console.log(this.#button)
        const ids = Array.from(this.#button).filter(t => t)
        console.log(ids)
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

    #addClassToElement(className) {
        this.#button.classList.add(className)
    }

    #setProgression(progress) {
        this.#button.classList.add(className)
    }

    #calculateDasharray(radius) {
        return Math.PI * radius * 2
    }

    #calculateDashoffset(percentageShown, circumference) {
        return ((100 - percentageShown) / 100) * circumference
    }

    get button() {
        return this.#button
    }
}