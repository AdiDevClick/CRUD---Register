import { fetchJSON } from "../../functions/api.js";

export class ReadPage {
    /** @type {HTMLElement} */
    #form;
    /** @type {String} */
    #target;
    constructor(form) {
        this.#form = form;
        this.#target = this.#form.dataset.target;
        const noteContainer = document.querySelector(".note_container");
        const stars = noteContainer.querySelectorAll(".star");

        // Events
        stars.forEach((star) => {
            star.addEventListener("click", () => this.#onClick(star));
        });
        this.#form.addEventListener("submit", this.#onSubmit);
    }

    /**
     * Défini l'étoile sélectionnée pour permettre d'envoyer
     * la requête SQL et filtrer les commentaires
     * @param {HTMLElement} element
     */
    async #onClick(element) {
        const label = element.querySelector("label");
        const url = new URL(window.location);
        // const recipeId = urlParams.get("id");
        const rating = label.getAttribute("for").split("-")[2];
        url.searchParams.set("reviews", rating);
        const id = url.searchParams.get("id");
        const params = {
            fields: ["*"],
            table: ["comments c"],
            where: {
                conditions: {
                    "c.rating": rating,
                    "c.recipe_id": "= :recipe_id",
                },
            },
            error: ["Erreur dans la récupération des commentaires"],
        };
        // console.log(JSON.stringify(params));
        // console.log(this.#target + `?id=${id}&review=${rating}`);
        const query = await fetchJSON(
            `${this.#target}?id=${id}&review=${rating}`,
            {
                method: "POST",
                json: params,
            }
        );
        console.log(query);
    }

    #onSubmit(e) {
        // e.preventDefault();
        console.log(e);
    }
}
