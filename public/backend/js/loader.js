window.addEventListener("load", function () {
    const loader = document.getElementById("pageLoaderSubject");
    if (loader) {
        loader.style.transition = "opacity 0.4s ease";
        loader.style.opacity = "0";
        setTimeout(() => loader.remove(), 400);
    }
});
