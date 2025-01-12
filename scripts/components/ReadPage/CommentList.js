import { fetchJSON } from "../../functions/api.js";
import { formatDate } from "../../functions/time.js";
import { Toaster } from "../Toaster.js";
import { Comment } from "./Comment.js";
import { InfinitePagination } from "./InfinitePagination.js";

export class CommentList {
    /** @type {HTMLElement} */
    #form;
    /** @type {String} */
    #endpoint;
    /** @type {HTMLElement} */
    #loader;
    #loaderTarget;
    #newComment = {};
    #elements;

    #comments = [];
    /** @type {HTMLElement} */
    #commentTarget;
    constructor(form) {
        this.#form = form;

        const noteContainer = document.querySelector(".comment-grid");
        const stars = noteContainer.querySelectorAll(".star");

        this.#loaderTarget = noteContainer.querySelector(
            ".js-infinite-pagination"
        );
        this.#endpoint = this.#form?.dataset.endpoint;
        if (this.#form) {
            // Retrieve querySelectors
            this.#elements = JSON.parse(this.#form.dataset.elements);
            // Event
            this.#form.addEventListener("submit", this.#onSubmit.bind(this));
        } else {
            this.#elements = JSON.parse(this.#loaderTarget.dataset.elements);
        }

        this.#commentTarget = noteContainer.querySelector(
            ".js-comments-target"
        );

        // Events
        stars.forEach((star) => {
            star.addEventListener("click", (e) => this.#filterComments(star));
        });

        // const target = document.querySelector(this.#loader.dataset.target);
        // const comment = target.querySelector(".comment");
        // const label = comment.querySelector("label");
        // const rating = label.getAttribute("for").split("-")[2];
        const url = new URL(window.location);
        const id = url.searchParams.get("id");
        const defaultParams = {
            fields: [
                "comment_id",
                "comment",
                "c.user_id",
                "c.recipe_id",
                "u.full_name as user_name",
                "c.title",
                "review",
            ],
            limit: "5",
            table: ["comments c"],
            join: { "users u": "u.user_id = c.user_id" },
            date: ["DATE_FORMAT(c.created_at, '%d/%m/%Y') as comment_date"],
            where: {
                conditions: {
                    "c.recipe_id": "= :recipe_id",
                    "c.comment_id": "> :comment_id",
                },
                logic: "AND",
            },
            error: ["Il n'y a plus de commentaires à charger"],
            save_this_last_id: "comment_id",
            searchMode: true,
        };

        // const loadComments = new InfinitePagination(this.#loaderTarget, {
        //     params: defaultParams,
        //     searchParams: { id: id },
        // });
        this.#loader = new InfinitePagination(this.#loaderTarget, {
            params: defaultParams,
            searchParams: { id: id, query: 1 },
        });
    }

    /**
     * Défini l'étoile sélectionnée pour permettre d'envoyer
     * la requête SQL et filtrer les commentaires à afficher
     * @param {HTMLElement} element
     */
    async #filterComments(element) {
        console.log("je clique");
        const label = element.querySelector("label");
        const url = new URL(window.location);
        // const recipeId = urlParams.get("id");
        const rating = label.getAttribute("for").split("-")[2];
        // url.searchParams.set("reviews", rating);
        const id = url.searchParams.get("id");
        const params = {
            fields: [
                "comment_id",
                "comment",
                "c.title",
                "c.user_id",
                "c.recipe_id",
                "review",
                "u.full_name as user_name",
            ],
            table: ["comments c"],
            join: { "users u": "u.user_id = c.user_id" },
            date: ["DATE_FORMAT(c.created_at, '%d/%m/%Y') as comment_date"],
            where: {
                conditions: {
                    "c.review": "= " + rating,
                    "c.recipe_id": "= :recipe_id",
                    // "c.recipe_id": " = " + id,
                    "c.comment_id": "> :comment_id",
                },
                logic: "AND",
            },
            error: ["Il n'y a pas de commentaires avec cette note"],
            save_this_last_id: "comment_id",
            searchMode: true,
        };
        // const params = {
        //     fields: ["comment_id", "comment", "user_id", "c.title", "review"],
        //     table: ["comments c"],
        //     date: ["DATE_FORMAT(c.created_at, '%d/%m/%Y') as comment_date"],
        //     where: {
        //         conditions: {
        //             "c.review": "= " + rating,
        //             "c.recipe_id": "= :recipe_id",
        //         },
        //     },
        //     error: ["Il n'y a pas de commentaires avec cette note"],
        //     fetchAll: true,
        // };

        /**
         * A REMETTRE
         */
        if (!this.#loader) {
            this.#loader = new InfinitePagination(this.#loaderTarget, {
                params: params,
                searchParams: { id: id, query: rating, _reset: 1, _limit: 5 },
            });
        } else {
            this.#loader._loadMore(
                params,
                {
                    id: id,
                    query: rating,
                    // _reset: 1,
                    _limit: 5,
                },
                { reset: true }
            );
        }

        /**
         * FIN DE A REMETTRE
         */

        // const comments = await fetchJSON(
        //     `${this.#target}?id=${id}&query=${rating}`,
        //     {
        //         method: "POST",
        //         json: params,
        //     }
        // );
        // const target = document.querySelector(".js-comments-target");
        // target.classList.add("hidden");
        // target.addEventListener(
        //     "animationend",
        //     (e) => {
        //         this.#onTransitionEnd(e, comments);
        //     },
        //     { once: true }
        // );
    }

    #onTransitionEnd(event, comments) {
        // Clears content
        if (comments.error) {
            event.currentTarget.classList.remove("hidden");
            return (event.currentTarget.innerText =
                "Il n'y a aucun commentaire disponible avec cette note");
        }
        // event.currentTarget.style.display = "none";
        event.currentTarget.innerHTML = "";

        // Append new content
        const template = document
            .querySelector("#comment-layout")
            .content.firstElementChild.cloneNode(true);
        console.log(template);
        comments.forEach((comment) => {
            for (const [key, value] of Object.entries(comment)) {
                console.log(key, value);
            }
        });
    }

    /**
     *
     * @param {SubmitEvent} e
     */
    async #onSubmit(e) {
        e.preventDefault();
        const form = e.currentTarget;

        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;

        const data = new FormData(form);
        let checked = [];
        let review = false;
        const newComment = {};
        // const newComment = {
        //     comment_id: Date.now(),
        //     recipe_id: data.recipe_id,
        //     title: data.title,
        //     review: review,
        //     comment: data.comment,
        // };
        const stars = form.querySelectorAll(".star");
        stars.forEach((star) => {
            const input = star.querySelector("input");
            if (input.checked) review = true;
        });
        try {
            data.forEach((value, key) => {
                const trimedValue = value.toString().trim();
                const inputElement = form.querySelector(`[name="${key}"]`);

                if (trimedValue === "")
                    throw new Error(`Veuillez remplir tous les champs`);
                // throw new Error(`Votre champs ${key} est vide`);

                if (!review) throw new Error(`Veuillez choisir une note`);

                if (trimedValue === "on") {
                    inputElement.checked = false;
                    inputElement.removeAttribute("checked");
                    inputElement.parentElement.classList.remove("hover");
                    return checked.push(key);
                }

                if (key === "recipe_id") {
                    // const input = form.querySelector('[name="recipe_id"]');
                    this.#pushContent("user_id", inputElement.id, newComment);
                    this.#pushContent(
                        "user_name",
                        inputElement.dataset.name,
                        newComment
                    );
                }
                this.#pushContent(key, trimedValue, newComment);
                if (inputElement.name !== "recipe_id") inputElement.value = "";
            });

            checked.sort((a, b) => b - a);

            this.#pushContent("review", checked[0], newComment);

            const today = new Date();

            this.#pushContent("comment_date", formatDate(today), newComment);
            // this.#pushContent("created_at", formatDate(today), newComment);
            this.#pushContent("comment_id", Date.now(), newComment);

            // const comment = new Comment(newComment);
            // this.#commentTarget.prepend(comment.element);

            // this.#comments.push(comment);

            this.#pushContent("any_post", "insert_comment", newComment);
            this.#pushContent("session_name", "REGISTERED_COMMENT", newComment);

            // let cookies = document.cookie.split(`; `);
            // const values = cookies.find((c) => c.startsWith("adibou"));

            const response = await fetchJSON(
                // "../comments/post_create.php",
                `../recipes/${this.#endpoint}`,
                {
                    method: "POST",
                    // headers: { "X-Requested-With": "XMLHttpRequest" },
                    json: newComment,
                }
            );

            if (!response.ok) {
                throw new Error(
                    `Erreur HTTP! Status: ${response.status} - ${response.message}`,
                    {
                        cause: {
                            status: response.status,
                            message: response.message,
                            ok: response.ok,
                        },
                    }
                );
            }

            if (response.status === 200) {
                if (response.canCreateTooltips)
                    this.#pushContent(
                        "canCreateTooltips",
                        response.canCreateTooltips,
                        newComment
                    );
                // Sets the newly created comment_id from the table to the displayed comment
                const comment = new Comment(newComment, this.#elements);
                this.#commentTarget.prepend(comment.element);
                comment.element.id = response.comment_id;
                comment.element.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });

                comment.element.focus();

                this.#comments.push(comment);

                new Toaster(
                    `Votre commentaire a été ajouté avec succès`,
                    "Succès"
                );
                button.disabled = false;
            }
        } catch (error) {
            new Toaster(`${error.message}`, "Erreur");
            button.disabled = false;
        }
    }

    /**
     * Sauvegarde chaque élément trouvé sous forme de clé => value dans l'objet item
     * @param {Object} item
     * @param {string} key
     * @param {string} value
     * @returns
     */
    #pushContent(key, value, item) {
        item[key] = item[key] ? [...item[key], value] : value;
        return item[key];
    }
}
