import { alertMessage, createElement, debounce, filterArrayToRetrieveUniqueValues, retrieveUniqueNotAllowedCharFromRegex } from "../functions/dom.js"

export class ErrorHandler {

    /** @type {Array} */
    #error = []
    /** @type {HTMLButtonElement} */
    #formButton = document.querySelector('#submit')
    /** @type {HTMLFormElement} */
    #form
    // #formName
    // #formEmail
    // #formAge
    // /** @type {String} */
    // #formPassword
    // /** @type {String} */
    // #formPwdRepeat
    /** @type {HTMLElement} */
    #alert = document.querySelector('.alert-error')
    /** @type {HTMLElement} */
    #tooltip = document.querySelector('.tooltiptext')
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
    #emailInputRegex = new RegExp("([a-z0-9A-Z._-]+)@([a-z0-9A-Z_-]+)\\.([a-z\.]{2,6})$")
    /** @type {RegExpConstructor} */
    // #allowedSpecialChars = '/^[\\w\\s,.:;_?\'!\\"éèêëàâäôöûüùçÀ-]+$/g '
    #allowedSpecialChars = new RegExp('^[\\w\\s,.:;_?\'!\\"*()~&éèêëàâäôöûüùçÀ-]+$')
    /** @type {Array} tested and not allowedSpecialChars char */
    #wrongInput = []
    /** @type {String} */
    #invalidEmailMessage = `Votre email est invalide 
                exemple valide : monEmail@mail.fr`
    /** @type {String} */
    #invalidPwMessage = 'Vos mots de passes ne sont pas identiques'
    /** @type {String} */
    #noSpaceAllowedMessage = 'Veuillez ne pas utiliser d\'espace'
    /** @type {String} */
    #notANumberError = 'Seuls les nombres sont autorisés'
    /** @type {Array} */
    #inputsToListen  = []
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
     * @param {Boolean} [options.useMyOwnListener=false] Permet de définir si l'on souhaite utiliser son propre eventListener dans son script - par défaut : false
     * @param {Boolean} [options.isSpecialCharactersAllowed=false] Permet de définir si l'on souhaite autoriser des caractères spéciaux pour l'input - par défaut : false
     * @param {Object} [options.debounceDelay=1000] Permet de définir un délai après intéraction de l'utilisateur - par défaut : 1s
     */
    constructor(form, options = {}) {
        this.#form = form
        this.options = Object.assign({}, {
            debouncing: true,
            debounceDelay: 100,
            canBeEmpty: false,
            whichInputCanBeEmpty: ['step_3', 'step_4', 'step_5', 'step_6', 'file', 'resting_time'],
            useMyOwnListener: false,
            isSpecialCharactersAllowed: false,
            whichInputAllowSpecialCharacters: ['Mot de Passe', 'Mot de Passe de confirmation', 'Email', 'file'],
        }, options)
        if (!this.#alert) {
            this.#alert = createElement('p', {
                class: 'alert-error'
            })
            // this.#alert = alertMessage(error.message)
            if (this.#form.id !== 'search-form') this.#form.insertAdjacentElement(
                'afterbegin',
                this.#alert
            )
        }
        if (!this.options.debouncing) this.options.debounceDelay = 50
        this.#inputsToListen = 'input, textarea'
        // this.#inputs = `#password, #pwdRepeat, #email, #username, #age,
        // #title, #step_1, #step_2, #step_3, #step_4, #step_5, #step_6,
        // #total_time, #resting_time, #oven_time, #persons`
        this.#listenInputs = Array.from(this.#form.querySelectorAll(this.#inputsToListen))
        
        this.#listenInputs.forEach(input => {
            
            // Creating valid / invalid icon for earch inputs
            let icon

