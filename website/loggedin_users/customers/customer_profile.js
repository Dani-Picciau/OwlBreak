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
  const info_box = document.querySelector('.personal-information-box');
  const security_box = document.querySelector('.security-box');
  const statistics_box = document.querySelector('.statistics-box');

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

  menuItems[0].click();
});

function togglePassword(inputId) {
  const input = document.getElementById(inputId);
  const toggleBtn = input.nextElementSibling;
  const eyeOpen = toggleBtn.querySelector('.eye-open');
  const eyeClosed = toggleBtn.querySelector('.eye-closed');
  
  if (input.type === 'password') {
    input.type = 'text';
    eyeOpen.style.display = 'none';
    eyeClosed.style.display = 'block';
  } else {
    input.type = 'password';
    eyeOpen.style.display = 'block';
    eyeClosed.style.display = 'none';
  }
}

function validatePassword(password) {
  const requirements = {
    length: password.length >= 8,
    uppercase: /[A-Z]/.test(password),
    lowercase: /[a-z]/.test(password),
    number: /\d/.test(password),
    special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
  };
  
  return requirements;
}

function updateRequirements(password) {
  const requirements = validatePassword(password);
  
  document.getElementById('lengthReq').className = `requirement-icon ${requirements.length ? 'requirement-valid' : 'requirement-invalid'}`;
  document.getElementById('uppercaseReq').className = `requirement-icon ${requirements.uppercase ? 'requirement-valid' : 'requirement-invalid'}`;
  document.getElementById('lowercaseReq').className = `requirement-icon ${requirements.lowercase ? 'requirement-valid' : 'requirement-invalid'}`;
  document.getElementById('numberReq').className = `requirement-icon ${requirements.number ? 'requirement-valid' : 'requirement-invalid'}`;
  document.getElementById('specialReq').className = `requirement-icon ${requirements.special ? 'requirement-valid' : 'requirement-invalid'}`;
  
  return Object.values(requirements).every(req => req);
}

function validateForm() {
  const currentPassword = document.getElementById('currentPassword').value;
  const newPassword = document.getElementById('newPassword').value;
  const confirmPassword = document.getElementById('confirmPassword').value;
  const submitBtn = document.getElementById('submitBtn');
  
  let isValid = true;
  
  // Reset error messages
  document.querySelectorAll('.error-message').forEach(el => {
    el.style.display = 'none';
    el.textContent = '';
  });
  
  // Validate current password
  if (!currentPassword) {
    isValid = false;
  }
  
  // Validate new password
  if (!newPassword) {
      isValid = false;
  } else {
    const passwordValid = updateRequirements(newPassword);
    if (!passwordValid) {
      isValid = false;
    }
      
    // Check if new password is same as current
    if (newPassword === currentPassword && currentPassword) {
      document.getElementById('newPasswordError').textContent = 'La nuova password deve essere diversa da quella attuale';
      document.getElementById('newPasswordError').style.display = 'block';
      isValid = false;
    }
  }
  
  // Validate confirm password
  if (!confirmPassword) {
    isValid = false;
  } else if (newPassword !== confirmPassword) {
    document.getElementById('confirmPasswordError').textContent = 'Le password non coincidono';
    document.getElementById('confirmPasswordError').style.display = 'block';
    isValid = false;
  }
  
  submitBtn.disabled = !isValid;
}

// Event listeners
document.getElementById('newPassword').addEventListener('input', validateForm);
document.getElementById('currentPassword').addEventListener('input', validateForm);
document.getElementById('confirmPassword').addEventListener('input', validateForm);

document.getElementById('passwordForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  // Simulate successful password change
  const successMessage = document.getElementById('successMessage');
  successMessage.style.display = 'flex';
  
  // Reset form
  this.reset();
  validateForm();
  
  // Hide success message after 5 seconds
  setTimeout(() => {
      successMessage.style.display = 'none';
  }, 5000);
});

// Initial validation
validateForm();
