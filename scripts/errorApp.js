import { ErrorHandler } from "./components/ErrorHandler.js"

const forms = document.querySelectorAll('form')
// form.addEventListener('submit', e => {
//     e.preventDefault()
//     console.log('object2')
//     // $script
//     // this.#onSubmit(e.currentTarget)
//     form.removeEventListener('submit', e)
// })
forms.forEach(form => {
    // debugger
    new ErrorHandler(form)
})