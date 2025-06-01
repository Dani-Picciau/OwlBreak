// Effetto caricamento pagina dall'alto al basso
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
  const info_box = document.querySelector('.personal-information-box');
  const security_box = document.querySelector('.security-box');
  const statistics_box = document.querySelector('.statistics-box');
  const add_user_box = document.querySelector('.add-user-box');
  const remove_user_box = document.querySelector('.remove-user-box');

  menuItems.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;

      // feedback visivo
      menuItems.forEach(i => i.classList.remove('active'));
      btn.classList.add('active');

      // Gestione visualizzazione della cronologia ordini
      if (cat === 'informazioni-personali') {
        info_box.classList.add('show');
        security_box.classList.remove('show');
        statistics_box.classList.remove('show');
        
      } else {
        info_box.classList.remove('show');
        if (cat === 'sicurezza'){
          security_box.classList.add('show');
          statistics_box.classList.remove('show');
        }else{
          security_box.classList.remove('show');
          statistics_box.classList.add('show');
        }
      }
      
    });
  });
});

//onclick sull'occhio permette di mostrare la password tramite questa funzione
function togglePassword(inputId) {
  const input = document.getElementById(inputId);
  const container = input.parentElement; // Prendi il div contenitore
  const toggleBtn = container.querySelector('span'); // Trova lo span nel contenitore
  const eyeOpen = toggleBtn.querySelector('.eye-open');
  const eyeClosed = toggleBtn.querySelector('.eye-closed');
  
  if (input.type === 'password') {
    eyeOpen.style.display = 'none';
    eyeClosed.style.display = 'block';
    input.type = 'text';
  } else {
    eyeOpen.style.display = 'block';
    eyeClosed.style.display = 'none';
    input.type = 'password';
  }
}

/* L'event listener sull'input di testo 'newPassword' permette di richiamare la funzione updateRequirements e aggiornare in tempo reale i requisiti della nuova password dopo ogni inserimento ('input') di ogni lettera */
document.getElementById('newPassword').addEventListener('input', function() {
  const password = this.value;
  if (password) {
    updateRequirements(password);
  } else {
    // Reset tutti i requisiti se il campo è vuoto
    document.querySelectorAll('.requirement-icon').forEach(icon => {
      icon.className = 'requirement-icon requirement-invalid';
    });
  }
});

/* La funzione updateRequirements controlla che la password in ingresso alla funzione, ovvero quella che l'utente sta inserendo, rispecchi determinati requisiti. Nel caso in cui le contizioni in requirements = {...} si avverino, la classe dell'elemento corrispondente viene cambiata e il check diventa verde. 
Lo scopo di tutto ciò è per avere un'esperienza utente migliore, gli stessi controlli verranno effettuati anche lato php, per avere maggiore sicurezza prima, che la password venga inserita nella procedura */
function updateRequirements(password) {
  const requirements = {
    length: password.length >= 8,
    uppercase: /[A-Z]/.test(password),
    lowercase: /[a-z]/.test(password),
    number: /\d/.test(password),
    special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
  };
  
  document.getElementById('lengthReq').className = `requirement-icon ${requirements.length ? 'requirement-valid' : 'requirement-invalid'}`;
  document.getElementById('uppercaseReq').className = `requirement-icon ${requirements.uppercase ? 'requirement-valid' : 'requirement-invalid'}`;
  document.getElementById('lowercaseReq').className = `requirement-icon ${requirements.lowercase ? 'requirement-valid' : 'requirement-invalid'}`;
  document.getElementById('numberReq').className = `requirement-icon ${requirements.number ? 'requirement-valid' : 'requirement-invalid'}`;
  document.getElementById('specialReq').className = `requirement-icon ${requirements.special ? 'requirement-valid' : 'requirement-invalid'}`;
}

/*Qui gestisco il tempo di visualizzazione per i box di:
  - successo cambiamento password;
  - errore cambiamento password.
  Una volta che i messaggi vengono visualizzati, scompaiono dopo 10000ms.
*/
document.addEventListener('DOMContentLoaded', function() {
  const successMessage = document.getElementById('successMessage');
  const errorMessage = document.getElementById('errorMessage');
  
  if (successMessage && successMessage.style.display !== 'none') {
    setTimeout(() => {
      successMessage.style.display = 'none';
    }, 10000);
  }
  
  if (errorMessage && errorMessage.style.display !== 'none') {
    setTimeout(() => {
      errorMessage.style.display = 'none';
    }, 10000);
  }
});

