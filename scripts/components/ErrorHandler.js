import { alertClass, alertID, allowedSpecialChars, emailInputRegex, emptyAlert, formButton, formIDToAvoidChecking, hiddenAlertClass, hiddenClass, inputErrorClass, inputsNotToAppend, inputsToListen, invalidEmailMessage, invalidPwMessage, noSpaceAllowedMessage, notANumberError, thisInputShouldBeInt, tooltip, userInputRegex, wrongNumber } from "../configs/ErrorHandlerConfig.js"
import { alertMessage, createElement, debounce, filterArrayToRetrieveUniqueValues, retrieveUniqueNotAllowedCharFromRegex, setObjectPropertyTo } from "../functions/dom.js"


//TODO : un mutation obs pour permettre de vérifier l'ajout d'inputs

export class ErrorHandler {

    /** @type {Array} */
    #error = []
    /** @type {HTMLButtonElement} */
    #formButton = document.querySelector(formButton)
    /** @type {HTMLFormElement} */
    #form
    /** @type {String} */
    #formIDToAvoidChecking = formIDToAvoidChecking
    /** @type {HTMLElement} */
    #alert = document.querySelector(alertID)
    /** @type {HTMLElement} */
    #tooltip = document.querySelector(tooltip)
    /** @type {Array < HTMLElement >} */
    #thisInputIDShouldBeInt = Array.from(document.querySelectorAll(thisInputShouldBeInt))
    /** @type {String} */
    #password
    /** @type {String} */
    #pwdRepeat
    /** @type {String} */
    #email
    /** @type {String} */
    #name
    /** @type {Number} */
    #age
    /** @type {RegExpConstructor} */
    #emailInputRegex = emailInputRegex
    /** @type {RegExpConstructor} */
    #allowedSpecialChars = allowedSpecialChars
    /** @type {RegExpConstructor} */
    #userInputRegex = userInputRegex
    /** @type {Array} tested and not allowedSpecialChars char */
    #wrongInput = []
    /** @type {String} */
    #invalidEmailMessage = invalidEmailMessage
    /** @type {String} */
    #invalidPwMessage = invalidPwMessage
    /** @type {String} */
    #noSpaceAllowedMessage = noSpaceAllowedMessage
    /** @type {String} */
    #notANumberError = notANumberError
    /** @type {String} */
    #wrongNumber = wrongNumber
    /** @type {String} */
    #emptyAlert = emptyAlert
    /** @type {String} */
    #alertClass = hiddenAlertClass
    /** @type {String} */
    #inputErrorClass = inputErrorClass
    /** @type {String} */
    #alertText
    /** @type {String} */
    #hiddenClass = hiddenClass
    /** @type {Array} input types to listen to */
    #inputsToListen = inputsToListen
    /** @type {Array} input that will not append the valid / invalid icon */
    #inputsNotToAppend = Array.from(document.querySelectorAll(inputsNotToAppend))
    /** @type {Boolean} */
    #pwStatus = true
    /** @type {Boolean} */
    #isEmpty = false
    /** @type {Boolean} */
    #isNumber  = false
    /** @type {Boolean} */
    #isCharAllowed = false
    /** @type {Boolean} */
    #spaceNotAllowed = false
    /** @type {Array} */
    #listenInputs = []
    /** @type {Array} */
    #count = []

