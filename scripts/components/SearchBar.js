import { fetchJSON } from "../functions/api.js"
import { debounce } from "../functions/dom.js"

export class SearchBar
{
    #url
    constructor(searchForm, options = {}) {
        this.searchForm = searchForm
        this.options = Object.assign({}, {
            debouncing: true,
            debounceDelay: 1000,
            canBeEmpty: false,
            // whichInputCanBeEmpty: ['step_3', 'step_4', 'step_5', 'step_6', 'file'],
            // isSpecialCharactersAllowed: false,
            // whichInputAllowSpecialCharacters: ['Mot de Passe', 'Mot de Passe de confirmation', 'Email', 'file'],
        }, options)

        this.searchForm.addEventListener('submit', this.#newSearch)
        this.searchForm.addEventListener('input', debounce((e) => {
            this.#newSearch(e)
        }, (this.options.debounceDelay)))
    }

    #newSearch(e) {
        console.log(e)
        const form = e.target
        console.log(form)
        console.log(form.value)
        let data = new FormData(this.searchForm)
        console.log(data)
        // if (!this.#modifyFormDataValues(form, data)) return
        // try {
        //     if (!this.#isSentAlready) {
        //         this.#ingredientList = await fetchJSON(url, {
        //             method: 'POST',
        //             // json: data,
        //             body: data,
        //             // img: true,
        //         })
        //     }
        // } catch(e) {
        //     console.log(e)
        // }
    }
}