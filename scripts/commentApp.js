import { CommentList } from "./components/ReadPage/CommentList.js";

const form = document.querySelector(".comment-form");
const stars = form?.querySelectorAll(".star");
stars?.forEach((star, index) => {
    star.addEventListener("mouseenter", (e) => {
        e.preventDefault();
        handleMouseEnter(e, index);
    });
});

/**
 * Permet d'activer la checkbox de chaque étoile qui se trouve avant l'étoile sélectionner
 * Cela permet de mettre l'effet d'hovering sur les étoiles concernées
 * @param {MouseEvent} event Hover
 * @param {number} index Le numéro de l'étoile sélectionnée
 */
function handleMouseEnter(event, index) {
    const controller = new AbortController();
    const target = event.currentTarget;

    // Ajouter la classe 'hover' à toutes les étoiles jusqu'à celle survolée
    for (let i = 0; i < index; i++) {
        stars[i].classList.add("hover");
    }

    // Listener pour le changement (change) sur une étoile
    target.children[1].addEventListener(
        "click",
        () => {
            // e.preventDefault();
            // Resets checked status of all stars
            stars.forEach((star) => {
                star.querySelector("input").checked = false;
                star.querySelector("input").removeAttribute("checked");
            });
            for (let i = 0; i <= index; i++) {
                // Add hover to all stars under the target's
                stars[i].classList.add("hover");
                // Add checked status to all selected stars
                stars[i].querySelector("input").checked = true;
                stars[i].querySelector("input").setAttribute("checked", "");
            }
        },
        { once: true, signal: controller.signal }
    );

    // Listener pour le mouseleave
    target.addEventListener(
        "mouseleave",
        (e) => {
            e.preventDefault();
            stars.forEach((star) => {
                if (!star.children[1].checked) {
                    star.classList.remove("hover");
                }
            });
            controller.abort();
        },
        { once: true, signal: controller.signal }
    );
}

window.addEventListener("DOMContentLoaded", () => {
    new CommentList(form);
});
