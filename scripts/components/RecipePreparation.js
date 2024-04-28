import { fetchJSON } from "../functions/api.js"
import { createElement } from "../functions/dom.js"
import { Toaster } from "./Toaster.js"

export class RecipePreparation {

    #preparations = []
    #listElement
    #element

    constructor(preparations) {
        this.#preparations = preparations
        // this.#preparations.forEach(preparation => {
        //     const newPreparation = new PreparationIngredient(preparation)
        //     this.#listElement.append(preparation.element)
        // })
    }

    appendTo(element) {
        this.#element = element
        this.#listElement = document.querySelector('.js-ingredients-list')

        // this.#listElement = document.querySelector('.list-group')
        this.#preparations.forEach(preparation => {
            const ingredient = new PreparationIngredient(preparation)
            this.#listElement.append(ingredient.element)
        })

        element.querySelectorAll('.add_ingredient button').forEach(button => {
            button.addEventListener('click', e => {
                // this.#onSubmit(e)
                // this.#onClick(e)
                const ingredient = new PreparationIngredient(preparation)
                this.#listElement.append(ingredient.element)
            })
        })

        element.querySelector('form').addEventListener('submit', e => {
            e.preventDefault()
            console.log(e)
            this.#onSubmit(e.currentTarget)
            element.removeEventListener('submit', e)
        })
    }
    
    #onClick(e) {
        // const ids = 
        const ingredients = document.querySelector('.ingredient')
        const button = e.currentTarget
        const select = createElement('select', {
            class: 'select',
            name: 'ingredient',
            id: ''
        })
        const option = createElement('option', {

        })
        ingredients.append(`<select class="select" name="ingredient" id="ingredient3" aria-placeholder="test">
            <option value="empty">-- Choisissez votre ingrédient --</option>
            <option value="oeuf">Oeuf</option>
            <option value="sel">Sel</option>
            <option value="sucres">Sucre</option>
            <option value="beurre">Beurre</option>
        </select>`)
        
        // ingredients.append(ingredient)
    }

    #onUpdate() {
        localStorage.setItem('ingredients', JSON.stringify(this.#preparations))
    }

    #onSubmit(form) {
        const title = new FormData(form).get('persons').toString().trim()
        try {
            if (title === '') {
                throw new Error('Veuillez remplir le champ')
            }
            const data = {
                id: Date.now(),
                title: title,
                completed: false
            }
            const newTodo = new TodoListItem(data)
            this.#listElement.prepend(newTodo.element)
            this.#preparations.push(data)
            this.#onUpdate()
            form.reset()
            const alert = alertMessage('Nouvelle tâche ajoutée avec succès!', 'success')
            const alertWrapper = document.querySelector('.btn-group')
            alertWrapper.insertAdjacentElement(
                'afterend',
                alert
            )
        } catch (error) {
            new Toaster(error, 'Erreur')
        }
    }
}

class PreparationIngredient {
    constructor(ingredient) {
        
    }
}

export class IngredientFormFetch
{

    /** @type {string} */
    #endpoint
    /** @type {HTMLTemplateElement} */
    #template
    /** @type {HTMLElement} */
    #target
    /** @type {object} */
    #elements
    /** @type {object} */
    #element
    /** @type {FormData} */
    #form
    /** @type {FormDataEntryValue} input value */
    #formIngredient
    /** @type {} */
    #list
    /** @type {Array} individual ingredient */
    #ingredientList = []
    /** @type {Array} the whole preparation card list */
    #preparationList = []
    /** @type {Array} error list */
    #error = []
    /** @type {HTMLButtonElement} */
    #formButton
    /** @type {HTMLButtonElement} */
    #formValidationButton