            if (!icon) {
                icon = createElement('span')
                input.insertAdjacentElement(
                    'afterend',
                    icon
                )
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
            // !! ATTENTION !! : Ce script n'est pas bloquant !!
            input.addEventListener('input', debounce((e) => {
                let isEmpty = false
                let isANumber = false
                // console.log('count dans le input au tout début => ', count)
                // let count = 0
                // this.#count = count
                const emptyAlert = 'Un ou plusieurs champs sont vides'
                // console.log(e)
                // Checking if inputs are empty
                isEmpty = this.#isEmptyInputs(e.target)
                // Checking if passwords are same
                this.#isExactPassword()
                // Checking if the character used is allowed
                this.#charsNotAllowed(e)
                isANumber = this.#isANumber(e.target)

                // this.#charsNotAllowed(e.target)
                if (input.id === 'username') this.#isSpaceAllowed(input)
                // console.log('count dans le input après les premiers checks => ', count)
                if (isEmpty) {
                // if (this.#isEmpty && count !== 0) {
                // if (this.#isEmpty && this.#error.length > 1) {
                    // count++
                    // console.log('is empty => ', count)

                    this.#count.push(input)
                    // this.#count = this.#count + count
                    // console.log('global count in is empty => ', this.#count)
                    this.#alert.innerText = emptyAlert
                    input.classList.add('input_error')
                    return
                } else if (input.id !=="file" &&
                    input.id !== "email" &&
                    input.id !== "password" &&
                    input.id !== "pwdRepeat" &&
                    !this.#isCharAllowed) {
                    // let element 
                    // for (let i = 0; i < this.#wrongInput.length; i++) {
                    // }
                    // this.#alert.innerText = `Le caractère " ${
                    //     for (let i = 0; i < this.#wrongInput.length; i++) {
                    //         this.#wrongInput[i]
                    //     }
                    // } " n\'est pas autorisé.`
                    for (let [index, element] of Object.entries(this.#wrongInput)) {
                        this.#wrongInput[index] = `  ${element} `
                    }
                    // count++
                    this.#count.push(input)
                    // this.#count = this.#count + count
                    this.#alert.innerText = `Les caractères suivants ne sont pas autorisés : ${this.#wrongInput} `
                    input.classList.add("input_error")
                    input.style.borderBottom = "1px solid red"
                    // const styledInput = input.value.split('').map((char, index) => {
                    //     if (this.#wrongInput.toString().includes(char)) {
                    //         return `<span class="highlight">${char}</span>`
                    //     }
                    //     // return cshar
                    // }).join('')
                    // input.innerHTML = styledInput
                    // console.log('is charnotAllowed => ', count)
                } else if (input.id === "email" && !this.#emailInputRegex.test(input.value)) {
                    // count++
                    this.#count.push(input)
                    // this.#count = this.#count + count
                    this.#alert.innerText = this.#invalidEmailMessage
                    input.classList.add("input_error")
                    input.style.borderBottom = "1px solid red"
                    // console.log('is emailregex => ', count)
                    return
                } else if (!this.#pwStatus && input.id === "password") {
                    // count++
                    this.#count.push(input)
                    // this.#count = this.#count + count
                    this.#alert.innerText = this.#invalidPwMessage
                    input.classList.add("input_error")
                    // console.log('is pwstatus => ', count)
                    return
                } else if (!this.#pwStatus && input.id === "pwdRepeat") {
                    // count++
                    this.#count.push(input)
                    // this.#count = this.#count + count
                    this.#alert.innerText = this.#invalidPwMessage
                    input.classList.add("input_error")
                    // console.log('is pwstatusRepeat => ', count)
                    return
                } else if (this.#spaceNotAllowed && input.id === 'username') {
                    // count++
                    this.#count.push(input)
                    // this.#count = this.#count + count
                    this.#alert.innerText = this.#noSpaceAllowedMessage
                    input.classList.add("input_error")
                    input.style.borderBottom = "1px solid red"
                    // console.log('is spacenotallowed => ', count)
                    return
                } else if (!isANumber) {
                // } else if ((input.id === "persons" ||
                //     input.id === "total_time" ||
                //     input.id === "resting_time" ||
                //     input.id === "oven_time") && !isANumber) {
                    // count++
                    debugger
                    this.#count.push(input)
                    // this.#count = this.#count + count
                    this.#alert.innerText = this.#notANumberError
                    input.classList.add("input_error")
                    input.style.borderBottom = "1px solid red"
                    console.log('is not a number => ', this.#count)
                    return
                } else {
                    // count -1
                    // console.log('else => ', count)

                    // console.log('global count avant soustraction => ', this.#count)
                    // this.#count === 0 ? null : this.#count = this.#count -1
                    // this.#count = this.#count -1
                    // this.#count = count
                    input.removeAttribute('style')
                    if (this.#tooltip.hasAttribute('style')) this.#tooltip.removeAttribute('style')
                    // console.log('global count => ', this.#count)
                    this.#count = filterArrayToRetrieveUniqueValues(this.#count, input)
                    // console.log('global count => ', this.#count)

                    if (this.#count.length === 0) {
                    // if (this.#error.length === 0) {
                        this.#alert.innerText = ''
                        this.#formButton.disabled = false
                        // input.classList.add("valid_input")
                    }
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
        if (this.options.useMyOwnListener) return
        this.#form.addEventListener('submit', e => {
            this.#onSubmit(e)
            // e.currentTarget.reset()
        })
    }

    /**
     * Vérifie que le caractère d'une input est autorisé
     * @param {EventTarget} inputEvent
     * @returns 
     */
    #charsNotAllowed(inputEvent) {
        if (!this.#allowedSpecialChars.test(inputEvent.target.value) && !this.#isEmpty) {
            // Retrieve every character that isn't allowed and only unique entries
            this.#wrongInput = retrieveUniqueNotAllowedCharFromRegex(inputEvent.target.value, this.#allowedSpecialChars)
            this.#isCharAllowed = false
            inputEvent.target.classList.remove('valid_input')
            return
            // inputEvent.target.parentNode.span = `<span class="highlight">${inputEvent.data}</span>`
            // inputEvent.target.parentNode.firstElementChild.innerHTML = `<span class="highlight">${this.#wrongInput}</span>`
            // console.log(inputEvent.target.parentNode)
            // document.querySelector('.js-text').innerContent = `<span class="highlight">${inputEvent.data}</span>`
        } 
            this.#isCharAllowed = true
            // inputEvent.target.classList.remove('input_error')
            // inputEvent.target.classList.add('valid_input')
        // }
        return
    }

    #isANumber(inputEvent) {
        let isANumber = false
        console.log(inputEvent)
        if ((inputEvent.id === "persons" ||
            inputEvent.id === "total_time" ||
            inputEvent.id === "resting_time" ||
            inputEvent.id === "oven_time") && isNaN(inputEvent.value)) {
            // Retrieve every character that isn't allowed and only unique entries
            this.#isNumber = false
            isANumber = false
            inputEvent.classList.remove('valid_input')
            this.#tooltip.style.visibility = 'visible'
            console.log('test')

            // return
            // inputEvent.target.parentNode.span = `<span class="highlight">${inputEvent.data}</span>`
            // inputEvent.target.parentNode.firstElementChild.innerHTML = `<span class="highlight">${this.#wrongInput}</span>`
            // console.log(inputEvent.target.parentNode)
            // document.querySelector('.js-text').innerContent = `<span class="highlight">${inputEvent.data}</span>`
        // } else {
            return
        }
            this.#isNumber = true
            isANumber = true
        // }
            
            // inputEvent.target.classList.remove('input_error')
            // inputEvent.target.classList.add('valid_input')
        // }
        return isANumber
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
        let isEmpty = false
        if (input.value.toString().trim() === '' || input.value.toString().trim() === ' ') {
            // if (!this.#isEmpty) {
                // count++
                // this.#count++
                this.#isEmpty = true
                isEmpty = true
                // input.classList.remove('valid_input')
                input.classList.remove("valid_input")
                input.classList.add('input_error')
                console.log('je suis vide => ',input.value)
                console.log(input)
            // }
        } else {
            // if (this.#isEmpty) {

            // if (this.#isEmpty && (input.value.toString().trim() !== '' || input.value.toString().trim() === ' ')) {
                // count--
                // this.#count--
                this.#isEmpty = false
                isEmpty = false
                input.classList.remove('input_error')
                input.classList.add("valid_input")
                // input.classList.add('valid_input')
                console.log(input)
                console.log('je suis remplis => ',input.value)
            // }
                
            // if (count === 0) this.#isEmpty = false
        }
        return isEmpty
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
            return
        } else if (!input.value.toString().includes(' ')) {
            this.#spaceNotAllowed = false
            input.classList.remove('input_error')
            return
        }
    }

    /**
     * Supprime une erreur de l'array
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
     * Vérifie que les inputs password & pwdRepeat soient similaires
     * Créera une border rouge si ce n'est pas le cas
     * Un status "#pwStatus" sera créé
     */
    #isExactPassword() {
        if (this.#password?.value !== this.#pwdRepeat?.value) {
            this.#pwStatus = false
            this.#password.style.borderBottom = '1px solid red'
            this.#pwdRepeat.style.borderBottom = '1px solid red'
        } else {
            this.#pwStatus = true
            this.#password?.removeAttribute('style')
            this.#pwdRepeat?.removeAttribute('style')
        }
    }

    /**
     * Le submit n'est pas prevent par défaut -
     * Si une erreur est trouvée, il sera preventDefault et le bouton d'envoi ne sera pas réactivé
     * Les listeners d'inputs n'ajoutent pas d'erreurs à l'#error array
     * Un toaster sera envoyé sous le formulaire si une erreur est trouvée
     * @param {SubmitEvent} form 
     * @returns 
     */
    async #onSubmit(form) {
        // form.preventDefault()
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

    /**
     * Vérifie les inputs et renvoie True / False
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
        // Par défaut : aucune
        for (const [key, value] of data) {
            arrayKey[key] = { value: value.toString().trim(), canBeEmpty: this.canBeEmpty, allowSpecialCharacters: this.allowSpecialCharacters }
            for (const keys of this.options.whichInputCanBeEmpty) {
                if (key === keys) {
                    arrayKey[key].canBeEmpty = true
                }
            }
            for (const keys of this.options.whichInputAllowSpecialCharacters) {
                if (key === keys) {
                    arrayKey[key].allowSpecialCharacters = true
                }
            }
        }
        for (const key in arrayKey) {
            if (!arrayKey[key].canBeEmpty && arrayKey[key].value === '') {
                this.#error.push(`Veuillez renseigner votre ${key}`)
                count++
                this.#listenInputs.forEach(input => {
                    if (key === input.name) {
                        input.classList.add("input_error")
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
                        input.classList.add("input_error")
                    }
                })
            } else {
                this.#removeError(`Les caractères spéciaux ne sont pas autorisés pour le ${key}`)
            }
            if (key === 'email') {
                if (!this.#emailInputRegex.test(arrayKey[key].value)) {
                    this.#email.classList.add("input_error")
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
        if (!this.#pwStatus) {
            // const message = 'Vos mots de passes ne sont pas identiques'
            this.#password.classList.add("input_error")
            this.#error.push(this.#invalidPwMessage)
        } else {
            this.#removeError(this.#invalidPwMessage)
        }
        if (this.#spaceNotAllowed) {
            // const message = 'Veuillez ne pas utiliser d\'espace'
            this.#error.push(this.#noSpaceAllowedMessage)
        } else {
            this.#removeError(this.#noSpaceAllowedMessage)
        }
        if (this.#error.length > 1 && count > 1) {
            for (const error of this.#error) {
                this.#alert.innerText = 'Un ou plusieurs champs doivent être renseignés'
                this.#error = this.#error.filter((t) => t !== error)
            }
            return false
        } else if (this.#error.length === 1) {
            for (const error of this.#error) {
                this.#alert.innerText = error 
                this.#error = this.#error.filter((t) => t !== error)
            }
            return false
        } else if (this.#error.length > 0 && specialCount > 0) {
            for (const error of this.#error) {
                this.#alert.innerText = 'Les caractères spéciaux ne sont pas autorisés'
                this.#error = this.#error.filter((t) => t !== error)
            }
            return false
        } else if (this.#error.length === 0) {
            this.#alert.innerText = '' 
            return true
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

    get listenedInputs() {
        return this.#listenInputs
    }
}