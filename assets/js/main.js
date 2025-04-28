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

  // ---------------------------------
  // AJOUT pour "Créer un compte et approuver"
  // ---------------------------------

  let isLoggedIn = false; // METS true ici si connecté, false sinon

  document
    .getElementById("crea-compte-approuver")
    .addEventListener("click", function (event) {
      event.preventDefault(); // Empêche l'envoi du formulaire normalement

      if (isLoggedIn) {
        // Récupérer les valeurs du formulaire
        const nombrePersonnes = document.getElementById("nombre-prsn").value;
        const destination = document.getElementById("destination").value;
        const logement = document.getElementById("logement").value;
        const transport = document.getElementById("transport").value;
        const date = document.getElementById("date").value;

        // Récupérer le filtre actif
        const filtreBtn = document.querySelector(".filtre-btn.active");
        const filtre = filtreBtn ? filtreBtn.textContent : "";

        // Construire l'URL avec les paramètres
        const url = `recom.html?personnes=${nombrePersonnes}&destination=${destination}&logement=${logement}&transport=${transport}&date=${date}&filtre=${filtre}`;

        // Rediriger vers recom.html avec les paramètres
        window.location.href = url;
      } else {
        alert("Veuillez vous connecter pour continuer !");
        window.location.href = "auth/login.php"; // Redirige vers la page de login
      }
    });
});
