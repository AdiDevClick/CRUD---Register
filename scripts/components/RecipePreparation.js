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
    /** @type {HTMLDivElement} */
    #customIngredients
    #newAttachedItems = []
    newAttachedItems = []
    #creationStatus = false
    #modifyStatus = false
    element = []

    constructor(form) {
        this.#form = form
        this.#endpoint = form.dataset.endpoint
        this.#template = document.querySelector(form.dataset.template)
        this.#target = document.querySelector(form.dataset.target)
        this.#elements = JSON.parse(form.dataset.elements)
        this.#element = JSON.parse(`{"ingredient": ".js-value"}`)

        this.#formValidationButton = this.#form.querySelector('#button')
        this.#formButton = this.#form.querySelector('#add_custom')
        this.#customIngredients = this.#form.querySelectorAll('.custom-ingredient')
        console.log(this.#customIngredients)

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
        let count = 0
        list.forEach(ingredient => {
            console.log(ingredient)
            count = count+1
            const elementTemplate = this.#template.content.firstElementChild.cloneNode(true)
            elementTemplate.setAttribute('id', count)
            elementTemplate.setAttribute('name', 'ingredient-'+count)
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
            // this.#listen(this.#newAttachedItems)
            elementTemplate.addEventListener('click', this.#listen.bind(elementTemplate))
            // this.#listen(elementTemplate)
            // this.element = elementTemplate
            // this.element.addEventListener('click', this.#listen)
        })
    }

    #listen(item) {
        // this.#form.querySelectorAll('.custom-ingredient').forEach(ingredient => {
        let newAttachedItems
        let validationItems
        let creationStatus = false
        let modifyStatus
        let clickStatus
        let validatedStatus
        // let id = newAttachedItems?.element.id

        const ingredient = item.currentTarget
        // const elem = item.currentTarget.querySelector(id)
        console.log('test')
        console.log(item.currentTarget)
        // this.#customIngredients.forEach(ingredient => {
        console.log(newAttachedItems)
        console.log(creationStatus)
        console.log(this.element)
        console.log(item)
        console.log(newAttachedItems)
        // item.removeEventListener('click', this.#listen)
        if (!newAttachedItems) {
            console.log(ingredient)
            newAttachedItems = new AttachmentToThis(ingredient)
            ingredient.append(newAttachedItems.element)
            creationStatus = true
            console.log(newAttachedItems.element.id)
            console.log(newAttachedItems)
        }
        creationStatus = true
        // if (newAttachedItems && clickStatus ) {
        //     console.log('object')
        //     clickStatus = false
        //     item.append(newAttachedItems.element)
        //     // item.removeEventListener('click', e)
        // }

        // if (newAttachedItems && validatedStatus) {
        //     console.log('object')
        //     clickStatus = true
        //     // item.append(newAttachedItems.element)
        //     // item.removeEventListener('click', e)
        // }
        // item.removeEventListener('click', e)
        

        ingredient.addEventListener('delete', e => {
            e.preventDefault()
            const message = `${e.detail.innerText} supprimé avec succès`
            new Toaster(message)
        }, {once: true})

        ingredient.addEventListener('modify', e => {
            e.preventDefault()
            if (!validationItems) {
                newAttachedItems.element.remove()
                validationItems = new UserValidations(ingredient)
                ingredient.append(validationItems.element)
                // this.#creationStatus = true
                // modifyStatus = true
            }
            // item.removeEventListener('click', this.#listen(item))
            console.log('event removed')
            // console.log(item.removeEventListener('click', this.#listen(item)))
            // if (validationItems) {
            //     console.log('modify')
            //     // clickStatus = true
            //     item.append(newAttachedItems.element)
            // }
        }, {once: true})

        ingredient.addEventListener('validate', e => {
            e.preventDefault()
                console.log(validationItems.element)
                validationItems.element.remove()
                modifyStatus = false
                // creationStatus = false
                clickStatus = false
                validatedStatus = true
                // console.log(newAttachedItems.element)
                // validationItems = new UserValidations(item)
                // item.append(validationItems.element)
                // this.#creationStatus = true
                // this.#listen(item.currentTarget)
                // ingredient = ingredient.cloneNode(true);
                // ingredient.addEventListener('click', this.#listen.bind(ingredient), {once: true})
        }, {once: true})

        ingredient.addEventListener('canceled', e => {
            e.preventDefault()
                console.log(validationItems.element)
                validationItems.element.remove()
                // this.#modifyStatus = false
                // console.log(newAttachedItems.element)
                // validationItems = new UserValidations(item)
                // item.append(validationItems.element)
                // this.#creationStatus = true
            // item.removeEventListener('validate', e)
        }, {once: true})
    }
    // #listen(item) {
    //     // this.#form.querySelectorAll('.custom-ingredient').forEach(ingredient => {
    //     let newAttachedItems
    //     let validationItems
    //     let creationStatus
    //     let modifyStatus
    //     let clickStatus
    //     let validatedStatus
    //     console.log('test')
        
    //     // this.#customIngredients.forEach(ingredient => {
    //     item.addEventListener('click', e => {
    //         console.log(newAttachedItems)
    //         e.preventDefault()
    //         if (!newAttachedItems && !creationStatus) {
    //             newAttachedItems = new AttachmentToThis(item)
    //             item.append(newAttachedItems.element)
    //             creationStatus = true
    //             // item.removeEventListener('click', e)
    //         }

    //         if (clickStatus) {
    //             console.log('object')
    //             clickStatus = false
    //             item.append(newAttachedItems.element)
    //             // item.removeEventListener('click', e)
    //         }

    //         if (newAttachedItems && validatedStatus) {
    //             console.log('object')
    //             clickStatus = true
    //             // item.append(newAttachedItems.element)
    //             // item.removeEventListener('click', e)
    //         }
    //         // item.removeEventListener('click', e)
            
    //     })

    //     item.addEventListener('delete', e => {
    //         e.preventDefault()
    //         const message = `${e.detail.innerText} supprimé avec succès`
    //         new Toaster(message)
    //     }, {once: true})

    //     item.addEventListener('modify', e => {
    //         e.preventDefault()
    //         item.removeEventListener('click', e)
    //         if (!validationItems) {
    //             newAttachedItems.element.remove()
    //             validationItems = new UserValidations(item)
    //             item.append(validationItems.element)
    //             // this.#creationStatus = true
    //             // modifyStatus = true
    //         }
            
    //         // if (validationItems) {
    //         //     console.log('modify')
    //         //     // clickStatus = true
    //         //     item.append(newAttachedItems.element)
    //         // }
    //     }, {once: true})

    //     item.addEventListener('validate', e => {
    //         e.preventDefault()
    //             console.log(validationItems.element)
    //             validationItems.element.remove()
    //             modifyStatus = false
    //             creationStatus = false
    //             clickStatus = false
    //             validatedStatus = true
    //             // console.log(newAttachedItems.element)
    //             // validationItems = new UserValidations(item)
    //             // item.append(validationItems.element)
    //             // this.#creationStatus = true
    //             // this.#listen(item)
    //     }, {once: true})

    //     item.addEventListener('canceled', e => {
    //         e.preventDefault()
    //             console.log(validationItems.element)
    //             validationItems.element.remove()
    //             // this.#modifyStatus = false
    //             // console.log(newAttachedItems.element)
    //             // validationItems = new UserValidations(item)
    //             // item.append(validationItems.element)
    //             // this.#creationStatus = true
    //         // item.removeEventListener('validate', e)
    //     }, {once: true})
    // }

    #onDelete(e) {
        new CustomEvent('delete', {
            detail: e.currentTarget,
            bubbles: true
        })
    }

    #onModify(e) {
        new CustomEvent('modify', {
            detail: e.currentTarget,
            bubbles: true
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
            this.#newAttachedItems = elementTemplate
            this.#listen(this.#newAttachedItems)
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
        // this.#newAttachedItems[elementTemplate] = elementTemplate
        // this.#listen(elementTemplate)
        this.#listen(elementTemplate)
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

export class IngredientsFrom {

    #count
    #list = []
    element = []
    #ingredient = []
    /** @type {HTMLFormElement} */
    #form
    /** @type {string} */
    #endpoint
    /** @type {HTMLTemplateElement} */
    #template
    /** @type {HTMLElement} */
    #target
    /** @type {object} */
    #elements
    /** @type {HTMLButtonElement} */
    #formButton
    /** @type {HTMLButtonElement} */
    #formValidationButton
    /** @type {HTMLDivElement} */
    #customIngredients
    /** @type {Array} individual ingredient */
    #ingredientList = []
    /** @type {Array} the whole preparation card list */
    #preparationList = {}
    /** @type {Array} error list */
    #error = []

    constructor(list) {
        this.#list = list
        // this.#template = document.querySelector('#ingredient-template')
        // this.#target = document.querySelector(".js-ingredient-group")


        // this.#form = form
        
        console.log(this.#customIngredients)
    }

    append(formElement) {
        this.#form = formElement
        this.#endpoint = this.#form.dataset.endpoint
        this.#template = document.querySelector(this.#form.dataset.template)
        this.#target = document.querySelector(this.#form.dataset.target)
        this.#elements = JSON.parse(this.#form.dataset.elements)
        // this.#element = JSON.parse(`{"ingredient": ".js-value"}`)

        this.#formValidationButton = this.#form.querySelector('#button')
        this.#formButton = this.#form.querySelector('#add_custom')
        this.#customIngredients = this.#form.querySelectorAll('.custom-ingredient')


        // let count = 0
        this.#count = 0
        this.#list.forEach(ingredient => {
            const savedIngredient = new Ingredient(ingredient)
            this.#count = this.#count+1
            // count = count+1
            this.#ingredient = savedIngredient.element
            this.#ingredient.setAttribute('id', this.#count)
            // this.#ingredient.setAttribute('id', count)
            this.#ingredient.setAttribute('name', 'ingredient-'+this.#count)
            // this.#ingredient.setAttribute('name', 'ingredient-'+count)
            this.#target.prepend(this.#ingredient)
            this.#onIngredientDelete(this.#ingredient)

            // this.#ingredient.addEventListener('delete', ({detail: ingredient}) => {
            //     console.log(ingredient.innerText)
            //     console.log(this.#ingredientList)
            //     this.#ingredientList = this.#list.filter((t) => t !== ingredient)
                // this.#onUpdate('ingredients', this.#list)
            // })
        })
        // this.#target.addEventListener('click', this.#listen, {once: true})
        // this.#ingredient.addEventListener('click', this.#listen)
        this.#form.addEventListener('submit', e => {
            e.preventDefault()
            this.#onSubmit(e.currentTarget)
        })

        this.#formButton.addEventListener('click', this.#addNewIngredient.bind(this))
    }

    #addNewIngredient(e) {
        e.preventDefault()
        this.#count = this.#count+1
        let input = this.#form.querySelector('#custom_ingredient')
        if (!this.#isInputChecked(input.value)) {
            return
        }
        // const elementTemplate = this.#template.content.firstElementChild.cloneNode(true)
        
        // elementTemplate.setAttribute('name', 'ingredient-'+ids)
        // console.log(elementTemplate)
        // for (const [key, selector] of Object.entries(this.#elements)) {
        //     console.log(this.#elements)
        //     console.log(selector)
        //     console.log(key)
        //     console.log(elementTemplate.querySelector(selector))
        //     // elementTemplate.querySelector(selector).innerText = this.#list[key]
        //     elementTemplate.innerText = input.value
        // }
        // const ingredients = 
        this.#ingredient = new Ingredient(input.value)
        this.#ingredient.element.setAttribute('id', this.#count)
        this.#ingredient.element.setAttribute('name', 'ingredient-'+this.#count)
        this.#target.prepend(this.#ingredient.element)
        // this.#newAttachedItems[elementTemplate] = elementTemplate
        // this.#listen(elementTemplate)
        // this.#listen(elementTemplate)
        // this.#ingredientList = this.#ingredientList.filter((task) => task !== this.#list)
        // for (const ingredient of this.#ingredientList) {
            // this.#ingredientList.push({'ingredient' : elementTemplate.value})
        this.#list.push(this.#ingredient.element.innerText)
        // this.#ingredientList.push(elementTemplate.value)
        
            
        // this.#ingredientList[elementTemplate.value] = 'test'
        //     for (let ingredient in this.#ingredientList) {
        //         console.log(ingredient)
        //         console.log(this.#ingredientList[ingredient])
        //         console.log(ingredient['ingredient'])
        //         ingredient['ingredient'] = elementTemplate.value
        //     }
        // this.#ingredientList['ingredient'] = {'elementTemplate.value'}
        this.#onIngredientDelete(this.#ingredient.element)
        this.#onUpdate('ingredients', this.#list)

        input.value = ''
        // this.#form.querySelector('#custom_ingredient').value = ''
        // this.#onUpdate('test', this.#preparationList)
        // new Toaster('Liste d\'ingrédients validé', 'Succès')
        this.#formButton.removeEventListener('click', this.#addNewIngredient.bind(this))
    }

    #onIngredientDelete(ingredient) {
        ingredient.addEventListener('delete', e => {
            this.#list = this.#list.filter((i) => i !== e.detail.innerText)
            this.#onUpdate('ingredients', this.#list)
        })
    }

    async #onSubmit(form) {
        console.log(form)
        // const ids = Date.now()
        const data = new FormData(form)
        this.#formButton.disabled = true
        try {
            // if (!this.#isInputChecked(data)) {
            //     return
            // }
            this.#ingredientList = await fetchJSON(this.#endpoint, {
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
            console.log(this.#ingredientList)
            console.log(this.#preparationList)
            // this.#preparationList = this.#preparationList.filter((task) => task === this.#list)
            this.#preparationList.formData = this.#ingredientList
            // this.#preparationList.push(this.#ingredientList)
            this.#preparationList.ingredients = this.#list
            // this.#preparationList.push(this.#list)
            // this.#ingredientList = this.#ingredientList.filter((task) => task !== this.#list)
            // this.#ingredientList.push(elementTemplate.value)
            // this.#onUpdate('ingredients', this.#ingredientList)
            console.log(this.#preparationList)
            this.#onUpdate('preparationList', this.#preparationList)
            
            // form.reset()
            // this.#formIngredient = ''
            // this.#form.querySelector('#custom_ingredient').value = ''

            const success = 'Votre préparation a été validée'
            new Toaster(success, 'Succès')
            this.#formButton.disabled = false
        } catch (error) {
            // console.log(error.message)
            new Toaster(error.message, 'Erreur')
        }
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

    #onUpdate(storageName, items) {
        localStorage.setItem(storageName, JSON.stringify(items))
    }
    
    #listen(item) {
        // this.#form.querySelectorAll('.custom-ingredient').forEach(ingredient => {
        

        let newAttachedItems
        let validationItems
        let creationStatus = false
        let modifyStatus
        let clickStatus
        let validatedStatus
        const ingredient = item.currentTarget
        // this.element = ingredient
        console.log('test')
        console.log(item.currentTarget)
        // this.#customIngredients.forEach(ingredient => {
        console.log(newAttachedItems)
        console.log(creationStatus)
        // this.removeEventListener('click', this.#listen.bind(this))
        if (!newAttachedItems && !creationStatus) {
            newAttachedItems = new AttachmentToThis(ingredient)
            ingredient.append(newAttachedItems.element)
            creationStatus = true
            console.log('g créé')
        }

        // if (newAttachedItems && clickStatus ) {
        //     console.log('object')
        //     clickStatus = false
        //     item.append(newAttachedItems.element)
        //     // item.removeEventListener('click', e)
        // }

        // if (newAttachedItems && validatedStatus) {
        //     console.log('object')
        //     clickStatus = true
        //     // item.append(newAttachedItems.element)
        //     // item.removeEventListener('click', e)
        // }
        // item.removeEventListener('click', e)
        

        ingredient.addEventListener('delete', e => {
            e.preventDefault()
            const message = `${e.detail.innerText} supprimé avec succès`
            new Toaster(message)
        }, {once: true})

        ingredient.addEventListener('modify', e => {
            e.preventDefault()
            if (!validationItems) {
                newAttachedItems.element.remove()
                validationItems = new UserValidations(ingredient)
                ingredient.append(validationItems.element)
                // this.#creationStatus = true
                // modifyStatus = true
            }
            // item.removeEventListener('click', this.#listen(item))
            console.log('event removed')
            // console.log(item.removeEventListener('click', this.#listen(item)))
            // if (validationItems) {
            //     console.log('modify')
            //     // clickStatus = true
            //     item.append(newAttachedItems.element)
            // }
        }, {once: true})

        ingredient.addEventListener('validate', e => {
            e.preventDefault()
                console.log(validationItems.element)
                validationItems.element.remove()
                modifyStatus = false
                // creationStatus = false
                clickStatus = false
                validatedStatus = true
                // console.log(newAttachedItems.element)
                // validationItems = new UserValidations(item)
                // item.append(validationItems.element)
                // this.#creationStatus = true
                // this.#listen(item.currentTarget)
                // ingredient = ingredient.cloneNode(true);
                // ingredient.addEventListener('click', this.#listen.bind(ingredient), {once: true})
            this.element.addEventListener('click', this.#listen.bind(ingredient), {once: true})
        }, {once: true})

        ingredient.addEventListener('canceled', e => {
            e.preventDefault()
                console.log(validationItems.element)
                validationItems.element.remove()
                // this.#modifyStatus = false
                // console.log(newAttachedItems.element)
                // validationItems = new UserValidations(item)
                // item.append(validationItems.element)
                // this.#creationStatus = true
            // item.removeEventListener('validate', e)
        }, {once: true})
    }
}

class Ingredient {

    element = []
    #template
    #count = 0
    #creationStatus = false
    #validationStatus = false
    #done = false
    #ingredient
    #newModifierButtons = []
    #validationItems = []
    #modifyStatus
    #observer = []
    #ratio = .3
    #handleIntersect = (entries, observer) => {
        entries.forEach(entry => {
            console.log(entry)
            this.element.addEventListener('click', this.#onClick.bind(this))
            // if (entry.addEventListener('click') > this.#ratio) {
            //     this.#loadMore()
            // }
            console.log('je suis dans lobs')
        })
    }
    #options = {
        root: null,
        rootMargin: '0px',
        threshold: this.#ratio
    }
    #closed = false

    constructor(ingredient) {
        console.log(ingredient)
        this.#ingredient = ingredient
        this.#template = document.querySelector('#ingredient-template')
        this.#count = this.#count+1

        this.element = this.#template.content.firstElementChild.cloneNode(true)
        // this.element.setAttribute('id', this.#count)
        // this.element.setAttribute('name', 'ingredient-'+this.#count)
        
        this.element.innerText = this.#ingredient

        this.element.addEventListener('click', this.#onClick.bind(this))
        this.element.addEventListener('modify', this.#onModify.bind(this))
        this.element.addEventListener('validate', this.#onValidate.bind(this))
        this.element.addEventListener('canceled', this.#onCancel.bind(this))
        this.element.addEventListener('closeAction', this.#onClose.bind(this))

        this.element.addEventListener('delete', e => {
            e.preventDefault()
            const message = `${e.detail.innerText} supprimé avec succès`
            new Toaster(message)
        }, {once: true})

        // window.addEventListener('DOMContentLoaded', () => {
        //     this.#observer = new IntersectionObserver(this.#handleIntersect, this.#options)
        //     this.#observer.observe(this.element)
        // })
    }

    #onClick(e) {
        e.preventDefault()
        if (this.#validationStatus || this.#closed || this.#done) {
            this.#validationStatus = false
            this.#closed = false
            this.#done = false
            return
        }
        
        if (!this.#newModifierButtons.element) {
            this.#newModifierButtons = new AttachmentToThis(this.element)
            this.element.append(this.#newModifierButtons.element)
        }
    }

    #onModify(e) {
        e.preventDefault()
        if (!this.#validationItems.element) {
            this.#newModifierButtons.element.remove()
            this.#validationItems = new UserValidations(this.element)
            this.element.append(this.#validationItems.element)
            this.#validationStatus = true
        }
    }

    #onValidate(e) {
        e.preventDefault()
        this.#validationItems.element.remove()
        this.#validationStatus = true
        this.#newModifierButtons = []
        this.#validationItems = []
    }
    
    #onCancel(e) {
        e.preventDefault()
        this.#validationItems.element.remove()
        this.#validationItems = []
        this.#newModifierButtons = []
        this.#done = true
    }

    #onClose(e) {
        e.preventDefault()
        this.#closed = true
        this.#newModifierButtons = []
        this.#validationItems = []
    }

    get element() {
        return this.element
    }

    get onClick() {
        return this.#onClick.bind(this)
    }
}

class AttachmentToThis {
    #item
    #element = []
    #modifier
    #deleter
    #closeButton

    constructor(item) {
        this.#item  = item
        if (this.#element.length > 0 ){
            return
        }

        this.#element = createElement('div', {
            class: 'custom-ingredient__interactive-elements',
            id: 'attach-'+this.#item.id
        })

        this.#modifier = createElement('div', {
            class: 'interactive-elements__modify',
            name: 'modify',
            id: 'modify-'+this.#item.id
        })
        this.#deleter = createElement('div', {
            class: 'interactive-elements__delete',
            name: 'delete',
            id: 'delete-'+this.#item.id
        })
        this.#closeButton = createElement('div', {
            class: 'interactive-elements__close',
            name: 'close',
            id: 'close-'+this.#item.id
        })
        this.#modifier.innerText = ' MODIFY '
        this.#deleter.innerText = ' DELETE '
        this.#closeButton.innerText = ' CLOSE '

        this.#element.append(this.#deleter)
        this.#element.append(this.#closeButton)
        this.#element.prepend(this.#modifier)

        this.#deleter.addEventListener('click', this.#onRemove.bind(this), {once: true})
        this.#modifier.addEventListener('click', this.#onModify.bind(this))
        this.#closeButton.addEventListener('click', this.#onClose.bind(this))
    }

    /**
     * @param {PointerEvent} e 
     */
    #onRemove(e) {
        e.preventDefault()
        this.#item.remove()
        const deleteEvent = new CustomEvent('delete', {
            detail: this.#item,
            cancelable: true,
            bubbles: false
        }, {once: true})
        this.#item.dispatchEvent(deleteEvent)
    }

    #onModify(e) {
        e.preventDefault()
        this.#item.setAttribute('contenteditable', true)
        const modifierEvent = new CustomEvent('modify', {
            detail: this.#item,
            cancelable: true,
            bubbles: false
        })
        this.#item.dispatchEvent(modifierEvent)
    }

    #onClose(e) {
        e.preventDefault()
        this.#item.setAttribute('contenteditable', false)
        this.#element.remove()
        const closeEvent = new CustomEvent('closeAction', {
            detail: this.#item,
            cancelable: true,
            bubbles: false
        })
        this.#item.dispatchEvent(closeEvent)
    }

    get element() {
        return this.#element
    }
}

