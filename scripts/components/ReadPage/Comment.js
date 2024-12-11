import { fetchJSON } from "../../functions/api.js";
import { importThisModule } from "../../functions/dom.js";
import { Toaster } from "../Toaster.js";

export class Comment {
    /**
     * This callback type is called `requestCallback` and is displayed as a global symbol.
     * @callback moveCallback
     * @param {number} offset
     */
    #moveCallbacks = [];
    /** @type {AbortController} */
    #controller;
    /** @type {AbortController} */
    #scrollController;
    /** @type {HTMLElement} */
    #element;
    /** @type {HTMLElement} */
    #commentBody;
    /** @type {object} */
    #comment;
    /** @type {HTMLElement} */
    #title;
    /** @type {HTMLElement} */
    #userId;
    /** @type {HTMLElement} */
    #creationDate;
    /** @type {HTMLElement} */
    #profilePicture;

    /** @type {HTMLElement} */
    #starTemplate;
    /** @type {number} */
    #left;
    /** @type {number} */
    #top;
    #data;
    /** @type {Array} contient les boutons valider/annuler */
    #validationItems = [];
    /** @type {Array} contient les boutons modifier/supprimer/fermer */
    #newModifierButtons = [];
    #done;
    #validationStatus;
    /** @type {HTMLElement} */
    #editableContent;
    /** @type {object} */
    #elements;

