import { fetchJSON } from "../../functions/api.js";
import { createElement, importThisModule } from "../../functions/dom.js";
import { resetURL } from "../../functions/url.js";
// import { BubbleCreativePlugin } from "../BubbleCreativePlugin.js"
import { ErrorHandler } from "../ErrorHandler.js";
import { Toaster } from "../Toaster.js";
// import { DrawerTouchPlugin } from "./DrawerTouchPlugin.js"

/**
 * @typedef {object} Ingredient
 */
export class IngredientsFrom {
    /** @type {Number} */
    #count = 0;
    /** @type {Ingredient[]} Ingredient List*/
    #list = [];
    /** @type {Ingredient[]} Ingredient */
    #ingredient = [];
    /** @type {HTMLFormElement} */
    #form;
    /** @type {string} */
    #endpoint;
    /** @type {HTMLTemplateElement} */
    #ingredientTemplate;
    /** @type {HTMLTemplateElement} */
    #recipeStepsTemplate;
    /** @type {HTMLElement} */
    #gridContainer;
    /** @type {HTMLButtonElement} */
    #addStepsButton;
    /** @type {HTMLElement} */
    #target;
    #targets = [];
    /** @type {object} */
    #elements;
    /** @type {HTMLButtonElement} */
    #formButton;
    /** @type {Array} Individual ingredient */
    #ingredientList = [];
    /** @type {Object} The whole preparation card list */
    #preparationList = {};
    /** @type {Array} Error list */
    #error = [];
    /** @type {String} */
    #url;
    #allowedFiles = "image/jpeg, image/png, image/jpg, image/gif";
    /** @type {Boolean} */
    #isSentAlready = false;
    /** @type {ErrorHandler} */
    #errorHandler;
    /** @type {DrawerTouchPlugin} */
    #touchPlugin;
    /** @type {BubbleCreativePlugin} */
    #bubbleCreativeMenu;
    #file;
    #imagePreview;
    /** @type {Object} */
    #previewsButton;
    #stepCarousel;

