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
  const info_box= document.querySelector('.personal-information-box');

  menuItems.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;

      // feedback visivo
      menuItems.forEach(i => i.classList.remove('active'));
      btn.classList.add('active');

      // Gestione visualizzazione della cronologia ordini
      if (cat === 'informazioni-personali') {
        info_box.classList.add('show');
        
      } else {
        info_box.classList.remove('show');
        
      }
      
    });
  });

  menuItems[0].click();
});