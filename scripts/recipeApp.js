import { IngredientFormFetch, IngredientsFrom, RecipePreparation } from "./components/RecipePreparation.js"
import { Toaster } from "./components/Toaster.js"
import { fetchJSON } from "./functions/api.js"

const drawerButton = document.querySelector('.drawer__button')
const recipe = document.querySelector('.recipe')

try {
    const ingredientsInStorage = localStorage.getItem('ingredients')?.toString()
    let list = []

    if (ingredientsInStorage) {
        list = JSON.parse(ingredientsInStorage)
    }

    const ingredients = new IngredientsFrom(list)
    ingredients.appendTo(document.querySelector('.js-form-fetch'))

    if (list.length === 0) {
        throw new Error("Aucun ingrédient enregistré")
    }
} catch (error) {
    new Toaster(error, 'Erreur')
}

const onClose = function (e) {
    e.preventDefault()
    recipe.classList.add('hidden')
    recipe.addEventListener('animationend', () => {
        recipe.classList.remove('hidden')
        recipe.classList.remove('open')
    }, {once: true})
}
const onOpen = function (e) {
    e.preventDefault()
    if (!recipe.classList.contains('open')) recipe.classList.add('open')
    drawerButton.addEventListener('click', onClose, {once: true})
}

if (!recipe.classList.contains('open')) drawerButton.addEventListener('click', onOpen)