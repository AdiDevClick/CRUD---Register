const loaded = document.querySelector('.form-loaded')

const options = {
    root: null,
    rootMargin: '0px',
}

const intersectHandler = function(entries, observer) {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('form-show')
            observer.unobserve(entry.target)
        }
    })
}

// const observer = new IntersectionObserver((entries) => {
//     entries.forEach((entry) => {
//         if (entry.isIntersecting) {
//             entry.target.classList.add('form-show')
//             observer.unobserve(entry.target)
//         }
//     })
// })
// const observer = new IntersectionObserver((entries) => {
//     entries.forEach((entry) => {
//         if (entry.isIntersecting) {
//             entry.target.classList.add('form-show')
//         } else {
//             entry.target.classList.remove('form-show')
//         }
//     })
// })
document.documentElement.classList.add('form-loaded')


window.addEventListener("DOMContentLoaded", function () {
    const observer = new IntersectionObserver(intersectHandler, options)
    const hiddenElements = document.querySelectorAll('.form-hidden')
    hiddenElements.forEach((el) => observer.observe(el))
})