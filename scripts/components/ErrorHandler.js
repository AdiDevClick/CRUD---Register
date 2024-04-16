import { alertMessage, createElement } from "../functions/dom.js"

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
            // const alert = alertMessage(error.message)
            this.#form.insertAdjacentElement(
                'beforebegin',
                this.#alert
            )
        }

        this.#inputs = [this.#password, this.#age, this.#pwdRepeat, this.#email, this.#name]

        this.#data = new FormData(this.#form)
        console.log(this.#inputs)
        this.#formName = this.#data.get('username').toString().trim()
        this.#formEmail = this.#data.get('email').toString().trim()
        this.#formPassword = this.#data.get('password').toString().trim()
        this.#formPwdRepeat = this.#data.get('pwdRepeat').toString().trim()
        this.#formAge = this.#data.get('age').toString().trim()
        console.log(this.#formPassword)
        console.log(this.#formEmail)
        console.log(this.#formName)
        console.log(this.#formPwdRepeat)
        console.log(this.#formAge)
        
        console.log(this.#password.value)

        this.#inputs.forEach(input => {
            input.addEventListener('input', e => {
                this.#isEmptyInputs(e.currentTarget)
                // if (input === this.#password || this.#pwdRepeat) this.#isExactPassword()
                this.#isExactPassword(e.currentTarget)
                // return this.#alert
                this.#resetInputs(input)
            })
        })

        this.#password.addEventListener('input', e => {
            // e.preventDefault()
            console.log(this.#password.value)
        })
        this.#pwdRepeat.addEventListener('keydown', e => {
            // e.preventDefault()
            console.log(e.key)
        })
        this.#email.addEventListener('keydown', e => {
            // e.preventDefault()
            console.log(e.key)
        })
        this.#name.addEventListener('keydown', e => {
            // e.preventDefault()
            console.log(e.key)
        })

        this.#age.addEventListener('keydown', e => {
            // e.preventDefault()
            console.log(e.key)
        })

        this.#form.addEventListener('submit', e => {
            e.preventDefault()
            this.#onSubmit(e.currentTarget)
        })
    }

    #isEmptyInputs(input) {
        // let message = ''
        console.log(input.value)
        // this.#inputs.forEach(input => {
        if (input.value.toString().trim() === '') {
            // this.#status = true
            // this.#emptyError.push(input)
            this.#error.push(input)
            this.#alert.innerText = 'Un ou plusieurs champs sont vides'
            input.classList.add('input_error')
            // this.#error = this.#error.filter(t => t === input)
            // this.#emptyError = this.#emptyError.filter(t => t === input)

            // console.log(this.#error)
        } else {
            console.log(this.#error)
            // Pass the removeValue function into the filter function to return the specified value
            // const x = this.#error.filter(this.#removeValue(input))

            // this.#error = this.#error.splice(...this.#error, t => t === input)
            console.log("jai reduit dans le empty")
            // console.log(x)
            // this.#error = this.#error.filter(t => t !== input)

            // this.#error.splice(input)
            // console.log(this.#error)
            // this.#status = false
            // this.#alert.innerText = 't'
            // input.classList.remove('input_error')
        }
        // return this.#status
    }

    
    #removeValue(input, value, index, arr) {
        // If the value at the current array index matches the specified value (2)
        if (value === input) {
        // Removes the value from the original array
            arr.splice(index, 1)
            return true
        }
        return false
    }



    #isExactPassword(input) {
        // if (input !== this.#password || this.#pwdRepeat) return
        if (this.#password.value  !== this.#pwdRepeat.value  ) {
            // this.#status = true
            this.#error.push(input)
            // this.#pwError.push(input)
            this.#password.classList.add("input_error")
            this.#pwdRepeat.classList.add("input_error")
            this.#alert.innerText = 'Vos mots de passes de non pas identiques'
            // console.log(this.#alert.innerText)
            // console.log(this.#error)
            // this.#pwError = this.#pwError.filter(t => t === input)
        } else {
            // this.#error.splice(input)
            console.log(this.#error.filter(t => t !== input))
            // this.#error = this.#error.filter(t => t !== input)
            // this.#pwError = this.#pwError.filter(t => t === input)
            // this.#error = this.#error.splice(...this.#error, t => t === input)
            console.log("jai reduit dans le password")
            console.log(this.#error)

            // console.log(this.#error)
            // this.#status = false
            // console.log('test2')
            // this.#password.classList.remove("input_error")
            // this.#pwdRepeat.classList.remove("input_error")
            // this.#alert.innerText = 'e'
            // console.log(this.#alert.innerText)
        }
        // return this.#status
    }

    #resetInputs(input) {
        if (this.#error.length === 0) {
        // if (this.#pwError.length === 0 && this.#emptyError.length === 0) {
            // console.log(this.#error)
            // this.#status = false
            this.#error = [b]
            this.#alert.innerText = 't'
            input.classList.remove('input_error')
        }
    }

    async #onSubmit(form) {
        const data = new FormData(form)
        this.#formButton.disabled = true
        try {
            if (!this.#isInputChecked(data)) {
                this.#formButton.disabled = false
                return
            }
            // form.reset()
            this.#formButton.disabled = false
        } catch (error) {
            const alert = alertMessage(error.message)
            this.#form.insertAdjacentElement(
                'beforeend',
                alert
            )
            this.#formButton.disabled = false
            alert.addEventListener('close', () => {
                this.#formButton.disabled = false
                // alert.removeEventListener('close', (e))
            }, {once: true})
        }
    }

    #isInputChecked(formDatas) {
        
        // this.#formName = formDatas.get('username').toString().trim()
        // this.#formEmail = formDatas.get('email').toString().trim()
        // this.#formComment = formDatas.get('body').toString().trim()
        this.#formName = formDatas.get('username').toString().trim()
        this.#formEmail = formDatas.get('email').toString().trim()
        this.#formPassword = formDatas.get('password').toString().trim()
        this.#formPwdRepeat = formDatas.get('pwdRepeat').toString().trim()
        this.#formAge = formDatas.get('age').toString().trim()
        console.log(this.#formPassword)
        console.log(this.#formEmail)
        console.log(this.#formName)
        console.log(this.#formPwdRepeat)
        console.log(this.#age)
        const password = this.#form.querySelector('#password')
        const pwdRepeat= this.#form.querySelector('#pwdRepeat')
        const email = this.#form.querySelector('#email')
        const name = this.#form.querySelector('#username')
        const age = this.#form.querySelector('#age')
        console.log(email)
        console.log(name)
        console.log(password)
        console.log(pwdRepeat)
        console.log(age)
        if (this.#formName === '') {
            const message = 'Veuillez renseigner votre Nom d\'Utilisateur'
            name.classList.add("input_error")
            name.setAttribute('placeholder', message)
            this.#alert.innerHTML = 'test'
            // console.log(this.#alert)
            this.#error.push(message)
        } else {
            name.classList.remove("input_error")
            name.setAttribute('placeholder', 'Votre pseudo...')
        }
        if (this.#formEmail === '') {
            const message = 'Veuillez renseigner votre Email'
            email.classList.add("input_error")
            email.setAttribute('placeholder', message)
            this.#error.push(message)
        } else {
            email.classList.remove("input_error")
            email.setAttribute('placeholder', 'Votre email...')
        }
        // if (this.#formComment === '') {
        //     const message = "Vous n'avez pas écrit de commentaire"
        //     body.classList.add("error")
        //     body.setAttribute('placeholder', message)
        //     this.#error.push(message)
        // } else {
        //     body.classList.remove("error")
        //     body.setAttribute('placeholder', 'Votre commentaire...')
        // }
        // if (this.#formPassword  !== this.#formPwdRepeat  ) {
        //     const message = 'Vos mots de passes de non pas identiques'
        //     password.classList.add("input_error")
        //     pwdRepeat.classList.add("input_error")
        //     this.#error.push(message)
        // } else {
        //     password.classList.remove("input_error")
        //     pwdRepeat.classList.remove("input_error")
        // }
        if (this.#age === '') {
            const message = 'Veuillez renseigner votre âge'
            age.classList.add("input_error")
        } else {
            age.classList.remove("input_error")
        }

        if (this.#error.length >= 1) {
            for (const error of this.#error) {
                this.#error = this.#error.filter((t) => t !== error)
                const errorElement = alertMessage(error)
                this.#form.insertAdjacentElement(
                    'beforebegin',
                    errorElement
                )
                errorElement.addEventListener('close', () => {
                    this.#formButton.disabled = false
                    // errorElement.removeEventListener('close',(e) )
                }, {once: true})
            }
            return false
        } else {
            return true
        }
    }
}