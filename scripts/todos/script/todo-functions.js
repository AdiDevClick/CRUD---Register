/**
 * Permet de retirer complètement UN ou PLUSIEURS éléments du DOM
 * @param {HTMLElement} wrapperSelector 
 */
export function hideElements(wrapperSelector) {
    const wrappers = document.querySelectorAll(wrapperSelector)
    wrappers.forEach(wrapper => {
        wrapper.remove()
    })
}

/**
 * Permet de retirer une classe à un élément du DOM
 * @param {HTMLElement} select 
 * @param {HTMLElement} classes 
 */
export function removeClass(select, classes) {
    const selector = document.querySelector(select)
        selector.classList.remove(classes)
}

/**
 * Permet de rajouter une classe à un élément du DOM
 * @param {HTMLElement} select 
 * @param {HTMLElement} classes 
 */
export function addClass(select, classes) {
    const selector = document.querySelector(select)
        selector.classList.add(classes)
}

/**
 * Permet de créer une liste de Todos importée depuis
 * une API Json. Elle prendra en compte son 'title', son status 'completed'
 * et son 'id'
 * @param {HTMLElement} wrapperSelector 
 * @param {HTML} tagName 
 * @param {HTMLInputElement} checkbox 
 * @param {HTMLLabelElement} label 
 * @param {HTMLElement} icon 
 * @param {Array} arrElements 
 * @returns 
 */
export function createTodos(wrapperSelector, tagName, checkbox, label, icon, arrElements) {
    const wrapper = document.querySelector(wrapperSelector)  
    for (const arrElement of arrElements) {
        if  (arrElement) {
            
            let li_object = {
                id: 'todo-wrapper-'+arrElement.id,
                class: 'todo list-group-item d-flex align-items-center'
            }
            let checkbox_object = {
                type: 'checkbox',
                id: 'todo-'+arrElement.id,
                class: 'form-check-input'
            }
            let label_object = {
                for: 'todo-'+arrElement.id,
                class: 'ms-2 form-check-label'
            }
            let icon_object = {
                class: 'bi-trash ms-auto btn btn-danger btn-sm',
                id: arrElement.id
            }

            const newTodoList = createElements(tagName, li_object)
            const newCheckbox = createElements(checkbox, checkbox_object)
            const newLabel = createElements(label, label_object)
            const newIcon = createElements(icon, icon_object)

            if (arrElement?.completed == true) {
                newCheckbox.setAttribute('checked', '')
            }

            newLabel.innerText = arrElement.title

            newTodoList.appendChild(newCheckbox)
            newTodoList.appendChild(newLabel)
            newTodoList.appendChild(newIcon)

            wrapper.append(newTodoList)  
        }         
    }   
    return wrapper
}

/**
 * Permet de créer un Todo unique et créé par l'utilisateur
 * @param {HTMLElement} wrapperSelector 
 * @param {HTML} tagName 
 * @param {HTMLInputElement} checkbox 
 * @param {HTMLLabelElement} label 
 * @param {HTMLElement} icon 
 * @param {Array} arrElements 
 * @returns 
 */
export function createTodo(wrapperSelector, tagName, checkbox, label, icon, arrElements) {
    const wrapper = document.querySelector(wrapperSelector)   
    let ids = ''

    for (const element of arrElements) {
        ids = element?.id
    }

    let icon_object = {
        class: 'bi-trash ms-auto btn btn-danger btn-sm',
        id: ids
    }

    let li_object = {
        class: 'todo list-group-item d-flex align-items-center',
        id: 'todo-wrapper-'+ids
    }
    let checkbox_object = {    
        type: 'checkbox',
        id: 'todo-'+ids,
        class: 'form-check-input'
    }
    let label_object = {
        for: 'todo-'+ids,
        class: 'ms-2 form-check-label'
    }

    const newTodoList = createElements(tagName, li_object)
    const newCheckbox = createElements(checkbox, checkbox_object)
    const newLabel = createElements(label, label_object)
    const newIcon = createElements(icon, icon_object)

    if (arrElements) {
        for (const arrElement of arrElements) {  
            newLabel.innerText = arrElement?.title   
        } 
    }
    
    newTodoList.appendChild(newCheckbox)
    newTodoList.appendChild(newLabel)
    newTodoList.appendChild(newIcon)

    wrapper.append(newTodoList)    
    return wrapper
}

/**
 * Crer une Div avec une couleur à définir
 * pour afficher un message d'erreur ou loader
 * Il ne peut y avoir qu'une seule instance du message, il sera supprimé à chaque appel
 * @param {Node} wrapperSelector 
 * @param {HTMLElement} selector 
 * @param {string} message 
 * @param {HTMLStyleElement} color 
 * @returns {HTMLElement}
 */
export function showMessage(wrapperSelector, selector, message, color = '') {
    let newSpan = document.querySelector(selector)
    const createdSpan = document.querySelector('.alert')
    // const createdBtn = document.querySelector('.btn-close')
    const wrapper = document.querySelector(wrapperSelector)
    if (createdSpan) {
        createdSpan.remove()
        // createdBtn.remove()
    }

    if (!newSpan) {
        let input_objectSpan = {
            role:"alert"
        }
        newSpan = createElements('div', input_objectSpan)
        let input_object = {
            type: 'button',
            class: 'btn-close',
            'data-bs-dismiss': 'alert',
            'aria-label': 'Close'
        }
        let newButton = createElements('button', input_object)
        newSpan.classList = `${selector} alert-dismissible m-2 fade show`
        // newSpan.insertAdjacentElement("beforebegin", newButton)
        newSpan.innerText = message
        newSpan.style.color = color
        newSpan.append(newButton)
        // span.appendChild(newButton)
        // wrapper.append(newButton)
        wrapper.append(newSpan)
    }   
    return newSpan
}

/**
 * Permet de créer un élément HTML avec plusieurs options 
 * @param {HTMLElement} name 
 * @param {Array} options 
 * @returns 
 */
function createElements(name, options) {
    let element = document.createElement(name);
    for (let key in options) {
        element.setAttribute(key, options[key]);
    }
    return element
}