/**
 * Supprime et remplace les Paramètres URL de
 * l'historique de Navigation -
 * @param {String} page
 * @param {URLSearchParams} paramName
 * @param {String} urlParams
 * @returns
 */
export function resetURL(page, paramName = null, urlParams = null) {
    urlParams?.delete(paramName)
    return window.history.replaceState({}, document.title, page);
}