    /** @type {Object} */
    #nextButton;
    /** @type {HTMLElement} */
    #tabulation = document.querySelector(".tabulation");
    /** @type {StepsHandlerPlugin} */
    #stepHandlerPlugin;
    /**
     * @typedef {Object} DataItem
     * @property {string} class - La classe CSS associée à l'étape.
     * @property {string} data - Le nom de l'étape.
     * Un tableau d'objets représentant les étapes avec leurs classes CSS associées.
     * @type {DataItem[]}
     */
    #datas = [
        { class: ".js-one", data: "step1" },
        { class: ".js-two", data: "step2" },
        { class: ".js-three", data: "step3" },
        { class: ".js-four", data: "step4" },
        { class: ".js-five", data: "final" },
    ];

    /**
     * @param {Ingredient[]} list
     * // ATTENTION !! // Il ne peut y avoir qu'une seule option possible
     * @param {Boolean} [options.post=true] Permet d'envoyer des données lors de la création de recette - par défaut : true
     * @param {Boolean} [options.get=false] Permet de modifier une recette déjà existante - par défaut : false
     */
    constructor(list, options = {}) {
        this.#list = list;
        this.#list = this.#list.filter((k, v) => k !== "");
        this.options = Object.assign(
            {},
            {
                post: true,
                get: false,
            },
            options
        );
        this.options.get ? (this.options.post = false) : null;
        this.#gridContainer = document.querySelector(".card_container");
        this.#addStepsButton = this.#gridContainer.querySelector(".plus");
        // this.#recipeStepsTemplate = document.querySelector(document.dataset.steps_template)

        // Loading plugin and create elements
        this.#importPlugin();

        // Evènements
        // window.addEventListener("DOMContentLoaded", () => {
        this.#addStepsButton.addEventListener("click", (e) => {
            e.preventDefault();
            // Hide the button if needed
            let count = this.#addSteps(e);
            if (count >= 5) {
                this.#addStepsButton.disabled = true;
                this.#addStepsButton.classList.add("hidden");
                this.#addStepsButton.remove();
            }
        });
        // });

        // const secondarySubmit = document.querySelector('#step-button-right-corner')
        // secondarySubmit.addEventListener('submit', e => {
        //     e.preventDefault()
        //     console.log(this.#submitionStep)
        //     console.log(e)
        //     return
        //     if (this.#submitionStep === 5) {
        //         if (!this.#errorHandler.checkInputs(e)) {
        //             // Si une erreur est détectée lors de l'envoi en mode mobile
        //             // et que le drawer est ouvert, il sera fermé.
        //             this.#touchPlugin.resetStates
        //             // Afficher le tooltip en fonction du paramétrage :
        //             // Si une input de type INT est en erreur ou empty
        //             this.#errorHandler.triggerToolTip(e)
        //             return
        //         }
        //         this.#onSubmit(e)
        //     }
        // // }, { once: true, signal: controller.signal } )
        // })
        // window.onpopstate = (e) => {
        //     e.preventDefault()
        //     if (window.location.hash === '#username' || window.location.hash.startsWith('#')) {
        //         closeMenu(e)
        //         console.log('closing menu cause username || #  recipe')
        //         return
        //     }
        //     console.log('else popstate recipe')
        // }
    }

    /**
     * Importe le Touch Plugin -
     * !! ATTENTION !! Il est important de le faire
     * dans cette fonction asynchrone dû à un conflit avec 'this'
     */
    async #importPlugin() {
        // Delay import until we have an append content
        // do {
        //    break
        // } while (this.form == undefined)
        // const Carousel = await importThisModule('Carousel')
        // this.#stepCarousel =  Carousel.create(document.querySelector('#recipe_creation'), {
        //     visibleSlides: 1,
        //     automaticScrolling: false,
        //     pagination: false,
        // })
        // Handle desktop/tablet and next/previews buttons step creation and submit
        this.#stepHandlerPlugin = await importThisModule(
            "StepsHandlerPlugin",
            this,
            "RecipePreparation"
        );
        // Handle mobile touch and DOM refactor on resize
        this.#touchPlugin = await importThisModule(
            "DrawerTouchPlugin",
            this,
            "RecipePreparation"
        );
    }

    #pushThisElements(array, items, objectKey) {
        for (const [element, key] of Object.entries(array)) {
            key.push(items);
        }
        if (!array.step1) {
            array[objectKey] = [items];
        }
        return array[objectKey];
    }

    /**
     * Rajoute des textarea à la demande de l'utilisateur -
     * Un nombre de 4 peut être rajouté au maximum -
     * @param {PointerEvent} event
     * @param {Number} count Permet de définir le nombre de textareas rajoutés -
     * @returns
     */
    #addSteps(event) {
        const recipeStepsTemplate = this.#gridContainer
            .querySelector("#recipe-input-template")
            .content.firstElementChild.cloneNode(true);
        const forAttribute =
            event.currentTarget.previousElementSibling.firstElementChild
                .htmlFor;

        let newIdNumber = forAttribute.split("_")[1];
        let count = newIdNumber;

        while (count < 6) {
            if (newIdNumber) newIdNumber++;

            const textarea = recipeStepsTemplate.querySelector("textarea");
            textarea.id = "step_" + newIdNumber;
            textarea.name = "step_" + newIdNumber;
            const label = recipeStepsTemplate.querySelector("label");
            label.htmlFor = "step_" + newIdNumber;
            label.innerText = `Etape ${newIdNumber}`;
            switch (newIdNumber) {
                case 3:
                    textarea.placeholder = `Renseignez votre troisième étape`;
                    break;
                case 4:
                    textarea.placeholder = `Renseignez votre quatrième étape`;
                    break;
                case 5:
                    textarea.placeholder = `Renseignez votre cinquième étape`;
                    break;
                case 6:
                    textarea.placeholder = `Renseignez votre sixième étape`;
                    break;
                default:
                    null;
                    break;
            }
            event.currentTarget.hash = textarea.id;
            event.currentTarget.insertAdjacentElement(
                "beforebegin",
                recipeStepsTemplate
            );
            return count;
        }
        return count;
    }

    /**
     * Ajoute un Ingrédient de la liste à la cible "element"
     * @param {HTMLElement} element
     */
    appendTo(element) {
        this.#form = element;
        this.#errorHandler = new ErrorHandler(this.#form, {
            whichInputCanBeEmpty: [
                "custom_ingredients",
                "step_3",
                "step_4",
                "step_5",
                "step_6",
                "file",
                "video_file",
                "video_link",
                "add_preparation",
            ],
            useMyOwnListener: true,
            inputsNotToAppendIcon: "#custom_ingredients",
            // inputsNotToAppendIcon: `#custom_ingredients, #${this.#stepHandlerPlugin.getNextButton.firstElementChild.id}, #${this.#stepHandlerPlugin.getPreviewsButton.firstElementChild.id}`
            // inputsNotToAppendIcon: `#custom_ingredients, #${this.#nextButton.button.firstElementChild.id}, #${this.#previewsButton.button.firstElementChild.id}`
        });
        this.#endpoint = this.#form.dataset.endpoint;
        this.#ingredientTemplate = document.querySelector(
            this.#form.dataset.template
        );
        this.#target = document.querySelector(this.#form.dataset.target);
        this.#targets = document.querySelectorAll(".js-ingredient-group");
        this.#elements = JSON.parse(this.#form.dataset.elements);
        this.#file = this.#form.querySelector(".file-uploader");
        this.#imagePreview = this.#form.querySelector(".profile-picture");
        // this.#element = JSON.parse(`{"ingredient": ".js-value"}`)
        // this.#formValidationButton = this.#form.querySelector('#button')
        this.#formButton = this.#form.querySelector(".js-add-custom");
        this.#list.forEach((ingredient) => {
            if (ingredient === "") return;
            this.ingre = ingredient;
            const savedIngredient = new Ingredient(this);
            this.#ingredient = savedIngredient.element;
            this.#target.prepend(this.#ingredient);
        });

        this.#form.addEventListener("submit", (e) => {
            e.preventDefault();

            if (!this.#errorHandler.checkInputs(e)) {
                // Si une erreur est détectée lors de l'envoi en mode mobile
                // et que le drawer est ouvert, il sera fermé.
                this.#touchPlugin.resetStates;
                // Afficher le tooltip en fonction du paramétrage :
                // Si une input de type INT est en erreur ou empty
                this.#errorHandler.triggerToolTip(e);
                return;
            }
            this.#onSubmit(e);
        });

        this.#file.addEventListener("change", (e) => {
            e.preventDefault();
            const image = URL.createObjectURL(e.target.files[0]);
            this.#imagePreview.style.backgroundImage = `url(${image})`;
        });
        // if (this.options.post) {
        //     this.#form.addEventListener('submit', e => {
        //         e.preventDefault()
        //         if (!passedInputs.checkInputs) return
        //         this.#onSubmit(e)
        //     })
        // }
        // if (this.options.get) {
        //     this.#form.addEventListener('submit', e => {
        //         e.preventDefault()
        //         if (!passedInputs.checkInputs) return
        //         this.#onRecipeUpdate(e)
        //     })
        // }
        // const bubbleMenu = document.querySelector('.bubble-menu')

        // if (bubbleMenu) this.#bubbleCreativeMenu = new BubbleCreativePlugin(this)

        this.#formButton.addEventListener(
            "click",
            this.#addNewIngredient.bind(this)
        );
    }

    /**
     * Ajoute un Ingrédient à la liste lorsqu'il est créé par l'utilisateur"
     * @param {PointerEvent} e
     * @returns si les inputs ne sont pas remplies
     */
    #addNewIngredient(e) {
        e.preventDefault();
        const input = e.target.previousElementSibling;
        if (!this.#isInputChecked(input)) {
            return;
        }
        this.ingre = input.value;
        this.#ingredient = new Ingredient(this);
        this.#target.prepend(this.#ingredient.element);
        this.#list.push(this.#ingredient.element.innerText);
        this.onUpdate("ingredients", this.#list);
        input.value = "";

        this.#formButton.removeEventListener(
            "click",
            this.#addNewIngredient.bind(this)
        );
    }

    /**
     * Récupère l'étape à laquelle l'utilisateur s'est arrêté
     * @type {Number}
     */
    get currentSubmitionStep() {
        return this.#stepHandlerPlugin.currentSubmitionStep;
    }

    /**
     * Récupère les données des étapes et les classes des éléments associés
     * @type {DataItem}
     */
    get datas() {
        return this.#datas;
    }

    /** @type {HTMLElement} */
    get gridContainer() {
        return this.#gridContainer;
    }

    /**
     * @returns {HTMLTemplateElement}
     */
    get ingredientTemplate() {
        return this.#ingredientTemplate;
    }

    /** @type {HTMLElement} */
    get tabulation() {
        return this.#tabulation;
    }

    /**
     * Retourne le error handler
     * @returns {Object<ErrorHandler>}
     */
    get errorHandler() {
        return this.#errorHandler;
    }

    /**
     * Récupère le TouchPlugin de la recette
     * @returns {Object<DrawerTouchPlugin>}
     */
    get touchPlugin() {
        return this.#touchPlugin;
    }

    /**
     * Retourne 'this.ingre' qui est instancié lors de la création d'un ingrédient
     * @returns {String}
     */
    get ingredient() {
        return this.ingre;
    }

    /**
     * Retourne l'emplacement HTML qui servira de zone d'insertion
     * @returns {HTMLElement}
     */
    get form() {
        return this.#form;
    }

    /**
     * Retourne la fonction #onSubmit(event) avec son event en paramètre
     * @Function onSubmit(event)
     */
    get onSubmit() {
        return (event) => {
            return this.#onSubmit(event);
        };
    }

    /**
     * Retourne le count avec +1
     * @returns {Number}
     */
    get count() {
        return this.#count++;
    }

    /**
     * @type {Array}
     * @param {String} item
     */
    set listPush(item) {
        this.#list.push(item);
    }

    /**
     *
     * @param {HTMLElement} ingredient
     */
    #onIngredientDelete() {
        // ingredient.addEventListener('delete', e => {
        this.#list = this.#list.filter((i) => i !== e.detail.innerText);
        this.onUpdate("ingredients", this.#list);
        // }, {once: true})
    }
    // #onIngredientDelete(ingredient) {
    //     ingredient.addEventListener('delete', e => {
    //         this.#list = this.#list.filter((i) => i !== e.detail.innerText)
    //         this.#onUpdate('ingredients', this.#list)
    //     }, {once: true})
    // }

    /**
     * Retourne la liste d'ingrédients
     * @returns {Array}
     */
    get ingredientList() {
        return this.#list;
    }

    get gridContainer() {
        return this.#gridContainer;
    }

    /**
     * Modifie la liste établie par un nouvel Array
     * @type {Array}
     * @param {Array} newList
     */
    set setIngredientList(newList) {
        this.#list = newList;
    }

    /**
     * Sauvegarde toute la liste de préparation dans un
     * localStorage 'preparationList' pour une récupération dans la database -
     * Toutes les inputs sont envoyées par fetch dans la DB et la liste
     * est envoyée telle-quelle au format JSON dans 'custom_ingredient'
     * Le serveur devra renvoyer un objet {status: 'success'} encodé au format JSON
     * pour que cela fonctionne
     * S'il renvoie un array d'erreur, elles devront être traitées
     * @param {SubmitEvent} e
     */
    async #onSubmit(e) {
        let form;

        // Si l'on souhaite appelle la méthode depuis
        // un évènement qui n'utilise pas de "submit" :
        // On utilise un event target plutôt que le html form
        e instanceof HTMLElement ? (form = e) : (form = e.target);
        let data = new FormData(form);
        let url = this.options.get ? this.#url : "Process_PreparationList.php";
        // Modification de la clé 'custom_ingredient'
        // pour pouvoir faire passer la liste dynamique des ingrédients
        // ajoutés par l'utilisateur au format JSON dans la
        // database en même-temps que les données inputs
        // if (!confirm('Voulez-vous envoyer votre recette ?')) {
        //     // no
        //     return
        // }
        if (!this.#modifyFormDataValues(form, data)) return;
        // return
        try {
            if (!this.#isSentAlready) {
                this.#ingredientList = await fetchJSON(url, {
                    method: "POST",
                    // json: data,
                    body: data,
                    // img: true,
                });
                this.#ingredientList.img_status
                    ? (this.#isSentAlready = true)
                    : (this.#isSentAlready = false);
                this.#ingredientList.img_on_server
                    ? (this.#isSentAlready = true)
                    : null;
                if (this.options.get) {
                    if (this.#ingredientList.status === "success")
                        window.location.assign(
                            "../index.php?success=recipe-updated"
                        );
                    if (this.#ingredientList.update_status === "success")
                        window.location.assign(
                            "../index.php?success=recipe-updated"
                        );
                    if (
                        this.#ingredientList.status === "RCPUPDTSTMTEXECNT" &&
                        !this.#ingredientList.img_status
                    ) {
                        if (
                            !confirm(
                                "Aucune modification n'a été faite, souhaitez-vous continuer vers l'accueil ?"
                            )
                        ) {
                            // no
                            return;
                        }
                        resetURL("../index.php?success=recipe-unchanged");
                        location.reload();
                        // console.log(window.history)
                        // window.history.replaceState({}, document.title, page);
                        // window.location.assign('../index.php?success=recipe-unchanged')
                    } else if (
                        this.#ingredientList.status === "RCPUPDTSTMTEXECNT" &&
                        this.#ingredientList.img_status
                    ) {
                        // window.location.assign('../index.php?success=recipe-updated')
                        resetURL("../index.php?success=recipe-updated");
                        location.reload();
                    }
                }
                if (this.options.post) {
                    if (this.#ingredientList.status === "success") {
                        // window.location.assign('../index.php?success=recipe-shared')
                        resetURL("../index.php?success=recipe-shared");
                        location.reload();
                    }
                }
                this.#saveIngredientListToStorage();
            } else {
                // window.location.assign('../index.php?success=recipe-shared')
                resetURL("../index.php?success=recipe-shared");
                location.reload();
                new Toaster("Un envoi a déjà été effectué", "Erreur");
            }
        } catch (error) {
            new Toaster(error.message, "Erreur");
        }
    }

    /**
     * Sauvegarde toute la liste de préparation dans un
     * localStorage 'preparationList' pour une récupération dans la database -
     * Toutes les inputs sont envoyées par fetch dans la DB et la liste
     * est envoyée telle-quelle au format JSON dans 'custom_ingredient'
     * Le serveur devra renvoyer un objet {status: 'success'} encodé au format JSON
     * pour que cela fonctionne
     * S'il renvoie un array d'erreur, elles devront être traitées
     * @param {SubmitEvent} e
     */
    async #onRecipeUpdate(e) {
        const form = e.target;
        let data = new FormData(form);

        this.#modifyFormDataValues(form, data);

        try {
            this.#ingredientList = await fetchJSON(this.#url, {
                // this.#ingredientList = await fetchJSON('Process_Updated_PreparationList.php', {
                method: "POST",
                // json: data,
                body: data,
            });

            if (this.#ingredientList.status === "success") {
                // window.location.assign('../index.php?success=recipe-updated')
                resetURL("../index.php?success=recipe-updated");
                location.reload();
            }

            this.#saveIngredientListToStorage();
        } catch (error) {
            new Toaster(error.message, "Erreur");
        }
    }

    /**
     * Si l'utilisateur ajoute un fichier ou un ingrédient personnalisé :
     * Vérifie si le type de fichier et la taille sont acceptés.
     * Les ingrégients personnalisés seront toujours sauvegardé dans le localstorage -
     * @param {HTMLElement} form
     * @param {FormData} formData
     * @returns {Boolean}
     */
    #modifyFormDataValues(form, formData) {
        let status = true;
        for (let [key, value] of formData) {
            if (key === "custom_ingredients") {
                // this.#list.forEach(element => {
                //     if (element.includes("'")) {
                //         element = element.replace("'", "\\\'")
                //     }
                // })
                formData.set("custom_ingredients", this.#list);
            }
            if (key === "file" && value.name) {
                // check file type
                if (!this.#allowedFiles.includes(value.type)) {
                    new Toaster(
                        "Ce type de fichier n'est pas autorisé. Veuillez n'utiliser que : JPG, JPEG, PNG ou GIF",
                        "Erreur"
                    );
                    form.querySelector("input[name='file']").value = "";
                    return (status = false);
                }
                // check file size (< 10MB)
                if (value.size > 10 * 1024 * 1024) {
                    new Toaster(
                        "Votre fichier ne peut dépasser 10MB",
                        "Erreur"
                    );
                    form.querySelector("input[name='file']").value = "";
                    return (status = false);
                }
                // formData.set('file', this.#list)
            }
        }
        return status;
    }

    /**
     * Sauvegarde dans le localStorage les ingrédients ajoutés
     * par l'utilisateur -
     * Si le boutton submit est disabled, il sera réactivé -
     */
    #saveIngredientListToStorage() {
        this.#preparationList.formData = this.#ingredientList;
        this.#preparationList.ingredients = this.#list;
        this.onUpdate("preparationList", this.#preparationList);
        // const success = 'Votre préparation a été validée'
        this.#formButton.disabled = false;
    }

    /**
     * Vérifie que l'input utilisateur n'est pas vide
     * Ajoute la classe 'error' à l'input ID '#custom_ingredient'
     * @returns {Boolean} True => Si aucune erreur n'est trouvée
     * @returns {Boolean} False => Si au moins une erreur a été trouvée
     */
    #isInputChecked(input) {
        const body = this.#form.querySelector(".js-ingredient-input");
        const inputValue = input.value.toString().trim();

        if (inputValue === "") {
            const message = "Veuillez renseigner l'ingrédient à ajouter";
            body.classList.add("error");
            body.setAttribute("placeholder", "Saissisez votre ingrédient...");
            this.#error.push(message);
        } else {
            body.classList.remove("error");
            body.setAttribute("placeholder", "Votre ingrédient...");
        }

        if (this.#error.length >= 1) {
            for (const error of this.#error) {
                this.#error = this.#error.filter((t) => t !== error);
                new Toaster(error, "Erreur");
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * Sauvegarde un objet dans le localStorage
     * @param {String} storageName
     * @param {JSON} items
     */
    onUpdate(storageName, items) {
        // debugger
        localStorage.setItem(storageName, JSON.stringify(items));
    }

    /**
     * @param {string} url
     */
    set setUpdateAdress(url) {
        this.#url = url;
    }
}

class Ingredient {
    /** @type {Array} */
    #ingredientList;
    /** @type {Array} contient l'élément créé qui sera renvoyé sur la page */
    element = [];
    /** @type {HTMLTemplateElement} */
    #template;
    /** @type {Number} */
    #count;
    /** @type {Boolean} */
    #validationStatus = false;
    /** @type {Boolean} */
    #done = false;
    /** @typedef {Object} IngredientsFrom */
    #ingredient;
    /** @type {Array} contient les boutons modifier/supprimer/fermer */
    #newModifierButtons = [];
    /** @type {Array} contient les boutons valider/annuler */
    #validationItems = [];
    // #observer = []
    // #ratio = .3
    // #handleIntersect = (entries, observer) => {
    //     entries.forEach(entry => {
    //         console.log(entry)
    //         this.element.addEventListener('click', this.#onClick.bind(this))
    //         // if (entry.addEventListener('click') > this.#ratio) {
    //         //     this.#loadMore()
    //         // }
    //         console.log('je suis dans lobs')
    //     })
    // }
    // #options = {
    //     root: null,
    //     rootMargin: '0px',
    //     threshold: this.#ratio
    // }

    /**
     * Crer un élément HTML
     * @param {IngredientsFrom} ingredient
     * @param {Number} count
     */
    constructor(ingredient) {
        this.#ingredientList = ingredient;
        this.#ingredient = ingredient.ingredient;
        if (this.#ingredient === "") return;
        // this.#template = document.querySelector('#ingredient-template')
        this.#template = this.#ingredientList.ingredientTemplate;
        this.#count = ingredient.count;

        this.element = this.#template.content.firstElementChild.cloneNode(true);
        this.element.setAttribute("id", this.#count);
        this.element.setAttribute("name", "ingredient-" + this.#count);

        const p = this.element.querySelector("p");
        p.innerText = this.#ingredient;

        this.element.addEventListener("click", this.#onClick.bind(this));
        this.element.addEventListener("modify", this.#onModify.bind(this));
        this.element.addEventListener("validate", this.#onValidate.bind(this));
        this.element.addEventListener("canceled", this.#onCancel.bind(this));
        this.element.addEventListener("closeAction", this.#onClose.bind(this));

        this.element.addEventListener(
            "delete",
            (e) => {
                e.preventDefault();
                const item = e.detail.id;
                ingredient.setIngredientList = ingredient.ingredientList.filter(
                    (i, k) => k != item
                );
                ingredient.onUpdate("ingredients", ingredient.ingredientList);
                const message = `L'ingrédient ${p.innerText} a été supprimé avec succès`;
                new Toaster(message, "Succès");
            },
            { once: true }
        );
    }

    /**
     * Crer des éléments d'intéractions (supprimer/modifier/fermer)
     * L'élément clické sera mis en avant et poussé vers l'intérieur
     * Si il sort du champs de vision
     * @param {PointerEvent} e
     * @returns
     */
    #onClick(e) {
        e.preventDefault();
        if (this.#validationStatus || this.#done) {
            this.#validationStatus = false;
            this.#done = false;
            return;
        }
        if (!this.#newModifierButtons.element) {
            this.#newModifierButtons = new AttachmentToThis(this.element);
            this.element.append(this.#newModifierButtons.container);
            // Quick repaint - Permet d'avoir un style Right: 0 correct
            this.#newModifierButtons.container.offsetWidth;
            // End of repaint
            this.#elementStyle(this.#newModifierButtons.element);
            this.#elementZStyle(2);
        }
    }

    /**
     * Permet de forcer la position d'un élément
     * qui dépasse du bord droit en le poussant vers l'intérieur
     * et inversement quand il est à gauche
     * @param {HTMLElement} element
     */
    #elementStyle(element) {
        const drawer = document.querySelector(".show_drawer");
        const offsets = this.element.getBoundingClientRect();
        const cardOffsets = drawer?.getBoundingClientRect();

        if (
            cardOffsets?.right - 10 <
            offsets.left + this.#newModifierButtons.containerWidth
        ) {
            element.style.left = "unset";
            element.style.right = "0";
            return;
        }

        if (
            cardOffsets?.left - 10 <
            offsets.right - this.#newModifierButtons.containerWidth
        ) {
            element.style.left = "0";
            element.style.right = "unset";
        }
    }

    /**
     * Permet de modifier le zIndex d'un élément
     * @param {HTMLStyleElement} zIndex
     */
    #elementZStyle(zIndex) {
        this.element.style.zIndex = zIndex;
    }

    /**
     * Enregistre les données et crer les boutons de validations / cancel
     * La zone de texte sera automatiquement focus
     * @param {PointerEvent} e
     */
    #onModify(e) {
        e.preventDefault();
        // Permet d'instancier les données enregistrées
        this.data = this.element.firstElementChild.innerText;
        // Fin de l'enregistrement
        const valueArea = this.element.querySelector(".js-value");
        valueArea.focus();
        if (!this.#validationItems.element) {
            this.#newModifierButtons.element.remove();
            this.#validationItems = new UserValidations(this.element);
            this.element.append(this.#validationItems.element);
            this.#validationStatus = true;
        }
    }

    /**
     * Sauvegarde le nouvel input utilisateur
     * dans un localStorage en cas de refresh
     * et pour une utilisation future
     * @param {PointerEvent} e
     */
    #onValidate(e) {
        e.preventDefault();
        const item = e.detail.id;
        let data = this.element.firstElementChild.innerText;
        if (data !== "") {
            this.#setValidation(item);
            this.#ingredientList.listPush = data;
            this.#ingredientList.onUpdate(
                "ingredients",
                this.#ingredientList.ingredientList
            );
        } else {
            this.#setValidation(item);
            this.#ingredientList.onUpdate(
                "ingredients",
                this.#ingredientList.ingredientList
            );
            this.element.remove();
            this.#newModifierButtons.removeStopElement;
        }
    }

    /**
     * Supprime les boutons d'intéraction et repasse
     * la zone de texte en zIndex 0 -
     * Filtre les nouveaux inputs pour
     * permettre un enregistrement dans le localStorage
     * @param {String} item
     */
    #setValidation(item) {
        this.#removeInteractiveElements();
        this.#arrayReset();
        this.#validationStatus = true;
        this.data = null;
        this.#ingredientList.setIngredientList =
            this.#ingredientList.ingredientList.filter((i, k) => k != item);
    }

    /**
     * Reset les array des boutons d'intéractions
     * pour éviter une surcharge mémoire
     * Réinitialise le zIndex des élements HTML
     */
    #arrayReset() {
        this.#newModifierButtons = [];
        this.#validationItems = [];
        this.#elementZStyle("auto");
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
     * Supprime les boutons d'intéraction et
     * réinitialise les données préalablement enregistrées
     * en cas d'annulation utilisateur
     * @param {PointerEvent} e
     */
    #onCancel(e) {
        e.preventDefault();
        this.#removeInteractiveElements();
        this.#arrayReset();
        this.#done = true;
        this.element.firstElementChild.innerText = this.data;
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
        !this.data
            ? (this.data = this.element.firstElementChild.innerText)
            : (this.element.firstElementChild.innerText = this.data);
        this.#arrayReset();
    }

    get element() {
        return this.element;
    }

    get onClick() {
        return this.#onClick.bind(this);
    }
}

class AttachmentToThis {
    /** @type {Ingredient} item */
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
    #stop;

    /** @param {Ingredient} item */
    constructor(item) {
        this.#item = item;
        if (this.#element.length > 0) {
            return;
        }

        this.#container = document.querySelector(
            "#interactive-container-" + this.#item.id
        );

        // this.#container = createElement("div", {
        //     class: "interactive-container",
        //     id: "interactive-container-" + this.#item.id,
        //     contenteditable: false,
        // });
        // this.#element = createElement("div", {
        //     class: "interactive-container__elements",
        //     id: "attach-" + this.#item.id,
        //     contenteditable: false,
        // });
        // this.#modifier = createElement("img", {
        //     class: "interactive-elements__modify",
        //     name: "modify",
        //     id: "modify-" + this.#item.id,
        //     src: "../img/edit.svg",
        // });
        // this.#deleter = createElement("img", {
        //     class: "interactive-elements__delete",
        //     name: "delete",
        //     id: "delete-" + this.#item.id,
        //     src: "../img/bin.svg",
        // });
        // this.#closeButton = createElement("img", {
        //     class: "interactive-elements__close",
        //     name: "close",
        //     id: "close-" + this.#item.id,
        //     src: "../img/close.svg",
        // });
        // this.#stop = createElement("div", {
        //     class: "js-stops",
        //     name: "stop",
        //     id: "stop-" + this.#item.id,
        // });
        // this.#deleter.innerText = " DELETE ";
        // this.#closeButton.innerText = " CLOSE ";

        // document
        //     .querySelectorAll(".js-stop-appender")
        //     .forEach((stop) => stop.prepend(this.#stop));
        // // document.querySelector('.recipe').prepend(this.#stop)
        // this.#container.append(this.#element);

        // this.#element.append(this.#deleter);
        // this.#element.append(this.#closeButton);
        // this.#element.prepend(this.#modifier);
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

        this.#stop = this.#container.querySelector(".js-stops");
        this.#stop.id = "stop-" + this.#item.id;
        this.#stop.classList.remove("hide");

        this.#deleter.innerText = " DELETE ";
        this.#closeButton.innerText = " CLOSE ";

        document
            .querySelectorAll(".js-stop-appender")
            .forEach((stop) => stop.prepend(this.#stop));

        // document.querySelector('.recipe').prepend(this.#stop)
        // this.#container.append(this.#element);
        // this.#element.style.zIndex = "1000"
        // this.#element.style.position = "sticky"
        // do {
        // let styles = window.getComputedStyle(this.#element);
        // console.log(styles.zIndex, this.#element);
        // } while(this.#element.parentElement && (this.#element = this.#element.parentElement));

        this.#isCreated = true;

        this.#deleter.addEventListener("click", this.#onRemove.bind(this), {
            once: true,
        });
        this.#modifier.addEventListener("click", this.#onModify.bind(this));
        this.#closeButton.addEventListener("click", this.#onClose.bind(this));
        this.#stop.addEventListener("click", this.#onClose.bind(this), {
            once: true,
        });
        this.#element.addEventListener("click", this.#stopPropagation);
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
        this.#item.remove();
        this.#stop.remove();
        const deleteEvent = new CustomEvent(
            "delete",
            {
                detail: this.#item,
                cancelable: true,
                bubbles: false,
            },
            { once: true }
        );
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
        this.#item.firstElementChild.setAttribute("contenteditable", true);
        this.#item.firstElementChild.style.userSelect = "text";
        this.#item.firstElementChild.style.webkitUserSelect = "text";
        this.#item.firstElementChild.style.webkitUserModify = "read-write";
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
        const closeEvent = new CustomEvent("closeAction", {
            detail: this.#item,
            cancelable: true,
            bubbles: true,
        });
        this.#item.dispatchEvent(closeEvent);
        this.#item.firstElementChild.setAttribute("contenteditable", false);
        this.#item.firstElementChild.removeAttribute("style");
        this.#container.remove();
        this.#stop.remove();
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
        return this.#stop.remove();
    }

    get creationStatus() {
        return this.#isCreated;
    }

    get onClose() {
        return this.#onClose.bind(this);
    }

    get stopPropagation() {
        return this.#stopPropagation.bind(this);
    }
}

class UserValidations {
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
        const container = item.querySelector(".interactive-container");

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
        container.append(this.#element);

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
