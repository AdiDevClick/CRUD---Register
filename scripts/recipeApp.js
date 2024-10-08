import { DrawerTouchPlugin } from "./components/DrawerTouchPlugin.js"
import { IngredientsFrom } from "./components/RecipePreparation.js"
import { Toaster } from "./components/Toaster.js"
import { fetchJSON, fetchTemplate } from "./functions/api.js"
import { appendToAnotherLocation, transformToComment, restoreFromComment, restoreToDefaultPosition, unwrap } from "./functions/dom.js"

const drawerButton = document.querySelector('.drawer__button')
const recipe = document.querySelector('.recipe')
// const grid = document.querySelector('.card_container')
const allRes = document.querySelector('.all-resolutions')
const mobileOnly = document.querySelector('.mobile-only')
const drawer = document.querySelector('.drawer')

let mobile = false

if (window.innerWidth < 577) {
    mobile = true
} else {
    mobile = false
}
 
if (!mobile) {
    const url = '../templates/Recipe_Layout_All_Resolutions.php'
    const target = '.all-resolutions'

    
    const elementsToUnwrap = [
        '.img_preview',
        '#submit-recipe'
    ]
    // pour revenir à defaut
    // unwrap('.card')

    // const section = document.querySelector('#recipe_creation_all_resolutions')
    // document.querySelector('.show_drawer').insertAdjacentElement('beforebegin', document.querySelector('.js-append-to-drawer'))
    // elementsToUnwrap.forEach(element => {
    //     section.append(document.querySelector(element))
    // })
    
    // async function fetchData(url) {
    //     const response = await fetch(url);
    //     if (!response.ok) {
    //         throw new Error('Network response was not ok');
    //     }
    //     return response.text();
    // }
    
    // fetchData(url).then(data => {
    //     const parser = new DOMParser();
    //     const doc = parser.parseFromString(data, 'text/html');
    //     const targetElement = doc.querySelector(target);
    //     if (targetElement) {
    //         const commentedData = document.createComment(targetElement);
    //         document.querySelector('main').appendChild(commentedData);
    //     } else {
    //         console.error('Target element not found');
    //     }
    // })

    // console.log(await test)
    // const newDiv = document.createElement('div')
    // const append = await allResolutionsData
    // newDiv.innerHTML = allResolutionsData
    // const test = `<!-- ${newDiv}`
    // document.querySelector("main").appendChild(commentedData)

    // removeComment("main", commentedData)

    /*
    * A Réactiver
    */

    // const allResolutionsData = await includes(url, target)

    // const commentedData = document.createComment(allResolutionsData.outerHTML)
    // restoreDefault('#recipe_creation_all_resolutions')

    


    


    // restoreToDefaultPosition(elementsToReset)
    // restoreFromComment('main', commentedData)
    // transformToComment(target)


    // restoreFromComment('main', commentedData)

    // document.querySelector("main").append(test)
    // document.querySelector("main").innerHTML = test
    // document.querySelector("main").insertAdjacentHTML("beforebegin", append)
    // document.querySelector("main").append(append)



    // const append = document.querySelector("main").append(await allResolutionsData)
    // const main = document.querySelector("main").innerHTML.includes('<!--')
    // const test = `<code> <!-- ${append} `
    // const test = document.createComment(`${await allResolutionsData}`) 
    // const docu = new DOMParser().parseFromString("<xml></xml>", "application/xml");
    // console.log(append)
    // const comment = docu.createComment(append) 
    // const comment = document.createComment(append) 

    // const news = new XMLSerializer()
    // if (main) {
        // docu.querySelector("xml").appendChild(comment)
        // document.querySelector("main").insertAdjacentHTML("beforebegin", test)
        // console.log(news.serializeToString(docu));
        // document.querySelector("main").appendChild(news.serializeToString(comment))
    // }
    
} else {
    const url = '../templates/Recipe_Layout_Mobile_Only.php'
    const target = '.mobile-only'

    // pour la version mobile
    console.log('je load la page et jappend pour mobile')
    appendToAnotherLocation('#recipe_creation')
    // const mobileData = includes(url, target)
    // console.log(await test)
    // document.querySelector("main").append(await mobileData)
}

async function includes(url, target) {
    const data = await fetchTemplate(url, target)
    return data
} 

const queryString = document.location
const urlParams = new URLSearchParams(queryString.search)

if (queryString.toString().includes("create_recipes.php")) {
    try {
        let list = []
        const ingredientsInStorage = localStorage.getItem('ingredients')?.toString()
        if (ingredientsInStorage) {
            list = JSON.parse(ingredientsInStorage)
        }
    
        const ingredients = new IngredientsFrom(list)
        const positions = document.querySelectorAll('.js-form-fetch')
        positions.forEach(position => {
            // const ingredients = new IngredientsFrom(list)
            ingredients.appendTo(position)
        })
        // ingredients.appendTo(document.querySelector('.js-form-fetch'))
    
        if (list.length === 0) {
            throw new Error("Aucun ingrédient enregistré")
        }
    } catch (error) {
        new Toaster(error, 'Erreur')
        console.log(error)
    }
}

if (queryString.toString().includes("update_recipes.php")) {
    try {
        const id = urlParams.get('id')
        const url = 'Process_PreparationList.php?id='+id
        // const url = 'Process_Updated_PreparationList.php?id='+id
        const fetchedIngredients = await fetchJSON(url, {
            method: 'GET'
        })
        if (fetchedIngredients.status === 'failed') window.location.assign('../index.php?update=failed')

        // if (fetchedIngredients.custom_ingredients) {
            // console.log(fetchedIngredients.custom_ingredients.split(","))
            const returnedIngredients = fetchedIngredients.custom_ingredients.split(",")
            localStorage.setItem('update-ingredients', JSON.stringify(returnedIngredients))
            const ingredients = new IngredientsFrom(returnedIngredients, {
                get: true
            })
            ingredients.setUpdateAdress = url
            // console.log(ingredients)
            // return
            ingredients.appendTo(document.querySelector('.js-form-fetch'))
        // }
    } catch (error) {
        new Toaster(error, 'Erreur')
        console.log(error)

    }
}

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