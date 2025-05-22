// Effetto caricamento pagina utente
const PageContent = document.querySelector('#page-content');
window.addEventListener('load', () => {
  PageContent.classList.add('visible');
})

// Scroll per tornare al top della pagina
window.addEventListener('scroll', function() {
  const backToTop = document.getElementById('back-to-top');
  if (window.scrollY > 300) {
    backToTop.style.opacity = '1';
  } else {
    backToTop.style.opacity = '0';
  }
});

// Codice per mostrare solo i prodotti appartenenti alla propria categoria
document.addEventListener('DOMContentLoaded', () => {
  const menuItems = document.querySelectorAll('.menu-category');
  const waiting_box = document.querySelector('.waiting-box');
  const delivered_box = document.querySelector('.delivered-box');
  
  menuItems.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;

      // feedback visivo
      menuItems.forEach(i => i.classList.remove('active'));
      btn.classList.add('active');

      // Gestione visualizzazione della cronologia ordini
      if (cat === 'attesa') {
        waiting_box.classList.add('show');
        delivered_box.classList.remove('show');
      } else {
        waiting_box.classList.remove('show');
        delivered_box.classList.add('show');
      }
      
    });
  });

  menuItems[0].click();
});

const toggleSlider = document.querySelector('.thumb');
const sliderContainer = document.querySelector('.toggle-slider');

toggleSlider.addEventListener('click', function() {
  if (toggleSlider.classList.contains('active')) { //Verifico se la classe .active è già attiva
      toggleSlider.classList.remove('active');
      sliderContainer.classList.remove('active');
  } else {
      toggleSlider.classList.add('active');
      sliderContainer.classList.add('active');
  }
});