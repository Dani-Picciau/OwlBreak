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
  const product_box = document.querySelector('.product-box');
  const products  = document.querySelectorAll('.product');
  const searchbar = document.querySelector('.search-bar');
  const orders_history = document.querySelector('.orders-history');

  menuItems.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;

      // feedback visivo
      menuItems.forEach(i => i.classList.remove('active'));
      btn.classList.add('active');

      // Gestione visualizzazione della cronologia ordini
      if (cat === 'Cronologia ordini') {
        orders_history.classList.add('show');
        // Nasconde tutti i prodotti quando si visualizza la cronologia
        products.forEach(p => p.classList.remove('show'));
      } else {
        orders_history.classList.remove('show');
        
        // Gestione card dei prodotti per altre categorie
        if (cat === 'Carrello') {
          products.forEach(p => p.classList.remove('show'));
        } else {
          products.forEach(p => {
            if (p.dataset.category === cat) {
              p.classList.add('show');
            } else {
              p.classList.remove('show');
            }
          });
        }
      }

      
      if (cat === 'Carrello' || cat === 'Cronologia ordini') {
        product_box.classList.remove('show');
        searchbar.classList.remove('show'); 
      } else {
        product_box.classList.add('show');
        searchbar.classList.add('show'); 
      }
    });
  });
  
  menuItems[0].click();
});

//Funzionamento search-bar
const searchInput = document.querySelector('.search-input');

searchInput.addEventListener('input', () => {
  const searchTerm = searchInput.value.toLowerCase();
  
  document.querySelectorAll('.product').forEach(product => {
    const productName = product.dataset.name.toLowerCase();
    
    // Verifica se è nella categoria attualmente selezionata
    // (Presumo che tu stia usando la classe 'show' per indicare prodotti nella categoria corrente)
    const isInSelectedCategory = product.classList.contains('show') || document.querySelector('.category-filter.active') === null; // se nessun filtro è attivo
    
    if (searchTerm === '') {
      // Se la ricerca è vuota, mostra tutti i prodotti della categoria corrente
      if (isInSelectedCategory) {
        product.style.display = '';
      } else {
        product.style.display = 'none';
      }
    } else {
      // Se c'è un termine di ricerca, mostra solo i prodotti che corrispondono nella categoria corrente
      if (isInSelectedCategory && productName.includes(searchTerm)) {
        product.style.display = '';
      } else {
        product.style.display = 'none';
      }
    }
  });
});

// Wait for the document to be fully loaded