    /**
     * @param {HTMLFormElement} form 
     * // IMPORTANT !! Exemple d'option à configurer : whichInputCanBeEmpty: ['pwdRepeat', 'password', 'username']
     * @param {String, Array} [options.whichInputAllowSpecialCharacters=[string]] Permet de définir quelles inputs peuvent contenir des caractères spéciaux - par défaut : ['Mot de Passe']
     * @param {String, Array} [options.whichInputCanBeEmpty=[string]] Permet de définir quelles inputs peuvent être vides -
     * Il faut utiliser le nom de l'input - par défaut : ['step_3', 'step_4', 'step_5', 'step_6']
     * @param {Boolean} [options.debouncing=true] Permet de définir un délai après intéraction de l'utilisateur - par défaut : true
     * @param {Boolean} [options.canBeEmpty=false] Permet de définir si toutes les inputs peuvent être vides (non conseillé) - par défaut : false
     * @param {Boolean} [options.useMyOwnListener=false] =>
     * !! ATTENTION !!!
     * Il est possible d'utiliser le getter isInputChecked pour vérifier les inputs sur son propre script -
     * Permet de définir si l'on souhaite utiliser son propre submit eventListener dans son script -
     * par défaut : false -
     * Il est recommandé d'utiliser son propre Listener pour permettre de gérer le submit -
     * @param {Boolean} [options.isSpecialCharactersAllowed=false] Permet de définir si l'on souhaite autoriser des caractères spéciaux pour l'input - par défaut : false
     * @param {Object} [options.debounceDelay=1000] Permet de définir un délai après intéraction de l'utilisateur - par défaut : 1s
     */
    constructor(form, options = {}) {
        this.#form = form
        this.options = Object.assign({}, {
            debouncing: true,
            debounceDelay: 50,
            canBeEmpty: false,
            whichInputCanBeEmpty: ['step_3', 'step_4', 'step_5', 'step_6', 'file', 'video_file', 'video_link', 'resting_time'],
            useMyOwnListener: false,
            isSpecialCharactersAllowed: false,
            whichInputAllowSpecialCharacters: ['Mot de Passe', 'Mot de Passe de confirmation', 'Email', 'file', 'video_file'],
        }, options)
        if (this.#alert) this.#alertText = this.#alert.innerText
        if (!this.#alert) {
            this.#alert = createElement('p', {
                class: `${this.#alertClass}`,
                id: alertClass
            })
            if (this.#form.id !== this.#formIDToAvoidChecking) this.#form.insertAdjacentElement(
                'afterbegin',
                this.#alert
            )
        }
        console.log(this.#alert.innerText)

        if (!this.options.debouncing) this.options.debounceDelay = 50
        this.#listenInputs = Array.from(this.#form.querySelectorAll(this.#inputsToListen))
        
        this.#listenInputs.forEach(input => {
            // Setting which input can be empty
            setObjectPropertyTo(this.options.whichInputCanBeEmpty, input, input.name, 'canBeEmpty', true)
            // Setting which input can accept special char
            setObjectPropertyTo(this.options.whichInputAllowSpecialCharacters, input, input.name, 'allowSpecialCharacters', true)
            // Creating valid / invalid icon for each inputs
            const noIconInput = this.activeInput(this.#inputsNotToAppend, input)
            let icon
            if (!icon) {
                if (input.id !== noIconInput.id) {
                    icon = createElement('span')
                    input.insertAdjacentElement(
                        'afterend',
                        icon
                    )
                }
            }
            if (input.id === 'password') this.#password = input
            if (input.id === 'pwdRepeat') this.#pwdRepeat = input
            if (input.id === 'age') this.#age = input
            if (input.id === 'username') this.#name = input
            if (input.id === 'email') {
                this.#email = input
                if (input.classList.contains('form__field')) input.setAttribute('placeholder', '')
            }
            // Inputs will be debounced @ -> get debounceDelay
            // Les listeners d'inputs n'ajoutent pas d'erreurs à l'#error array
            // !! ATTENTION !! : Ce script n'est pas bloquant -
            // 
            // Le bouton d'envoi n'est désactivé qu'en cas d'écoute du submit !!
            input.addEventListener('input', debounce((e) => {
                // Checking if input is empty
                this.isEmptyInputs(e.target)
                // Checking if passwords are same
                this.isExactPassword(e.target)
                // Checking if the character used is allowed
                this.#charsNotAllowed(e.target)
                // Checking if the character used is INT
                this.isANumber(e.target)
                // Should we display the tooltip ?
                this.triggerToolTip()
                if (input.id === 'username') this.isSpaceAllowed(input)
                // switch (e.target) {
                //     case e.target.isEmpty:
                //         console.log('test')
                //         this.#displayErrorMessage(this.#emptyAlert, input)
                //         break
                //     case !input.isCharAllowed:
                //         for (let [index, element] of Object.entries(this.#wrongInput)) {
                //             this.#wrongInput[index] = `  ${element} `
                //         }
                //         this.#displayErrorMessage(`Les caractères suivants ne sont pas autorisés : ${this.#wrongInput} `, input)
                //         break
                //     case input.id === "email" && !this.#emailInputRegex.test(input.value):
                //         this.#displayErrorMessage(this.#invalidEmailMessage, input)
                //         break
                //     case !this.#pwStatus && (input.id === "password" || input.id === "pwdRepeat"):
                //         this.#displayErrorMessage(this.#invalidPwMessage, input)
                //         break
                //     case this.#spaceNotAllowed && input.id === 'username':
                //         this.#displayErrorMessage(this.#noSpaceAllowedMessage, input)
                //         break
                //     case input.isANumber === false:
                //         this.#displayErrorMessage(this.#notANumberError, input)
                //         break
                //     case input.isANumber && input.value <= 0:
                //         this.#displayErrorMessage(this.#wrongNumber, input)
                //         break
                //     default:
                //         input.classList.remove(this.#inputErrorClass)
                //         this.#count = filterArrayToRetrieveUniqueValues(this.#count, input, 'input')
                //         break
                // }
                if (input.isEmpty) {
                    this.#displayErrorMessage(this.#emptyAlert, input)
                    return
                } else if (!input.isCharAllowed) {
                    for (let [index, element] of Object.entries(this.#wrongInput)) {
                        this.#wrongInput[index] = `  ${element} `
                    }
                    this.#displayErrorMessage(`Les caractères suivants ne sont pas autorisés : ${this.#wrongInput} `, input)
                } else if (input.id === "email" && !this.#emailInputRegex.test(input.value)) {
                    this.#displayErrorMessage(this.#invalidEmailMessage, input)
                    return
                } else if (!this.#pwStatus && (input.id === "password" || input.id === "pwdRepeat")) {
                    this.#displayErrorMessage(this.#invalidPwMessage, input)
                    return
                } else if (this.#spaceNotAllowed && input.id === 'username') {
                    this.#displayErrorMessage(this.#noSpaceAllowedMessage, input)
                    return
                } else if (input.isANumber === false) {
                    this.#displayErrorMessage(this.#notANumberError, input)
                    return
                } else if (input.isANumber && input.value <= 0) {
                    this.#displayErrorMessage(this.#wrongNumber, input)
                    return
                } else {
                    // input.classList.add("valid_input")
                    input.classList.remove(this.#inputErrorClass)
                    // input.removeAttribute('style')
                    // if (this.#tooltip?.hasAttribute('style')) this.#tooltip.removeAttribute('style')
                    this.#count = filterArrayToRetrieveUniqueValues(this.#count, input, 'input')

                }
                console.log(this.#count)
                console.log("text alert => ", this.#alertText)
                console.log("innerText => ", this.#alert.innerText)
                if (this.#alertText !== null && undefined) {
                // if (this.#count.length === 0 && (this.#email.classList.contains('input_error') || this.#name.classList.contains('input_error'))) {
                    this.#alert.innerText = this.#alertText
                    this.#alertText = null
                } else if (this.#count.length === 0) {
                    // if (this.#error.length === 0) {
                    this.#alert.classList.add(this.#hiddenClass)
                    this.#alert.innerText = ''
                    this.#formButton.disabled = false
                    // input.classList.add("valid_input")
                } else {
                    this.#alert.innerText = this.#count[0].alert
                }
            }, (this.debounceDelay)))
        }, {once: true})


        // this.#password.addEventListener('input', e => {
        //     // e.preventDefault()
        //     console.log(this.#password.value)
        // })
        // this.#pwdRepeat.addEventListener('keydown', e => {
        //     // e.preventDefault()
        //     console.log(e.key)
        // })
        // this.#email.addEventListener('keydown', e => {
        //     // e.preventDefault()
        //     console.log(e.key)
        // })
        // this.#name.addEventListener('keydown', e => {
        //     // e.preventDefault()
        //     console.log(e.key)
        // })

        // this.#age.addEventListener('keydown', e => {
        //     // e.preventDefault()
        //     console.log(e.key)
        // })

        // If you want a generic submit checker
        if (this.options.useMyOwnListener) return
        this.#form.addEventListener('submit', e => {
            this.onSubmit(e)
            // e.currentTarget.reset()
        })
    }

    /**
     * Permet d'enregistrer l'élément qui contient une erreur dans l'Array #count -
     * Fera apparaître l'alerte avec le message passé dans les paramètres -
     * Si l'élément est #password ou #pwdRepeat, les 2 éléments auront une classe d'érreur simultanément -
     * L'élément en erreur recevra l'icone d'erreur -
     * @param {String} message Message à afficher
     * @param {HTMLElement} element Element HTML qui recevra/supprimera une classe
     */
    #displayErrorMessage(message, element) {
        // this.#count.push(element)
        this.#count.push( { input: element, alert: message } )
        this.#alert.classList.remove(this.#hiddenClass)
        this.#alert.innerText = message
        element.classList.add(this.#inputErrorClass)
        element.classList.remove("valid_input")
        if (element === this.#password) this.#pwdRepeat.classList.add(this.#inputErrorClass)
        if (element === this.#pwdRepeat) this.#password.classList.add(this.#inputErrorClass)
    }

    /**
     * Vérifie que le caractère d'une input est autorisé
     * @param {EventTarget} input
     * @returns 
     */
    #charsNotAllowed(input) {
        if (!input.allowSpecialCharacters && !this.#allowedSpecialChars.test(input.value) && !input.isEmpty) {
            // Retrieve every character that isn't allowed and only unique entries
            console.log(input)
            this.#wrongInput = retrieveUniqueNotAllowedCharFromRegex(input.value, this.#allowedSpecialChars)
            input.isCharAllowed = false
            // this.#isCharAllowed = false
            return
        }
        input.isCharAllowed = true
            // this.#isCharAllowed = true
        return
    }

    /**
     * Vérifie que la valeur de l'élément HTML passée en paramètre est de type INT
     * @param {HTMLElement} input
     * @returns {Boolean}
     */
    #isANumber(input) {
        let inputShouldBeInt = this.activeInput(this.#thisInputIDShouldBeInt, input)
        if (input.id !== inputShouldBeInt.id) return
        if (input.id === inputShouldBeInt.id && isNaN(input.value)) {
            this.#isNumber = false
            input.isANumber = false
            // inputEvent.classList.remove('valid_input')
            // Affiche le tooltip lorsqu'une erreur est détéctée
            if (this.#tooltip) this.#tooltip.style.visibility = 'visible'
            return
        } else {
            this.#isNumber = true
            input.isANumber = true
            input.value = input.value.trim()
            this.#tooltip?.removeAttribute('style')
            input.classList.remove(this.#inputErrorClass)
        }
        return
    }

    get isANumber() {
        return this.#isANumber.bind(this)
    }

    /**
     * Filtre le String pour en ressortir un Array de HTML Elements
     * Sélectionne seulement l'élément demandé
     * @param {String} arr
     * @param {HTMLElement} element
     * @returns {HTMLElement}
     */
    #selectActiveInput(arr, element) {
        let activeInput = arr.filter( (value) => value.id === element.id )
        if (activeInput[0]) activeInput = activeInput[0]
        return activeInput
    }

    get activeInput() {
        return this.#selectActiveInput.bind(this)
    }

    /**
     * Affiche le tooltip si une input du drawer est en erreur
     * @param {Boolean} isEmpty 
     */
    #triggerToolTip() {
        for (const input of this.#thisInputIDShouldBeInt) {
            if (input.classList.contains('input_error') || input.isEmpty) {
                this.#tooltip.style.visibility = 'visible'
                return
            }
            this.#tooltip.removeAttribute('style')
        }
    }

    get triggerToolTip() {
        return this.#triggerToolTip.bind(this)
    }

    // #isInvalidInput(inputs) {
    //     console.log(inputs.currentTarget.value.toString().trim())
    //     const input = inputs.currentTarget
    //     if (input === this.#name && input.value.toString().trim().includes(' ')) {
    //         this.#spaceNotAllowed = true
    //         this.#error.push(input)
    //     } else {
    //         // this.#error = this.#error.filter(t => t !== input)
    //         // this.#resetInputs(input)
    //     }
    // }

    /**
     * Vérifie qu'une input n'est pas empty
     * @param {EventTarget} input 
     * @returns 
     */
    #isEmptyInputs(input) {
        if (!input.canBeEmpty && (input.value.toString().trim() === '' || input.value.toString().trim() === ' ') || !input.value) {
            // this.#isEmpty = true
            input.isEmpty = true
            console.log(input)
        } else {
            // this.#isEmpty = false
            input.isEmpty = false
            input.classList.add("valid_input")
        }
        return
    }

    get isEmptyInputs() {
        return this.#isEmptyInputs.bind(this)
    }

    /**
     * Vérifie qu'une input ne contient pas d'espace lors de la frappe
     * Un status "#spaceNotAllowed" sera créé
     * @param {EventTarget} input 
     * @returns 
     */
    #isSpaceAllowed(input) {
        if (input.id !== 'username') return
        if (input.value.toString().trim().includes(' ')) {
            this.#spaceNotAllowed = true
            input.value = input.value.toString().trim()
            return
        } else if (!input.value.toString().includes(' ')) {
            this.#spaceNotAllowed = false
            return
        }
    }

    get isSpaceAllowed() {
        return this.#isSpaceAllowed.bind(this)
    }

    /**
     * Supprime une erreur de l'array -
     * Ne prend pas en compte la position dans l'array mais la phrase d'erreur
     * @param {String} error 
     * @returns 
     */
    #removeError(error) {
        this.#error = this.#error.filter((t, m) => t !== error)
    }
    
