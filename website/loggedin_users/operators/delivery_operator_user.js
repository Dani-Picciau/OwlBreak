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

// Codice per mostrare gli ordini in attesa o consegnati
document.addEventListener('DOMContentLoaded', () => {
  const menuItems = document.querySelectorAll('.menu-category');
  const waiting_box = document.querySelector('.waiting-box');
  const delivered_box = document.querySelector('.delivered-box');
  const orders = document.querySelectorAll('.order');
  
  menuItems.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;

      // feedback visivo
      menuItems.forEach(i => i.classList.remove('active'));
      btn.classList.add('active');

      // Gestione visualizzazione ordini
      if (cat === 'attesa') {
        waiting_box.classList.add('show');
        delivered_box.classList.remove('show');
        // Mostra solo ordini in attesa
        orders.forEach(p => {
          if (p.dataset.category === cat) {
            p.classList.add('show');
          } else {
            p.classList.remove('show');
          }
        });
      } else {
        delivered_box.classList.add('show');
        waiting_box.classList.remove('show');
        // Mostra solo ordini consegnati
        orders.forEach(p => {
          if (p.dataset.category === cat) {
            p.classList.add('show');
          } else {
            p.classList.remove('show');
          }
        });
      } 
    });
  });

  menuItems[0].click();
});


document.querySelectorAll('.orderToggle').forEach(function(toggleInput) {

  const orderElement = toggleInput.closest('.order');
  const sliderContainer = orderElement.querySelector('.toggle-slider');
  const toggleSlider = orderElement.querySelector('.thumb');
  
  //Serve per sincronizzare il toggle con gli ordini già consegnati al caricamento della pagina
  if (toggleInput.checked) {
    toggleSlider.classList.add('active');
    sliderContainer.classList.add('active');
    orderElement.classList.add('active');
  }
  
  toggleInput.addEventListener('change', function() {
    if (toggleInput.checked) {
      toggleSlider.classList.add('active');
      sliderContainer.classList.add('active');
      orderElement.classList.add('active');
    } else {
      toggleSlider.classList.remove('active');
      sliderContainer.classList.remove('active');
      orderElement.classList.remove('active');
    }
  });
});


