import { createElement } from "../functions/dom.js"

class ProgressiveStepButton {
    
    #r
    constructor(circumference) {
        this.#r = circumference
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

    #calculateDasharray(r) {
        return Math.PI * r * 2
    }

    #calculateDashoffset(percentageShown, circumference) {
        return ((100 - percentageShown) / 100) * circumference
    }
}