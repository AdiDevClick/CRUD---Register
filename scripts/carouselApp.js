import { importThisModule } from "./functions/dom.js";

const url = window.location;

async function onReady(url) {
    console.log(url);
    if (
        url.toString().includes("index.php") ||
        url.toString() === "/recettes/"
    ) {
        const carousel1 = document.querySelector("#carousel1");
        if (carousel1) {
            const Carousel = await importThisModule("Carousel");
            Carousel.create(carousel1, {
                visibleSlides: 3,
                slidesToScroll: 2,
                automaticScrolling: false,
                infinite: true,
                loop: false,
                pagination: true,
                // navigation: false,
            });
        }
    }
}

if (window.readyState !== "loading") {
    onReady(url.pathname);
} else {
    window.addEventListener("DOMContentLoaded", onReady);
}
