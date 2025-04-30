// Effetto caricamento pagina utente
const PageContent = document.querySelector('#page-content');
window.addEventListener('load', () => {
  PageContent.classList.add('visible');
})

// Effetto scelta categoria prodotto
document.addEventListener("click", function () {
    const categories = document.querySelectorAll(".menu-category");

    categories.forEach(function (item) {
        item.addEventListener("click", function () {
            // Rimuovi l'effetto dagli altri
            categories.forEach(el => el.classList.remove("active"));
            // Applica l'effetto a quello cliccato
            item.classList.add("active");
        });
    });
});

// Scroll per tornare al top della pagina
window.addEventListener('scroll', function() {
  const backToTop = document.getElementById('back-to-top');
  if (window.scrollY > 300) {
    backToTop.style.opacity = '1';
  } else {
    backToTop.style.opacity = '0';
  }
});

// Codice per mostrare solo i prodotti appartenenti alla categoria corrispondente