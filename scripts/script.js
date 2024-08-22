let menu
let lastKnownScrollPosition = 0
let ticking = false
const toggleBtnIcon = document.querySelector('.toggle_btn i')
const toggleBtn = document.querySelector('.toggle_btn')
const toggleBtnBox = document.querySelector('.toggle_btn-box')
const navbar = document.querySelector('.navbar') 
// const navbar = document.querySelector('.navbar-grid') 
const nav = document.querySelector('.nav')
const header = document.querySelector('header')
const logo = document.querySelector('.logo')
const dropDownMenu = document.querySelector('.dropdown-menu')
const dropDownMenuBackground = document.querySelector('.dropdown-menu-background')
const leftSideMenu = document.querySelector('.leftside-menu')
const leftSideMenuBackground = document.querySelector('.leftside-menu-background')



const openMenu = function (e) {
    e.preventDefault()
    dropDownMenu.classList.toggle('open')
    dropDownMenuBackground.classList.toggle('open')
    toggleBtn.classList.toggle('open')
    // navbar.classList.toggle('open')
    // nav.classList.toggle('open')
    // dropDownMenu.classList.add('open')
    // dropDownMenuBackground.classList.add('open')
    // toggleBtn.classList.add('open')
    // navbar.classList.add('open')
    // menu.addEventListener('click', closeMenu, {once: true})

    // nav.addEventListener('click', closeMenu, {once: true})
    // toggleBtnBox.addEventListener('click', closeMenu)
    dropDownMenuBackground.addEventListener('click', closeMenu, {once: true})
    // toggleBtn.addEventListener('click', closeMenu, {once: true})
    
    // dropDownMenuBackground.addEventListener('click', closeMenu, {once: true})
    // const link = e.target.getAttribute('href')
    // if (!link && navbar.classList.contains('open')) {
    // navbar.addEventListener('click', ifOpened, {once: true})
    // }

    // dropDownMenuBackground.addEventListener('click', closeMenu)
    dropDownMenu.addEventListener('click', stopPropagation)
}
// const openMenu = function (e) {
//     e.preventDefault()
//     menu = document.querySelector('.nav')
//     dropDownMenu.classList.add('open')
//     dropDownMenuBackground.classList.add('open')
//     toggleBtn.classList.add('open')
//     navbar.classList.add('open')

//     menu.addEventListener('click', closeMenu, {once: true})


//     // navbar.addEventListener('click', closeMenu, {once: true})
//     // toggleBtnBox.addEventListener('click', closeMenu)
//     toggleBtnBox.addEventListener('click', closeMenu, {once: true})
//     // toggleBtn.addEventListener('click', closeMenu, {once: true})
    
//     // dropDownMenuBackground.addEventListener('click', closeMenu, {once: true})
//     // const link = e.target.getAttribute('href')
//     // if (!link && navbar.classList.contains('open')) {
//     // navbar.addEventListener('click', ifOpened, {once: true})
//     // }
//     // dropDownMenuBackground.addEventListener('click', closeMenu)
//     // dropDownMenu.addEventListener('click', stopPropagation)
// }


const stopPropagation = function(e) {
    e.stopPropagation()
}

export const closeMenu = (e) => {
    e.preventDefault()
    dropDownMenu.classList.remove('open')
    dropDownMenuBackground.classList.remove('open')
    toggleBtn.classList.remove('open')
    // navbar.classList.remove('open')
    // nav.classList.remove('open')
    dropDownMenu.removeEventListener('click', stopPropagation)
    // toggleBtnBox.removeEventListener('click', openMenu)
    
    // dropDownMenu.removeEventListener('click', stopPropagation)
    // dropDownMenuBackground.removeEventListener('click', closeMenu)
    // toggleBtnBox.removeEventListener('click', closeMenu)
    // navbar.removeEventListener('click', ifOpened)
}

// export function closeMenu(e) {
//     e.preventDefault()
//     dropDownMenu.classList.remove('open')
//     dropDownMenuBackground.classList.remove('open')
//     toggleBtn.classList.remove('open')
//     navbar.classList.remove('open')
//     dropDownMenu.removeEventListener('click', stopPropagation)
// }


