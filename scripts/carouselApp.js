import { importThisModule } from "./functions/dom.js";

const url = window.location;
const carousel1 = document.querySelector("#carousel1");

async function onReady(url) {
    if (url.toString().includes("index.php") && carousel1) {
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

if (window.readyState !== "loading") {
    onReady(url.pathname);
} else {
    window.addEventListener("DOMContentLoaded", onReady);
}
