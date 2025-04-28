document.addEventListener("DOMContentLoaded", function () {
  // Gestion des boutons de filtre
  const filtreBtns = document.querySelectorAll(".filtre-btn");

  filtreBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      // Retirer la classe active de tous les boutons
      filtreBtns.forEach((b) => b.classList.remove("active"));
      // Ajouter la classe active au bouton cliqué
      this.classList.add("active");
    });
  });
});
