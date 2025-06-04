function togglePassword(inputId) {
  const input = document.getElementById(inputId);
  const container = input.parentElement;
  const toggleBtn = container.querySelector('span'); 
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