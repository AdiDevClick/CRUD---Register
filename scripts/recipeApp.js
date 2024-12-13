import { IngredientsFrom } from "./components/RecipePreparation/RecipePreparation.js";
import { Toaster } from "./components/Toaster.js";
import { fetchJSON } from "./functions/api.js";
// import { appendToAnotherLocation, transformToComment, restoreFromComment, restoreToDefaultPosition, unwrap } from "./functions/dom.js"

// async function includes(url, target) {
//     const data = await fetchTemplate(url, target)
//     return data
// }
async function onReady() {
    const queryString = document.location;
    const urlParams = new URLSearchParams(queryString.search);
    if (queryString.toString().includes("create_recipes.php")) {
        try {
            let list = [];
            const ingredientsInStorage = localStorage
                .getItem("ingredients")
                ?.toString();
            if (ingredientsInStorage) {
                list = JSON.parse(ingredientsInStorage);
            }

            const ingredients = new IngredientsFrom(list);
            const positions = document.querySelectorAll(".js-form-fetch");
            positions.forEach((position) => {
                // const ingredients = new IngredientsFrom(list)
                ingredients.appendTo(position);
            });
            // ingredients.appendTo(document.querySelector('.js-form-fetch'))

            if (list.length === 0) {
                throw new Error("Aucun ingrédient enregistré");
            }
        } catch (error) {
            new Toaster(error, "Erreur");
            console.log("create", error);
        }
    }

    if (queryString.toString().includes("update_recipes.php")) {
        try {
            const id = urlParams.get("id");
            const url = "Process_PreparationList.php?id=" + id;
            const fetchedIngredients = await fetchJSON(url);

            if (fetchedIngredients.status === "failed")
                window.location.assign("../index.php?update=failed");

            const returnedIngredients =
                fetchedIngredients.custom_ingredients.split(",");

            localStorage.setItem(
                "update-ingredients",
                JSON.stringify(returnedIngredients)
            );

            const ingredients = new IngredientsFrom(returnedIngredients, {
                get: true,
            });
            ingredients.setUpdateAdress = url;
            ingredients.appendTo(document.querySelector(".js-form-fetch"));
        } catch (error) {
            window.location.assign("../index.php?failed=fetch");
            console.log("update", error);
        }
    }
}
// const onReady = async function () {};

// async function fetchIngredients(url) {
//     const returnedIngredients = await fetchJSON(url);
//     if (returnedIngredients.status === "failed")
//         window.location.assign("../index.php?update=failed");

//     let response = returnedIngredients.custom_ingredients.split(",");
//     return response;
// }

// const onClose = function (e) {
//     e.preventDefault()
//     console.log('je ferme')
//     recipe.classList.add('hidden')
//     recipe.addEventListener('transitionend', () => {
//     // recipe.addEventListener('animationend', () => {
//         recipe.classList.remove('hidden')
//         recipe.classList.remove('open')
//     }, {once: true})
// }
// const onOpen = function (e) {
//     e.preventDefault()
//     console.log('jouvre')

//     if (!recipe.classList.contains('open')) recipe.classList.add('open')
//     // drawerButton.addEventListener('click', onClose, {once: true})
//     drawer.addEventListener('click', onClose, {once: true})
// }

// if (!recipe.classList.contains('open')) {
//     // drawerButton.addEventListener('click', onOpen)
//     console.log('je crer mon listener')
//     drawer.addEventListener('click', onOpen)
// }

// new DrawerTouchPlugin(grid)

if (window.readyState !== "loading") {
    onReady();
} else {
    window.addEventListener("DOMContentLoaded", onReady);
}
