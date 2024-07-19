import { SearchBar } from "./components/SearchBar.js"

const searchForm = document.querySelectorAll('#search-form')
const searchBar = document.querySelector('#search')
const input = searchBar.querySelector('#header-search-box-input')
const closeSearchBtn = searchBar.querySelector('.close-search')
const searchIcon = searchBar.querySelector('.icon-search-input')
const body = document.querySelector('main')
const navLinks = document.querySelector('.links')
let isOpened

const onClose = function (e) {
    e.preventDefault()
    if (isOpened && searchBar.classList.contains('open')) {
        navLinks.classList.remove('hidden')
        searchBar.classList.remove('open')
        isOpened = false
    }
}

const onOpen = function (e) {
    e.preventDefault()
    if (searchBar.classList.contains('open') || isOpened) return
    if (!isOpened) {
        isOpened = true
        searchBar.classList.add('open')
        navLinks.classList.add('hidden')
        searchBar.addEventListener('transitionend', (e) => {
            input.focus()
            body.addEventListener('click', onClose, {once: true})
            closeSearchBtn.addEventListener('click', onClose, {once: true})
        }, {once: true})
    }
}

searchIcon.addEventListener('click', onOpen)

searchForm.forEach(form => {
    new SearchBar(form, {
        debounceDelay: 1000
    })
})

