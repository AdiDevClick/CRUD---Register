import { createElement } from "../../functions/dom.js";

export class UserValidations {
    #item;
    /** @type {Array | HTMLElement} */
    #element = [];
    /** @type {HTMLElement} */
    #validate;
    /** @type {HTMLElement} */
    #cancel;

    /**
     * Crer une div contenant des boutons d'intéraction
     * @param {Object | HTMLElement} item
     * @returns
     */
    constructor(item) {
        this.#item = item;
        if (this.#element.length > 0) {
            return;
        }
        // const container = item.querySelector(".interactive-container");

        this.#element = createElement("div", {
            class: "interactive-container__elements",
        });

        this.#validate = createElement("img", {
            class: "interactive-elements__validate",
            name: "validate",
            id: "validate-" + this.#item.id,
            src: "../img/check-mark.svg",
        });
        this.#cancel = createElement("img", {
            class: "interactive-elements__cancel",
            name: "cancel",
            id: "cancel-" + this.#item.id,
            src: "../img/cancel.svg",
        });

        this.#element.append(this.#cancel);
        this.#element.prepend(this.#validate);
        // container.append(this.#element);

        this.#cancel.addEventListener("click", (e) => this.#onCancel(e));
        this.#validate.addEventListener("click", (e) => this.#onValidation(e));
    }

    /**
     * Annule l'intéraction en cours et dispatch un customEvent "canceled"
     * Pour une future intéraction
     * @param {PointerEvent} e
     */
    #onCancel(e) {
        e.preventDefault();
        this.#item.firstElementChild.setAttribute("contenteditable", false);
        this.#item.firstElementChild.removeAttribute("style");
        const cancelEvent = new CustomEvent("canceled", {
            detail: this.#item,
            cancelable: true,
            bubbles: false,
        });
        this.#item.dispatchEvent(cancelEvent);
    }

    /**
     * Valide l'intéraction en cours et dispatch un customEvent "validate"
     * Pour une future intéraction
     * @param {PointerEvent} e
     */
    #onValidation(e) {
        e.preventDefault();
        this.#item.firstElementChild.setAttribute("contenteditable", false);
        this.#item.firstElementChild.removeAttribute("style");
        const validateEvent = new CustomEvent("validate", {
            detail: this.#item,
            cancelable: true,
            bubbles: false,
        });
        this.#item.dispatchEvent(validateEvent);
    }

    /** @returns {NodeListOf.<HTMLElement>} */
    get element() {
        return this.#element;
    }
}
