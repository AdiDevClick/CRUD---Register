import { RecipePreparation } from "./components/RecipePreparation.js"
import { fetchJSON } from "./functions/api.js"

const list = await fetchJSON('https://jsonplaceholder.typicode.com/todos/?_limit=1)')


// const preparations = document.querySelectorAll('#js-preparation')
// preparations.forEach(preparation => {
//     new RecipePreparation(preparation)
// })
const preparations = new RecipePreparation(list)
preparations.appendTo(document.querySelector('#js-preparation'))
// console.log('hello world')
// const loaded = document.querySelector('.form-loaded')
// console.log(loaded)