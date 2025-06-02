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
        add_user_box.classList.remove('show');
        remove_user_box.classList.remove('show');
      } else {
        info_box.classList.remove('show');
        if (cat === 'sicurezza'){
          security_box.classList.add('show');
          statistics_box.classList.remove('show');
          add_user_box.classList.remove('show');
          remove_user_box.classList.remove('show');
        } else {
          security_box.classList.remove('show');
          if(cat === 'statistiche'){
            statistics_box.classList.add('show');
            add_user_box.classList.remove('show');
            remove_user_box.classList.remove('show');
          } else{
            statistics_box.classList.remove('show');
            if(cat === 'aggiunta-utente'){
              add_user_box.classList.add('show');
              remove_user_box.classList.remove('show');
            }
            else {
              remove_user_box.classList.add('show');
              add_user_box.classList.remove('show');
            } 
          }
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

/* L'event listener sugli input di testo permette di richiamare la funzione updateRequirements e aggiornare in tempo reale i requisiti delle password dopo ogni inserimento ('input') di ogni lettera. Ogni input aggiorna solo i propri requisiti specifici */
const inputs = ['newPassword', 'defaultPassword'];
inputs.forEach(id => {
  document.getElementById(id).addEventListener('input', function() {
    //type prende il risultato "new" o "default" in base a quale dei due input sta eseguendo l'evento
    const type = (id === 'newPassword') ? 'security' : 'add-user';
    if (this.value) {
      updateRequirements(this.value, type);
    } else {
      resetRequirements(type);
    }
  });
});

/* La funzione updateRequirements controlla che la password in ingresso alla funzione, ovvero quella che l'utente sta inserendo, rispecchi determinati requisiti. Nel caso in cui le contizioni in requirements = {...} si avverino, la classe dell'elemento corrispondente viene cambiata e il check diventa verde. 
Lo scopo di tutto ciò è per avere un'esperienza utente migliore, gli stessi controlli verranno effettuati anche lato php, per avere una maggiore sicurezza prima che la password venga inserita nella procedura */
function updateRequirements(password, type) {
  const requirements = {
    length: password.length >= 8,
    uppercase: /[A-Z]/.test(password),
    lowercase: /[a-z]/.test(password),
    number: /\d/.test(password),
    special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
  };
  
  const suffix = (type === 'security') ? '' : '2';
  // Aggiorno solo i requisiti della nuova password in base al suffisso
  document.getElementById(`lengthReq${suffix}`).className = `requirement-icon ${requirements.length ? `requirement-valid${suffix}` : `requirement-invalid${suffix}`}`;
  document.getElementById(`uppercaseReq${suffix}`).className = `requirement-icon ${requirements.uppercase ? `requirement-valid${suffix}` : `requirement-invalid${suffix}`}`;
  document.getElementById(`lowercaseReq${suffix}`).className = `requirement-icon ${requirements.lowercase ? `requirement-valid${suffix}` : `requirement-invalid${suffix}`}`;
  document.getElementById(`numberReq${suffix}`).className = `requirement-icon ${requirements.number ? `requirement-valid${suffix}` : `requirement-invalid${suffix}`}`;
  document.getElementById(`specialReq${suffix}`).className = `requirement-icon ${requirements.special ? `requirement-valid${suffix}` : `requirement-invalid${suffix}`}`;
}

// Mi serve anche un altra funzione per resettare i requisiti di una specifica password che viene inserita in un form differente, altrimenti i due check entrerebbero in conflitto
function resetRequirements(type) {
  const suffix = (type === 'security') ? '' : '2';
  
  // Reset solo i requisiti della nuova password
  document.getElementById(`lengthReq${suffix}`).className = `requirement-icon requirement-invalid${suffix}`;
  document.getElementById(`uppercaseReq${suffix}`).className = `requirement-icon requirement-invalid${suffix}`;
  document.getElementById(`lowercaseReq${suffix}`).className = `requirement-icon requirement-invalid${suffix}`;
  document.getElementById(`numberReq${suffix}`).className = `requirement-icon requirement-invalid${suffix}`;
  document.getElementById(`specialReq${suffix}`).className = `requirement-icon requirement-invalid${suffix}`;
}

//Qui gestisco i diversi tasti "annulla" per i diversi form nella pagina
const inputsButton = ['reset-form-security', 'reset-form-add-user', 'reset-form-remove-user'];
inputsButton.forEach(id => {
  document.getElementById(id).addEventListener('click', () => {
    if(id == 'reset-form-security'){
      const form = document.getElementById('securityForm'); //Riferimento al form su cui voglio effettuare le operazioni
      form.reset();
      resetRequirements('security');
    } else if(id == 'reset-form-add-user'){
      const form = document.getElementById('addUserForm'); //Riferimento al form su cui voglio effettuare le operazioni
      form.reset();
      resetRequirements('add-user');
    } else{
      const form = document.getElementById('removeUserForm'); //Riferimento al form su cui voglio effettuare le operazioni
      form.reset();
    }
  });
});

/*Qui gestisco il tempo di visualizzazione per i box di:
  - successo cambiamento password;
  - errore cambiamento password.
  Una volta che i messaggi vengono visualizzati, scompaiono dopo 7000ms=7s.
*/
document.addEventListener('DOMContentLoaded', function() {
  const successMessage_security = document.getElementById('successMessage-security');
  const errorMessage_security = document.getElementById('errorMessage-security');
  const successMessage_addUser = document.getElementById('successMessage-addUser');
  const errorMessage_addUser = document.getElementById('errorMessage-addUser');
  const successMessage_removeUser = document.getElementById('successMessage-removeUser');
  const errorMessage_removeUser = document.getElementById('errorMessage-removeUser');


  if (successMessage_security && successMessage_security.style.display !== 'none') {
    setTimeout(() => {
      successMessage_security.style.display = 'none';
    }, 7000);
  }

  if (errorMessage_security && errorMessage_security.style.display !== 'none') {
    setTimeout(() => {
      errorMessage_security.style.display = 'none';
    }, 7000);
  }

  if (successMessage_addUser && successMessage_addUser.style.display !== 'none') {
    setTimeout(() => {
      successMessage_addUser.style.display = 'none';
    }, 7000);
  }

  if (errorMessage_addUser && errorMessage_addUser.style.display !== 'none') {
    setTimeout(() => {
      errorMessage_addUser.style.display = 'none';
    }, 7000);
  }

  if (successMessage_removeUser && successMessage_removeUser.style.display !== 'none') {
    setTimeout(() => {
      successMessage_removeUser.style.display = 'none';
    }, 7000);
  }

  if (errorMessage_removeUser && errorMessage_removeUser.style.display !== 'none') {
    setTimeout(() => {
      errorMessage_removeUser.style.display = 'none';
    }, 7000);
  }
});

