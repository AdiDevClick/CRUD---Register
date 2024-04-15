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
            this.#alert.innerText = 'Un ou plusieurs champs sont vides'
            input.classList.add('input_error')
        } else {
            this.#alert.innerText = ''
            input.classList.remove('input_error')
        }
        console.log(this.#alert.innerText)
    }

    #isExactPassword(input) {
        // if (input !== this.#password || this.#pwdRepeat) return
        if (this.#password.value  !== this.#pwdRepeat.value  ) {
            console.log('test')
            this.#password.classList.add("input_error")
            this.#pwdRepeat.classList.add("input_error")
            this.#alert.innerText = 'Vos mots de passes de non pas identiques'
            console.log(this.#alert.innerText)
        } else {
            console.log('test2')
            this.#password.classList.remove("input_error")
            this.#pwdRepeat.classList.remove("input_error")
            this.#alert.innerText = ''
            console.log(this.#alert.innerText)
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