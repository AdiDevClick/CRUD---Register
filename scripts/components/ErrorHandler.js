import { alertMessage, createElement } from "../functions/dom.js"
// import { createElement } from "../functions/dom.js"

export class ErrorHandler {

    /** @type {Array} */
    #error = []
    /** @type {HTMLButtonElement} */
    #formButton = document.querySelector('button')
    /** @type {HTMLFormElement} */
    #form
    #formName
    #formEmail
    #formAge
    #formPassword
    #formPwdRepeat
    // #alert
    #alert = document.querySelector('.alert-error')
    #data
    #password
    #pwdRepeat
    #email
    #name
    #age
    #inputs  = []
    #status = false
    #pwError = []
    #emptyError = []
    #deletedErrors
    #pwStatus = true
    #isEmpty = false
    #spaceNotAllowed = false

    /**
     * @param {HTMLFormElement} form 
     */
    constructor(form) {
        this.#form = form
        this.#password = this.#form.querySelector('#password')
        this.#pwdRepeat = this.#form.querySelector('#pwdRepeat')
        this.#email = this.#form.querySelector('#email')
        this.#name = this.#form.querySelector('#username')
        this.#age = this.#form.querySelector('#age')
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
        if (this.#email.classList.contains('form__field')) this.#email.setAttribute('placeholder', '')

        this.#inputs = [this.#password, this.#age, this.#pwdRepeat, this.#email, this.#name]
        this.#inputs.forEach(input => {
            input?.addEventListener('input', e => {
                this.#formButton.disabled
                this.#isEmptyInputs(e.currentTarget)
                this.#isExactPassword(e.currentTarget)
                if (this.#isEmpty) {
                    this.#alert.innerText = 'Un ou plusieurs champs sont vides'
                    input.classList.add('input_error')
                } else if (!this.#pwStatus) {
                    this.#alert.innerText = 'Vos mots de passes de non pas identiques'
                    this.#password.classList.add("input_error")
                    this.#pwdRepeat.classList.add("input_error")
                } else if (this.#spaceNotAllowed) {
                    this.#alert.innerText = 'Veuillez ne pas utiliser d\'espace'
                    this.#name.classList.add("input_error")
                } else {
                    this.#alert.innerText = ''
                    this.#formButton.disabled = false
                }
            })
        })


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

    #isEmptyInputs(input) {
        if (input.value.toString().trim() === '') {
            this.#isEmpty = true
            this.#error.push(input)
        } else if (input.value.toString().trim().includes(' ')) {
            this.#spaceNotAllowed = true
            this.#error.push(input)
        } else {
            this.#error = this.#error.filter(t => t !== input)
            this.#resetInputs(input)
        }
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

    // #resetInput(input) {
    //     console.log('un delete de larray est demandé')
    //     this.input = input
    //     this.#error = this.#error.filter(this.#removeValue.bind(this))
    // }

