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

window.addEventListener('DOMContentLoaded', refreshOrders);

function refreshOrders() {
  Promise.all([
    fetch('delivery_operator_includes/delivered_panel.php', {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
      document.querySelector('.delivered-box').innerHTML = html;
    }),
    fetch('delivery_operator_includes/waiting_panel.php', {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
      document.querySelector('.waiting-box').innerHTML = html;
    })
  ])
  .then(() => {
    // Riattacco gli event listener per i toggle appena inseriti
    attachToggleListeners();
    attachMenuLogic();
    toggleStyle();
    updateOrderCounts();
  })
  .catch(error => console.error('Errore durante l\'aggiornamento dei pannelli:', error));
}

function updateOrderCounts() {
  const waitingOrders = document.querySelectorAll('.order[data-category="attesa"]').length;
  const deliveredOrders = document.querySelectorAll('.order[data-category="consegnato"]').length;
  
  // Aggiorna i conteggi nel menu
  const waitingCount = document.querySelector('.menu-category[data-category="attesa"] span p');
  const deliveredCount = document.querySelector('.menu-category[data-category="consegnato"] span p');
  
  if (waitingCount) waitingCount.textContent = waitingOrders;
  if (deliveredCount) deliveredCount.textContent = deliveredOrders;
}

function attachMenuLogic() {
  const menuItems = document.querySelectorAll('.menu-category');
  const waiting_box = document.querySelector('.waiting-box');
  const delivered_box = document.querySelector('.delivered-box');

  menuItems.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;

      menuItems.forEach(i => i.classList.remove('active'));
      btn.classList.add('active');

      if (cat === 'attesa') {
        waiting_box.classList.add('show');
        delivered_box.classList.remove('show');
      } else {
        delivered_box.classList.add('show');
        waiting_box.classList.remove('show');
      }

      // Aggiorna visibilità ordini
      document.querySelectorAll('.order').forEach(order => {
        if (order.dataset.category === cat) {
          order.classList.add('show');
        } else {
          order.classList.remove('show');
        }
      });
    });
  });

  // Trigger prima categoria
  menuItems[0].click();
}

function toggleStyle() {
  document.querySelectorAll('.orderToggle').forEach(function(toggleInput) {

    const orderElement = toggleInput.closest('.order');
    const sliderContainer = orderElement.querySelector('.toggle-slider');
    const toggleSlider = orderElement.querySelector('.thumb');
    
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
}

const waitingBox   = document.querySelector('.waiting-box');
const deliveredBox = document.querySelector('.delivered-box');

function attachToggleListeners() {
  document.querySelectorAll('.orderToggle').forEach(toggle => {
    const orderEl = toggle.closest('.order');

    // Aggiorna stile iniziale
    if (toggle.checked) orderEl.classList.add('active');

    toggle.addEventListener('change', () => {
      const isDelivered = toggle.checked;
      const email       = orderEl.dataset.email;
      const date        = orderEl.dataset.date;
      const time        = orderEl.dataset.time;
      const products    = Array.from(orderEl.querySelectorAll('.product-item'))
                               .map(span => span.dataset.product);

      const updateRequests = products.map(product => {
        const body = new URLSearchParams({
          email, date, time, product,
          status: isDelivered ? 1 : 0
        });

        return fetch('delivery_operator_includes/update_order_status.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'},
          body
        })
        .then(resp => {
          if (!resp.ok) return resp.text().then(txt => { throw new Error(txt) });
          return resp.json();
        });
      });

      Promise.all(updateRequests)
        .then(() => {
          setTimeout(() => {
            refreshOrders();
          }, 500); // 2000 ms = 2 secondi
        })
        .catch(error => console.error('Errore nell\'aggiornamento dello stato ordine:', error));
    });
  });
}