    /**
     * @type {Comment}
     * @param {object} comment Les informations du commentaire
     * @param {object} elements Les différents dataset qui serviront
     * de querySelector pour chaque information
     */
    constructor(comment, elements = {}) {
        this.#comment = comment;
        this.#elements = elements;

        console.log(this.#comment);
        console.log(this.#elements);

        this.#element = document
            .querySelector("#comment-layout")
            .content.firstElementChild.cloneNode(true);

        this.#controller = new AbortController();

        for (const [commentKey, commentValue] of Object.entries(
            this.#comment
        )) {
            for (const [key, selector] of Object.entries(this.#elements)) {
                if (key === commentKey && key !== "comment_id") {
                    this.#element.querySelector(selector).innerText =
                        commentValue;
                }
            }
        }

        this.#userId = this.#element.querySelector(".js-user-id");
        this.#userId.innerText = this.#comment.user_name;

        this.#editableContent = this.#element.querySelector(".js-editable");

        // this.#commentBody = this.#element.querySelector(".js-comment");
        // this.#commentBody.innerText = this.#comment.comment;

        // this.#title = this.#element.querySelector(".js-title");
        // this.#title.innerText = this.#comment.title;

        // this.#creationDate = this.#element.querySelector(".js-created_at");
        // this.#creationDate.innerText = this.#comment.comment_date;

        // Pour future utilisation
        this.#profilePicture = this.#element.querySelector(
            ".js-profile_picture"
        );

        const starsTarget = this.#element.querySelector(".js-stars-target");

        this.#createFiveStars(
            starsTarget,
            this.#comment.comment_id,
            this.#comment.review
        );
        this.#element.id = this.#comment.comment_id;

        if (this.#comment.canCreateTooltips) {
            this.#element.addEventListener("click", this.#onClick.bind(this));
            this.#element.addEventListener("modify", this.#onModify.bind(this));
            this.#element.addEventListener(
                "validate",
                this.#onValidate.bind(this)
            );
            this.#element.addEventListener(
                "canceled",
                this.#onCancel.bind(this)
            );
            this.#element.addEventListener(
                "closeAction",
                this.#onClose.bind(this)
            );
            this.#element.addEventListener(
                "delete",
                this.#onDelete.bind(this),
                {
                    signal: this.#controller.signal,
                }
            );
        }

        // this.#element.addEventListener("delete", this.#onDelete.bind(this), {
        //     once: true,
        //     signal: this.#controller.signal,
        // });
    }

    /**
     * Permet de créer 5 étoiles qui auront la possibilité d'être pleines ou vides.
     * @param {HTMLElement} target La target du DOM où append les éléments
     * @param {number} id L'ID de l'élément; Chaque étoile aura un ID différent
     * @param {number} review Un chiffre compris de 1 à 5
     */
    #createFiveStars(target, id, review) {
        for (let i = 5; i > 0; i--) {
            const starTemplate = document
                .querySelector("#star-layout")
                .content.firstElementChild.cloneNode(true);

            const label = starTemplate.querySelector("label");
            label.htmlFor = `box-${id}${i}`;

            const checkbox = starTemplate.querySelector("input");
            checkbox.name = `box-${id}${i}`;
            checkbox.id = `box-${id}${i}`;

            if (review >= i) {
                checkbox.setAttribute("checked", "");
                checkbox.checked = true;
            }
            const svg = starTemplate.querySelector("svg");
            svg.id = `${id}${i}`;
            svg.querySelectorAll("path")[1].setAttribute(
                "clip-path",
                `url(#clip-${id}${i})`
            );
            const clipPath = svg.querySelector("clipPath");
            clipPath.id = `clip-${id}${i}`;

            const rect = clipPath.querySelector("rect");
            // Sets the inner color of the star
            // 100% => colored / 0 => empty
            rect.style.width = checkbox.checked ? "100%" : "0";

            target.prepend(starTemplate);
        }
    }

    /**
     * @param {PointerEvent} event
     */
    async #onClick(event) {
        event.preventDefault();

        // Smooth scroll the comment to the center of the container
        this.#element.scrollIntoView({ behavior: "smooth", block: "center" });

        this.#element.focus();

        this.#element.classList.add("hover");

        const commentsContainer = document.querySelector(".js-comments-target");
        // Creates tooltips
        if (!this.#newModifierButtons.element) {
            this.#newModifierButtons = await importThisModule(
                "InteractiveTooltip",
                this.#element,
                "UserInteractions"
            );
        }

        // Saves comment position
        this.#definePosition();

        // Apply style position
        this.#onContainerScroll(
            this.#newModifierButtons.container.firstElementChild
        );
        this.#moveCallbacks.forEach((cb) => cb(this.#top, this.#left));

        // Events
        this.#scrollController = new AbortController();
        this.#element.append(this.#newModifierButtons.container);
        document.addEventListener("scroll", this.#definePosition.bind(this), {
            signal: this.#scrollController.signal,
        });
        commentsContainer.addEventListener(
            "scroll",
            this.#definePosition.bind(this),
            {
                signal: this.#scrollController.signal,
            }
        );
    }

    /**
     * Enregistre les données et crer les boutons de validations / cancel
     * La zone de texte sera automatiquement focus
     * @param {PointerEvent} e
     */
    async #onModify(e) {
        e.preventDefault();

        // Permet d'instancier les données à modifier
        this.#data = this.#editableContent.innerText;
        // Fin de l'enregistrement

        this.#editableContent.focus();

        if (!this.#validationItems.element) {
            this.#newModifierButtons.element.remove();
            this.#validationItems = await importThisModule(
                "UserValidations",
                this.#element,
                "UserInteractions"
            );
        }
        // Saves comment position
        this.#definePosition();

        // Apply style position
        this.#onContainerScroll(this.#validationItems.element);
        this.#moveCallbacks.forEach((cb) => cb(this.#top, this.#left));
        // this.#element.append(this.#validationItems.element);
        this.#newModifierButtons.element.remove();
        this.#newModifierButtons.container.append(
            this.#validationItems.element
        );
        this.#validationStatus = true;
    }

    /**
     * Sauvegarde le nouvel input utilisateur
     * dans un localStorage en cas de refresh
     * et pour une utilisation future
     * @param {PointerEvent} e
     */
    async #onValidate(e) {
        e.preventDefault();

        // Retrieve selected comment ID
        const item = e.detail.id;

        let data = this.#editableContent.innerText.toString().trim();

        console.log(" new data => ", data);
        console.log(" old data => ", this.#data);
        if (data !== "" && data !== this.#data) {
            try {
                const postDatas = {
                    comment_id: item,
                    user_id: this.#comment.user_id,
                    comment: data,
                    recipe_id: this.#comment.recipe_id,
                    session_name: "UPDATE_COMMENT",
                    any_post: "update_comment",
                };
                this.#validationItems.element.remove();
                this.#newModifierButtons.container.append(
                    this.#newModifierButtons.element
                );
                this.#editableContent.removeAttribute("style");
                this.#editableContent.setAttribute("contenteditable", false);

                const response = await fetchJSON(
                    "../recipes/Process_PreparationList.php",
                    {
                        method: "POST",
                        json: postDatas,
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

                this.#validationStatus = true;
                this.#data = null;
                new Toaster(
                    "Votre commentaire a été mis à jour avec succès",
                    "Succès"
                );
            } catch (error) {
                new Toaster(error.message, "Erreur");
            }
        }
        if (data === this.#data) {
            this.#resetValuesAndDisableEdit();
        }
        if (data === "") {
            this.#resetValuesAndDisableEdit();
            new Toaster(
                "Il n'est pas possible de valider un commentaire vide. Vous pouvez néanmoins le supprimer si vous le souhaitez.",
                "Erreur"
            );
        }
    }

    #resetValuesAndDisableEdit() {
        this.#validationItems.element.remove();
        this.#newModifierButtons.container.append(
            this.#newModifierButtons.element
        );
        this.#validationStatus = true;
        this.#editableContent.innerText = this.#data;
        this.#editableContent.removeAttribute("style");
        this.#editableContent.setAttribute("contenteditable", false);
        this.#data = null;
    }

    /**
     * Supprime les boutons d'intéraction et
     * réinitialise les données préalablement enregistrées
     * en cas d'annulation utilisateur
     * @param {PointerEvent} e
     */
    #onCancel(e) {
        e.preventDefault();
        // this.#removeInteractiveElements();
        // this.#arrayReset();
        this.#validationItems.element.remove();
        this.#newModifierButtons.container.append(
            this.#newModifierButtons.element
        );
        this.#done = true;
        this.#editableContent.innerText = this.#data;
        this.#editableContent.removeAttribute("style");
        this.#editableContent.setAttribute("contenteditable", false);
    }

    /**
     * Permet de supprimer le commentaire
     * @param {PointerEvent} event
     */
    async #onDelete(event) {
        event.preventDefault();
        const itemId = event.detail.id;
        const params = {
            comment_id: itemId,
            user_id: this.#comment.user_id,
            recipe_id: this.#comment.recipe_id,
            session_name: "DELETE_COMMENT",
            any_post: "delete_comment",
        };

        try {
            const response = await fetchJSON(
                "../recipes/Process_PreparationList.php",
                {
                    method: "POST",
                    json: params,
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

            this.#validationStatus = true;
            this.#data = null;
            this.#element.remove();

            new Toaster("Le commentaire a été supprimé avec succès", "Succès");
            this.#controller.abort();
        } catch (error) {
            new Toaster(error.message, "Erreur");
        }
    }

    /**
     * Supprime les boutons d'intéractions
     */
    #removeInteractiveElements() {
        this.#newModifierButtons.removeStopElement;
        this.#newModifierButtons.container.remove();
        this.#validationItems.element.remove();
    }

    /**
     * Reset les array des boutons d'intéractions
     * pour éviter une surcharge mémoire
     * Réinitialise le zIndex des élements HTML
     */
    #arrayReset() {
        this.#newModifierButtons = [];
        this.#validationItems = [];
        this.#element.classList.remove("hover");
        this.#editableContent.removeAttribute("style");
        this.#editableContent.setAttribute("contenteditable", false);
    }

    /**
     * Ferme certains éléments créés
     * et réinitialise les array pour éviter la surcharge mémoire
     * Réinitialise les données qui n'ont pas été validées par l'utilisateur
     * @param {PointerEvent} e
     */
    #onClose(e) {
        e.preventDefault();
        if (this.#validationStatus) {
            this.#validationStatus = false;
        }
        this.#validationItems.element?.remove();
        !this.#data
            ? (this.#data = this.#editableContent.innerText)
            : (this.#editableContent.innerText = this.#data);
        // !this.data
        //     ? (this.data = this.element.firstElementChild.innerText)
        //     : (this.element.firstElementChild.innerText = this.data);
        this.#arrayReset();
        this.#scrollController.abort();
    }

    /**
     * Défini les à quel emplacement sera placé le tooltip.
     * Le top du commentaire cliqué est utilisé comme base d'insertion.
     * Cette fonction retourne un callback
     */
    #definePosition() {
        const offsets = this.#element.getBoundingClientRect();
        // Top & Left positions of the Comment card will serve as append location
        this.#top = offsets.y - 40;
        this.#left = offsets.left + 20;

        // Registering the tooltip positions
        this.#moveCallbacks.forEach((cb) => cb(this.#top, this.#left));
    }

    /** @param {moveCallback} */
    #onSlide(callback) {
        this.#moveCallbacks.push(callback);
    }

    /**
     * A chaque appel, défini la position de l'élément
     * @param {HTMLElement} element
     */
    #onContainerScroll(element) {
        this.#onSlide((top, left) => {
            element.style.position = "fixed";
            element.style.top = `${top}px`;
            element.style.left = `${left}px`;
        });
    }

    /** @Returns Comment */
    get element() {
        return this.#element;
    }
}
