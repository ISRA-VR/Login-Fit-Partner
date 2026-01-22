(function(){
  const overlay = document.getElementById('loading-overlay');
  const logoutLink = document.querySelector('a[href*="view=logout"]');
  if (!logoutLink || !overlay) return;

  logoutLink.addEventListener('click', function(ev){
    ev.preventDefault();
    overlay.classList.add('active');
    // Llamar al logout vía fetch para destruir sesión y borrar cookie
    fetch('index.php?view=logout', { credentials: 'same-origin' })
      .finally(() => {
        // Esperar 5 segundos para mostrar overlay antes de redirigir
        setTimeout(() => {
          window.location.href = 'index.php?view=login';
        }, 1000);
      });
  });
})();