class UserValidations {
    #item
    #element = []
    #validate
    #cancel

    constructor(item) {
        this.#item  = item
        if (this.#element.length > 0 ){
            return
        }

        this.#element = createElement('div', {
            class: 'custom-ingredient__interactive-elements'
        })

        this.#validate = createElement('div', {
            class: 'interactive-elements__validate',
            name: 'validate',
            id: 'validate-'+this.#item.id
        })
        this.#cancel = createElement('div', {
            class: 'interactive-elements__cancel',
            name: 'cancel',
            id: 'cancel-'+this.#item.id
        })
        this.#validate.innerText = ' VALIDATE '
        this.#cancel.innerText = ' CANCEL '

        this.#element.append(this.#cancel)
        this.#element.prepend(this.#validate)

        this.#cancel.addEventListener('click', e => this.#onCancel(e))
        this.#validate.addEventListener('click', e => this.#onValidation(e))
    }

    /**
     * @param {PointerEvent} e 
     */
    #onCancel(e) {
        e.preventDefault()
        // this.#item.remove()
        this.#item.setAttribute('contenteditable', false)
        const cancelEvent = new CustomEvent('canceled', {
            detail: this.#item,
            cancelable: true,
            bubbles: false
        })
        this.#item.dispatchEvent(cancelEvent)
    }

    #onValidation(e) {
        e.preventDefault()
        this.#item.setAttribute('contenteditable', false)
        const validateEvent = new CustomEvent('validate', {
            detail: this.#item,
            cancelable: true,
            bubbles: false
        })
        this.#item.dispatchEvent(validateEvent)
    }

    get element() {
        return this.#element
    }
}