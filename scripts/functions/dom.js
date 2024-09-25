/**
 * Permet de céer un élément avec les attributs de son choix
 * ex : createElement('li', {
 *      class: 'myClass',
 *      role: 'myRole'
 * })
 * La fonction permet de vérifier si la valeur est nulle :
 * ex : createElement('li', {
 *      class: 'myClass',
 *      role: 'myRole',
 *      checked: todo.completed ? '' : null,
 * })
 * @param {String} tagName - NodeListOf.<HTMLElement>
 * @param {Object} attributes 
 * @returns {HTMLElement}
 */
export function createElement(tagName, attributes = {}) {
    const element = document.createElement(tagName)
    for (const [attribute, value] of Object.entries(attributes)) {
        if (value !== null) {
            element.setAttribute(attribute, value)
        }
    }
    return element
}

/**
 * Permet de récupérer les caractères qui ne sont pas autorisés -
 * La fonction s'assure que les caractères renvoyés soient uniques -
 * Très utile pour afficher dynamiquement lors d'une saisie utilisateur
 * un message d'erreur contenant un caractère interdit -
 * Etant donné la nature d'une Regexp, l'array sera réinitialisé lorsque l'input
 * sera correcte ou qu'une saisie sur une autre input est faite -
 * @param {String} value - Une chaîne de string type input value
 * @param {RegExp} allowedCharsRegex - Une chaine Regexp normale
 * @returns {Array}
 */
export function retrieveUniqueNotAllowedCharFromRegex(value, allowedCharsRegex) {
    return Array.from(value)
        .filter( (value, index, self) =>
            !value.match(allowedCharsRegex) &&
            index === self.findIndex( (v) => v === value
            )
        )
}

/**
 * Permet de filtrer un tableau et de ne récupérer que des valeurs uniques -
 * @param {Array} arr Array to filter
 * @param {*} object Any object - L'objet sera transformé en Array s'il ne l'est pas déjà -
 * @param {String} [property=null] Si une propriété de l'objet a été définie, passer son nom -
 * Exemple : Array[0]key.property
 * @returns {Array} filtered array
 */
export function filterArrayToRetrieveUniqueValues(arr = [], objects = [], property = null) {
    objects = Array.isArray(objects) ? objects : [objects]
    return arr.filter( (value, index, self) =>
        // value !== object &&
        // index === self.findIndex( (v) => v === value
        // )

        (property ? value[property] !== objects.some((object) => value[property] !== object) : objects.filter((object) => value !== object)) &&
        index === self.findIndex( (v) => v === value
        )
        
    )
}

// function returnValue(arr, property) {
//     console.log(arr[property])
//     const myElements = Object.keys(arr)
//     console.log(myElements[property])
//     for (const element of Object.keys(arr)) {
//         const elem = arr[property]
//         return elem
//     }
// }

/**
 * Modification d'un objet -
 * Ajoute une clé et sa valeur à cet objet
 * en comparant le nom de la clé -
 * @param {Array} arr
 * @param {Object} object
 * @param {String} objectKey
 * @param {String} propertyToSet
 * @param {Boolean} bool
 */
export function setObjectPropertyTo(arr, object, objectKey, propertyToSet, bool = true) {
    for (const keys of arr) {
        if (objectKey === keys) {
            object[propertyToSet] = bool
        }
    }
}

/**
 * Fonction pour retirer du DOM un HTMLElement contenu dans un commentaire
 * @param {String} targetSelector représente une classe ou un élément HTML
 * @param {HTMLElement} commentNode un objet HTML. Il retourne son nodeValue pour récupérer son contenu
 */
export function removeComment(targetSelector, commentNode) {
    const targetElement = document.querySelector(targetSelector)
    if (targetElement) {
        const comments = Array.from(targetElement.childNodes).filter(node => 
            node.nodeType === Node.COMMENT_NODE && node.nodeValue.trim() === commentNode.nodeValue.trim()
            // node.nodeType === Node.COMMENT_NODE && node.nodeValue.trim() === commentText.trim()
        )
        comments.forEach(comment => targetElement.removeChild(comment))
    } else {
        console.error('Target element not found')
    }
}

/**
 * Fonction permettant d'insérer une balise commentaire avec un texte
 * @param {String} targetSelector représente une classe ou un élément HTML
 * @param {String} commentText
 */
