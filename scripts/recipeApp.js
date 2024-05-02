import { IngredientFormFetch, IngredientsFrom, RecipePreparation } from "./components/RecipePreparation.js"
import { Toaster } from "./components/Toaster.js"
import { fetchJSON } from "./functions/api.js"



try {
    const ingredientsInStorage = localStorage.getItem('ingredients')?.toString()
    let list = []

    if (ingredientsInStorage) {
        list = JSON.parse(ingredientsInStorage)
    }

    // const preparations = document.querySelectorAll('#js-preparation')
    // preparations.forEach(preparation => {
    //     new RecipePreparation(preparation)
    // })
    const ingredients = new IngredientsFrom(list)
    ingredients.appendTo(document.querySelector('.js-form-fetch'))

    // document
    //     .querySelectorAll('.js-form-fetch')
    //     .forEach(form => {
    //         const preparations = new IngredientFormFetch(form)
    //         preparations.appendThis(list)
    // })
    // const preparations = new RecipePreparation(list)
    // preparations.appendTo(document.querySelector('#js-preparation'))
    // console.log('hello world')
    if (list.length === 0) {
        throw new Error("Aucun ingrédient enregistré")
    }
// const loaded = document.querySelector('.form-loaded')
// console.log(loaded)
} catch (error) {
    new Toaster(error, 'Erreur')
}
