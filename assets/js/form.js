function changeValue(id, change) {
  const input = document.getElementById(id);
  const currentValue = parseInt(input.value) || 0;
  const newValue = Math.max(0, currentValue + change);
  input.value = newValue;
}

function togglePassword() {
  const passwordInput = document.getElementById("password");
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
  } else {
    passwordInput.type = "password";
  }
}

function validateForm() {
  const dateDebut = new Date(document.getElementById("date_debut").value);
  const dateFin = new Date(document.getElementById("date_fin").value);

  if (dateFin <= dateDebut) {
    alert("La date de fin doit être postérieure à la date de début");
    return false;
  }

  // Vous pouvez ajouter d'autres validations ici

  alert("Formulaire envoyé avec succès!");
  return true;
}

// Définir les dates par défaut
document.addEventListener("DOMContentLoaded", function () {
  const today = new Date();
  const tomorrow = new Date();
  tomorrow.setDate(today.getDate() + 1);

  const formatDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
  };

  document.getElementById("date_debut").value = formatDate(today);
  document.getElementById("date_fin").value = formatDate(tomorrow);
});
