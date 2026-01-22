document.addEventListener('DOMContentLoaded', () => {
  const toggleEl = document.getElementById('togglePassword');
  if (toggleEl) {
    toggleEl.addEventListener('click', function () {
      const pass = document.getElementById('password');
      if (!pass) return;
      if (pass.type === 'password') {
        pass.type = 'text';
        this.classList.replace('bi-eye-slash', 'bi-eye');
      } else {
        pass.type = 'password';
        this.classList.replace('bi-eye', 'bi-eye-slash');
      }
    });
  }

  // Mostrar loading al enviar el formulario de login
  const loginForm = document.getElementById('login-form');
  const loadingOverlay = document.getElementById('loading-overlay');
  const btnLogin = document.getElementById('btn-login');
  const emailInput = document.querySelector('input[name="email"]');
  const passInput = document.getElementById('password');
  const overlayMsg = loadingOverlay ? loadingOverlay.querySelector('p') : null;

  const showOverlayLogin = () => {
    if (btnLogin) {
      btnLogin.disabled = true;
      btnLogin.textContent = 'Iniciando sesión...';
    }
    if (overlayMsg) {
      overlayMsg.textContent = 'Iniciando sesión';
      overlayMsg.classList.add('dots');
    }
    if (loadingOverlay) {
      loadingOverlay.classList.add('active');
    }
  };

  if (loginForm && loadingOverlay && btnLogin) {
    // Click en botón: más fiable, se dispara aunque no haya submit por Enter
    btnLogin.addEventListener('click', (ev) => {
      if (!loginForm.checkValidity()) return; // el navegador mostrará validación
      ev.preventDefault();
      showOverlayLogin();
      setTimeout(() => {
        if (typeof loginForm.requestSubmit === 'function') loginForm.requestSubmit();
        else loginForm.submit();
      }, 2000);
    });

    // Fallback: submit por Enter en inputs
    loginForm.addEventListener('submit', (ev) => {
      // Si el botón ya manejó el flujo, evitamos doble overlay
      const emailOk = emailInput && /.+@.+\..+/.test(String(emailInput.value || ''));
      const passOk = passInput && String(passInput.value || '').length > 0;
      if (!emailOk || !passOk) return; // que el navegador gestione required

      ev.preventDefault();
      showOverlayLogin();
      setTimeout(() => {
        loginForm.submit();
      }, 1000);
    });
  }
});