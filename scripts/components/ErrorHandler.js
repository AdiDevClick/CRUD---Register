import { commonPasswords } from "../configs/CommonPasswords.js"
import { alertClass, alertID, allowedSpecialChars, emailInputRegex, emptyAlert, formButton, formIDToAvoidChecking, hiddenAlertClass, hiddenClass, inputErrorClass, inputsCanBeEmpty, inputsCanContainSpecialChars, inputsNotToAppend, inputsToListen, inputValidClass, invalidEmailMessage, invalidPwMessage, noSpaceAllowedMessage, notANumberError, notIdenticalPasswords, pwCannotBeUsername, pwIsTooCommon, sectionToWatch, strongPwDigitInputRegex, strongPwLengthInputRegex, strongPwLowerCaseInputRegex, strongPwSpecialCharInputRegex, strongPwUpperCaseInputRegex, thisInputShouldBeInt, tooltip, userInputRegex, wrongNumber } from "../configs/ErrorHandler.config.js"
import { alertMessage, createElement, debounce, filterArrayToRetrieveUniqueValues, retrieveUniqueNotAllowedCharFromRegex, setObjectPropertyTo } from "../functions/dom.js"


/**
 * @todo rendre le tooltip dynamique
 * @todo {userInputRegex} à setup pour le username
 */
export class ErrorHandler {

