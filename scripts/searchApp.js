import { SearchBar } from "./components/SearchBar.js"

const searchBar = document.querySelectorAll('#search-form')

searchBar.forEach(bar => {
    new SearchBar(bar, {
        debounceDelay: 1000
    })
})
