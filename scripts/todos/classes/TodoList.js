import { addClass, createTodo, createTodos, hideElements, removeClass, showMessage } from "../script/todo-functions.js"
import { Item } from "./Item.js"

export class TodoList 
{
    #list = []

    addTodo(todo) {
        this.list.push(new Item(todo))
    }

    addTodos(todos) {
        this.list.push(...todos)
    }

    addTodos2(todos) {
        for (const element of todos) {
            this.list.push(new Item(element.title, element.completed))
        }
    }

    get list() {
        return this.#list
    }

    modifyStatus() {
        const selectAll = document.querySelector('button[data-filter="all"]')
        const filteredByAll = selectAll.classList.contains('active')
        let listCheckBoxes = document.querySelectorAll('input[type="checkbox"]') ; // les boutons checkbox
        listCheckBoxes.forEach(box => {
            box.addEventListener("change", (event) => {    // On écoute les btn checkbox
                const checkedBox = event.target
                if (checkedBox) {
                    this.list.filter((el) =>  {
                        if ('todo-'+el?.id === checkedBox.id && checkedBox.checked === true) {
                            el.completed = true
                            checkedBox.classList.add('active') 
                            checkedBox.setAttribute('checked', '')
                            if (!filteredByAll) {
                                hideElements('#todo-wrapper-'+el.id)
                            } 
                        }
                        if ('todo-'+el?.id == checkedBox.id && checkedBox.checked === false) {
                            el.completed = false
                            checkedBox.classList.remove('active') 
                            checkedBox.removeAttribute('checked')
                            if (!filteredByAll) {
                                hideElements('#todo-wrapper-'+el.id)
                            } 
                        }
                    })
                }
        })
                })
    }

    async fetchData() {
        try {
            showMessage('#liveAlertPlaceholder', 'alert alert-success', 'Chargement...')
            const newPosts = await fetch('https://jsonplaceholder.typicode.com/todos?_limit=5', {
                method: 'GET',
                headers: {
                    Accept: 'application/json'
                }
            }) 
            if (!newPosts.ok) {
                throw new Error('Impossible de récupérer les données', {cause: newPosts})
            }    
            const posts = await newPosts.json()   
            document.querySelector('.alert').remove()
            // const te = () => {
            //     for (const element of posts) {
            //         // this.addTodo(new Item(element.title, element.completed)) 
            //         this.addTodo(element)  
            //     }
            // }
            // te()
            this.addTodos2(posts) 
            createTodos('.list-group', 'li', 'input', 'label', 'i', this.list) 
            this.removeTodo()
            this.modifyStatus()
        } catch(error) {
            console.log({cause: error})
            showMessage('#liveAlertPlaceholder', 'alert alert-danger', error.message + {cause: error}, '')
            return
        }
    }
    async #postData(url="", data = this.list) {
        try {
            showMessage('#liveAlertPlaceholder', 'alert alert-success', 'Chargement...')
            const newPosts = await fetch('http://127.0.0.1:5500/index2.html', {
                method: 'POST',
                headers: {
                    // Accept: 'application/json',
                    "Content-Type": 'application/json'
                },
                body: JSON.stringify(data)
            }) 
            if (!newPosts.ok) {
                throw new Error('Impossible de récupérer les données', {cause: newPosts})
            }    
            const posts = await newPosts.json()   
            document.querySelector('.alert').remove()
            const te = () => {
                for (const element of posts) {
                    // this.addTodo(new Item(element.title))                    
                    this.addTodo(element.title)                    
                }
            }
            te()
            // console.log(this.list[0])
            console.log(this.list)
            // console.log(te())
            // this.addTodo(te()) 
            // console.log(this.list)  
            // console.log(this.list[0].completed)
            // console.log(posts)
            // console.log(posts[0].completed)

            // this.removeTodo(5)
            // const doc = document.contains('#loader')
            // doc.remove()     
            createTodos('.list-group', 'li', 'input', 'label', 'i', this.list) 
        } catch(error) {
            console.log({cause: error})
            showMessage('#liveAlertPlaceholder', 'alert alert-danger', error.message + {cause: error}, '')
            return
        }
    }

    async newTodos() {        
        const newTodo = document.querySelector('form')
        newTodo.addEventListener('submit', (event) => {
            const data = new FormData(newTodo)
            let title = data.get('title') 

            try {
                if (title === '') {
                    event.preventDefault()
                throw new Error (`Le champ est vide, veuillez saisir un texte`, {cause: event})
            }   

            event.preventDefault()        
            this.addTodo(title)
            showMessage('#liveAlertPlaceholder', 'alert alert-success', 'Todo ajouté avec succès !')
            createTodo('.list-group', 'li', 'input', 'label', 'i', this.list)
            document.querySelector('input[name="title"]').value = ''
            this.removeTodo()
            this.modifyStatus()
            } catch(error) {
                console.log(error.message, {cause: error})
                showMessage('#liveAlertPlaceholder', 'alert alert-danger', error.message, '') 
            }            
        })
    } 

    findAllByTitle() {
        document.querySelector('button[data-filter="all"]')
            .addEventListener('click', () => {
                removeClass('button[data-filter="done"]', 'active')
                removeClass('button[data-filter="todo"]', 'active')
                addClass('button[data-filter="all"]', 'active')
                hideElements('.todo')
                createTodos('.list-group', 'li', 'input', 'label', 'i', this.list)
                this.removeTodo()
                this.modifyStatus()
            })                
    }    

    findTodos() {
        document.querySelector('button[data-filter="todo"]')
            .addEventListener('click', () => {
                removeClass('button[data-filter="done"]', 'active')
                removeClass('button[data-filter="all"]', 'active')
                addClass('button[data-filter="todo"]', 'active')
                const filteredElements = this.list.filter((el) =>  el?.completed == false)                
                if (filteredElements) {
                    hideElements('.todo')
                    createTodos('.list-group', 'li', 'input', 'label', 'i', filteredElements)
                    this.removeTodo()
                    this.modifyStatus()
                }                
            })
        // Sends all the results from the array        
    }

    findDone() {
        document.querySelector('button[data-filter="done"]')
            .addEventListener('click', () => {
                removeClass('button[data-filter="todo"]', 'active')
                removeClass('button[data-filter="all"]', 'active')
                addClass('button[data-filter="done"]', 'active')
                const filteredElements = this.list.filter((el) => el?.completed === true)
                if (filteredElements) {
                hideElements('.todo')
                createTodos('.list-group', 'li', 'input', 'label', 'i', filteredElements)
                this.removeTodo() 
                this.modifyStatus()
                }   
            }) 
        // Sends all the results from the array
    }

    findByWord(word) {
        // Sends all the results from the array
        return this.list.filter((el) => el.title.toLowerCase().includes(word.toLowerCase()))
    }

    removeTodo() {        
        let icons = document.getElementsByClassName('bi-trash')
        for (const icon of icons) {
            icon.addEventListener('click', (event) => {
                const activeIcon = event.currentTarget
                if (activeIcon) {
                    this.list.filter((el) => {
                    if (el) {
                        if (el?.id == activeIcon?.id) {
                        // delete this.list[el.id]
                            this.list[el.id] = undefined
                            const alert = document.querySelector('.alert')
                            const doc = document.getElementById('todo-wrapper-'+activeIcon.id)
                            doc?.remove()
                            if (alert) {
                                alert.remove()
                            }                            
                            }                        
                        }                    
                    })
                }
            })
        }    
    }
}