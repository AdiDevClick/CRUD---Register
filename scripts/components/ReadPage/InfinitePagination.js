import { fetchJSON, retrieveSearchParams } from "../../functions/api.js";
import { Toaster } from "../Toaster.js";
import { Comment } from "./Comment.js";

export class InfinitePagination {
    /** @type {Object} */
    #params = {};
    /** @type {HTMLElement} */
    #loader;
    /** @type {string} */
    #endpoint;
    /** @type {HTMLTemplateElement} */
    #template;
    /** @type {HTMLElement} */
    #target;
    /** @type {number} */
    #limit;
    /** @type {object} */
    #elements;
    /** @type {IntersectionObserver} */
    #observer;
    /** @type {Number} */
    #ratio = 0.3;
    #handleIntersect = (entries, observer) => {
        entries.forEach((entry) => {
            if (entry.intersectionRatio > this.#ratio) {
                // this.options.searchParams._reset = 0;
                console.log("Je suis dans mon OBSERVER");
                this.#loadMore();
            }
        });
    };
    #intersectOptions = {
        root: null,
        rootMargin: "0px",
        threshold: this.#ratio,
    };
    #loading = false;
    #page = 1;
    /** @type {object} */
    #SQLQueryElements = {};
    constructor(element, options = {}) {
        console.log(element, options);
        this.#loader = element;
        this.#template = document.querySelector(element.dataset.template);
        this.#endpoint = element.dataset.endpoint;
        this.#target = document.querySelector(element.dataset.target);
        this.#elements = JSON.parse(element.dataset.elements);
        this.#limit = JSON.parse(element.dataset.limit);
        this.options = Object.assign(
            {},
            {
                params: {},
                searchParams: {},
            },
            options
        );
        // window.addEventListener("DOMContentLoaded", () => {
        this.#observer = new IntersectionObserver(
            this.#handleIntersect,
            this.#intersectOptions
        );
        this.#observer.observe(this.#loader);
        // });
    }

    /**
     *
     * @param {*} reset
     * @returns
     */
    async #loadMore(reset = false) {
        console.log("je load MORE");
        console.log(reset);
        if (reset.reset) {
            this.options.searchParams["_reset"] = 1;
        } else {
            this.options.searchParams["_reset"] = 0;
        }
        if (!this.#loading) {
            try {
                this.#loading = true;
                // const searchParams = new URLSearchParams(this.#endpoint);
                // searchParams.set("_page", this.#page);
                // searchParams.set("_limit", this.#limit);
                this.options.searchParams["_page"] = this.#page;
                this.options.searchParams["_limit"] = 5;

                console.log(
                    retrieveSearchParams(
                        this.#endpoint,
                        this.options.searchParams
                    )
                );
                console.log(this.options.params);
                this.#SQLQueryElements = await fetchJSON(
                    retrieveSearchParams(
                        this.#endpoint,
                        this.options.searchParams
                    ),
                    // `${this.#target}?${this.#retrieveSearchParams()}`,
                    {
                        method: "POST",
                        json: this.options.params,
                    }
                );
                this.#page++;
                console.log(this.#SQLQueryElements);
                // const target = document.querySelector(".js-comments-target");

                // If "_reset=1" searchParams is set
                this.#onReset(this.#SQLQueryElements);

                // If empty or error
                if (this.#SQLQueryElements.error) {
                    this.#noMoreContent();
                }

                this.#createNewComments(this.#SQLQueryElements);
                this.#target.append(this.#loader);

                this.#loading = false;
            } catch (error) {
                console.log(error);
                new Toaster(error.cause.message, error.cause.status);
            }
        }
        return;
    }

    #onReset(comments) {
        if (!this.options.searchParams._reset == 1) return;
        console.log("JE RESET MON VISUEL");
        this.#target.classList.add("hidden");
        this.#target.addEventListener(
            "animationend",
            this.#onTransitionEnd.bind(this, comments),
            { once: true }
        );
    }

    /**
     * Déconnecte l'observeur et display un message de succès
     */
    #noMoreContent() {
        this.#page = 1;
        this.#disconnectObserver();
        throw new Error("Tout le contenu a été chargé", {
            cause: {
                message: "Tout le contenu a été chargé",
                status: "Succès",
            },
        });
    }

    #disconnectObserver() {
        if (this.#observer) {
            this.#loading = false;
            this.#observer.unobserve(this.#loader);
            this.#observer.disconnect();
            this.#loader.remove();
        }
        this.#observer.observe(this.#loader);
        return;
    }

    /**
     * Vide le contenu HTML de la target et le repasse à visible
     * Puis append le loader
     * @param {Array} comments
     * @param {AnimationEvent} event
     */
    #onTransitionEnd(comments, event) {
        console.log("Je CLEAR content");
        // Clears content
        // event.currentTarget.style.display = "none";
        event.currentTarget.innerHTML = "";

        // Append new content
        // const template =
        //     this.#template.content.firstElementChild.cloneNode(true);

        this.#createNewComments(comments);
        this.#target.append(this.#loader);

        event.currentTarget.classList.remove("hidden");
        // document.querySelector(".comment-form_container").append(this.#loader);
    }

    /**
     * Permet de créer et prepend les commentaires reçus
     * @param {Array} commentsArray
     */
    #createNewComments(commentsArray) {
        commentsArray.forEach((comment) => {
            const newComment = new Comment(comment, this.#elements);
            this.#target.append(newComment.element);
        });
    }

    get _loadMore() {
        return (params, searchParams, resetStatus) => {
            this._setSearchParamsOptions = searchParams;
            this._setParamsOptions = params;
            return this.#loadMore(resetStatus);
        };
    }

    /**
     * Permet de modifier les options params de l'objet
     * @param {object} searchParams
     */
    set _setSearchParamsOptions(searchParams) {
        this.options.searchParams = searchParams;
    }

    /**
     * Permet de modifier les options searchParams de l'objet
     * @param {object} params
     */
    set _setParamsOptions(params) {
        this.options.params = params;
    }
}
