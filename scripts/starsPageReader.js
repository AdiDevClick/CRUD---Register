import { ReadPage } from "./components/ReadPage/ReadPage.js";

const form = document.querySelector(".comment-form");
const stars = form.querySelectorAll(".star");
stars.forEach((star, index) => {
    star.addEventListener("mouseenter", (e) => {
        e.preventDefault();
        handleMouseEnter(e, index);
    });
});

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
        (e) => {
            // Resets checked status of all stars
            stars.forEach((star) => {
                star.querySelector("input").checked = false;
            });
            for (let i = 0; i <= index; i++) {
                // Add hover to all stars under the target's
                stars[i].classList.add("hover");
                // Add checked status to all selected stars
                stars[i].querySelector("input").checked = true;
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
    new ReadPage(form);
});