    // #removeValue(value, index, arr) {
    //     // If the value at the current array index matches the specified value (2)
    //     if (value === this.input) {
    //     // Removes the value from the original array
    //         arr.splice(index, 1)
    //         return arr
    //     }
    //     return arr
    // }

    /**
     * Vérifie que les inputs password & pwdRepeat soient similaires -
     * Créera une border rouge si ce n'est pas le cas -
     * Un status "#pwStatus" sera créé -
     */
    #isExactPassword() {
        debugger
        if (this.#password && this.#pwdRepeat) {
            if (((this.#password.value !== this.#pwdRepeat.value) || (this.#pwdRepeat.value !== this.#password.value)) || this.#password.isEmpty || this.#pwdRepeat.isEmpty || !this.#password.value || !this.#pwdRepeat.value) {
                this.#pwStatus = false
                this.#password.classList.remove('valid_input')
                this.#pwdRepeat.classList.remove('valid_input')
            } else {
                this.#pwStatus = true
                this.#password.classList.remove('input_error')
                this.#pwdRepeat.classList.remove('input_error')
                this.#password.classList.add('valid_input')
                this.#pwdRepeat.classList.add('valid_input')
                this.#count = filterArrayToRetrieveUniqueValues(this.#count, [this.#password, this.#pwdRepeat], 'input')
            }
            console.log(this.#count)
        }
    }

    get isExactPassword() {
        return this.#isExactPassword.bind(this)
    }

    /**
     * Le submit n'est pas prevent par défaut -
     * Si une erreur est trouvée, il sera preventDefault et le bouton d'envoi ne sera pas réactivé -
     * Les listeners d'inputs n'ajoutent pas d'erreurs à l'#error array -
     * Un toaster sera envoyé sous le formulaire si une erreur est trouvée -
     * @param {SubmitEvent} form 
     * @returns 
     */
    async #onSubmit(form) {
        this.#formButton.disabled = true
        try {
            if (!this.#isInputChecked()) {
                form.preventDefault()
                this.#formButton.disabled = true
                return
            }
            this.#formButton.disabled = false
        } catch (error) {
            const alert = alertMessage(error.message)
            this.#form.insertAdjacentElement(
                'beforeend',
                alert
            )
        }
    }

