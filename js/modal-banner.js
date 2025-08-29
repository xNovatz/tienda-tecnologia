// Este JS es utilizado en: index.php
document.getElementById('abrirModal').onclick = () => {
  document.getElementById('modalProducto').style.display = 'flex';
};

document.querySelector('.modal-cerrar').onclick = () => {
  document.getElementById('modalProducto').style.display = 'none';
};

// Cierra el modal al hacer clic fuera del contenido
window.onclick = function(e) {
  const modal = document.getElementById('modalProducto');
  if (e.target === modal) {
    modal.style.display = 'none';
  }
};

document.getElementById('formEditarProducto').onsubmit = function(e) {
  e.preventDefault();

  const formData = new FormData(this);
  fetch('php/editar_banner.php', {
    method: 'POST',
    body: formData
  }).then(res => res.text())
    .then(data => {
      alert(data);
      location.reload(); // Refrescar para ver cambios
    });
};
