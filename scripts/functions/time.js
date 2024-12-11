/**
 * Formate une date en chaîne de caractères selon le format "dd/mm/yyyy".
 * @param {Date} date - L'objet Date à formater.
 * @returns {string} La date formatée en chaîne de caractères.
 */
export function formatDate(date) {
    // Ajoute un zéro devant les jours à un chiffre
    const day = String(date.getDate()).padStart(2, "0");
    // Les mois commencent à 0
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
}
