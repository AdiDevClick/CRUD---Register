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
const actionBtn = document.querySelector('.action-btn')
const loaded = document.documentElement

const wrapper = document.querySelector('#wrapper')
const content = wrapper.innerHTML
const oldUrl = window.location
let newUrl
let newEvent

// console.log("new URL => " , oldUrl, "\n  old URL => "+ newUrl)
let isOpened

const onClose = function (e) {
    // console.log("new URL => " , oldUrl, "\n  old URL => "+ newUrl)
    // if (e.type === 'click') e.preventDefault()
    if (e.type === 'click' && (!loaded.classList.contains('search-loaded') || searchBar.classList.contains('open'))) e.preventDefault()
    if (isOpened && searchBar.classList.contains('open')) {

        // input.removeAttribute('style')
        // wrapper.innerHTML = content
        // console.log('url dans le close => ', newUrl)
        // history.pushState({}, document.title, newUrl)
        input.style.animation = 'width-shrink .2s'
        // input.style.disableAnimation = 'width-shrink'
        navLinks.classList.remove('hidden')
        burgerMenu.classList.remove('hidden')
        actionBtn?.classList.remove('hidden')
        searchBar.classList.remove('open')
        // loaded.classList.remove('search-loaded')

        // if (wrapper.classList.contains('hidden')) wrapper.classList.remove('hidden')
        isOpened = false
        console.log(newEvent)
        if (newEvent?.type === "observer") newEvent.detail.disconnect()

        console.log('je suis passé je ferme le menu')
    }
}

const onOpen = function (e) {
    e.preventDefault()
    if (newUrl !== oldUrl) newUrl = oldUrl.href
    if (searchBar.classList.contains('open') || isOpened) return
    if (!isOpened) {
        isOpened = true
        input.removeAttribute('style')
        searchBar.classList.add('open')
        navLinks.classList.add('hidden')
        burgerMenu.classList.add('hidden')
        actionBtn?.classList.add('hidden')
        // input.focus({ focusVisible: true })

        searchBar.addEventListener('transitionend', (e) => {
            input.focus({ focusVisible: true })
            // input.style.animation = 'width-shrink 0.2s'
            body.addEventListener('click', onClose, {once: true})
            closeSearchBtn.addEventListener('click', onClose, {once: true})
        }, {once: true})
    }
    // console.log("new URL => " , oldUrl, "\n  old URL => "+ newUrl)
}

// window.onpopstate = (e) => {
//     wrapper.innerHTML = content
//     history.pushState({}, document.title, newUrl)
// }

searchIcon.addEventListener('click', onOpen)

window.addEventListener("DOMContentLoaded", (e) => {
    const observer = new MutationObserver(callback)
    observer.observe(loaded, { attributes: true })
    newEvent = new CustomEvent('observer', {
        bubbles: false,
        detail: observer
    }, {once: true})
})

pagination.forEach(element => {
    new SearchBar(element, {
        debounceDelay: 1000
    })
})

function callback(mutationsList, observer) {
    mutationsList.forEach(mutation => {
        if (mutation.attributeName === 'class' && mutation.target.classList.contains('search-loaded')) {
            onClose(mutation)
            // console.log(window.history.state)
            // const doc = document.querySelector('#wrapper')
            // console.log(doc)
            // doc.addEventListener('animationend', (e) => console.log(e))
        }
    })
}