    /** @type {Array} */
    #error = []
    /**
     * @module ErrorHandler.config.js~formButton
     * @type {HTMLButtonElement}
     */
    #formButton = document.querySelector(formButton)
    /** @type {HTMLFormElement} */
    #form
    /**
     * @see module:../configs/ErrorHandler.config.js
     * @type {String}
     */
    #formIDToAvoidChecking = formIDToAvoidChecking
    /**
     * @module ErrorHandler.config.js
     * @type {HTMLElement}
     */
    #alert = document.querySelector(alertID)
    /**
     * @module ErrorHandler.config.js
     * @type {HTMLElement}
     */
    #tooltip = document.querySelector(tooltip)
    /**
     * @module ErrorHandler.config.js
     * @type {Array < HTMLElement >}
     */
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
    /**
     * @module ErrorHandler.config.js
     * @type {RegExpConstructor} 
     */
    #emailInputRegex = emailInputRegex
    /**
     * @module ErrorHandler.config.js
     * @type {RegExpConstructor} 
     */
    #allowedSpecialChars = allowedSpecialChars
    /**
     * @module ErrorHandler.config.js
     * @type {RegExpConstructor} 
     */
    #userInputRegex = userInputRegex
    // #passwordInputRegex = strongPasswordInputRegex
    // #passwordInputRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\[\]{};':"\\|,.<>\/?~`]).{8,32}$/
    /** 
     * Tested and not allowedSpecialChars char
     * @type {Array}
     */
    #wrongInput = []
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #invalidEmailMessage = invalidEmailMessage
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #invalidPwMessage = invalidPwMessage
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #notIdenticalPasswords = notIdenticalPasswords
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #pwCannotBeUsername = pwCannotBeUsername
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #pwIsTooCommon = pwIsTooCommon
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #noSpaceAllowedMessage = noSpaceAllowedMessage
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #notANumberError = notANumberError
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #wrongNumber = wrongNumber
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #emptyAlert = emptyAlert
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #alertClass = hiddenAlertClass
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #inputErrorClass = inputErrorClass
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #inputValidClass = inputValidClass
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #hiddenClass = hiddenClass
    /**
     * Input types to listen to
     * @module ErrorHandler.config.js
     * @type {Array}
     */
    #inputsToListen = inputsToListen
    /**
     * @module ErrorHandler.config.js
     * @type {Array < String >} input names that can be empty
     */
    #inputsCanBeEmpty = inputsCanBeEmpty
    /**
     * @module ErrorHandler.config.js
     * @type {Array < String >} input names that can accept special chars
     */
    #inputsCanContainSpecialChars = inputsCanContainSpecialChars
    /**
     * @module ErrorHandler.config.js
     * @type {Array} inputs that will not append the (valid / invalid) icon
     */
    #inputsNotToAppend
    // #inputsNotToAppend = Array.from(document.querySelectorAll(inputsNotToAppend))
    /** @type {String} */
    #alertText
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
    /** @type {HTMLElement} */
    #pwTooltip
    /**
     * Utilisé pour retourner les erreurs lors de la saisie dynamique -
     * L'array contient les inputs qui renvoient une erreur de saisie
     * pour chaque frappe de l'utilisateur -
     * Il est ensuite filtré pour ne ressortir qu'un seul type d'erreur par inputs
     * @type {Array}
     */
    #count = []
    /**
     * @module ErrorHandler.config.js
     * @type {String}
     */
    #sectionToWatch = sectionToWatch
    /** @type {MutationObserver} */
    #observer
    /**
     * Vérifie les inputs compatibles nouvellements créées -
     * On utilise this.#inputsToListen pour passer les inputs qui doivent être filtrées -
     * @type {MutationObserver}
     */
    #handleObserver = (mutationsList, observer) => {
        mutationsList.forEach(mutation => {
            if (mutation.addedNodes.length > 0) {
                const addedNodes = mutation.addedNodes[0].children
                for (const node of addedNodes) {
                    if (this.#inputsToListen.includes(node.type)) {
                        // Setting which input can be empty
                        setObjectPropertyTo(this.options.whichInputCanBeEmpty, node, node.name, 'canBeEmpty', true)
                        // Setting which input can accept special char
                        setObjectPropertyTo(this.options.whichInputAllowSpecialCharacters, node, node.name, 'allowSpecialCharacters', true)
                        // Creating valid / invalid icon for each inputs
                        this.#createIconContainer(node)
                        // Main dynamic checker
                        node.addEventListener('input', debounce((e) => {
                            this.#dynamicCheck(e.target)
                        }, (this.debounceDelay)))
                    }
                }
            }
        })
    }

    /**
     * @param {HTMLFormElement} form 
     * // IMPORTANT !! Exemple d'option à configurer : whichInputCanBeEmpty: ['pwdRepeat', 'password', 'username']
     * @param {String, Array} [options.whichInputAllowSpecialCharacters=[string]] Permet de définir quelles inputs peuvent contenir des caractères spéciaux - par défaut : ['Mot de Passe']
     * @param {String, Array} [options.whichInputCanBeEmpty=[string]] Permet de définir quelles inputs peuvent être vides -
     * Il faut utiliser le nom de l'input - par défaut : ['step_3', 'step_4', 'step_5', 'step_6']
     * @param {Boolean} [options.debouncing=true] Permet de définir un délai après intéraction de l'utilisateur - par défaut : true
     * @param {Boolean} [options.canBeEmpty=false] Permet de définir si toutes les inputs peuvent être vides (non conseillé) - par défaut : false
     * @param {Boolean} [options.strongPassword=true] => 
     * Permet de définir si les mots de passes saisis doivent être forts - par défaut : true
     * Une liste de mots de passes communs sera alors pris en compte -
     * @param {Boolean} [options.useMyOwnListener=false] =>
     * !! ATTENTION !!!
     * Il est possible d'utiliser le getter isInputChecked pour vérifier les inputs sur son propre script -
     * Permet de définir si l'on souhaite utiliser son propre submit eventListener dans son script -
     * par défaut : false -
     * Il est recommandé d'utiliser son propre Listener pour permettre de gérer le submit -
     * @param {Boolean} [options.isSpecialCharactersAllowed=false] Permet de définir si l'on souhaite autoriser des caractères spéciaux pour l'input - par défaut : false
     * @param {Object} [options.debounceDelay=1000] Permet de définir un délai après intéraction de l'utilisateur - par défaut : 1s
     * @param {Boolean} [options.createTooltips=true] Permet de créer des tooltips pour informer l'utilisateur - par défaut : true
     * @param {Object} [options.inputsNotToAppendIcon=inputsNotToAppend] Permet de définir l'id des inputs qui ne doivent
     * pas créer d'icônes de validation - par défaut : inputsNotToAppend du fichier config
     */
    constructor(form, options = {}) {
        this.#form = form
        this.options = Object.assign({}, {
            debouncing: true,
            debounceDelay: 50,
            canBeEmpty: false,
            strongPassword: true,
            whichInputCanBeEmpty: this.#inputsCanBeEmpty,
            useMyOwnListener: false,
            isSpecialCharactersAllowed: false,
            whichInputAllowSpecialCharacters: this.#inputsCanContainSpecialChars,
            createTooltips: true,
            inputsNotToAppendIcon: inputsNotToAppend
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

        if (!this.options.debouncing) this.options.debounceDelay = 50
        this.#listenInputs = Array.from(this.#form.querySelectorAll(this.#inputsToListen))
        
        this.#listenInputs.forEach(input => {
            // Setting which input can be empty
            setObjectPropertyTo(this.options.whichInputCanBeEmpty, input, input.name, 'canBeEmpty', true)
            // Setting which input can accept special char
            setObjectPropertyTo(this.options.whichInputAllowSpecialCharacters, input, input.name, 'allowSpecialCharacters', true)
            // Creating valid / invalid icon for each inputs
            if (input.id === 'password') {
                // Creating tooltip for password input
                this.#pwTooltip = this.#createTooltipContainer(input)
                // Setting if strong password is required
                setObjectPropertyTo(this.options.strongPassword, input, input.name, 'strongPassword', true)
                this.#password = input
            }
            if (input.id === 'pwdRepeat') {
                // tooltip = this.#createTooltipContainer(input)
                // Setting if strong password is required
                setObjectPropertyTo(this.options.strongPassword, input, input.name, 'strongPassword', true)
                this.#pwdRepeat = input
            }
            if (input.id === 'age') this.#age = input
            if (input.id === 'username') this.#name = input
            if (input.id === 'email') {
                this.#email = input
                if (input.classList.contains('form__field')) {
                    input.setAttribute('placeholder', '')
                } else {
                    input.setAttribute('placeholder', 'monEmail@mail.com')
                }
            }

            // Create side icons
            this.#createIconContainer(input)

            // Main dynamic checker
            input.addEventListener('input', debounce((e) => {
                this.#dynamicCheck(e.target)
            }, (this.debounceDelay)))
        })
        
        // Evènements
        window.addEventListener('DOMContentLoaded', (e) => {
            const target = document.querySelector(this.#sectionToWatch)
            if (target) {
                this.#observer = new MutationObserver(this.#handleObserver)
                this.#observer.observe(target, { childList: true })
            }
        })
        // If you want a generic submit checker
        if (this.options.useMyOwnListener) return
        this.#form.addEventListener('submit', e => {
            this.onSubmit(e)
            // e.currentTarget.reset()
        })
    }

    /**
     * Inputs will be debounced @ -> get debounceDelay
     * Les listeners d'inputs n'ajoutent pas d'erreurs à l'#error array
     * !! ATTENTION !! : Ce script n'est pas bloquant -
     * Le bouton d'envoi n'est désactivé qu'en cas d'écoute du submit !!
     * @param {HTMLInputElement} element 
     */
    #dynamicCheck(input) {
        // console.log(input.allowSpecialCharacters)
        // Checking if input is empty
        this.isEmptyInputs(input)
        // Checking if the password matches validation regex
        this.#validateThisPassword(input, input.strongPassword, 'isValidPassword')
        // Checking if passwords are same
        this.isExactPassword(input)
        // Checking if the character used is allowed
        this.#charsNotAllowed(input)
        // Checking if the character used is INT
        this.isANumber(input)
        // Should we display the tooltip ?
        this.triggerToolTip()
        if (input.id === 'username') this.isSpaceAllowed(input)
        if (input.id === "password" || input.id === "pwdRepeat") {
            // Checking common password list
            const isCommonPassword = commonPasswords.includes(input.value)
            // const isCommonPassword = filterArrayToRetrieveUniqueValues(input.value, commonPasswords)
            if (isCommonPassword) {
            // if (isCommonPassword.length === 0) {
                this.#displayErrorMessage(this.#pwIsTooCommon, input)
                return false
            }
            // Checking for strong password
            if (!input.isValidPassword) {
                this.#displayErrorMessage(this.#invalidPwMessage, input)
                this.#pwTooltip.style.visibility = 'visible'
                return false
            }
            // Checking for identical password et pwdRepeat
            if (!this.#pwStatus) {
                this.#displayErrorMessage(this.#notIdenticalPasswords, input)
                return false
            }
            // Checking that password isn't the same as username
            if (input.value === username.value) {
                this.#displayErrorMessage(this.#pwCannotBeUsername, input)
                return false
            }
        }
        if (input.isEmpty) {
            this.#displayErrorMessage(this.#emptyAlert, input)
            return false
        } else if (!input.isCharAllowed) {
            for (let [index, element] of Object.entries(this.#wrongInput)) {
                this.#wrongInput[index] = `  ${element} `
            }
            this.#displayErrorMessage(`Les caractères suivants ne sont pas autorisés : ${this.#wrongInput} `, input)
            return false
        } else if (input.id === "email" && !this.#emailInputRegex.test(input.value)) {
            this.#displayErrorMessage(this.#invalidEmailMessage, input)
            return false
        } else if (this.#spaceNotAllowed && input.id === 'username') {
            this.#displayErrorMessage(this.#noSpaceAllowedMessage, input)
            return false
        } else if (input.isANumber === false) {
            console.log('not a number')
            this.#displayErrorMessage(this.#notANumberError, input)
            return false
        } else if (input.isANumber && input.value <= 0) {
            console.log('value should be +')
            this.#displayErrorMessage(this.#wrongNumber, input)
            return false
        } else {
            // input.classList.add("valid_input")
            input.classList.remove(this.#inputErrorClass)
            if (this.#pwTooltip) this.#pwTooltip.style.visibility = 'hidden'

            // input.removeAttribute('style')
            // if (this.#tooltip?.hasAttribute('style')) this.#tooltip.removeAttribute('style')
            this.#count = filterArrayToRetrieveUniqueValues(this.#count, input, 'input')

        }
        console.log(this.#count)
        console.log("text alert => ", this.#alertText)
        console.log("innerText => ", this.#alert.innerText)

        if (this.#alertText !== null && undefined) {
        // !! IMPORTANT !!
        // In case of submit and an error occured.
        // This will display the server error.
        // if (this.#count.length === 0 && (this.#email.classList.contains('input_error') || this.#name.classList.contains('input_error'))) {
            this.#alert.innerText = this.#alertText
            this.#alertText = null
            console.log('je reset le texte')
        } else if (this.#count.length === 0) {
            // No error found, reseting states
            this.#alert.classList.add(this.#hiddenClass)
            this.#alert.innerText = ''
            this.#formButton.disabled = false
            // console.log('ca fail je reset car count 0')
            return true
        } else {
            // Several errors found, showing the most recent one
            console.log('ca fail display de la last error')
            this.#alert.innerText = this.#count[this.#count.length - 1].alert
            return false
        }
    }
    // /**
    //  * Inputs will be debounced @ -> get debounceDelay
    //  * Les listeners d'inputs n'ajoutent pas d'erreurs à l'#error array
    //  * !! ATTENTION !! : Ce script n'est pas bloquant -
    //  * Le bouton d'envoi n'est désactivé qu'en cas d'écoute du submit !!
    //  * @param {HTMLInputElement} element 
    //  */
    // #dynamicCheck(input) {
    //     input.addEventListener('input', debounce((e) => {
    //         // Checking if input is empty
    //         this.isEmptyInputs(e.target)
    //         // Checking if the password isn't from common list

    //         // Checking if the password matches validation regex
    //         this.#validateThisPassword(e.target, e.target.strongPassword, 'isValidPassword')
    //         // Checking if passwords are same
    //         this.isExactPassword(e.target)
    //         // Checking if the character used is allowed
    //         this.#charsNotAllowed(e.target)
    //         // Checking if the character used is INT
    //         this.isANumber(e.target)
    //         // Should we display the tooltip ?
    //         this.triggerToolTip()
    //         console.log(e.target.isValidPassword)
    //         if (input.id === 'username') this.isSpaceAllowed(input)
    //         if (input.id === "password" || input.id === "pwdRepeat") {
    //             const isCommonPassword = filterArrayToRetrieveUniqueValues(commonPasswords, input.value)
    //             if (isCommonPassword.length > 0) {
    //                 this.#displayErrorMessage(this.#pwIsTooCommon, input)
    //                 return
    //             }
    //         }
    //         if (input.isEmpty) {
    //             this.#displayErrorMessage(this.#emptyAlert, input)
    //             return
    //         } else if (!input.isCharAllowed) {
    //             for (let [index, element] of Object.entries(this.#wrongInput)) {
    //                 this.#wrongInput[index] = `  ${element} `
    //             }
    //             this.#displayErrorMessage(`Les caractères suivants ne sont pas autorisés : ${this.#wrongInput} `, input)
    //             return
    //         } else if ((input.id === "password" || input.id === "pwdRepeat") && (input.value === commonPasswords)) {
    //             console.log(input.value)
    //             // this.#displayErrorMessage(this.#pwCannotBeUsername, input)
    //             return
    //         } else if (!input.isValidPassword) {
    //             // } else if (!input.isValidPassword && (input.id === "password" || input.id === "pwdRepeat")) {
    //             console.log('je suis visible')
    //             this.#displayErrorMessage(this.#invalidPwMessage, input)
    //             this.#pwTooltip.style.visibility = 'visible'
    //             return
    //         } else if (input.id === "email" && !this.#emailInputRegex.test(input.value)) {
    //             this.#displayErrorMessage(this.#invalidEmailMessage, input)
    //             return
    //         } else if (!this.#pwStatus && (input.id === "password" || input.id === "pwdRepeat")) {
    //             this.#displayErrorMessage(this.#notIdenticalPasswords, input)
    //             return
    //         } else if ((input.id === "password" || input.id === "pwdRepeat") && (input.value === username.value)) {
    //             this.#displayErrorMessage(this.#pwCannotBeUsername, input)
    //             return
    //         } else if (this.#spaceNotAllowed && input.id === 'username') {
    //             this.#displayErrorMessage(this.#noSpaceAllowedMessage, input)
    //             return
    //         } else if (input.isANumber === false) {
    //             this.#displayErrorMessage(this.#notANumberError, input)
    //             return
    //         } else if (input.isANumber && input.value <= 0) {
    //             this.#displayErrorMessage(this.#wrongNumber, input)
    //             return
    //         } else {
    //             // input.classList.add("valid_input")
    //             input.classList.remove(this.#inputErrorClass)
    //             if (this.#pwTooltip) this.#pwTooltip.style.visibility = 'hidden'

    //             // input.removeAttribute('style')
    //             // if (this.#tooltip?.hasAttribute('style')) this.#tooltip.removeAttribute('style')
    //             this.#count = filterArrayToRetrieveUniqueValues(this.#count, input, 'input')
    //         }
    //         console.log(this.#count)
    //         console.log("text alert => ", this.#alertText)
    //         console.log("innerText => ", this.#alert.innerText)
    //         if (this.#alertText !== null && undefined) {
    //             // !! IMPORTANT !! In case of submit and an error occured
    //         // if (this.#count.length === 0 && (this.#email.classList.contains('input_error') || this.#name.classList.contains('input_error'))) {
    //             this.#alert.innerText = this.#alertText
    //             this.#alertText = null
    //             console.log('je reset le texte')
    //         } else if (this.#count.length === 0) {
    //             // if (this.#error.length === 0) {
    //             this.#alert.classList.add(this.#hiddenClass)
    //             this.#alert.innerText = ''
    //             this.#formButton.disabled = false
    //             console.log('ca fail je reset car count 0')
    //             // input.classList.add("valid_input")
    //         } else {
    //             console.log('ca fail display de la last error')
    //             this.#alert.innerText = this.#count[this.#count.length - 1].alert
    //         }
    //     }, (this.debounceDelay)))
    // }

    /**
     * Si le conteneur d'icône (valide / invalide) input n'existe pas,
     * Il sera créé et rajouté à l'élément -
     * @param {HTMLInputElement} input 
     */
    #createIconContainer(input) {
        this.#inputsNotToAppend = Array.from(document.querySelectorAll(this.options.inputsNotToAppendIcon ? this.options.inputsNotToAppendIcon : inputsNotToAppend))
        // const noIconInput = this.activeInput(this.options.inputsNotToAppendIcon, input)
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
    }

    /**
     * Permet de créer un tooltip pour le mot de passe -
     * Il indiquera les prérequis pour le strong password -
     * @param {HTMLElement} element - L'élément sur lequel il sera rataché
     * @returns {HTMLElement} tooltip
     */
    #createTooltipContainer(element) {
        const noTooltipInput = this.activeInput(this.#inputsNotToAppend, element)
        let tooltip = document.querySelector('#tooltip-pw')
        if (!tooltip) {
            if (element.id !== noTooltipInput.id) {
                tooltip = createElement('div', {
                    class: 'tooltiptext',
                    id: 'tooltip-pw'
                })
                const context = createElement('p', {
                    style: 'color: #ffa500;'
                })
                context.innerText = 'Votre mot de passe doit contenir au moins :'
                const lowerCase = createElement('p', { id: 'lowercase' })
                lowerCase.innerText = '1 minuscule'
                const upperCase = createElement('p', { id: 'uppercase' })
                upperCase.innerText = '1 majuscule'
                const digit = createElement('p', { id: 'digit' })
                digit.innerText = '1 chiffre'
                const specialChar = createElement('p', { id: 'specialchar' })
                specialChar.innerText = '1 caractère spécial'
                const length = createElement('p', { id: 'length' })
                length.innerText = 'Il doit contenir entre 8 et 128 caractères'
                tooltip.append(context, lowerCase, upperCase, digit, specialChar, length)
                element.insertAdjacentElement(
                    'afterend',
                    tooltip
                )
            }
        }
        return tooltip
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
        this.#addErrorClass(element)
        if (element === this.#password) this.#pwdRepeat.classList.add(this.#inputErrorClass)
        if (element === this.#pwdRepeat) this.#password.classList.add(this.#inputErrorClass)
    }

    /**
     * Vérifie que le caractère d'une input est autorisé
     * @param {EventTarget} input
     * @returns 
     */
    #charsNotAllowed(input) {
        console.log(input)
        if ((!input.allowSpecialCharacters || input.allowSpecialCharacters == undefined) && !this.#allowedSpecialChars.test(input.value)) {
            // Retrieve every character that isn't allowed and only unique entries
            console.log(input)
            this.#wrongInput = retrieveUniqueNotAllowedCharFromRegex(input.value, this.#allowedSpecialChars)
            input.isCharAllowed = false
            // this.#isCharAllowed = false
            return
        }
        input.isCharAllowed = true
        console.log(input.allowSpecialCharacters)
            // this.#isCharAllowed = true
        return
    }

    /**
     * Vérifie que les conditions de strong password soient réunies.
     * Rajoute une nouvelle propriété à l'input.
     * @param {HTMLElement} input
     * @param {PropertyKey} inputProperty - Vérifie que la clé existe et est true.
     * @param {String} newProperty - Ajoute cette nouvelle propriété à l'input.
     * @returns
     */
    #validateThisPassword(input, inputProperty, newProperty) {
        if (inputProperty) {
            const erreurs = []
            let lowerCase = document.querySelector('#lowercase')
            let upperCase = document.querySelector('#uppercase')
            let digit = document.querySelector('#digit')
            let specialChar = document.querySelector('#specialchar')
            let length = document.querySelector('#length')
            if (!strongPwLowerCaseInputRegex.test(input.value)) {
                this.#addErrorClass(lowerCase)
                erreurs.push("Le mot de passe doit contenir au moins une lettre minuscule.")
            } else {
                this.#setValidClass(lowerCase)
            }
            if (!strongPwUpperCaseInputRegex.test(input.value)) {
                this.#addErrorClass(upperCase)
                erreurs.push("Le mot de passe doit contenir au moins une lettre majuscule.")
            } else {
                this.#setValidClass(upperCase)
            }
            if (!strongPwDigitInputRegex.test(input.value)) {
                this.#addErrorClass(digit)
                erreurs.push("Le mot de passe doit contenir au moins un chiffre.")
            } else {
                this.#setValidClass(digit)
            }
            if (!strongPwSpecialCharInputRegex.test(input.value)) {
                this.#addErrorClass(specialChar)
                erreurs.push("Le mot de passe doit contenir au moins un caractère spécial.")
            } else {
                this.#setValidClass(specialChar)
            }
            if (!strongPwLengthInputRegex.test(input.value)) {
                this.#addErrorClass(length)
                erreurs.push("Le mot de passe doit contenir entre 8 et 128 caractères.")
            } else {
                this.#setValidClass(length)
            }
            input[newProperty] = false
            if (erreurs.length > 0) return
            // if (erreurs.length > 0) return false
        }
        // if (inputProperty && !RegExp.test(input.value)) {
        //     // Retrieve every character that isn't allowed and only unique entries
        //     console.log(input)
        //     // this.#wrongInput = retrieveUniqueNotAllowedCharFromRegex(input.value, this.#allowedSpecialChars)
        //     input[newProperty] = false
        //     // this.#isCharAllowed = false
        //     console.log(input.value)
        //     return
        // }
        input[newProperty] = true
            // this.#isCharAllowed = true
        return
        // return true
    }

    /**
     * Permet d'ajouter la classe d'erreur définie dans le fichier config
     * Cela supprimera la classe de validation
     * @param {HTMLElement} input 
     */
    #addErrorClass(input) {
        input.classList.add(this.#inputErrorClass)
        input.classList.remove(this.#inputValidClass)
    }

    /**
     * Permet d'ajouter la classe de validation définie dans le fichier config
     * Cela supprimera la classe d'erreur
     * @param {HTMLElement} input 
     */
    #setValidClass(input) {
        input.classList.remove(this.#inputErrorClass)
        input.classList.add(this.#inputValidClass)
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
            input.value = ''
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
        // if (input.value !== '' || !input.value) return
        if (!input.canBeEmpty && (input.value.toString().trim() === '' || !input.value)) {
            // this.#isEmpty = true
            input.isEmpty = true
            // return false
        } else {
            // this.#isEmpty = false
            input.isEmpty = false
            input.classList.add("valid_input")
        }
        return
        // return true
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

    #validateInputFormat(input) {

    }

    /**
     * Vérifie que les inputs password & pwdRepeat soient similaires -
     * Créera une border rouge si ce n'est pas le cas -
     * Un status "#pwStatus" sera créé -
     */
    #isExactPassword() {
        if (this.#password && this.#pwdRepeat) {
            if (((this.#password.value !== this.#pwdRepeat.value) || (this.#pwdRepeat.value !== this.#password.value)) || this.#password.isEmpty || this.#pwdRepeat.isEmpty || !this.#password.value || !this.#pwdRepeat.value) {
                this.#pwStatus = false
                this.#password.classList.remove('valid_input')
                this.#pwdRepeat.classList.remove('valid_input')
            } else {
                this.#pwStatus = true
                this.#setValidClass(this.#password)
                this.#setValidClass(this.#pwdRepeat)
                // Retrieve only 1 type au char to display to the user
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
            if (!this.#isInputChecked(form)) {
                form.preventDefault()
                console.log('je refuse')
                this.#formButton.disabled = true
                return
            }
            this.#formButton.disabled = false
        } catch (error) {
            console.log(error)
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
    #isInputChecked(event) {
        console.log(event)
        // event.preventDefault()
        let isArray = false
        let target = []
        
        if (event instanceof HTMLElement) target = event
        if (event instanceof (NodeList || Array)) {
            for (const element of event) {
                target.push(element)
            }
            isArray = true
        } else {
            isArray = false
            target = event.target
        }

        // console.log(target)

        const arrayKey = []
        const errors = []
        const data = new FormData(this.#form)
        // Permet de définir quelle input peut-être vide
        // Permet de définir aussi quelle input peut contenir des caractères spéciaux
        // Par défaut : aucune
        // console.log(data.entries())
        for (const [key, value] of data) {
            // Setting default option values
            // console.log(key, value)
            arrayKey[key] = { value: value.toString().trim(), canBeEmpty: this.canBeEmpty, allowSpecialCharacters: this.allowSpecialCharacters }
            // Setting which input can be empty
            if (!this.options.canBeEmpty) {
                setObjectPropertyTo(this.options.whichInputCanBeEmpty, arrayKey[key], key, 'canBeEmpty', true)
            }
            // Setting which input can accept special char
            setObjectPropertyTo(this.options.whichInputAllowSpecialCharacters, arrayKey[key], key, 'allowSpecialCharacters', true)
            // Setting strong password option
            if (key.includes('Mot de Passe' || 'Mot de Passe de confirmation')) {
                setObjectPropertyTo(this.options.strongPassword, arrayKey[key], key, 'strongPassword', true)
            }
            // console.log(this.#listenInputs)
            // Setting input ID related to the data
            for (let i = 0; i < this.#listenInputs.length; i++) {
                // console.log(this.#listenInputs[i].name)
                if (key.includes(this.#listenInputs[i].name)) {
                    // console.log(key)
                    setObjectPropertyTo(true, arrayKey[key], key, 'id', `#${this.#listenInputs[i].id}`)
                }
            }
        }
        // console.log(arrayKey)
        // console.log(event)
        
        for (const key in arrayKey) {
            let element
            if (isArray) {
                for (const el of target) {
                    element = Array.from(el.querySelector(arrayKey[key].id)).filter(t => t)
                }
                // target.forEach(el => {
                //     element = el.querySelector(arrayKey[key].id)
                // })
            } else {
                element = target.querySelector(arrayKey[key].id)
            }
            // if (!this.#isEmptyInputs(element)) {
            //     this.#displayErrorMessage(this.#emptyAlert, element)
            //     errors.push(this.#emptyAlert)
            //     // return false
            // }
            // if (key.includes('Mot de Passe' || 'Mot de Passe de confirmation')) {
            //     if (!this.#validateThisPassword(arrayKey[key], arrayKey[key].strongPassword, 'isValidPassword')) {
            //         this.#displayErrorMessage(this.#invalidPwMessage, element)
            //         errors.push(this.#invalidPwMessage)
            //         // return false
            //     }
            //     // } else {
            //     //     return true
            //     // }
            // }
            // this.#isExactPassword()
            // if (!this.#pwStatus) {
            //     this.#displayErrorMessage(this.#notIdenticalPasswords, element)
            //     errors.push(this.#notIdenticalPasswords)
            //     // return false
            // }
            // // console.log(arrayKey[key])
            // if (errors.length > 0) {
            //     console.log('length ++')
            //     return false
            // } else {
            //     return true
            // }
            // this.#dynamicCheck(element)
            if (element) {
                console.log(element)
                const validation = this.#dynamicCheck(element)
                console.log(validation)
                errors.push(validation)
            }
        }
        if (errors.includes(false)) {
            return false
        } else {
            return true
        }
    }

    /**
     * Vérifie un groupe d'inputs et renvoie True / False -
     * Trim toutes les inputs trouvées et les convertis dans un nouvel Array -
     * Il faut utiliser le nom de l'input pour la récupérer dans le cas où l'on souhaite
     * faire quelque chose de spécifique avec -
     * Ce script est bloquant -
     * @returns
     */
    #checkBatchOfInputs(array) {
        const arrayKey = []
        const errors = []
        const data = new FormData(this.#form)
        // Permet de définir quelle input peut-être vide
        // Permet de définir aussi quelle input peut contenir des caractères spéciaux
        // Par défaut : aucune
        for (const [key, value] of data) {
            // Setting default option values
            arrayKey[key] = { value: value.toString().trim(), canBeEmpty: this.canBeEmpty, allowSpecialCharacters: this.allowSpecialCharacters }
            // Setting which input can be empty
            if (!this.options.canBeEmpty) {
                setObjectPropertyTo(this.options.whichInputCanBeEmpty, arrayKey[key], key, 'canBeEmpty', true)
            }
            // Setting which input can accept special char
            setObjectPropertyTo(this.options.whichInputAllowSpecialCharacters, arrayKey[key], key, 'allowSpecialCharacters', true)
            // Setting strong password option
            if (key.includes('Mot de Passe' || 'Mot de Passe de confirmation')) {
                setObjectPropertyTo(this.options.strongPassword, arrayKey[key], key, 'strongPassword', true)
            }
            // Setting input ID related to the data
            for (let i = 0; i < this.#listenInputs.length; i++) {
                if (key.includes(this.#listenInputs[i].name)) {
                    setObjectPropertyTo(true, arrayKey[key], key, 'id', `#${this.#listenInputs[i].id}`)
                }
            }
        }
        for (const key in arrayKey) {
            if (!arrayKey[key].id) continue
            for (const element of array) {
                if (!element) continue
                let elementToCheck = this.#findElement(element, arrayKey[key].id.split('#')[1])
                // console.log(elementToCheck)
                if (elementToCheck) {
                    const validation = this.#dynamicCheck(elementToCheck)
                    errors.push(validation)
                }
            }
        }
        if (errors.includes(false)) {
            return false
        } else {
            return true
        }
    }

    // Fonction récursive pour trouver l'élément avec l'ID correspondant
    #findElement(node, id) {
        if (node.id === id) {
            return node
        }
        
        for (const child of node.childNodes) {
            const found = this.#findElement(child, id)
            if (found) {
                return found
            }
        }
        return null
    }
    // #isInputChecked() {
    //     let arrayKey = []
    //     let count = 0
    //     let specialCount = 0
    //     const data = new FormData(this.#form)
    //     // Permet de définir quelle input peut-être vide
    //     // Permet de définir aussi quelle input peut contenir des caractères spéciaux
    //     // Par défaut : aucune
    //     for (const [key, value] of data) {
    //         // Setting default option values
    //         arrayKey[key] = { value: value.toString().trim(), canBeEmpty: this.canBeEmpty, allowSpecialCharacters: this.allowSpecialCharacters }
    //         // Setting which input can be empty
    //         setObjectPropertyTo(this.options.whichInputCanBeEmpty, arrayKey[key], key, 'canBeEmpty', true)
    //         // Setting which input can accept special char
    //         setObjectPropertyTo(this.options.whichInputAllowSpecialCharacters, arrayKey[key], key, 'allowSpecialCharacters', true)
    //     }
    //     for (const key in arrayKey) {
    //         if (!arrayKey[key].canBeEmpty && arrayKey[key].value === '') {
    //             this.#error.push(`Veuillez renseigner votre ${key}`)
    //             count++
    //             this.#listenInputs.forEach(input => {
    //                 if (key === input.name) {
    //                     input.classList.add(this.#inputErrorClass)
    //                 }
    //             })
    //         } else {
    //             this.#removeError(`Veuillez renseigner votre ${key}`)
    //         }
    //         if (!arrayKey[key].allowSpecialCharacters && arrayKey[key].value !== '' && !this.#allowedSpecialChars.test(arrayKey[key].value)) {
    //             this.#error.push(`Les caractères spéciaux ne sont pas autorisés pour le ${key}`)
    //             specialCount++
    //             this.#listenInputs.forEach(input => {
    //                 if (key === input.name) {
    //                     input.classList.add(this.#inputErrorClass)
    //                 }
    //             })
    //         } else {
    //             this.#removeError(`Les caractères spéciaux ne sont pas autorisés pour le ${key}`)
    //         }
    //         if (key === 'email') {
    //             if (!this.#emailInputRegex.test(arrayKey[key].value)) {
    //                 this.#email.classList.add(this.#inputErrorClass)
    //                 // this.#email.style.borderBottom = '1px solid red'
    //                 if (!this.#email.classList.contains('form__field')) {
    //                     this.#email.setAttribute('placeholder', 'monEmail@mail.com')
    //                 } else {
    //                     this.#email.setAttribute('placeholder', '')
    //                 }
    //                 this.#error.push(this.#invalidEmailMessage)
    //             } else {
    //                 this.#email.removeAttribute('style')
    //                 this.#removeError(this.#invalidEmailMessage)
    //             }
    //         }
    //     }
    //     // Not identical passwords
    //     if (!this.#pwStatus) {
    //         console.log(this.#pwStatus)
    //         // this.#password.classList.add(this.#inputErrorClass)
    //         this.#error.push(this.#notIdenticalPasswords)
    //     } else {
    //         this.#removeError(this.#notIdenticalPasswords)
    //     }
    //     // Space not allowed
    //     if (this.#spaceNotAllowed) {
    //         this.#error.push(this.#noSpaceAllowedMessage)
    //     } else {
    //         this.#removeError(this.#noSpaceAllowedMessage)
    //     }
    //     // More than 1 error found
    //     if (this.#error.length > 1 && count > 1) {
    //         this.#displayAlertFromArray(this.#error, this.#emptyAlert)
    //         return false
    //     // Only 1 error found
    //     } else if (this.#error.length === 1) {
    //         this.#displayAlertFromArray(this.#error)
    //         return false
    //     // Found errors related to wrong characters
    //     } else if (this.#error.length > 0 && specialCount > 0) {
    //         this.#displayAlertFromArray(this.#error, 'Les caractères spéciaux ne sont pas autorisés')
    //         return false
    //     // No more errors found
    //     } else if (this.#error.length === 0) {
    //         this.#alert.classList.add(this.#hiddenClass)
    //         this.#alert.innerText = ''
    //         return true
    //     }
    // }

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
        return (event) => {
            // if (event instanceof HTMLElement) null
            // if (event instanceof (NodeList || Array)) {
            //     null
            // } else {
                event.preventDefault()
            // }
            return this.#isInputChecked(event)
        }
    }

    /** @returns {Function} */
    get checkBatchOfInputs() {
        return (array) => {
            // array = Array.isArray(array) ? array : [array]
            return this.#checkBatchOfInputs(array)
        }
    }

    /** @returns {Array < String >} */
    get listenedInputs() {
        return this.#listenInputs
    }
}