// const closeMenu = function(e) {
//     e.preventDefault()
//     if (e.target.classList.contains('open')) {
//         dropDownMenu.classList.remove('open')
//         dropDownMenuBackground.classList.remove('open')
//         toggleBtn.classList.remove('open')
//         navbar.classList.remove('open')
//         console.log('modifyed')
//         dropDownMenu.removeEventListener('click', stopPropagation)

//     }
//     console.log('in close => ')
    
//     // dropDownMenu.removeEventListener('click', stopPropagation)
//     // dropDownMenuBackground.removeEventListener('click', closeMenu)
//     // toggleBtnBox.removeEventListener('click', closeMenu)
//     // navbar.removeEventListener('click', ifOpened)
// }

const ifOpened = function(e) {
    // e.preventDefault()
    const i = document.querySelector('i')

    if (e.target === i) console.log('test + ' + e.target)
    
    const link = e.target.getAttribute('href')
    if (navbar.classList.contains('open') && !link && e.target !== toggleBtnBox && e.target !== toggleBtn && e.target !== toggleBtnIcon) {
    // if (!link) {
        closeMenu()
    }
}

// window.addEventListener('click', e => {
//     e.preventDefault()
//     const link = e.target.getAttribute('href')
//     // if (!link && !toggleBtn.classList.contains('open')) nav.addEventListener('click', openMenu)
//     if (!link ) nav.addEventListener('click', openMenu)
// })
// toggleBtnBox.addEventListener('click', openMenu)
toggleBtnBox.addEventListener('click', openMenu)
// toggleBtnBox.addEventListener('click', e => {
    // dropDownMenu.classList.toggle('open')
    // dropDownMenuBackground.classList.toggle('open')
    // toggleBtn.classList.toggle('open')
    // navbar.classList.toggle('open')
//     openMenu(e)
// })

const onScrollEnd = function(e) {
    navbar.style.opacity = 1
    nav.style.opacity = 1
}

const onScroll = function(e) {
    lastKnownScrollPosition = window.scrollY
    if (!ticking) {
        window.requestAnimationFrame(() => {
            doSomething(lastKnownScrollPosition)
            ticking = false
        })
        navbar.style.opacity = 0
        nav.style.opacity = 0
        ticking = true
        document.addEventListener('scrollend', onScrollEnd, {once: true})
    }
}

// window.addEventListener('scroll', onScroll)
const hiddenVisibility = function(e) {
    if (navbar.classList.contains('fadeOut')) navbar.style.visibility = 'hidden'
    if (nav.classList.contains('fadeOut')) nav.style.visibility = 'hidden'
    // if ()
    console.log('object')
    // navbar.classList.remove('slideUp')
    // navbar.classList.add('fadeOut')
    // nav.classList.remove('slideUp')
    // nav.classList.add('fadeOut')

    // navbar.removeEventListener('animationend', hiddenVisibility)
    // nav.removeEventListener('animationend', hiddenVisibility)
    // document.removeEventListener('animationend', hiddenVisibility)
}

window.onscroll = function() {
    scrolling()
}

function scrolling() {
    lastKnownScrollPosition = window.scrollY
    // toggleBtnBox.style.visibility = 'visible'
    // if (window. === screenTop) {
    // if (window.scrolling === scrollTopMax) {
    if (document.documentElement.scrollTop === 0) {
    // if (document.documentElement.scrollTop === screenTop) {
        nav.classList.remove('fadeOut')
        nav.removeAttribute('style')
        nav.classList.add('slideUp')

        navbar.classList.remove('fadeOut')
        navbar.removeAttribute('style')
        navbar.classList.add('slideUp')
        
    } else {
        navbar.addEventListener('animationend', hiddenVisibility, {once: true})
        navbar.classList.remove('slideUp')
        navbar.classList.add('fadeOut')

        nav.addEventListener('animationend', hiddenVisibility, {once: true})
        nav.classList.remove('slideUp')
        nav.classList.add('fadeOut')
    }
    // navbar.classList.add('fadeOut')
}

// if (navbar.classList.contains('open')) {
// navbar.addEventListener('click', ifOpened)
// }
