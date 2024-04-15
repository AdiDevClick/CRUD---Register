import { Toaster } from "./components/Toaster.js"
import { resetURL } from "./functions/url.js"



let message
let type
let errAlert = false

const queryString = window.location
const urlParams = new URLSearchParams(queryString.search)

const error = urlParams.get('error')
if (error === 'invalid-input') {
    errAlert = true
    message = 'Veuillez modifier votre identifiant'
    resetURL('index.php', 'error', urlParams)
}
// error === 'invalid-input' ? message = 'Veuillez modifier votre identifiant' : errAlert = false

const success = urlParams.get('success')
if (success === 'disconnected') {
    errAlert = true
    type = 'Success'
    message = 'Vous avez été déconnecté avec succès'
    resetURL('index.php', 'success', urlParams)
}

if (success === 'recipe-shared') {
    errAlert = true
    type = 'Success'
    message = 'Votre recette vient d\'être partagée, elle semble excellente !'
    resetURL('index.php', 'success', urlParams)
}

if (success === 'recipe-updated') {
    errAlert = true
    type = 'Success'
    message = 'Votre recette a bien été mise à jour!'
    resetURL('index.php', 'success', urlParams)
}
// success === 'disconnected' ? message = 'Vous avez été déconnecté avec succès' : errAlert = false


const login = urlParams.get('login')
if (login === 'success') {
    errAlert = true
    type = 'Success'
    message = 'Vous êtes connecté avec succès'
    resetURL('index.php', 'login', urlParams)
}
// login === 'success' ? message = 'Vous êtes connecté avec succès' : errAlert = false

const register = urlParams.get('register')
if (register === 'success') {
    errAlert = true
    type = 'Success'
    message = 'Votre compte a été enregistré avec succès, vous pouvez maintenant vous connecter'
    resetURL('index.php', 'register', urlParams)
}

if (register === 'failed') {
    errAlert = true
    type = 'Erreur'
    message = 'Problème dans la création de votre compte, veuillez vérifier que tous les champs soient correctement remplis'
    resetURL('register.php', 'failed', urlParams)
}

// const updated = urlParams.get('updated')
const failed = urlParams.get('failed')
if (failed === 'recipe-creation') {
    errAlert = true
    type = 'Erreur'
    message = 'Veuillez vous enregistrer ou vous identifier avant de pouvoir partager une recette'
    resetURL('register.php', 'failed', urlParams)
}

// const updateRecipe = urlParams.get('failed')
if (failed === 'update-recipe') {
    errAlert = true
    type = 'Erreur'
    message = 'Veuillez vous enregistrer ou vous identifier avant de pouvoir modifier cette recette'
    resetURL('register.php', 'failed', urlParams)
}

if (errAlert) {
    new Toaster(message, type)
}