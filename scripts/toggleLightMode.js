/**
 * Light / Dark Theme toggle
 */

const rootAttribute = document.documentElement;
const toggleButton = document.querySelector("#lightmode-toggle");

// Get the user's preference from localStorage
const userPreference = localStorage.getItem("light-theme");

if (!userPreference) {
    removeAttribute();
} else {
    setAttribute();
}

const onClick = () => {
    if (rootAttribute.attributes.getNamedItem("light-theme")) {
        removeAttribute();
        localStorage.removeItem("light-theme");
    } else {
        setAttribute();
        localStorage.setItem("light-theme", true);
    }
};

toggleButton.addEventListener("change", onClick);

function removeAttribute() {
    toggleButton.removeAttribute("checked");
    rootAttribute.removeAttribute("light-theme");
}

function setAttribute() {
    rootAttribute.setAttribute("light-theme", "");
    toggleButton.setAttribute("checked", "");
}
