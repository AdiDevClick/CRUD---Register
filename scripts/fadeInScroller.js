const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        entry.isIntersecting ? entry.target.classList.add('form-show') : entry.target.classList.remove('form-show')
    })
})
// const observer = new IntersectionObserver((entries) => {
//     entries.forEach((entry) => {
//         if (entry.isIntersecting) {
//             entry.target.classList.add('form-show')
//         } else {
//             entry.target.classList.remove('form-show')
//         }
//     })
// })

const hiddenElements = document.querySelectorAll('.form-hidden')
hiddenElements.forEach((el) => observer.observe(el))