/**
 * Permet d'utiliser le bubble menu pour créer les différentes 
 * sections d'une création de recette
 */
export class BubbleCreativePlugin {

    /** @type {NodeListOf.<HTMLElement>} */
    #menuItems = []
    /** @type {HTMLElement} */
    #bubbleMenu
    /** @type {Array} class to hide / show */
    #elementsToShow = [
        '.js-form-recipe',
        '.show_drawer',
        '.js-append-to-drawer',
        '.img_preview',
    ]
    constructor(preparation) {
        this.preparation = preparation
        this.#bubbleMenu = document.querySelector('.bubble-menu')
        this.#menuItems = document.querySelectorAll('.menu-item')

        this.#menuItems.forEach(item => {
            console.log(item)
            item.addEventListener('click', this.#onClick)
        })
        console.log(this.preparation.errorHandler)
    }

    #onClick(e) {
        if (e.currentTarget.classList.contains('blue')) {
            console.log(e.currentTarget.dataset.target)
            document.querySelectorAll(e.currentTarget.dataset.target).forEach(item => {
                item.style.display = 'block'
                item.style.opacity = '1'
            })
            e.currentTarget.classList.add('hidden')
        } 
        if (e.currentTarget.classList.contains('green')) {
            console.log(e.currentTarget.dataset.target)
            document.querySelectorAll(e.currentTarget.dataset.target).forEach(item => {
                item.style.display = 'block'
                item.style.opacity = '1'
            })
            e.currentTarget.classList.add('hidden')
        }
        if (e.currentTarget.classList.contains('purple')) {
            console.log(e.currentTarget.dataset.target)
            document.querySelectorAll(e.currentTarget.dataset.target).forEach(item => {
                item.style.display = 'block'
                item.style.opacity = '1'
            })
            e.currentTarget.classList.add('hidden')
        }
        if (e.currentTarget.classList.contains('red')) {
            console.log(e.currentTarget.dataset.target)
            document.querySelectorAll(e.currentTarget.dataset.target).forEach(item => {
                item.style.display = 'block'
                item.style.opacity = '1'
            })
            e.currentTarget.classList.add('hidden')
        }
        if (e.currentTarget.classList.contains('orange')) {
            console.log(e.currentTarget.dataset.target)
            document.querySelectorAll(e.currentTarget.dataset.target).forEach(item => {
                item.style.display = 'block'
                item.style.opacity = '1'
            })
            e.currentTarget.classList.add('hidden')
        }
    }
}