    #isExactPassword(input) {
        if (this.#password?.value !== this.#pwdRepeat?.value) {
            this.#pwStatus = false
        } else {
                this.#pwStatus = true
                // this.#resetInputs(input)
                // this.#error = this.#error.filter(this.#removeValue.bind(this))
        }
    }

    #resetInputs(input) {
        // console.log(this.#error)
        if (this.#isEmpty && this.#error.length === 0) {
            // console.log('object2')
            this.#isEmpty = false
            input.classList.remove('input_error')
        }
        if (this.#spaceNotAllowed && this.#error.length === 0) {
            // console.log('object')
            this.#spaceNotAllowed = false
            input.classList.remove('input_error')
        }
    }

    async #onSubmit(form) {
        const data = new FormData(form.currentTarget)
        this.#formButton.disabled = true
        try {
            if (!this.#isInputChecked(data)) {
                form.preventDefault()
                this.#formButton.disabled = true
                return
            }
            // form.currentTarget.reset()
            this.#formButton.disabled = false
        } catch (error) {
            const alert = alertMessage(error.message)
            this.#form.insertAdjacentElement(
                'beforeend',
                alert
            )
            // this.#formButton.disabled = false
            // alert.addEventListener('close', () => {
            //     // this.#formButton.disabled = false
            //     // alert.removeEventListener('close', (e))
            // }, {once: true})
            // console.log(error)
        }
    }

    #isInputChecked(formDatas) {
        // if (this.#pwStatus && !this.#isEmpty && this.#error.length === 0) {
        //     // this.#formButton.disabled = false
        //     console.log('ok')
        //     return true
        // }
        let inputRegex = new RegExp("[a-z0-9A-Z._-]+@[a-z0-9A-Z_-]+\\.[a-zA-Z]+")
        
        this.#data = new FormData(this.#form)
        this.#formName = this.#data.get('username')?.toString().trim()
        this.#formEmail = this.#data.get('email')?.toString().trim()
        this.#formPassword = this.#data.get('password')?.toString().trim()
        this.#formPwdRepeat = this.#data.get('pwdRepeat')?.toString().trim()
        this.#formAge = this.#data.get('age')?.toString().trim()

        if (this.#formName === '') {
            // console.log("object 2")
            const message = 'Veuillez renseigner votre Nom d\'Utilisateur'
            this.#name.classList.add("input_error")
            // name.setAttribute('placeholder', message)
            this.#error.push(message)
        } else {
            /* debbug area */
            // console.log("object formName vide")
            // this.#error = this.#error.filter((t) => t !== message)
        //     name.classList.remove("input_error")
        //     name.setAttribute('placeholder', 'Votre pseudo...')
        }
        if (!inputRegex.test(this.#formEmail)) {
            // console.log("object 3")
            const message = `Votre email est invalide 
            exemple valide : monEmail@mail.com`
            this.#email.classList.add("input_error")
            if (!this.#email.classList.contains('form__field')) {
                this.#email.setAttribute('placeholder', 'monEmail@mail.com')
            } else {
                this.#email.setAttribute('placeholder', '')
            }
            this.#error.push(message)
        } else {
            /* debbug area */
            // console.log("object  email regext pas ok")
            // this.#error = this.#error.filter((t) => t !== message)
        //     email.classList.remove("input_error")
        //     email.setAttribute('placeholder', 'Votre email...')
        }
        if (this.#formPassword  !== this.#formPwdRepeat || this.#formPassword === '' || this.#formPwdRepeat === '') {
            // console.log("object 4")
            const message = 'Vos mots de passes ne sont pas identiques'
            this.#password.classList.add("input_error")
            this.#pwdRepeat.classList.add("input_error")
            this.#error.push(message)
            
        } else {
            /* debbug area */
            // console.log("object pw pas same")
            // this.#error = this.#error.filter((t) => t !== message)
        //     password.classList.remove("input_error")
        //     pwdRepeat.classList.remove("input_error")
        }
        if (this.#formAge <= 0 || null) {
            // console.log("object 5")
            const message = 'Veuillez renseigner votre âge'
            this.#age.classList.add("input_error")
            this.#error.push(message)
        } else {
            /* debbug area */
            // console.log("object age pas ok")
            // this.#error = this.#error.filter((t) => t !== message)
            
        //     age.classList.remove("input_error")
        }

        if (this.#error.length >= 1) {
            for (const error of this.#error) {
                this.#error = this.#error.filter((t) => t !== error)
                error.includes('Votre email est invalide') ? this.#alert.innerText = error : this.#alert.innerText = 'Un ou plusieurs champs sont vides'
                
                // this.#alert.innerText = 'Un ou plusieurs champs sont vides'
        //         const errorElement = alertMessage(error)
        //         this.#form.insertAdjacentElement(
        //             'beforebegin',
        //             errorElement
        //         )
        //         errorElement.addEventListener('close', () => {
        //             this.#formButton.disabled = false
        //             // errorElement.removeEventListener('close',(e) )
        //         }, {once: true})
            }
            return false
        } else {
            return true
        }
    }
}