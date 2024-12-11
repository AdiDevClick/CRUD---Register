export class InteractiveTooltip {
    /** @type {HTMLElement} item */
    #item;
    /** @type {Array} contient l'élément HTML */
    #element = [];
    /** @type {HTMLImageElement} */
    #modifier;
    /** @type {HTMLImageElement} */
    #deleter;
    /** @type {HTMLImageElement} */
    #closeButton;
    /** @type {Array<HTMLDivElement>} */
    #container = [];
    /** @type {Boolean} */
    #isCreated = false;
    /** @type {HTMLDivElement} */
    #stopElement;

    /** @param {HTMLElement} item */
    constructor(item) {
        this.#item = item;
        if (this.#element.length > 0) {
            return;
        }

        this.#container = document.querySelector(
            "#interactive-container-" + this.#item.id
        );

        if (!this.#container) {
            this.#container = document
                .querySelector("#dynamic-tooltips")
                .content.firstElementChild.cloneNode(true);
            this.#container.id = "interactive-container-" + this.#item.id;
        }

        this.#element = this.#container.querySelector(
            ".interactive-container__elements"
        );
        this.#element.id = "attach-" + this.#item.id;

        this.#modifier = this.#container.querySelector(
            ".interactive-elements__modify"
        );
        this.#modifier.id = "modify-" + this.#item.id;

        this.#deleter = this.#container.querySelector(
            ".interactive-elements__delete"
        );
        this.#deleter.id = "delete-" + this.#item.id;

        this.#closeButton = this.#container.querySelector(
            ".interactive-elements__close"
        );
        this.#closeButton.id = "close-" + this.#item.id;

        this.#stopElement = this.#container.querySelector(".js-stops");
        this.#stopElement.id = "stop-" + this.#item.id;
        this.#stopElement.classList.remove("hide");

        this.#deleter.innerText = " DELETE ";
        this.#closeButton.innerText = " CLOSE ";

        document
            .querySelectorAll(".js-stop-appender")
            .forEach((stop) => stop.prepend(this.#stopElement));

        this.#isCreated = true;

        // Events
        this.#deleter.addEventListener("click", this.#onRemove.bind(this));
        // this.#deleter.addEventListener("click", this.#onRemove.bind(this), {
        //     once: true,
        // });
        this.#modifier.addEventListener("click", this.#onModify.bind(this));
        this.#closeButton.addEventListener("click", this.#onClose.bind(this), {
            once: true,
        });
        this.#stopElement.addEventListener("click", this.#onClose.bind(this), {
            once: true,
        });
        this.#element.addEventListener(
            "click",
            this.#stopPropagation.bind(this)
        );
    }

    /**
     * @param {EventTarget} e
     */
    #stopPropagation(e) {
        e.stopPropagation();
    }

    /**
     * Supprime l'ingrédient et crer a custom Event 'delete'
     * @param {PointerEvent} e
     * @type {CustomEvent} delete
     */
    #onRemove(e) {
        e.preventDefault();
        console.log(e);
        // this.#item.remove();
        this.#stopElement.remove();
        const deleteEvent = new CustomEvent("delete", {
            detail: this.#item,
            cancelable: true,
            bubbles: false,
        });
        // const deleteEvent = new CustomEvent(
        //     "delete",
        //     {
        //         detail: this.#item,
        //         cancelable: true,
        //         bubbles: false,
        //     },
        //     { once: true }
        // );
        this.#item.dispatchEvent(deleteEvent);
    }

    /**
     * Permet de rendre éditable la zone 'p' de l'élément
     * Crer un custom event 'modify' lors de l'event
     * @param {PointerEvent} e
     * @type {CustomEvent} delete
     */
    #onModify(e) {
        e.preventDefault();
        const editable = this.#item.querySelector(".js-editable");
        editable.setAttribute("contenteditable", true);
        editable.style.userSelect = "text";
        editable.style.webkitUserSelect = "text";
        editable.style.webkitUserModify = "read-write";
        // this.#item.firstElementChild.setAttribute("contenteditable", true);
        // this.#item.firstElementChild.style.userSelect = "text";
        // this.#item.firstElementChild.style.webkitUserSelect = "text";
        // this.#item.firstElementChild.style.webkitUserModify = "read-write";
        const modifierEvent = new CustomEvent("modify", {
            detail: this.#item,
            cancelable: true,
            bubbles: false,
        });
        this.#item.dispatchEvent(modifierEvent);
    }

    /**
     * Supprime les éléments créés
     * Crer un custom event 'closeAction' lors de l'event
     * @param {PointerEvent} e
     */
    #onClose(e) {
        e.preventDefault();
        // debugger;
        // console.log("event => ", e);
        // console.log("close => ", e.currentTarget);
        // console.log("item => ", this.#item);
        // console.log("stop => ", this.#stop);
        // console.log("container => ", this.#container);
        const closeEvent = new CustomEvent("closeAction", {
            detail: this.#item,
            cancelable: true,
            bubbles: true,
        });
        this.#item.dispatchEvent(closeEvent);
        const editable = this.#item.querySelector(".js-editable");

        editable.setAttribute("contenteditable", false);
        editable.removeAttribute("style");
        // this.#item.firstElementChild.setAttribute("contenteditable", false);
        // this.#item.firstElementChild.removeAttribute("style");
        this.#container.remove();
        this.#stopElement.remove();
    }

    /**
     * @returns {Number}
     */
    get containerWidth() {
        return this.#element.offsetWidth;
    }

    /**
     * @returns {Array} this.#container
     */
    get container() {
        return this.#container;
    }

    /**
     * @returns {Array}
     */
    get element() {
        return this.#element;
    }

    /** @type {HTMLDivElement} removes the stop progatation element */
    get removeStopElement() {
        return this.#stopElement.remove();
    }

    get creationStatus() {
        return this.#isCreated;
    }

    get onClose() {
        return this.#onClose.bind(this);
    }

    get stopElement() {
        return this.#stopElement;
    }

    get stopPropagation() {
        return this.#stopPropagation.bind(this);
    }
}