export function insertComment(targetSelector, commentText) {
    const targetElement = document.querySelector(targetSelector);
    if (targetElement) {
        const commentNode = document.createComment(commentText);
        targetElement.appendChild(commentNode);
    } else {
        console.error('Target element not found');
    }
}

/**
 * Transforme et remplace un HTMLElement en un commentaire
 * @param {String} targetSelector représente une classe ou un élément HTML
 * Son outerHTML permettra d'insérer les balises autour de la target
 */
export function transformToComment(targetSelector) {
    const targetElement = document.querySelector(targetSelector);
    if (targetElement) {
        const commentText = targetElement.outerHTML;
        const commentNode = document.createComment(commentText);
        targetElement.replaceWith(commentNode);
    } else {
        console.error('Target element not found');
    }
}

/**
 * Sélectionne un Node contenant des éléments HTML à déplacer
 * @param {String} targetSelector représente une classe ou un élément HTML 
 * @param {String} isClass représente une classe. Il retourne son nodeValue pour match son contenu
 */
export function appendToAnotherLocation(targetSelector, isClass = 'js-form-recipe') {
    console.log('jappend pour le mobile')
    console.log(targetSelector)
    let newCardRecipeSection = document.querySelector('.card.recipe')
    const parentElement = document.querySelector(targetSelector)
    console.log(parentElement)
    let newCardFormRecipeSection = document.querySelector('.form-recipe')
    
    if (!newCardFormRecipeSection) {
        newCardFormRecipeSection = createElement('section', {
        class: "form-recipe"
        })
    }
    if (!newCardRecipeSection) {
        newCardRecipeSection = createElement('section', {
            // class: "card recipe"
            class: "card recipe js-stop-appender"
        })
    }
    const contentToMoveToNewCardFormRecipeSection = Array.from(parentElement.childNodes).filter(node => 
        (node.className === 'js-form-recipe') || (node.className === 'js-form-recipe plus')
    )
    const contentToMoveToNewCardRecipeSection = Array.from(parentElement.childNodes).find(node => 
        node.className === 'js-append-to-drawer'
    )
    const contentToMoveToNewCardRecipeSection2 = Array.from(parentElement.childNodes).filter(node => 
        node.className === 'img_preview' || node.className === 'add_ingredient'
    )
    if (contentToMoveToNewCardFormRecipeSection) {
        console.log(contentToMoveToNewCardFormRecipeSection)
        contentToMoveToNewCardFormRecipeSection.forEach(element => {
            newCardFormRecipeSection.append(element)
        })

        parentElement.querySelector('.show_drawer').prepend(contentToMoveToNewCardRecipeSection)
        contentToMoveToNewCardRecipeSection2.forEach(element => {
            parentElement.querySelector('.show_drawer').append(element)
        })

        newCardRecipeSection.append(parentElement.querySelector('.js-recipe'))
        newCardRecipeSection.append(parentElement.querySelector('.show_drawer'))

        parentElement.prepend(newCardFormRecipeSection)
        parentElement.append(newCardRecipeSection)
    } else {
        console.error('Comment node not found')
    }
}

/**
 * Permet de remonter d'un niveau le contenu de la target 
 * @param {String} target représente une classe/id
 */
export function unwrap(target) {
    const content = document.querySelectorAll(target)
    content.forEach(element => {
        debugger
        for (const elements of element.childNodes) {
            console.log(elements)
            console.log(element.parentNode)
            element.parentNode.append(elements)
        }
        // element.replaceWith(element.childNodes)
        // element.outerHTML = element.innerHTML
        // for (let i = 0; i < element.children; i++) {
        //     element.parentElement.append(element.children[i])
        // }
        // element.parentElement.append(element.childNodes)
    })
}

/**
 * Permet de unwrap un élément HTML et d'append ses childNodes
 * à un autre élément (ici ce sera "targetToAppendTo")
 * @param {String} targetToAppendTo représente élément HTML déjà sélectionné
 * @param {String} isClass représente une classe ou un ID. Il retourne ses childNodes
 */
