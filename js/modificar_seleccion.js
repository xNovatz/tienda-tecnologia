// Este JS es utilizado en: index.php
document.getElementById('categoriaSeleccion').addEventListener('change', async function () {
  const categoria = this.value;
  const res = await fetch(`php/obtener_productos_categoria.php?categoria=${categoria}`);
  const productos = await res.json();

  const select = document.getElementById('productoSeleccion');
  select.innerHTML = ''; // Limpiar opciones anteriores

  productos.forEach(prod => {
    const option = document.createElement('option');
    option.value = prod.id;
    option.textContent = prod.nombre;
    select.appendChild(option);
  });
});

document.getElementById('formSeleccionProducto').addEventListener('submit', async function (e) {
  e.preventDefault();
  const formData = new FormData(this);

  const res = await fetch('php/guardar_seleccion_producto.php', {
    method: 'POST',
    body: formData
  });

  const data = await res.text();
  mostrarToast(data); // ✅ reemplaza alert
  cerrarModalSeleccion(); // ✅ cierra el modal después de mostrar el mensaje
});

function cerrarModalSeleccion() {
  document.getElementById('modalSeleccionProducto').style.display = 'none';
}

document.getElementById('abrirSeleccion').onclick = () => {
  document.getElementById('modalSeleccionProducto').style.display = 'flex';
};

// Cerrar modal al hacer clic fuera del contenido
window.addEventListener('click', function (e) {
  const modal = document.getElementById('modalSeleccionProducto');
  const contenido = modal.querySelector('.modal-contenido');

  if (e.target === modal) {
    cerrarModalSeleccion();
  }
});

function mostrarToast(mensaje = "Producto asignado correctamente.") {
  const toast = document.getElementById('mensaje-toast');
  toast.textContent = mensaje;
  toast.classList.add('toast-activo');

  setTimeout(() => {
    toast.classList.remove('toast-activo');
  }, 2000); // dura 2 segundos
}
