/**
 * Remove and replace the URL Parameter from the history
 * delete the success/failed param in case of a browser return)
 * @param {string} page 
 * @param {string} paramName 
 * @returns 
 */
export function resetURL(page, paramName, urlParams) {
    urlParams.delete(paramName)
    return window.history.replaceState({}, document.title, page);
}