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

  // Animation des cartes
  const carts = document.querySelectorAll(".cart");
  carts.forEach((cart) => {
    cart.addEventListener("click", function () {
      const destination = this.querySelector("h5").textContent;
      window.location.href = `/voyage/details.php?destination=${encodeURIComponent(
        destination
      )}`;
    });
  });

  // Correction pour "Créer un compte et approuver"
  const btnCreerCompte = document.getElementById("crea-compte-approuver");

  if (btnCreerCompte) {
    btnCreerCompte.addEventListener("click", function (event) {
      event.preventDefault(); // Empêche l'envoi du formulaire normalement

      // Récupérer les valeurs du formulaire
      const nombrePersonnes = document.getElementById("nombre-prsn").value;
      const destination = document.getElementById("destination").value;
      const logement = document.getElementById("logement").value;
      const transport = document.getElementById("transport").value;
      const date = document.getElementById("date").value;

      // Récupérer le filtre actif
      const filtreBtn = document.querySelector(".filtre-btn.active");
      const filtre = filtreBtn ? filtreBtn.textContent : "";

      // Redirection avec chemins absolus depuis la racine du site
      window.location.href = `/auth/auth.php?personnes=${encodeURIComponent(
        nombrePersonnes
      )}&destination=${encodeURIComponent(
        destination
      )}&logement=${encodeURIComponent(
        logement
      )}&transport=${encodeURIComponent(transport)}&date=${encodeURIComponent(
        date
      )}&filtre=${encodeURIComponent(filtre)}`;
    });
  }
});
