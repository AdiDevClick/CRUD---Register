import { createElement } from "../functions/dom.js"

class ProgressiveStepButton {
    
    #r
    #template
    #button 
    constructor(element, width) {
        this.#r = Math.round(width / 2)
        this.#button = element
        this.#template = document.querySelector(element.data.template)
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

    #calculateDasharray(r) {
        return Math.PI * r * 2
    }

    #calculateDashoffset(percentageShown, circumference) {
        return ((100 - percentageShown) / 100) * circumference
    }
}