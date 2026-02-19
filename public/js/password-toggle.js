document.addEventListener("DOMContentLoaded", () => {
    const toggles = document.querySelectorAll(".js-toggle-password");
    toggles.forEach((toggle) => {
        const targetId = toggle.getAttribute("data-target");
        const input = targetId ? document.getElementById(targetId) : null;
        if (!input) return;

        toggle.addEventListener("click", () => {
            const isHidden = input.type === "password";
            input.type = isHidden ? "text" : "password";
            toggle.classList.toggle("is-visible", isHidden);
            toggle.setAttribute(
                "aria-label",
                isHidden ? "Sembunyikan sandi" : "Tampilkan sandi"
            );
        });
    });
});
