// Este JS es utilizado en: gabinetes.php, graphic.php, laptops.php, monitores.php, mouse.php, procesadores.php, rams.php
let productoAEliminarId = null;

document.addEventListener('click', function (e) {
  if (e.target.closest('.btn-eliminar')) {
    const id = e.target.closest('.btn-eliminar').dataset.id;
    productoAEliminarId = id;

    const modal = document.getElementById('modalConfirmacion');
    modal.style.display = 'flex';
  }
});

document.getElementById('btnConfirmarEliminar').addEventListener('click', async () => {
  const modal = document.getElementById('modalConfirmacion');
  modal.style.display = 'none';

  try {
    const respuesta = await fetch(`/tienda-tecnologia/php/eliminar_producto.php?id=${productoAEliminarId}`, {
      method: 'GET'
    });

    const data = await respuesta.json();

    if (data.exito) {
      const producto = document.querySelector(`.btn-eliminar[data-id="${productoAEliminarId}"]`).closest('.producto-detalle');
      if (producto) producto.remove();

      // ✅ Usa tu modal estilizado, no alert()
      mostrarMensajeExito("Producto eliminado correctamente.");
    } else {
      alert("No se pudo eliminar el producto.");
    }
  } catch (error) {
    console.error("Error al eliminar:", error);
    alert("Error del servidor al eliminar el producto.");
  }

  productoAEliminarId = null;
});

document.getElementById('btnCancelarEliminar').addEventListener('click', () => {
  const modal = document.getElementById('modalConfirmacion');
  modal.style.display = 'none';
  productoAEliminarId = null;
});

function mostrarMensajeExito(texto) {
  const modal = document.getElementById('modalExito');
  const mensaje = document.getElementById('mensajeExito');

  mensaje.textContent = texto;

  modal.style.display = 'block';
  modal.style.opacity = '1';
  modal.style.transition = 'opacity 0.5s';

  // Después de 3.5s empieza a desvanecer
  setTimeout(() => {
    modal.style.opacity = '0';
  }, 3500);

  // Después de 4s lo oculta
  setTimeout(() => {
    modal.style.display = 'none';
  }, 4000);
}

