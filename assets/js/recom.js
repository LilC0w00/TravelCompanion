// Data for listings
const listings = [
  {
    id: 1,
    title: "Athènes - Centre",
    rating: 4.9,
    location: "Lagonissi Grand Beach",
    type: "Appartement",
    description:
      "Appartement confortable | 1 lit double 10 minutes à pied de la plage",
    price: 61,
    image: "/api/placeholder/300/200",
    host: {
      name: "Marie",
      image: "/api/placeholder/60/60",
    },
  },
  {
    id: 2,
    title: "Athènes - Aéroport",
    rating: 4.9,
    location: "Grand rue",
    type: "Appartement",
    description:
      "Appartement confortable | 1 lit double 10 minutes à pied de l'aéroport",
    price: 161,
    image: "/api/placeholder/300/200",
    host: {
      name: "Julien",
      image: "/api/placeholder/60/60",
    },
  },
  {
    id: 3,
    title: "Athènes - Plage",
    rating: 4,
    location: "Lagonissi Grand Beach",
    type: "Appartement",
    description: "Appartement confortable | 1 lit double Plage privée",
    price: 601,
    image: "/api/placeholder/300/200",
    host: {
      name: "Sophie",
      image: "/api/placeholder/60/60",
    },
  },
];

// DOM elements
const listingsContainer = document.getElementById("listingsContainer");
const priceRange = document.getElementById("priceRange");
const priceDisplay = document.getElementById("priceDisplay");
const filterButtons = document.querySelectorAll(".filter-btn");
const searchForm = document.getElementById("searchForm");

// Display all listings on page load
window.addEventListener("DOMContentLoaded", () => {
  displayListings(listings);
  updatePriceDisplay();
});

// Update price display when slider is moved
priceRange.addEventListener("input", updatePriceDisplay);

function updatePriceDisplay() {
  priceDisplay.textContent = `${priceRange.value}€`;
}

// Handle filter button clicks
filterButtons.forEach((button) => {
  button.addEventListener("click", () => {
    // Toggle active class
    filterButtons.forEach((btn) => btn.classList.remove("active"));
    button.classList.add("active");

    // Filter listings based on selected filter
    const filter = button.dataset.filter;
    let filteredListings = [...listings];

    if (filter === "plage") {
      filteredListings = listings.filter(
        (listing) =>
          listing.location.toLowerCase().includes("beach") ||
          listing.title.toLowerCase().includes("plage")
      );
    } else if (filter === "montagne") {
      // Example filter - in a real app, you'd have this data
      filteredListings = listings.filter((listing) =>
        listing.title.toLowerCase().includes("montagne")
      );
    } else if (filter === "pasCher") {
      filteredListings = listings.filter((listing) => listing.price < 100);
    }

    displayListings(filteredListings);
  });
});

// Handle form submission
searchForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const destination = document.getElementById("destination").value;
  const accommodation = document.getElementById("accommodation").value;
  const transport = document.getElementById("transport").value;
  const departDate = document.getElementById("departDate").value;
  const personCount = document.getElementById("personCount").value;

  // In a real app, you would send this data to a server
  // For now, just log it
  console.log({
    destination,
    accommodation,
    transport,
    departDate,
    personCount,
  });

  // Filter listings based on selected destination
  const filteredListings = listings.filter((listing) =>
    listing.title.toLowerCase().includes(destination.toLowerCase())
  );

  displayListings(filteredListings);
});

// Function to display listings
function displayListings(listingsArray) {
  listingsContainer.innerHTML = "";

  if (listingsArray.length === 0) {
    listingsContainer.innerHTML =
      "<p>Aucun résultat trouvé. Veuillez modifier vos filtres.</p>";
    return;
  }

  listingsArray.forEach((listing) => {
    const listingElement = document.createElement("div");
    listingElement.classList.add("listing-card");

    listingElement.innerHTML = `
                  <div class="listing-image" style="background-image: url('${listing.image}')"></div>
                  <div class="listing-details">
                      <div class="listing-title">
                          ${listing.title}
                          <span class="listing-rating">★ ${listing.rating}</span>
                      </div>
                      <div class="listing-location">
                          <i>📍</i> ${listing.location}
                      </div>
                      <div class="listing-type">${listing.type}</div>
                      <div class="listing-description">${listing.description}</div>
                      <div class="listing-price">${listing.price}€ par nuit</div>
                  </div>
                  <div class="listing-host">
                      <div class="host-info">
                          <div class="host-image" style="background-image: url('${listing.host.image}')"></div>
                          <div class="host-name">Hôte : ${listing.host.name}</div>
                      </div>
                      <button class="contact-btn">En savoir +</button>
                      <button class="reserve-btn">RÉSERVER</button>
                  </div>
              `;

    listingsContainer.appendChild(listingElement);
  });
}
