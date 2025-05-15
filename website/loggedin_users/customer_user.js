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
  const cartItems = document.querySelector('.cart-item-container');

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
        cartItems.classList.remove('show');
      } else {
        orders_history.classList.remove('show');
        
        // Gestione card dei prodotti per altre categorie
        if (cat === 'Carrello') {
          cartItems.classList.add('show');
          products.forEach(p => p.classList.remove('show'));
        } else {
          cartItems.classList.remove('show');
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

document.addEventListener('DOMContentLoaded', () => {
  const cartContainer   = document.querySelector('.cart-item-container');
  const cartCountElem   = document.querySelector('.menu-category[data-category="Carrello"] span');

  // Funzione generica per tutte le azioni: add, increase, decrease, remove
  async function updateCart(action, productName) {
    try {
      cartContainer.classList.add('loading');
      
      const formData = new FormData();
      formData.append(action, productName);
      
      // 1) Invio la modifica
      let res = await fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      let json = await res.json();               // ricevo { success, cartCount }
      cartCountElem.textContent = json.cartCount;
      
      // 2) Chiedo il frammento aggiornato del carrello
      res = await fetch(window.location.href + '?ajax=cart', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const html = await res.text();
      const doc  = new DOMParser().parseFromString(html, 'text/html');
      cartContainer.innerHTML = doc.querySelector('.cart-item-container').innerHTML;

    } catch (err) {
      console.error('Cart AJAX error', err);
    } finally {
      cartContainer.classList.remove('loading');
    }
  }

  // Event delegation sul container principale
  document.body.addEventListener('click', e => {
    const btn = e.target.closest('button');
    if (!btn) return;

    // usa data-action e data-product sugli <button> o sugli <form>
    const action = btn.dataset.action;         // es. "add_to_cart", "increase", ...
    const product = btn.dataset.productName;   // il nome del prodotto

    if (action && product) {
      e.preventDefault();
      updateCart(action, product);
    }
  });
});