    constructor(form) {
        this.#form = form
        this.#endpoint = form.dataset.endpoint
        this.#template = document.querySelector(form.dataset.template)
        this.#target = document.querySelector(form.dataset.target)
        this.#elements = JSON.parse(form.dataset.elements)
        this.#element = JSON.parse(`{"ingredient": ".js-value"}`)

        this.#formValidationButton = this.#form.querySelector('#button')
        this.#formButton = this.#form.querySelector('#add_custom')

        this.#form.addEventListener('submit', e => {
            e.preventDefault()
            this.#onSubmit(e.currentTarget)
            this.#form.removeEventListener('submit', e)
        })

        this.#formButton.addEventListener('click', e => {
            e.preventDefault()
            this.#onClick(e)
            this.#formButton.removeEventListener('click', e)
        })
        // this.#formValidationButton.addEventListener('click', e => {
        //     e.preventDefault()
        //     this.#onSubmit(this.#form)
        //     this.#formValidationButton.removeEventListener('click', e)
        // })
    }

    appendThis(list) {
        console.log(list)
        const ids = Date.now()
        list.forEach(ingredient => {
            console.log(ingredient)
            const elementTemplate = this.#template.content.firstElementChild.cloneNode(true)
            elementTemplate.setAttribute('id', ids)
            elementTemplate.setAttribute('name', 'ingredient-'+ids)
            // for (const [key, selector] of Object.entries(this.#element)) {
            //     console.log('elements => ' , this.#element)
            //     console.log('list => ' , element)
            //     console.log('key => ', key)
            //     console.log('selector => ' , selector)
            //     elementTemplate.querySelector(selector).innerText = ingredient[key]
            //     // elementTemplate.querySelector(selector).innerText = '#'+list[key]
            // }
            elementTemplate.innerText = ingredient
            // elementTemplate.querySelector('.js-value').innerText = ingredient

            this.#target.prepend(elementTemplate)
        })
    }

    append(list) {
        let newKey
        const ids = Date.now()
        let input = this.#form.querySelector('#custom_ingredient')
        console.log(list)
        list.forEach(element => {
        // for (const element in list[0]) {
            console.log(element)
            const elementTemplate = this.#template.content.firstElementChild.cloneNode(true)
            // elementTemplate.setAttribute('id', ids)
            elementTemplate.setAttribute('id', element.id)
            elementTemplate.setAttribute('name', 'ingredient-'+ids)
            console.log(elementTemplate)
            for (const [key, selector] of Object.entries(this.#elements)) {
                console.log('elements => ' , this.#elements)
                console.log('list => ' , element)
                console.log('key => ', key)
                console.log('selector => ' , selector)
                // console.log('listKey => ' , Object.keys(list[0]).filter(i => { return i.startsWith('ingredient-') }))
                // let objectKey = Object.keys(element).filter(i => {
                //     if (i.startsWith('ingredient-') && key) key = i
                // })
                // console.log(list[0][startsWith(key)] === key)
                // if (objectKey) {
                //     console.log('objectKey')
                    
                    
                //     console.log('newKey')
                // }
                // if (element.startsWith(key+'-')) {
                //     key = key+'-'
                // }
                console.log('element[key] => ' , element[key])
                elementTemplate.querySelector(selector).innerText = element[key]
                // elementTemplate.querySelector(selector).innerText = element[key]
                // elementTemplate.querySelector(selector).innerText = '#'+list[key]
                console.log(elementTemplate.querySelector(selector))
            }
            this.#target.prepend(elementTemplate)
        // }
        })
        
    }

    #onClick(e) {
        const ids = Date.now()
        let input = this.#form.querySelector('#custom_ingredient')
        if (!this.#isInputChecked(input.value)) {
            return
        }
        const elementTemplate = this.#template.content.firstElementChild.cloneNode(true)
        elementTemplate.setAttribute('id', ids)
        elementTemplate.setAttribute('name', 'ingredient-'+ids)
        console.log(elementTemplate)
        for (const [key, selector] of Object.entries(this.#elements)) {
            console.log(this.#elements)
            console.log(selector)
            console.log(key)
            console.log(elementTemplate.querySelector(selector))
            // elementTemplate.querySelector(selector).innerText = this.#list[key]
            elementTemplate.innerText = input.value
        }
        // const ingredients = 
        this.#target.prepend(elementTemplate)
        this.#ingredientList = this.#ingredientList.filter((task) => task !== this.#list)
        // for (const ingredient of this.#ingredientList) {
            // this.#ingredientList.push({'ingredient' : elementTemplate.value})
        this.#ingredientList.push(elementTemplate.innerText)
        // this.#ingredientList.push(elementTemplate.value)
        
            
        // this.#ingredientList[elementTemplate.value] = 'test'
        //     for (let ingredient in this.#ingredientList) {
        //         console.log(ingredient)
        //         console.log(this.#ingredientList[ingredient])
        //         console.log(ingredient['ingredient'])
        //         ingredient['ingredient'] = elementTemplate.value
        //     }
        // this.#ingredientList['ingredient'] = {'elementTemplate.value'}
        this.#onUpdate('ingredients', this.#ingredientList)

        input.value = ''
        // this.#form.querySelector('#custom_ingredient').value = ''
        // this.#onUpdate('test', this.#preparationList)
        // new Toaster('Liste d\'ingrédients validé', 'Succès')
    }

    async #onSubmit(form) {
        // const ids = Date.now()
        const data = new FormData(form)
        this.#formButton.disabled = true
        try {
            // if (!this.#isInputChecked(data)) {
            //     return
            // }
            this.#list = await fetchJSON(this.#endpoint, {
                method: 'POST',
                json: data
            })
            // const elementTemplate = this.#template.content.firstElementChild.cloneNode(true)
            // elementTemplate.setAttribute('id', ids)
            // elementTemplate.setAttribute('name', 'ingredient-'+ids)
            // for (const [key, selector] of Object.entries(this.#elements)) {
            //     elementTemplate.querySelector(selector).innerText = this.#list[key]
            // }
            // // const ingredients = 
            // this.#target.prepend(elementTemplate)
            this.#preparationList = this.#preparationList.filter((task) => task === this.#list)
            this.#preparationList.push(this.#list)
            // this.#ingredientList = this.#ingredientList.filter((task) => task !== this.#list)
            // this.#ingredientList.push(elementTemplate.value)
            // this.#onUpdate('ingredients', this.#ingredientList)
            this.#onUpdate('preparationList', this.#preparationList)
            
            // form.reset()
            // this.#formIngredient = ''
            // this.#form.querySelector('#custom_ingredient').value = ''

            const success = 'Votre préparation a été validée'
            new Toaster(success, 'Succès')
            this.#formButton.disabled = false
        } catch (error) {
            new Toaster(error.message, 'Erreur')
        }
    }

    #onUpdate(storageName, items) {
        localStorage.setItem(storageName, JSON.stringify(items))
    }

    #isInputChecked(formDatas) {
        // this.#formIngredient = formDatas.get('custom_ingredient').toString().trim()
        const body = this.#form.querySelector('#custom_ingredient')
        const inputValue = body.value.toString().trim()
        console.log(body)
        if (inputValue === '') {
        // if (this.#formIngredient === '') {
            const message = "Vous n'avez pas ajouté d'ingrédient"
            body.classList.add("error")
            body.setAttribute('placeholder', message)
            this.#error.push(message)
        } else {
            body.classList.remove("error")
            body.setAttribute('placeholder', 'Votre ingrédient...')
        }

        if (this.#error.length >= 1) {
            for (const error of this.#error) {
                this.#error = this.#error.filter((t) => t !== error)
                new Toaster(error, 'Erreur')
            }
            return false
        } else {
            return true
        }
    }
}