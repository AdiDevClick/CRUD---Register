
class ErrorHandler {

    /** @type {Array} */
    #error = []
    /** @type {HTMLButtonElement} */
    #formButton = document.querySelector('button')
    /** @type {HTMLFormElement} */
    #form

    /**
     * @param {HTMLFormElement} form 
     */
    constructor(form) {
        this.#form = form

        this.#form.addEventListener('submit', e => {
            e.preventDefault()
            this.#onSubmit(e.currentTarget)
        })
    }

    async #onSubmit(form) {
        const data = new FormData(form)
        this.#formButton.disabled = true
        try {
            if (!this.#isInputChecked(data)) {
                return
            }
            form.reset()
            this.#formButton.disabled = false
        } catch (error) {
            const alert = alertMessage(error.message)
            this.#target.insertAdjacentElement(
                'beforeend',
                alert
            )
            alert.addEventListener('close', () => {
                this.#formButton.disabled = false
                // alert.removeEventListener('close', (e))
            }, {once: true})
        }
    }

    #isInputChecked(formDatas) {
        this.#formName = formDatas.get('name').toString().trim()
        this.#formEmail = formDatas.get('email').toString().trim()
        this.#formComment = formDatas.get('body').toString().trim()

        const body = this.#form.querySelector('textarea, body')
        const email = this.#form.querySelector('#email')
        const name = this.#form.querySelector('#name')
        if (this.#formName === '') {
            const message = 'Veuillez renseigner votre pseudo'
            name.classList.add("error")
            name.setAttribute('placeholder', message)
            this.#error.push(message)
        } else {
            name.classList.remove("error")
            name.setAttribute('placeholder', 'Votre pseudo...')
        }
        if (this.#formEmail === '') {
            const message = 'Veuillez renseigner votre email'
            email.classList.add("error")
            email.setAttribute('placeholder', message)
            this.#error.push(message)
        } else {
            email.classList.remove("error")
            email.setAttribute('placeholder', 'Votre email...')
        }
        if (this.#formComment === '') {
            const message = "Vous n'avez pas écrit de commentaire"
            body.classList.add("error")
            body.setAttribute('placeholder', message)
            this.#error.push(message)
        } else {
            body.classList.remove("error")
            body.setAttribute('placeholder', 'Votre commentaire...')
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