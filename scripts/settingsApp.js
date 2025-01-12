import { Settings } from "./components/Settings/Settings.js";

const menu = document.querySelector("aside");
const profilePicture = menu.querySelector(".picture");
const tabs = menu.querySelectorAll("li");

const onReady = () => {
    new Settings(tabs, profilePicture);
};

if (window.readyState !== "loading") {
    onReady();
} else {
    window.addEventListener("DOMContentLoaded", onReady);
}
