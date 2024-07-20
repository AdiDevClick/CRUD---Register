import { SearchBar } from "./components/SearchBar.js"

const searchForm = document.querySelectorAll('#search-form')
const pagination = document.querySelectorAll('.js-infinite-pagination')
const searchBar = document.querySelector('#search')
const input = searchBar.querySelector('#header-search-box-input')
const closeSearchBtn = searchBar.querySelector('.close-search')
const searchIcon = searchBar.querySelector('.icon-search-input')
const body = document.querySelector('main')
const navLinks = document.querySelector('.links')
const burgerMenu = document.querySelector('.toggle_btn-box')
let isOpened

const onClose = function (e) {
    e.preventDefault()
    if (isOpened && searchBar.classList.contains('open')) {
        // input.removeAttribute('style')
        input.style.animation = 'width-shrink .2s'

        // input.style.disableAnimation = 'width-shrink'
        navLinks.classList.remove('hidden')
        burgerMenu.classList.remove('hidden')
        searchBar.classList.remove('open')
        isOpened = false
    }
}

const onOpen = function (e) {
    e.preventDefault()
    if (searchBar.classList.contains('open') || isOpened) return
    if (!isOpened) {
        isOpened = true
        input.removeAttribute('style')
        searchBar.classList.add('open')
        navLinks.classList.add('hidden')
        burgerMenu.classList.add('hidden')
        searchBar.addEventListener('transitionend', (e) => {
            input.focus()
            // input.style.animation = 'width-shrink 0.2s'
            body.addEventListener('click', onClose, {once: true})
            closeSearchBtn.addEventListener('click', onClose, {once: true})
        }, {once: true})
    }
}

searchIcon.addEventListener('click', onOpen)

pagination.forEach(element => {
    new SearchBar(element, {
        debounceDelay: 1000
    })
})

