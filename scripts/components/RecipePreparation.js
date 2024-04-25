export class RecipePreparation {

    #preparations = []
    #listElement
    #element

    constructor(preparations) {
        this.#preparations = preparations
        this.#listElement = document.querySelector('.js-ingredients')

        // this.#preparations.forEach(preparation => {
        //     const newPreparation = new PreparationIngredient(preparation)
        //     this.#listElement.append(preparation.element)
        // })
        console.log(this.#listElement)
    }

    appendTo(element) {
        this.#element = element
        element.innerHTML = `
        <form class="d-flex p-4">
            <label for="title" class="visually-hidden">Password</label>
            <input required type="text" class="form-control" id="title" name="title" title placeholder="Saisissez une nouvelle tâche..." data-com.bitwarden.browser.user-edited="yes">

            <button type="submit" class="btn btn-primary">Ajouter</button>       
        </form>
        <main>
            <ul class="btn-group mb-4" role="group">
                <button class="btn btn-outline-primary active"type="button" data-filter="all">Toutes</button>
                <button class="btn btn-outline-primary"type="button" data-filter="todo">A Faire</button>
                <button class="btn btn-outline-primary"type="button" data-filter="done">Faites</button>
            </ul>

            <ul class="list-group">
            </ul>           
        </main>
        `
        this.#listElement = document.querySelector('.list-group')
        this.#preparations.forEach(preparation => {
            const ingredient = new PreparationIngredient(preparation)
            this.#listElement.append(ingredient.element)
        })

        // element.querySelectorAll('.btn-group button').forEach(button => {
        //     button.addEventListener('click', e => this.#onFilter(e))
        // })

        // element.querySelector('form').addEventListener('submit', e => {
        //     e.preventDefault()
        //     this.#onSubmit(e.currentTarget)
        // })
    }
}

class PreparationIngredient {
    constructor(ingredient) {
        
    }
}