export function restoreToDefaultPosition(targetToAppendTo, isClass) {
    const parentElement = document.querySelectorAll(isClass)
    // const contentToMove = Array.from(parentElement).map(child => {
    //     return child.childNodes
    // })
    const contentToMove = Array.from(parentElement).filter(child => child)
    // const contentToMove = [].slice.call(parentElement)
    
    // while (parentElement.children instanceof HTMLElement) {
    //     section.appendChild(parentElement.children);
    // }
    // let contentToMove = parentElement.forEach(element => {
    //     // element.parentElement.append(element.childNodes)
    //     [].slice.call(element.childNodes)
    // })
    // const contentToMove = [].slice.call(parentElement).forEach(node => node)
    // const contentToMove = Array.from(parentElement).filter(child => child)
    // let contentToMove
    // parentElement.forEach(element => {
        
    // })
    // let contentToMove = parentElement.forEach(element => {
    //     [].slice.call(element.childNodes)
    //     return element.childNodes
    // })
        // n.parentNode.closest(targetSelectors) === parent.closest(targetSelectors)
    
        //         node.nodeType === Node.ELEMENT_NODE
        //         // node.nodeType === Node.ELEMENT_NODE && node.classList.contains(`${element}`)
        //         // node.nodeType === Node.ELEMENT_NODE && node.classList(element)
    // let contentToMove = [].slice.call(parentElement[0].children)
    // console.log(contentToMove)
    // contentToMove = parentElement.map(child => {
        // console.log(child)
        // Array.from(child.childNodes).filter(node =>
        // node.nodeType === Node.ELEMENT_NODE
    // })
    // parentElement.forEach(element => {
    //     contentToMove = Array.from(element.childNodes).filter(node =>
    //         // console.log(node.nodeType === Node.ELEMENT_NODE)
    //         node.nodeType === Node.ELEMENT_NODE
    //         // node.nodeType === Node.ELEMENT_NODE && node.classList.contains(`${element}`)
    //         // node.nodeType === Node.ELEMENT_NODE && node.classList(element)
    //     )
    // })
    // console.log(contentToMove)
    // targetSelectors.forEach(element => {
    //     contentToMove = Array.from(parentElement.childNodes).filter(node =>
    //         // console.log(node.classList.contains("card"))
    //         node.nodeType === Node.ELEMENT_NODE && node.classList.contains(`${element}`)
    //         // node.nodeType === Node.ELEMENT_NODE && node.classList(element)
    //     )
    // })
    
        // node.className
        // targetSelectors.forEach(element => {
        //     console.log(node.className)
        // })
        // targetSelectors.forEach(element => {
        //     return node.className == element
        // })
    
    // targetSelectors.forEach(element => {
    //     Array.from(document.querySelector(element).childNodes).filter(node => 
    //         contentToMove.push(node)
    //     )
    // })

    // const contentToMoveToNewCardFormRecipeSection = Array.from(parentElement.childNodes).filter(node => 
    //     console.log(node)
    //     // console.log(document.querySelector(node).childNodes)
    // )

    if (contentToMove) {
        contentToMove.forEach(element => {
            while (element.children.length > 0) {
                targetToAppendTo.appendChild(element.children[0]);
            }
            element.remove()
        })
        // contentToMoveToNewCardFormRecipeSection.forEach(element => {
        //     newCardFormRecipeSection.append(element)
        // })

        // parentElement.querySelector('.show_drawer').prepend(contentToMoveToNewCardRecipeSection)
        // contentToMoveToNewCardRecipeSection2.forEach(element => {
        //     parentElement.querySelector('.show_drawer').append(element)
        // })

        // newCardRecipeSection.append(parentElement.querySelector('.js-recipe'))
        // newCardRecipeSection.append(parentElement.querySelector('.show_drawer'))

        // parentElement.prepend(newCardFormRecipeSection)
        // parentElement.append(newCardRecipeSection)
        // const parser = new DOMParser()
        // const doc = parser.parseFromString(commentNode.nodeValue, 'text/html')
        // const restoredElement = doc.body.firstChild
        // contentToMove.replaceWith(restoredElement)
        // parentElement.replaceWith(restoredElement)
    } else {
        console.error('Comment node not found')
    }
}

/**
 * Sélectionne un Node contenant dans une balise commentaire
 * Puis cherche le un commentaire qui match le commentedNode
 * Puis supprime les balises commentaires pour réactiver l'élément -
 * @param {String} targetSelector représente une classe ou un élément HTML 
 * @param {HTMLElement} commentedNode un objet HTML. Il retourne son nodeValue pour match son contenu
 */
