import { alertMessage, createElement, debounce } from "../functions/dom.js"

export class ErrorHandler {
    
    /** @type {Array} */
    #error = []
    /** @type {HTMLButtonElement} */
    #formButton = document.querySelector('button')
    /** @type {HTMLFormElement} */
    #form
    // #formName
    // #formEmail
    // #formAge
    /** @type {String} */
    #formPassword
    /** @type {String} */
    #formPwdRepeat
    #alert = document.querySelector('.alert-error')
    /** @type {String} */
    #data
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
    // #emailInputRegex = new RegExp("[a-z0-9A-Z._-]+@[a-z0-9A-Z_-]+\\.[a-zA-Z]+")
    /** @type {RegExpConstructor} */
    #emailInputRegex = new RegExp("([a-z0-9A-Z._-]+)@([a-z0-9A-Z_-]+)\\.([a-z\.]{2,6})$")
    /** @type {String} */
    #invalidEmailMessage = `Votre email est invalide 
                exemple valide : monEmail@mail.fr`
    /** @type {Array} */
    #inputs  = []
    /** @type {Boolean} */
    #pwStatus = true
    /** @type {Boolean} */
    #isEmpty = false
    /** @type {Boolean} */
    #spaceNotAllowed = false
    /** @type {Array} */
    #listenInputs = []

    /**
     * @param {HTMLFormElement} form 
     * @param {Boolean} [options.deboucing=true] Permet de définir un délai après intéraction de l'utilisateur - par défaut : 1s
     * @param {Object} [options.debounceDelay=1000] Permet de définir un délai après intéraction de l'utilisateur - par défaut : 1s
     */
    constructor(form, options = {}) {
        this.#form = form
        this.options = Object.assign({}, {
            deboucing: true,
            debounceDelay: 1000
        }, options)
        if (!this.#alert) {
            this.#alert = createElement('p', {
                class: 'alert-error'
            })
            // this.#alert = alertMessage(error.message)
            this.#form.insertAdjacentElement(
                'afterbegin',
                this.#alert
            )
        }

        if (!this.options.deboucing) this.options.debounceDelay = 50
        this.#inputs = 'input, textarea'
        // this.#inputs = `#password, #pwdRepeat, #email, #username, #age,
        // #title, #step_1, #step_2, #step_3, #step_4, #step_5, #step_6,
        // #total_time, #resting_time, #oven_time, #persons`
        this.#listenInputs = Array.from(this.#form.querySelectorAll(this.#inputs))
        this.#listenInputs.forEach(input => {
            if (input.id === 'password') this.#password = input
            if (input.id === 'pwdRepeat') this.#pwdRepeat = input
            if (input.id === 'age') this.#age = input
            if (input.id === 'username') this.#name = input
            if (input.id === 'email') {
                this.#email = input
                if (input.classList.contains('form__field')) input.setAttribute('placeholder', '')
            }
            // Inputs will be debounced @get debounceDelay
            // Les listeners d'inputs n'ajoutent pas d'erreurs à l'#error array
            input.addEventListener('input', debounce((e) => {
                this.#isEmptyInputs(e.target)
                this.#isExactPassword()
                if (input.id === 'username') this.#isSpaceAllowed(input)
                if (this.#isEmpty && this.#error.length > 1) {
                    this.#alert.innerText = 'Un ou plusieurs champs sont vides'
                    input.classList.add('input_error')
                    return
                } else if (input.id === "email" && !this.#emailInputRegex.test(input.value)) {
                    this.#alert.innerText = this.#invalidEmailMessage
                    input.classList.add("input_error")
                    input.style.borderBottom = "1px solid red"
                    return
                } else if (!this.#pwStatus && input.id === "password") {
                    this.#alert.innerText = 'Vos mots de passes ne sont pas identiques'
                    input.classList.add("input_error")
                    return
                } else if (!this.#pwStatus && input.id === "pwdRepeat") {
                    this.#alert.innerText = 'Vos mots de passes ne sont pas identiques'
                    input.classList.add("input_error")
                    return
                } else if (this.#spaceNotAllowed && input.id === 'username') {
                    this.#alert.innerText = 'Veuillez ne pas utiliser d\'espace'
                    input.classList.add("input_error")
                    input.style.borderBottom = "1px solid red"
                    return
                } else {
                    input.removeAttribute('style')
                    if (this.#error.length === 0) {
                        this.#alert.innerText = ''
                        this.#formButton.disabled = false
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

        this.#form.addEventListener('submit', e => {
            this.#onSubmit(e)
            // e.currentTarget.reset()
        })
    }

    #isInvalidInput(inputs) {
        console.log(inputs.currentTarget.value.toString().trim())
        const input = inputs.currentTarget
        if (input === this.#name && input.value.toString().trim().includes(' ')) {
            this.#spaceNotAllowed = true
            this.#error.push(input)
        } else {
            // this.#error = this.#error.filter(t => t !== input)
            // this.#resetInputs(input)
        }
    }

    /**
     * Vérifie qu'une input n'est pas empty
     * @param {EventTarget} input 
     * @returns 
     */
    #isEmptyInputs(input) {
        if (input.value.toString().trim() === '') {
            this.#isEmpty = true
            return
        } else {
            this.#isEmpty = false
            input.classList.remove('input_error')
            return
        }
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
            this.#password.removeAttribute('style')
            this.#pwdRepeat.removeAttribute('style')
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
        this.#formButton.disabled = true
        try {
            // const data = new FormData(form.currentTarget)
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
     * trim toutes les inputs trouvées et les convertis dans un nouvel Array
     * Il faut utiliser le nom de l'input pour la récupérer dans le cas où l'on souhaite
     * faire quelque chose de spécifique avec
     * @returns 
     */
    #isInputChecked() {
        let arrayKey = []
        
        this.#data = new FormData(this.#form)

        for (const [key, value] of this.#data) {
            arrayKey[key] = value.toString().trim()
        }
        for (const key in arrayKey) {
            if (key === 'password') this.#formPassword = arrayKey[key]
            if (key === 'pwdRepeat') this.#formPwdRepeat = arrayKey[key]
            if (key === 'username') {
                if (arrayKey[key] === '') {
                    const message = 'Veuillez renseigner votre Nom d\'Utilisateur'
                    this.#name.classList.add("input_error")
                    this.#error.push(message)
                } else {
                    this.#removeError('Veuillez renseigner votre Nom d\'Utilisateur')
                }
            }
            if (key === 'email') {
                if (!this.#emailInputRegex.test(arrayKey[key])) {
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
            if (key === 'password' || key === 'pwdRepeat') {
                if (arrayKey[key] === '') {
                    const message = 'Veuillez saisir un mot de passe'
                    this.#password.classList.add("input_error")
                    this.#pwdRepeat.classList.add("input_error")
                    this.#error.push(message)
                } else {
                    this.#removeError('Veuillez saisir un mot de passe')
                }
            }
            if (key === 'age') {
                if (arrayKey[key] <= 0 || null) {
                    const message = 'Veuillez renseigner votre âge'
                    this.#age.classList.add("input_error")
                    this.#error.push(message)
                } else {
                    this.#removeError('Veuillez renseigner votre âge')
                }
            }
        }
        if (!this.#pwStatus) {
            const message = 'Vos mots de passes ne sont pas identiques'
            this.#password.classList.add("input_error")
            this.#error.push(message)
        } else {
            this.#removeError('Vos mots de passes ne sont pas identiques')
        }
        if (this.#spaceNotAllowed) {
            const message = 'Veuillez ne pas utiliser d\'espace'
            // this.#password.classList.add("input_error")
            this.#error.push(message)
        } else {
            this.#removeError('Veuillez ne pas utiliser d\'espace')
        }
        if (this.#error.length > 1) {
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
        } else if (this.#error.length === 0) {
            this.#alert.innerText = '' 
            return true
        }
    }

    /** @returns {@param | options.debounceDelay} */
    get debounceDelay() {
        return this.options.debounceDelay
    }
}