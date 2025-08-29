// Este JS es utilizado en: gabinetes.php, graphic.php, laptops.php, monitores.php, mouse.php, procesadores.php, rams.php
let modoEdicion = false;
let productoEditandoId = null;

document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('modalAgregarProducto');
  const form = document.getElementById('formAgregarProducto');
  const mensaje = document.getElementById('mensajeAgregar');

  // Escuchar clicks en botones de modificar
  document.addEventListener('click', async (e) => {
    if (e.target.closest('.btn-modificar')) {
      const idProducto = e.target.closest('.btn-modificar').dataset.id;
      try {
        const respuesta = await fetch(`/tienda-tecnologia/php/obtener_producto.php?id=${idProducto}`);
        const data = await respuesta.json();

        if (data.exito) {
          // Mostrar modal y cargar datos
          modal.style.display = 'flex';
          form.nombre.value = data.producto.nombre;
          form.descripcion.value = data.producto.descripcion;
          form.precio.value = data.producto.precio;
          form.descuento.value = data.producto.descuento;
          form.categoria.value = data.producto.categoria;

          // Ajustes para edición
          productoEditandoId = idProducto;
          modoEdicion = true;
          form.querySelector('button[type="submit"]').textContent = 'Modificar';
          form.querySelector('label[for="imagen"]').textContent = '¿Desea cambiar la imagen?';
          form.imagen.required = false;
        } else {
          alert('Error al cargar los datos del producto');
        }
      } catch (error) {
        console.error('Error al obtener producto:', error);
      }
    }
  });

  // Restablecer modo edición al cerrar modal
  document.getElementById('cerrarModalAgregar').addEventListener('click', () => {
    modoEdicion = false;
    productoEditandoId = null;
    form.querySelector('button[type="submit"]').textContent = 'Agregar';
    form.querySelector('label[for="imagen"]').textContent = 'Imagen:';
    form.imagen.required = true;
  });
});
