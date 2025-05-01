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
  const products  = document.querySelectorAll('.product');

  menuItems.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;

      // feedback visivo
      menuItems.forEach(i => i.classList.remove('active'));
      btn.classList.add('active');

      // per ogni card...
      products.forEach(p => {
        if (cat === 'Carrello' || cat === 'Cronologia ordini') {
          p.classList.remove('show');
        }
        else if (p.dataset.category === cat) {
          p.classList.add('show');
        } else {
          p.classList.remove('show');
        }
      });
    });
  });

   menuItems[0].click();
});



