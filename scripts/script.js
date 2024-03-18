
const toggleBtnIcon = document.querySelector('.toggle_btn i')
const toggleBtn = document.querySelector('.toggle_btn')
const dropDownMenu = document.querySelector('.dropdown-menu')
const dropDownMenuBackground = document.querySelector('.dropdown-menu-background')
const leftSideMenu = document.querySelector('.leftside-menu')
const leftSideMenuBackground = document.querySelector('.leftside-menu-background')


/* function toggleBurgerMenu() 
{   
    toggleBtn.addEventListener("click", () => {
    let isOpenMenu = dropDownMenu.classList.toggle('open')
        toggleBtnIcon.classList = isOpenMenu
            ? 'fa-solid fa-xmark'
            : 'fa-solid fa-bars '    
    })
}
 */
function showMenu() { 
    /* let dropDownMenu = document.querySelector('.dropdown-menu')
    let toggleBtnIcon = document.querySelector('.toggle_btn i') */
    dropDownMenu ? dropDownMenu.classList.toggle('open') : null
    // dropDownMenu.classList.toggle('open')
    toggleBtn ? toggleBtn.classList.toggle('open') : null
    dropDownMenuBackground ? dropDownMenuBackground.classList.toggle('open') : null
    leftSideMenuBackground ? leftSideMenuBackground.classList.toggle('open') : null
    leftSideMenu ? leftSideMenu.classList.toggle('open') : null
}
/* function showMenu() { 
    /* let dropDownMenu = document.querySelector('.dropdown-menu')
    let toggleBtnIcon = document.querySelector('.toggle_btn i')
    let isOpenMenu = dropDownMenu.classList.toggle('open')
    // toggleBtnIcon.classList.add('fa-solid fa-xmark')
    toggleBtnIcon.classList = isOpenMenu
            ? 'fa-solid fa-xmark'
            : 'fa-solid fa-bars '
} */

/* function hideMenu() {
    /* let dropDownMenu = document.querySelector('.dropdown-menu')
    let toggleBtnIcon = document.querySelector('.toggle_btn i') 
    if (dropDownMenu.classList.contains('open')) {
        dropDownMenu.classList.remove('open')    
        toggleBtnIcon.classList = ('fa-solid fa-bars')
    }
} */
function hideMenu() {
    /* let dropDownMenu = document.querySelector('.dropdown-menu')
    let toggleBtnIcon = document.querySelector('.toggle_btn i')  */
    if (
        dropDownMenu.classList.contains('open') &&
        toggleBtn.classList.contains('open') &&
        dropDownMenuBackground.classList.contains('open') &&
        leftSideMenuBackground.classList.contains('open') &&
        leftSideMenu.classList.contains('open') 
    ) {
        dropDownMenu.classList.remove('open')    
        toggleBtn.classList.remove('open')    
        dropDownMenuBackground.classList.remove('open')    
        leftSideMenuBackground.classList.remove('open')    
        leftSideMenu.classList.remove('open')    
        // toggleBtnIcon.classList = ('fa-solid fa-bars')
    }
}

function initMenuListener() {
    /* let toggleBtn = document.querySelector('.toggle_btn')
    let dropDownMenu = document.querySelector('.dropdown-menu') */
    toggleBtn.addEventListener("click", () => {
        showMenu()
    })
}
function initMenuListenerCloser() {
    dropDownMenuBackground.addEventListener("click", (event) =>{
            /* if (
                positionClick.classList.contains('hero') ||
                positionClick.classList.contains('navbar')
                ){ */
            if (event.target !== dropDownMenu ) {
            hideMenu()
        }
    })
    leftSideMenuBackground.addEventListener("click", (event) =>{
            /* if (
                positionClick.classList.contains('hero') ||
                positionClick.classList.contains('navbar')
                ){ */
            if (event.target !== leftSideMenu ) {
            hideMenu()       
        }    
    })
   /*  dropDownMenu.addEventListener("click", (event) =>{
            if (event.target !== dropDownMenu ) {
            hideMenu()
        }    
    }) */
}

initMenuListener()
initMenuListenerCloser()