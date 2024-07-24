import { wait } from "./functions/dom.js"

const queryString = document.location
let words
const dynamicText = document.querySelector(".hero > p > span")

words = [
    "_Gourmande",
    "_à Tomber",
    "_Incroyable",
    "_pleine d'Amour"
]

if (queryString.toString().includes("read.php")) {
    const title = document.querySelector("title")
    words = [
        `${title.text}`
    ]
}

let wordIndex = 0
let charIndex = 0
let isDeleting = false

const typeEffect = async () => {
        // console.log(dynamicText)
    const currentWord = words[wordIndex]
    const currentChar = currentWord.substring(0, charIndex)
    dynamicText.textContent = currentChar
    dynamicText.classList.add('stop-blinking')

    if (dynamicText.isConnected && !isDeleting && charIndex < currentWord.length) {
        // If condition is true, type the next character
        charIndex++
        // setTimeout(typeEffect, 200)
        if (dynamicText.isConnected) await wait(200)
        if (dynamicText.isConnected) await typeEffect()
    } else if (dynamicText.isConnected && isDeleting && charIndex > 0) {
        // If condition is true, remove the previous character
        charIndex--
        // setTimeout(typeEffect, 100)
        if (dynamicText.isConnected) await wait(100)
        if (dynamicText.isConnected) await typeEffect()
    } else {
        // If the word is deleted, then switch to the next word
        if (!dynamicText.isConnected) return
        isDeleting = !isDeleting
        dynamicText.classList.remove('stop-blinking')
        wordIndex = !isDeleting ? (wordIndex +1) % words.length : wordIndex 
        // setTimeout(typeEffect, 1200)
        if (dynamicText.isConnected) await wait(1200)
        if (dynamicText.isConnected) await typeEffect()
    }
    
}

if (dynamicText.isConnected) {
    await typeEffect()
}