export function restoreFromComment(targetSelector, commentedNode = '') {
    const parentElement = document.querySelector(targetSelector)
    // const parentElement = document.querySelector(targetSelector).parentNode
    const commentNode = Array.from(parentElement.childNodes).find(node => 
        node.nodeType === Node.COMMENT_NODE && node.nodeValue.trim() === commentedNode.nodeValue.trim()
    )
    if (commentNode) {
        const parser = new DOMParser()
        const doc = parser.parseFromString(commentNode.nodeValue, 'text/html')
        const restoredElement = doc.body.firstChild
        commentNode.replaceWith(restoredElement)
    } else {
        console.error('Comment node not found')
    }
}

/**
 * Debounce une fonction de manière Asynchrone
 * Il faut spécifier la duration -
 * Cette fonction permet aussi de prendre en compte 
 * les paramètres de la fonction debounced
 * @param {Function} funct 
 * @param {Number} duration
 * @fires [debounce]
 * @returns {Function}
 */
export const debounce = function(funct, duration) {
    let timer
    return (...args) => {
        let context = this
        return new Promise((resolve) => {
            clearTimeout(timer)
            timer = setTimeout(() => {
                funct.apply(context, args)
                resolve(duration)
            }, duration)
        })
    }
}

/**
 * Accepte une promesse
 * @param {number} duration 
 * @returns {Promise}
 */
export function wait(duration, message) {
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve(message)
        }, duration)
    })
}
/**
 * Rejette une promesse de force
 * @param {number} duration 
 * @returns {Promise}
 */
export function waitAndFail(duration, message) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            reject(message)
        }, duration)
    })
}

/**
 * Crer une alerte en fonction d'un template HTML  id "#alert-layout" 
 */

/**
 * Modifier le "type" pour changer la couleur de l'alerte
 */

/**
 * Les différents types de couleur :
 * 'Erreur' => rouge (par défaut si rien de mentionné)
 * 'Success' => bleu
 */

/**
 * Utiliser le selecteur ".toast-container" pour insérer le toast
 * Le script se chargera de le créer sur le DOM dans le cas où il n'existe pas
 */

/**
 * IMPORTANT :
 * Pour utiliser le toaster, il faut importer la fonction 'alertMessage'
 * Exemple : import { alertMessage } from "./functions/dom.js"
 * 
 * Pour le CSS : 
 * Importer le toaster.css dans le main css file
 */

/**
 * Exemple d'utilisation : 
 *   const alert = alertMessage(error.message)
 *   const alertContainer = document.querySelector('.toast-container')
 *   alertContainer.insertAdjacentElement(
 *       'beforeend',
 *       alert
 *   )
 */

/** 
 * @param {string} message 
 * @param {string} type 
 * @returns {HTMLElement}
 */
export function alertMessage(message, type = 'Erreur') {
    let toasterDivContainer = document.querySelector('.toast-container')
    
    const alert = document.querySelector('#alert-layout').content.firstElementChild.cloneNode(true)
    const progress = alert.querySelector('.progress')
    const closeIcon = alert.querySelector('.toggle_btn-box')
    if (!toasterDivContainer) {
        toasterDivContainer = createElement('div', {
            class: 'toast-container',
            role: 'alert'
        })
        document.body.append(toasterDivContainer)
    }
    if (alert) {
        wait(50)
            .then(() => alert.classList.add('active'))
        closeIcon.classList.add('open')
        alert.classList.add(type)
        if (type === 'Success') {
            alert.querySelector('i').classList.add('fa-check')
        } else {
            alert.querySelector('i').classList.add('fa-info')
        }
        progress.classList.add('active')
        alert.querySelector('.text-1').innerText = type
        alert.querySelector('.text-2').innerText = message

        wait(5000)
            .then(() => alert.classList.remove('active'))

        wait(5300)
            .then(() => {
                progress.classList.remove('active')
                alert.remove()
                toasterDivContainer.remove()
            })
        
        closeIcon.addEventListener('click', e => {
            e.preventDefault()
            alert.classList.remove('active')
            alert.classList.add('close')
            
            wait(300)
                .then(() => progress.classList.remove('active'))
            closeIcon.dispatchEvent(new CustomEvent('delete'))
            alert.addEventListener('animationend', () => {
                alert.remove()
                toasterDivContainer.remove()
            })
            
        }, {once: true})
        return alert
    } else return
}