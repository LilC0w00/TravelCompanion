document.addEventListener("DOMContentLoaded", function () {
  // Gestion des boutons de filtre
  const filtreBtns = document.querySelectorAll(".filtre-btn");
  const filtreValue = document.getElementById("filtre-value");

  filtreBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      // Retirer la classe active de tous les boutons
      filtreBtns.forEach((b) => b.classList.remove("active"));
      // Ajouter la classe active au bouton cliqué
      this.classList.add("active");
      // Mettre à jour la valeur du filtre
      filtreValue.value = this.getAttribute("data-filtre");
    });
  });

  // Animation des cartes
  const carts = document.querySelectorAll(".cart");
  carts.forEach((cart) => {
    cart.addEventListener("click", function () {
      window.location.href = `voyage/details.php?destination=${
        this.querySelector("h5").textContent
      }`;
    });
  });
});
