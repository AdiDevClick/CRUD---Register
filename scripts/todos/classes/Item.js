import { showMessage } from "../script/todo-functions.js"
// import { TodoList } from "./TodoList.js"

export class Item 
{
    static _id = -1
    constructor(title, completed = false) 
    {
        this.title = title
        this.completed = completed
        this.id < Item._id ? this.id = Item._id : this.id = Item._id ++         
    }

    get isRemoved() {
        return this._removed
    }
    
    set isRemoved(value) {
        return this._removed = value
    }
}

async function sendData(data) {
    const postData = await fetch('https://jsonplaceholder.typicode.com/posts', {
        method: 'POST',
        headers: {
            // Accept: 'application/json',
            'Content-type': 'application/json; charset=UTF-8',
        },
        body: JSON.stringify(data),
    })  
    if (!postData.ok) {
        throw new Error("Impossible d'envoyer les données", {cause: postData})
    } 
    const posts = await postData.text()  
    console.log(posts)
    showMessage('#liveAlertPlaceholder', 'alert alert-success', 'Les données ont été envoyées correctement !')
}