    get onSubmit() {
        return this.#onSubmit.bind(this)
    }

    /**
     * Vérifie les inputs et renvoie True / False -
     * Trim toutes les inputs trouvées et les convertis dans un nouvel Array -
     * Il faut utiliser le nom de l'input pour la récupérer dans le cas où l'on souhaite
     * faire quelque chose de spécifique avec -
     * Ce script est bloquant -
     * @returns
     */
    #isInputChecked() {
        let arrayKey = []
        let count = 0
        let specialCount = 0
        const data = new FormData(this.#form)
        // Permet de définir quelle input peut-être vide
        // Permet de définir aussi quelle input peut contenir des caractères spéciaux
        // Par défaut : aucune
        for (const [key, value] of data) {
            // Setting default option values
            arrayKey[key] = { value: value.toString().trim(), canBeEmpty: this.canBeEmpty, allowSpecialCharacters: this.allowSpecialCharacters }
            // Setting which input can be empty
            setObjectPropertyTo(this.options.whichInputCanBeEmpty, arrayKey[key], key, 'canBeEmpty', true)
            // Setting which input can accept special char
            setObjectPropertyTo(this.options.whichInputAllowSpecialCharacters, arrayKey[key], key, 'allowSpecialCharacters', true)
        }
        for (const key in arrayKey) {
            if (!arrayKey[key].canBeEmpty && arrayKey[key].value === '') {
                this.#error.push(`Veuillez renseigner votre ${key}`)
                count++
                this.#listenInputs.forEach(input => {
                    if (key === input.name) {
                        input.classList.add(this.#inputErrorClass)
                    }
                })
            } else {
                this.#removeError(`Veuillez renseigner votre ${key}`)
            }
            if (!arrayKey[key].allowSpecialCharacters && arrayKey[key].value !== '' && !this.#allowedSpecialChars.test(arrayKey[key].value)) {
                this.#error.push(`Les caractères spéciaux ne sont pas autorisés pour le ${key}`)
                specialCount++
                this.#listenInputs.forEach(input => {
                    if (key === input.name) {
                        input.classList.add(this.#inputErrorClass)
                    }
                })
            } else {
                this.#removeError(`Les caractères spéciaux ne sont pas autorisés pour le ${key}`)
            }
            if (key === 'email') {
                if (!this.#emailInputRegex.test(arrayKey[key].value)) {
                    this.#email.classList.add(this.#inputErrorClass)
                    this.#email.style.borderBottom = '1px solid red'
                    if (!this.#email.classList.contains('form__field')) {
                        this.#email.setAttribute('placeholder', 'monEmail@mail.com')
                    } else {
                        this.#email.setAttribute('placeholder', '')
                    }
                    this.#error.push(this.#invalidEmailMessage)
                } else {
                    this.#email.removeAttribute('style')
                    this.#removeError(this.#invalidEmailMessage)
                }
            }
        }
        // Not identical passwords
        if (!this.#pwStatus) {
            console.log(this.#pwStatus)
            // this.#password.classList.add(this.#inputErrorClass)
            this.#error.push(this.#invalidPwMessage)
        } else {
            this.#removeError(this.#invalidPwMessage)
        }
        // Space not allowed
        if (this.#spaceNotAllowed) {
            this.#error.push(this.#noSpaceAllowedMessage)
        } else {
            this.#removeError(this.#noSpaceAllowedMessage)
        }
        // More than 1 error found
        if (this.#error.length > 1 && count > 1) {
            this.#displayAlertFromArray(this.#error, this.#emptyAlert)
            return false
        // Only 1 error found
        } else if (this.#error.length === 1) {
            this.#displayAlertFromArray(this.#error)
            return false
        // Found errors related to wrong characters
        } else if (this.#error.length > 0 && specialCount > 0) {
            this.#displayAlertFromArray(this.#error, 'Les caractères spéciaux ne sont pas autorisés')
            return false
        // No more errors found
        } else if (this.#error.length === 0) {
            this.#alert.classList.add(this.#hiddenClass)
            this.#alert.innerText = ''
            return true
        }
    }

    /**
     * Parcours l'array et affiche le message avec l'élément HTML alert
     * @param {Array} arr
     * @param {String} message
     */
    #displayAlertFromArray(arr, message = null) {
        for (const element of arr) {
            this.#alert.innerText = message ? message : element
            this.#alert.classList.remove(this.#hiddenClass)
            this.#removeError(element)
        }
    }

    /** @returns {@param | options.debounceDelay} */
    get debounceDelay() {
        return this.options.debounceDelay
    }

    /** @returns {@param | options.canBeEmpty} */
    get canBeEmpty() {
        return this.options.canBeEmpty
    }

    /** @returns {@param | options.isSpecialCharactersAllowed} */
    get allowSpecialCharacters() {
        return this.options.isSpecialCharactersAllowed
    }

    /** @returns {Function} */
    get checkInputs() {
        return this.#isInputChecked()
    }

    /** @returns {Array < String >} */
    get listenedInputs() {
        return this.#listenInputs
    }
}