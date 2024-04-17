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
    #deletedErrors
    #pwStatus = true
    #isEmpty = false

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
                this.#isEmptyInputs(input)
                // if (input === this.#password || this.#pwdRepeat) this.#isExactPassword()
                // this.#isExactPassword(input)
                
                this.#resetInputs(input)
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
        // this.input = input
        // this.#inputs.forEach(input => {
        if (input.value.toString().trim() === '') {
            this.#isEmpty = true
            this.#error.push(input)
            // this.#emptyError.push(input)
            // if (this.#error.filter(t => t !== input)) {
            //     return
            // } else {
            // this.#error = this.#error.filter(t => t === input)
            this.#alert.innerText = 'Un ou plusieurs champs sont vides'
            input.classList.add('input_error')
            // }
            // this.#error = this.#error.filter(t => t === input)
            // this.#emptyError = this.#emptyError.filter(t => t === input)
        } else {
            this.#isEmpty = false
            // if (this.#error.length === 0) this.#isEmpty = false
        }
        if (!this.#isEmpty && this.#pwStatus ) {
            // console.log('mes inputs sont remplies')
            // Pass the removeValue function into the filter function to return the specified value
            // const x = this.#error.filter(this.#removeValue(input))
            // this.#deletedErrors = this.#error.splice(input, 1)
            // this.#error = this.#error.splice(...this.#error, t => t === input)
            // console.log(x)
            // if (input.value.toString().trim() !== '') {
                // this.#alert.innerText = ''
                this.#isExactPassword(input)
                // this.input = input
                // this.#error = this.#error.filter(this.#removeValue.bind(this))
                // this.#error = this.#error.filter(i => i !== input)
                // const x = this.#error.findIndex(idx => idx === input)
                // this.#error.splice(x, 1)
                console.log(this.#error)
                // this.#pwStatus = true
            // }
            // const x = this.#error.findIndex(idx => idx === input.value.toString().trim() !== '')
            // console.log(x)
            // // this.input.classList.remove('input_error')
            // this.#error = this.#error.filter(this.#removeValue.bind(this))
            
            // this.#resetInput(input)
            // this.#error = this.#error.splice(input, 1)
            // this.#error = this.#error.splice(this.#error.filter(t => t === input), 1)
            // this.#error = [...this.#error - this.#deletedErrors]
            // console.log(this.#error.filter(t => t === input))
            // console.log('error après filter => '+this.#error)
            // this.#error = this.#error.slice(this.#error - input)
            // this.#error.splice(input)
            // console.log(this.#error)
            
            // this.#resetInputs(input)
            console.log(this.#error)
            console.log('jai donc delete cette input')
            // this.#alert.innerText = 't'
            // input.classList.remove('input_error')
        }
        if (!this.#isEmpty && this.#pwStatus) {
            this.#error = this.#error.filter(t => t !== input)
        }
    }

    
    #removeValue(value, index, arr) {
        // If the value at the current array index matches the specified value (2)
        if (value === this.input) {
        // Removes the value from the original array
            console.log('jai demandé" un splice')
            console.log(value)
            arr.splice(index, 1)
            return arr
        }
        return arr
    }

    #resetInput(input) {
        console.log('un delete de larray est demandé')
        this.input = input
        this.#error = this.#error.filter(this.#removeValue.bind(this))
    }

    #isExactPassword(input) {
        // if (input !== this.#password || this.#pwdRepeat) return
        if (this.#password.value !== this.#pwdRepeat.value && this.#pwStatus) {
            // this.#error.push(input)
            this.#pwStatus = false
            this.#password.classList.add("input_error")
            this.#pwdRepeat.classList.add("input_error")
            this.#alert.innerText = 'Vos mots de passes de non pas identiques'
            // return false
        } else {
            // this.input = input
            // if (this.#password.value === this.#pwdRepeat.value) {
                // console.log(this.#error)
                this.#pwStatus = true
                // this.#alert.innerText = ''
                // return true
                // this.#error = this.#error.filter(this.#removeValue.bind(this))
                // this.#error = this.#error.filter(pw => pw !== input)
            // this.#password.classList.remove("input_error")
            // this.#pwdRepeat.classList.remove("input_error")
            // }
        }
        // } else {

        // }
        // if (input !== this.#password || this.#pwdRepeat) return
        
    }

    #resetInputs(input) {
        console.log(this.#error)
        if (!this.#isEmpty && this.#pwStatus && this.#error.length === 0) {
        // if (this.#error.length === 0) {
        // if (this.#pwError.length === 0 && this.#emptyError.length === 0) {
            // console.log(this.#error)

            console.log('array empty')
            // this.#status = false
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