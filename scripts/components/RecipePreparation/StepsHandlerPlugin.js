import { ProgressiveCircleButton } from "../ProgressiveCircleButton.js"

export class StepsHandlerPlugin {

    /** @type {Ingredient} */
    #preparation
    /** @type {HTMLElement} */
    #gridContainer
    /** @type {Object} */
    #previewsButton
    /** @type {Object} */
    #nextButton
    /** @type {Number} */
    #submitionStep = 1
    /** @type {Object} */
    #content = {}
    /** @type {ErrorHandler} */
    #errorHandler
    /** @type {DrawerTouchPlugin} */
    #touchPlugin
    /** @type {Array} */
    #previewsContent = []
    /** @type {HTMLElement} */
    #tabulation = document.querySelector('.tabulation')

    #form
    /**
     * @typedef {Object} DataItem
     * @property {string} class - La classe CSS associée à l'étape.
     * @property {string} data - Le nom de l'étape.
     * Un tableau d'objets représentant les étapes avec leurs classes CSS associées.
     * @type {DataItem[]}
     */
    #datas = [
        { class: '.js-one', data: 'step1' },
        { class: '.js-two', data: 'step2' },
        { class: '.js-three', data: 'step3' },
        { class: '.js-four', data: 'step4' },
        { class: '.js-five', data: 'final' }
    ]

    constructor(preparation) {
        console.log('STEP LOADED')
        this.#preparation = preparation
        this.#gridContainer = this.#preparation.gridContainer
        this.#errorHandler = this.#preparation.errorHandler
        this.#form = this.#preparation.form
        // console.log('les variables : \n')
        // console.log('grid => ', this.#gridContainer)
        // console.log('errorHandler => ', this.#errorHandler)
        // console.log('touchPlugin => ', this.#touchPlugin)
        // console.log('form => ', this.#form)
        // // Create elements
        this.#createNextAndPreviewsButtons()

        // On hovering the previews button
        this.#previewsButton.button.addEventListener('mouseenter', e => {
            e.preventDefault()
            this.#onHover(e, this.#previewsButton, 2, 0, 25)
        })
        this.#previewsButton.button.addEventListener('touchstart', e => {
            e.preventDefault()
            this.#onHover(e, this.#previewsButton, 2, 0, 25)
        })
        
        // On hovering the next button
        this.#nextButton.button.addEventListener('mouseenter', e => {
            if (!this.#touchPlugin) this.#touchPlugin = this.#preparation.touchPlugin
            e.preventDefault()
            this.#onHover(e, this.#nextButton, 1, 25, 25)
        })
        this.#nextButton.button.addEventListener('touchstart', e => {
            if (!this.#touchPlugin) this.#touchPlugin = this.#preparation.touchPlugin
            e.preventDefault()
            this.#onHover(e, this.#nextButton, 1, 25, 25)
        })
    }

    /**
     * Cette fonction vérifie les inputs affichés à l'utilisateur à chaque étape -
     * Lorsque l'étape avant au-delà de la première, le bouton retour sera affiché -
     * Lorsque l'étape finale est atteinte, la flèche laissera place au texte de partage -
     * Si l'utilisateur atteint la dernière étape et qu'aucune erreur n'est trouvée,
     * la recette sera alors envoyée au serveur -
     * Le reste du temps, l'étape précédente sera cachée et l'étape suivante sera affichée.
     * @param {PointerEvent} event
     * @param {NodeListOf < HTMLElement >} previewsStepElements
     * @param {NodeListOf < HTMLElement >} nextStepElements
     * @param {Number} activeStep
     * @param {Number} previewslyActiveStep
     * @param {Array} datas
     * @returns
     */
    #onNextClick(event, previewsStepElements, nextStepElements, activeStep, previewslyActiveStep, datas) {
        // 1 - Sets progressBar's progress on each buttons
        this.#previewsButton.progressBar = this.#nextButton.getCurrentProgress
        this.#nextButton.progressBar = this.#nextButton.getCurrentProgress

        // 2 - Until we reach step 4
        if (this.#submitionStep <= 4) {
            // 1 - Check the current page step possible errors
            if (!this.#errorHandler.checkBatchOfInputs(previewsStepElements)) {
                // Afficher le tooltip en fonction du paramétrage :
                // Si une input de type INT est en erreur ou empty
                this.#errorHandler.triggerToolTip()
                return
            }
            // 2 - After final step
            if (this.#submitionStep === 4) {
                // Check all form inputs for possible errors before submit
                if (!this.#errorHandler.checkInputs(event)) {
                    // Si une erreur est détectée lors de l'envoi en mode mobile
                    // et que le drawer est ouvert, il sera fermé.
                    this.#touchPlugin.resetStates
                    // Afficher le tooltip en fonction du paramétrage :
                    // Si une input de type INT est en erreur ou empty
                    this.#errorHandler.triggerToolTip()
                    return
                }
                // No error found
                this.#preparation.onSubmit(this.#form)
                // this.#onSubmit(this.#form)
            }

            // 3 - Handle the previews steps
            if (this.#submitionStep < 4) {
                // 1 - Reset previews step datas before saving the new ones
                this.#content[datas[this.#submitionStep-1].data] = null

                // 2 - Getting individual elements from PREVIEWS STEP
                previewsStepElements.forEach(item => {
                    // 1 - Save elements
                    this.#previewsContent.push(item)
                    this.#pushContent(item)

                    // 2 - Hide elements
                    console.log(this.#touchPlugin)
                    this.#touchPlugin.getDisableScrollBehavior
                    this.#hideElement(item, 'fromRight')
                    // this.#preparation.hideElem(item)
                })
                // 3 - We can now instanciate the new STEP COUNTER
                this.#submitionStep++

                // 4 - Sets tabulation on this new step active
                activeStep.classList.toggle('active')
                activeStep.classList.toggle('greyed')
                previewslyActiveStep.classList.toggle('active')
            }

                // 4 - Getting individual elements from NEXT STEP
                // and display elements to the user
                this.#elementsToDisplay(nextStepElements, 'fromRight')
        }

        // At the final step
        if (this.#submitionStep === 4) {
            // Hide the arrow
            this.#nextButton.showArrow = 'none'
            // Show the share text
            this.#nextButton.showText = null
        }

        // If we advance to the 2nd step at least, we display the previews button
        if (this.#submitionStep > 0) this.#previewsButton.button.removeAttribute('style')
    }

    /**
     * Cette fonction permet de cacher l'étape précédente
     * et affiche l'étape suivante.
     * Elle est trigger par le 'click' event.
     * @param {Array} datas
     */
    #onPreviewsClick(datas) {
        // Select tab items to highlight
        const activeStep = this.#tabulation.querySelector(datas[this.#submitionStep-1].class)
        const previewslyActiveStep = this.#tabulation.querySelector(datas[this.#submitionStep-2].class)

        // Sets the current state of the progress bar
        this.#nextButton.progressBar = this.#previewsButton.getCurrentProgress
        this.#previewsButton.progressBar = this.#nextButton.getCurrentProgress

        // Save the previews step
        const nextStepData = this.#gridContainer.querySelectorAll(datas[this.#submitionStep-1].class)
        nextStepData.forEach(element => {
            this.#pushContent(element)
        })

        // Retrieve datas
        const hideNextStep = this.#content[datas[this.#submitionStep-1].data]
        const showPreviewsStep = this.#content[datas[this.#submitionStep-2].data]

        // 1 - Hide the current step content
        if (hideNextStep) {
            this.#touchPlugin.getDisableScrollBehavior

            hideNextStep.forEach(element => {
                this.#hideElement(element, 'fromLeft')
                // this.#preparation.hideElem(element)
            })
        }

        // 2 - Display the previews step content to the user
        this.#elementsToDisplay(showPreviewsStep, 'fromLeft')
        
        // 3 - We can now instanciate the new Step counter
        if (this.#submitionStep > 1) {
            this.#submitionStep--
            // 3.1 - Show the active step in the tabulation
            // It will grey out the previews steps
            // Modify the arrow / text behaviour
            if (this.#submitionStep < 4) {
                // Show the arrow until the last step
                this.#nextButton.showArrow = null
                // Hide the share text until the last step
                this.#nextButton.showText = 'none'
                previewslyActiveStep.classList.toggle('active')
                activeStep.classList.toggle('active')
                activeStep.classList.toggle('greyed')
            }
        }
        
        // 4 - If we get back to the first step, hide the previews button
        if (this.#submitionStep < 2) {
            this.#previewsButton.button.style.display = 'none'
            this.#previewsButton.button.disabled = true
        }
    }

    /**
     * Crer les boutons Next et Previews pour la création / édition
     * de recettes.
     */
    #createNextAndPreviewsButtons() {
        // DOM target
        const target = this.#gridContainer?.querySelector('.js-four')

        // Create previews step button
        this.#previewsButton = new ProgressiveCircleButton('left-corner', {
            progressStart: 25,
            flip: 'flip'
        }, this.#gridContainer)
        // Hide previews button by default
        this.#previewsButton.button.style.display = 'none'

        // Create next step button
        this.#nextButton = new ProgressiveCircleButton('right-corner', {
            progressStart: 0
        }, this.#gridContainer)

        // Insert buttons into the DOM
        target.insertAdjacentElement("afterend", this.#nextButton.button)
        target.insertAdjacentElement("afterend", this.#previewsButton.button)
    }

    /**
     * Lorsque l'utilisateur hover le bouton, la barre de progression
     * sera mise à jour en fonction de l'étape actuelle -
     * Un évènement 'click' sera créé en fonction du boutton qui aura été pressé -
     * @param {PointerEvent|TouchEvent} event
     * @param {Object} button L'Oject instancié du constructeur
     * @param {Number} startStep A quelle étape doit-on afficher la barre de progression ?
     * @param {Number} startValue La valeur de départ de la barre de progression
     * @param {Number} incrementValue La valeur à incrémenter à la barre de progression à chaque étape
     */
    #onHover(event, button, startStep, startValue, incrementValue) {
        // Disable submit button
        document.querySelector('#submit').disabled = true

        const controller = new AbortController()
        let progressValue = startValue
        // Specify what percentage shows the progress bar on hover
        while (this.#submitionStep !== startStep) {
            startStep++
            progressValue += incrementValue
        }
        if (this.#submitionStep == startStep) button.progressBar = progressValue

        // On click event
        if (button == this.#nextButton) {
            this.#handleThisNextStep(event.target, this.#datas, controller)
        } else {
            this.#handleThisPreviewsStep(event.target, this.#datas, controller)
        }

        // When user exits cursor from the button
        this.#onMouseLeaveResetButtonProgressBarValue(button.button, button, controller)
    }

    /**
     * Crer des évènements click/touchend.
     * Ces évènement prendront en charge les étapes suivante demandées par utilisateur.
     * @param {HTMLElement} eventTarget - Elément HTML qui a été cliqué
     * @param {DataItem[]} datas -
     * @param {AbortController} controller - Spécifie un controller à associer pour éviter
     * un conflit avec le mouseenter event.
     */
    #handleThisNextStep(eventTarget, datas, controller) {
        if (!datas[this.#submitionStep]) return

        // Tab items to highlight
        const activeStep = this.#tabulation.querySelector(datas[this.#submitionStep].class)
        const previewslyActiveStep = this.#tabulation.querySelector(datas[this.#submitionStep-1].class)
        // Steps elements to retrieve
        const nextStepElements = this.#gridContainer.querySelectorAll(datas[this.#submitionStep].class)
        const previewsStepElements = this.#gridContainer.querySelectorAll(datas[this.#submitionStep-1].class)
        
        // Event for mobile/desktop
        // Will display new step
        eventTarget.addEventListener('click', e => {
            e.preventDefault()
            this.#onNextClick(e, previewsStepElements, nextStepElements, activeStep, previewslyActiveStep, datas)
        }, { once: true, signal: controller.signal } )
        // Event for tablet/ipad
        // Will display new step
        eventTarget.addEventListener('touchend', e => {
            e.preventDefault()
            this.#onNextClick(e, previewsStepElements, nextStepElements, activeStep, previewslyActiveStep, datas)
        }, { once: true, signal: controller.signal } )
    }

    /**
     * Crer des évènements click/touchend.
     * Ces évènement prendront en charge le retour arrière utilisateur.
     * @param {HTMLElement} eventTarget
     * @param {Array} datas
     * @param {AbortSignal} controller
     */
    #handleThisPreviewsStep(eventTarget, datas, controller) {
        // Event for mobile/dekstop
        eventTarget.addEventListener('click', e => {
            e.preventDefault()
            this.#onPreviewsClick(datas)
        }, { once: true, signal: controller.signal } )
        // Event for tablet/ipad
        eventTarget.addEventListener('touchend', e => {
            e.preventDefault()
            this.#onPreviewsClick(datas)
        }, { once: true, signal: controller.signal } )
    }

    /**
     * Reset la valeur de la progress bar en utilisant l'oldOffsetValue
     * sauvegardé pa le mutator de la classeObject
     * @param {HTMLElement} HTMLbuttonElement 
     * @param {Object} buttonClassObject 
     * @param {AbortSignal} controller 
     */
    #onMouseLeaveResetButtonProgressBarValue(HTMLbuttonElement, buttonClassObject, controller) {
        HTMLbuttonElement.addEventListener('mouseleave', e => {
            e.preventDefault()
            // Resets progress bar value to the same one applyed when hover started
            buttonClassObject.progressBar = buttonClassObject.oldOffsetValue
            controller.abort()
        }, { once: true })
    }

    /**
     * Parcours le tableau et display chaque élément
     * @param {array} elements 
     */
    #elementsToDisplay(elements, type) {
        elements.forEach(element => {
            // Display new elements
            element.removeAttribute('style')
            element.style.position = "absolute"

            element.classList.remove('hidden')
            element.classList.add('show')
            // element.classList.add('show', 'slideToRight')

            if (type === 'fromRight') element.style.animation = 'slideStepFromTheRight 0.3s ease'
            if (type === 'fromLeft') element.style.animation = 'slideStepFromTheLeft 0.3s ease'

            element.addEventListener('animationend', e => {
                element.classList.remove('show')
                element.removeAttribute('style')
            }, { once:true } )
        })
    }

    /**
     * Applique la classe .hidden à un élément pour le cacher
     * Puis ajoute un style display='none'
     * pour laisser la fade out s'opérer
     * @param {HTMLElement} element 
     */
    #hideElement(element, type) {
        element.removeAttribute('style')
        element.style.position = "absolute"
        element.classList.add('hidden')
        if (type === 'fromRight') element.style.animation = 'slideStepFromTheLeft 0.1s ease reverse forwards'
        if (type === 'fromLeft') element.style.animation = 'slideStepFromTheRight reverse 0.1s ease'
        element.addEventListener('animationend', e => {
            element.removeAttribute('style')
            element.style.display = 'none'
        }, { once: true } )
    }

    /**
     * Sauvegarde chaque élément trouvé dans l'Array #content sous forme d'objet
     * @param {HTMLElement} item
     * @returns
     */
    #pushContent(item) {
        let step = 'step'+this.#submitionStep
        this.#content[step] = this.#content[step] ? [...this.#content[step], item] : [item]
        return this.#content[step]
    }

    get getNextButton() {
        return this.#nextButton.button
    }

    get getPreviewsButton() {
        return this.#previewsButton.button
    }

    /**
     * Récupère l'étape à laquelle l'utilisateur s'est arrêté
     * @type {Number}
     */
    get currentSubmitionStep() {
        return this.#submitionStep
    }
}