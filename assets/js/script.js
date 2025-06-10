document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".animate-card");
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add("fade-in");
        }, index * 200);
    });
});