// ====== Toast Notification ======
function showToast(message, type = "info") {
  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.textContent = message;
  document.body.appendChild(toast);

  // Forzar reflow para que se aplique la animación
  setTimeout(() => toast.classList.add("show"), 100);

  // Quitar después de 3s
  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// ====== Favoritos ======
document.addEventListener('DOMContentLoaded', () => {
  // Agregar a favoritos
  document.querySelectorAll('.btn-favorito').forEach(btn => {
    btn.addEventListener('click', () => {
      const idProducto = btn.dataset.id;

      fetch('/tienda-tecnologia/php/wishlist/agregar_wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_producto=${idProducto}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          showToast(data.message, "success");
        } else if (data.status === 'exists') {
          showToast(data.message, "info");
        } else {
          showToast(data.message, "error");
        }
      });
    });
  });

  // Quitar de favoritos
  document.querySelectorAll('.btn-favorito-remove').forEach(btn => {
    btn.addEventListener('click', () => {
      const idProducto = btn.dataset.id;

      fetch('/tienda-tecnologia/php/wishlist/eliminar_wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_producto=${idProducto}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          showToast(data.message, "success");
          btn.closest('.wishlist-card').remove();
        } else {
          showToast(data.message, "error");
        }
      });
    });
  });
});
