import { alertMessage } from "./dom.js";

/**
 * Fetch API au format JSON
 * @param {string} url
 * @param {RequestInit & {json?: object}} options
 * @returns {Promise}
 */
export async function fetchJSON(url = "", options = {}) {
    let headers = {
        Accept: "application/json",
        ...options.headers,
    };

    if (options.img) {
        headers["Content-Type"] = "multipart/form-data";
    }
    // if (!options.img) {
    //     headers = {
    //         Accept: "application/json",
    //         ...options.headers,
    //     };
    // } else {
    //     headers = {
    //         // Accept: 'image/jpeg',
    //         Accept: "application/json",
    //         ...options.headers,
    //     };
    //     headers["Content-Type"] = "multipart/form-data";
    // }
    // const headers = {
    //     Accept: 'application/json',
    //     Accept: 'image/jpeg',
    //     ...options.headers
    // }
    // if (options.json && !options.img) {
    //     try {
    //         options.body = JSON.stringify(Object.fromEntries(options.json));
    //     } catch (error) {
    //         options.body = JSON.stringify(options.json);
    //     }
    //     headers["Content-Type"] = "application/json; charset=UTF-8";
    // }
    if (options.json && !options.img) {
        Array.isArray(options.json)
            ? (options.body = JSON.stringify(Object.fromEntries(options.json)))
            : (options.body = JSON.stringify(options.json));
        // options.body = JSON.stringify(options.json);
        // options.body = JSON.stringify(Object.fromEntries(options.json));
        headers["Content-Type"] = "application/json; charset=UTF-8";
    }
    // if (options.json && !options.img) {

    //     options.body = JSON.stringify(Object.fromEntries(options.json));
    //     headers["Content-Type"] = "application/json; charset=UTF-8";
    // }
    // if (options.json && !(options.body instanceof FormData)) {
    //     try {
    //         // Si il est possible d'itérer (array)
    //         options.body = JSON.stringify(Object.fromEntries(options.json));
    //     } catch (error) {
    //         // En cas d'erreur, utiliser simplement `JSON.stringify(options.json)`
    //         options.body = JSON.stringify(options.json);
    //     }
    // }
    // if (options.img) {
    //     headers['Content-Type'] = 'image/jpeg'
    // }
    try {
        const response = await fetch(url, { ...options, headers });
        if (!response.ok) {
            throw new Error("Impossible de récupérer les données serveur", {
                cause: response,
            });
        }
        return response.json();
    } catch (error) {
        alertMessage(error.message);
    }
}
// export async function fetchJSON(url = "", options = {}) {
//     let headers = { Accept: "application/json", ...options.headers }; // Gestion des en-têtes pour FormData
//     if (options.body instanceof FormData) {
//         // Si le corps est un FormData, ne pas définir Content-Type car il est géré automatiquement
//         headers = { Accept: "application/json", ...options.headers };
//     } else {
//         // Gestion des en-têtes pour JSON
//         headers["Content-Type"] = "application/json; charset=UTF-8";
//     } // Conversion de `json` en chaîne JSON
//     if (options.json && !(options.body instanceof FormData)) {
//         try {
//             options.body = JSON.stringify(Object.fromEntries(options.json));
//             console.log("Données JSON envoyées:", options.body); // Journalisation des données JSON
//         } catch (error) {
//             console.error("Erreur de conversion JSON:", error);
//             options.body = JSON.stringify(options.json); // En cas d'erreur, utiliser simplement `JSON.stringify(options.json)`
//         }
//     }
//     try {
//         const response = await fetch(url, { ...options, headers });
//         if (!response.ok) {
//             throw new Error("Impossible de récupérer les données serveur", {
//                 cause: response,
//             });
//         }
//         return response.json();
//     } catch (error) {
//         alertMessage(error.message);
//     }
// }

/**
 * Fetch API au format Text (récupérer une modale ou un template)
 * @param {string} url
 * @param {string} targt
 * @returns {Promise}
 */
export async function fetchTemplate(url, targt) {
    const target = targt;
    const loadedTemplate = document.querySelector(target);
    try {
        if (!loadedTemplate) {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP Error! Status: ${response.status}`);
            }
            const htmlElement = document
                .createRange()
                .createContextualFragment(await response.text())
                .querySelector(target);
            if (!htmlElement) {
                throw new Error(
                    `L'élement ${target} n'existe pas à l'adresse : ${url}`
                );
            }
            // document.body.append(htmlElement)
            return htmlElement;
        }
        return loadedTemplate;
    } catch (error) {
        const alert = alertMessage(error.message);
        const container = document.querySelector(".toast-container");
        container.insertAdjacentElement("beforeend", alert);
    }
}
