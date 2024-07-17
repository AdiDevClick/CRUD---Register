import { fetchJSON } from "../functions/api.js"
import { debounce } from "../functions/dom.js"

export class SearchBar
{
    /** @type {String} */
    #url = '../recettes/recipes/Process_PreparationList.php'
    /** @type {Boolean} */
    #isSentAlready = false
    /** @type {Array} */
    #searchResults = []

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

        this.searchForm.addEventListener('submit', this.#newSearch.bind(this))
        this.searchForm.addEventListener('input', debounce((e) => {
            this.#newSearch(e)
        }, (this.options.debounceDelay)))
    }

    async #newSearch(e) {
        e.preventDefault()
        // const form = e.target
        let data = new FormData(this.searchForm)
        // let url = this.options.get ? this.#url : 'Process_PreparationList.php'
        const queryString = document.location
        const urlParams = new URLSearchParams(queryString.search)
        urlParams.set('query', data.get('query'))
        const query = urlParams.get('query')
        // urlParams.toString()

        // if (!this.#modifyFormDataValues(form, data)) return
        try {
            if (!this.#isSentAlready) {
                    this.#searchResults = await fetchJSON(this.#url+'?query='+query, {
                    method: 'GET'
                })
                console.log(this.#searchResults)
            }
        } catch(e) {
            console.log(e)
        }
    }

    set setUpdateAdress(url) {
        this.#url = url
    }
}