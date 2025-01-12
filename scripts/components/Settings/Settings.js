import { fetchJSON } from "../../functions/api.js";
import { debounceEvent, importThisModule } from "../../functions/dom.js";
import { Toaster } from "../Toaster.js";

export class Settings {
    /** @type {HTMLImageElement} */
    #editButton;
    /** @type {HTMLElement} */
    #profilePicture;
    /** @type {array} */
    #datasets = [];
    /** @type {HTMLElement} */
    #content;
    /** @type {array} */
    #userRecipesResponse = [];
    /** @type {object} */
    #settings = {
        profile: {
            title: "Mon Profil",
            template: "#toggle-parameters-layout",
            type: "toggle",
            item1: {
                target: "p",
                insert: "Activer les notifications",
                ids: {
                    target: "input",
                    id: "notifications",
                    name: "notifications",
                    label: "notifications",
                },
            },

            item2: {
                target: "p",
                insert: "Recevoir les newsletters",
                ids: {
                    target: "input",
                    id: "newsletters",
                    name: "newsletters",
                    label: "newsletters",
                },
            },
        },
        security: {
            title: "Sécurité",
            template: "#input-parameters-layout",
            type: "toggle",
            "Activer l'authentification à 2 facteurs": "toggle",
            "Modifier votre mot de passe": "toggle",
            "Modifier votre email": "toggle",
        },
        sharedcontent: {
            template: "#user-recipes-template",
            searchBarTemplate: "#searchbar-layout",
            title: "Mes Recettes",
            type: "carousel",
            "Mes recettes": "carousel",
            ids: {
                target: "input",
                id: "newsletters",
                name: "newsletters",
                label: "newsletters",
            },
        },
        favorites: {
            title: "Mes favoris",
            type: "carousel",
            "Mes recettes favorites": "carousel",
        },
        settings: {
            template: "#toggle-parameters-layout",
            title: "Paramètres",
            Autres: "toggle",
        },
    };
    /** @type {HTMLTemplateElement} */
    #inputTemplate;
    /** @type {HTMLTemplateElement} */
    #toggleTemplate;
    /** @type {NodeList} */
    #tabElements = [];
    /** @type {AbortController} */
    #controller;
    /** @type {string} */
    #url = "recipes/Process_PreparationList.php";
    /** @type {array} */
    #searchArray = [];
    /** @type {object} */
    #carousel;
    /** @type {HTMLElement} */
    #searchBar;
    /** @type {ObjectConstructor} */
    #CarouselModule;

    constructor(tabs, pictureHeader) {
        this.#tabElements = tabs;
        this.#editButton = pictureHeader.querySelector(".edit_img");
        this.#profilePicture = pictureHeader.querySelector(".profile_picture");
        this.#content = document.querySelector(".card");
        this.#toggleTemplate = document.querySelector(
            "toggle-parameters-layout"
        );
        this.#inputTemplate = document.querySelector("input-parameters-layout");

        this.#controller = new AbortController();

        this.#tabElements.forEach((subMenu) => {
            // const dataset = subMenu.dataset;
            // const dataset = subMenu.dataset.status;
            // if (dataset === undefined) subMenu.setAttribute("data-status", "");
            // for (let [key, value] of Object.entries(dataset)) {
            //     this.#datasets[key] = value;
            // }
            subMenu.addEventListener(
                "click",
                (e) => {
                    this.#hideContent(e.currentTarget);
                },
                { signal: this.#controller.signal }
            );
        });

        this.#profilePicture.addEventListener("click", (e) => {
            console.log(e);
        });

        this.#editButton.addEventListener("click", (e) => {
            console.log(e);
        });
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

    #hideContent(element) {
        const status = element.dataset.status;
        if (status === "active") return;

        this.#tabElements.forEach((element) => {
            element.setAttribute("data-status", "");
        });

        element.setAttribute("data-status", "active");

        this.#content.classList.add("hidden");
        this.#content.addEventListener(
            "animationend",
            (e) => {
                this.#showNewContent(
                    e.currentTarget,
                    element.dataset.element,
                    element
                );
            },
            { once: true }
        );
        // console.log(status);
        // for (let [datasetKey, datasetValue] of Object.entries(dataset)) {
        //     // if (datasetValue === "active") return;
        //     for (let arrayKey in this.#datasets) {
        //         if (
        //             arrayKey !== datasetKey &&
        //             this.#datasets[arrayKey] === "active"
        //         ) {
        //             const previewsActiveSubMenu =
        //                 element.parentElement.querySelector(
        //                     `[data-${arrayKey}]`
        //                 );
        //             previewsActiveSubMenu.dataset[arrayKey] = "";
        //             this.#datasets[arrayKey] = "";
        //         }
        //     }
        //     dataset[datasetKey] = "active";
        //     this.#datasets[datasetKey] = "active";
        //     // this.#content.classList.add("hidden");
        //     // this.#content.addEventListener("animationend", (e) => {
        //     //     this.#showNewContent(e.currentTarget, datasetKey);
        //     // });
        // }
    }

    async #showNewContent(card, datasetName, subMenuElement) {
        // Delete all curent content
        card.innerHTML = "";
        const title = document.querySelector("h1");

        // Accessing settings object
        for (const [menuName, value] of Object.entries(this.#settings)) {
            console.log(
                "datasetname => ",
                datasetName,
                "setting name",
                menuName,
                "value => ",
                value
            );

            // Checks dataset element name from the "li" and
            // grabs the matching object name
            if (datasetName === menuName) {
                // Modify page title
                title.innerText = value.title;
                // Verify what will be displayed to the user
                if (value.type === "toggle") {
                    const items = Object.entries(value);

                    items.forEach((element) => {
                        const template = document
                            .querySelector(value.template)
                            .content.firstElementChild.cloneNode(true);
                        if (element[0].startsWith("item")) {
                            const target = template.querySelector(
                                element[1].target
                            );
                            const input = template.querySelector(
                                element[1].ids.target
                            );
                            const label = template.querySelector("label");

                            target.innerText = element[1].insert;
                            input.id = element[1].ids.id;
                            input.name = element[1].ids.name;
                            label.htmlFor = element[1].ids.label;

                            this.#content.prepend(template);
                        }
                    });
                }

                if (value.type === "carousel") {
                    // Grab SQL response
                    if (this.#userRecipesResponse.length === 0) {
                        // Saving SQL request
                        this.#userRecipesResponse =
                            await this.#fetchUserRecipes();
                    }

                    // Display each elements using a template
                    this.#userRecipesResponse.forEach((element) => {
                        this.#displayElement(
                            element,
                            card,
                            subMenuElement,
                            value.template
                        );
                    });

                    // Create the carousel
                    if (!this.#carousel) {
                        // Saving Carousel module
                        this.#CarouselModule =
                            await importThisModule("Carousel");
                        // this.#carousel = await importThisModule("Carousel");

                        this.#carousel = this.#CarouselModule.create(card, {
                            visibleSlides: 3,
                            grid: true,
                            automaticScrolling: false,
                            listModeOnMobile: true,
                        });
                    } else {
                        console.log(this.#CarouselModule);
                        // this.#carousel.restyle;
                        this.#carousel = this.#CarouselModule.create(card, {
                            visibleSlides: 3,
                            grid: true,
                            automaticScrolling: false,
                            listModeOnMobile: true,
                        });
                        console.log("jai pourtant demandé le restyle");
                    }
                    // this.#carousel.create(card, {
                    //     visibleSlides: 3,
                    //     grid: true,
                    //     automaticScrolling: false,
                    //     listModeOnMobile: true,
                    // });

                    // Create searchBar
                    this.#createSearchBar(
                        card,
                        value.searchBarTemplate,
                        subMenuElement,
                        value.template
                    );
                }
            }
        }
        // this.#settings.forEach((setting) => {
        //     let template;
        //     console.log(setting);
        //     if (datasetName === setting) {
        //         template =
        //             this.#toggleTemplate.content.firstElementChild.cloneNode(
        //                 true
        //             );
        //     }
        // });
        card.classList.remove("hidden");
        // this.#controller.abort();
    }

    /**
     * Utilise le template pour générer la sticky searchBar
     * Un "click" event sera créé
     * @param {HTMLElement} element La carte contenant tout le contenu de la page
     * @param {string} templateId
     */
    #createSearchBar(element, searchBarTemplateId, menuName, templateId) {
        const searchBarTemplate = document
            .querySelector(searchBarTemplateId)
            .content.firstElementChild.cloneNode(true);
        const input = searchBarTemplate.querySelector("input");
        input.id = "user-searchbar";
        this.#searchBar = searchBarTemplate;

        element.prepend(searchBarTemplate);
        // element.previousElementSibling.append(searchBarTemplate);

        // Handle click event
        // searchBarTemplate.addEventListener(
        //     "change",
        //     this.#searchItems.bind(this)
        // );
        debounceEvent(
            searchBarTemplate,
            "input",
            (e) => this.#searchItems(e, element, menuName, templateId),
            500
        );
    }

    #searchItems(event, target, menuName, templateId) {
        event.preventDefault();

        // target.querySelector(".carousel").remove();

        // Resets search array
        this.#searchArray = [];

        // Resets carousel items array
        this.#carousel._resetItemsArray;

        // Delete actual content from the DOM container
        this.#carousel.getContainer.innerHTML = "";

        // Display elements
        this.#userRecipesResponse.forEach((element) => {
            const normalizedValue = this.#normalizeInputs(
                event.target.value.toLowerCase()
            );
            const normalizedTitle = this.#normalizeInputs(
                element.title.toLowerCase()
            );

            if (normalizedTitle.includes(normalizedValue)) {
                this.#searchArray.push(element);
                const newElement = this.#displayElement(
                    element,
                    target,
                    menuName,
                    templateId,
                    true
                );
                // console.log(this.#carousel);
                this.#carousel.appendToContainer(newElement);
                this.#carousel.setStyle();
            }
        });

        console.log(this.#userRecipesResponse);
        // const searchBar = event.currentTarget.firstElementChild;
        // searchBar.classList.add("open");
        // debounceEvent(, "input", this.#function, "1000")
        // console.log(this.#searchArray);
        // this.#carousel.create(target, {
        //     visibleSlides: 3,
        //     grid: true,
        //     automaticScrolling: false,
        //     listModeOnMobile: true,
        // });
        // target.prepend(this.#searchBar);

        // target.prepend(event.target);
    }

    #normalizeInputs(input) {
        const replacements = {
            à: "a",
            â: "a",
            ä: "a",
            ç: "c",
            é: "e",
            è: "e",
            ê: "e",
            ë: "e",
            î: "i",
            ï: "i",
            ô: "o",
            ö: "o",
            ù: "u",
            û: "u",
            ü: "u",
            ÿ: "y",
        };
        let normalized = input;
        for (const [accented, unaccented] of Object.entries(replacements)) {
            const regex = new RegExp(accented, "g");
            normalized = normalized.replace(regex, unaccented);
        }
        return normalized;
    }

    /**
     * Crer chaque élément du carousel
     * Un event "click" sera créé pour le toggle
     * @param {object} element
     * @param {HTMLElement} target
     * @param {HTMLElement} subMenuElement
     * @param {string} templateId
     */
    #displayElement(element, target, subMenuElement, templateId, bool = false) {
        // Retrieve selectors associated with the fetched data
        const elems = JSON.parse(subMenuElement.dataset.elements);

        const template = document
            .querySelector(templateId)
            .content.firstElementChild.cloneNode(true);

        const href = `./recipes/read.php?id=${element["recipe_id"]}`;
        const deleteHref = `./recipes/delete_recipes.php?id=${element["recipe_id"]}`;
        const modifyHref = `./recipes/update_recipes.php?id=${element["recipe_id"]}`;

        // Modify non-generique elements
        for (const [key, selector] of Object.entries(elems)) {
            const selectorTarget = template.querySelector(selector);
            switch (key) {
                case "title":
                    selectorTarget.innerText = element[key];
                    break;

                case "img_path":
                    selectorTarget.src = element[key];
                    break;

                case "youtubeID":
                    selectorTarget.id = element[key];
                    break;

                case "href":
                    selectorTarget.href = href;
                    break;

                case "delete-href":
                    selectorTarget.href = deleteHref;
                    break;

                case "modify-href":
                    selectorTarget.href = modifyHref;
                    break;

                default:
                    break;
            }
        }

        // Sets toggle input & label id/name
        const input = template.querySelector("input");
        const label = template.querySelector("label");
        const toggle = template.querySelector(".toggle");

        input.id = "toggle" + element["recipe_id"];
        input.name = "toggle" + element["recipe_id"];
        label.htmlFor = "toggle" + element["recipe_id"];

        // Turn ON/OFF toggle if SQL request mentions it
        if (element.is_enabled && element.is_enabled === 1) {
            input.setAttribute("checked", "");
        }

        // Append the item to the DOM
        target.append(template);

        // Create click event on toggle
        toggle.addEventListener("click", (e) => {
            this.#handleToggle(e, input, element["recipe_id"], element);
        });

        if (bool === true) {
            console.log("je return le template");
            return template;
        }
    }

    /**
     * Active ou désactive le toggle
     * Le status sera sauvegardé dans la Database
     * Le status sera aussi sauvegardé directement dans l'array this.#userRecipesResponse
     * pour ne pas avoir à refaire une requête SQL
     * @param {HTMLElement} element
     * @param {number} id
     */
    async #handleToggle(event, element, id, arrayElem) {
        event.preventDefault();

        element.toggleAttribute("checked");

        const params = {
            is_enabled: element.checked,
            session_name: "RECIPE_STATUS",
            any_post: "enable_recipe",
            recipe_id: id,
        };

        const response = await fetchJSON(this.#url, {
            method: "POST",
            json: params,
        });
        // console.log(response);
        // if (element.checked) console.log("checkied");

        // Saving new OFFLINE data
        element.checked
            ? (arrayElem.is_enabled = 1)
            : (arrayElem.is_enabled = 0);
    }

    /**
     * Récupère en une requête toutes les recettes de l'utilisateur
     * @param {HTMLElement} subMenuElement
     */
    async #fetchUserRecipes() {
        // const userId = subMenuElement.getAttribute("name").split("-")[1];
        const params = {
            fields: [
                "r.title",
                "i.img_path",
                "r.recipe_id",
                "i.youtubeID",
                "u.user_id",
                "r.is_enabled",
            ],
            table: ["recipes r"],
            join: {
                "images i": "r.recipe_id = i.recipe_id",
                "users u": "u.user_id = :recipe_id",
                // "users u": `u.user_id = ${userId}`,
            },
            date: ["DATE_FORMAT(r.created_at, '%d/%m/%Y') as recipe_date"],
            where: {
                conditions: {
                    "r.author": "= u.email",
                },
            },
            error: ["Erreur dans la récupération des recettes"],
            fetchAll: true,
            // silentExecute: true,
            permission: true,
            session_name: "ACCOUNT_RECIPES",
        };
        try {
            const response = await fetchJSON(this.#url + "?query=1&id=none", {
                method: "POST",
                json: params,
            });
            if (response.ok === false) {
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
            return response;
        } catch (error) {
            new Toaster(error.message, "Erreur");
        }
    }
}
