document.addEventListener("DOMContentLoaded", () => {
    const inputs = document.querySelectorAll(".js-file-input");
    inputs.forEach((input) => {
        const feedback = input.parentElement?.querySelector("[data-file-feedback]");
        const maxMb = Number(input.dataset.maxMb || 4);
        const maxBytes = maxMb * 1024 * 1024;
        const accept = (input.getAttribute("accept") || "")
            .split(",")
            .map((item) => item.trim().toLowerCase())
            .filter(Boolean);

        const setFeedback = (message, isError) => {
            if (!feedback) return;
            feedback.textContent = message || "";
            feedback.classList.toggle("error", Boolean(isError));
        };

        input.addEventListener("change", () => {
            if (!input.files || !input.files.length) {
                setFeedback("", false);
                return;
            }

            const file = input.files[0];
            const name = file.name.toLowerCase();
            const isTypeOk =
                !accept.length ||
                accept.some((ext) => name.endsWith(ext));

            if (!isTypeOk) {
                setFeedback("Format file tidak didukung.", true);
                input.value = "";
                return;
            }

            if (file.size > maxBytes) {
                setFeedback(`Ukuran file terlalu besar (maks ${maxMb}MB).`, true);
                input.value = "";
                return;
            }

            const sizeKb = Math.round(file.size / 1024);
            setFeedback(`File dipilih: ${file.name} (${sizeKb} KB).`, false);
        });
    });
});
