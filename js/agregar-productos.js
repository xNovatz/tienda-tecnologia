// Este JS es utilizado en: gabinetes.php, graphic.php, laptops.php, monitores.php, mouse.php, procesadores.php, rams.php
document.addEventListener('DOMContentLoaded', () => {
  const abrirModalBtn = document.getElementById('abrirModalAgregar');
  const cerrarModalBtn = document.getElementById('cerrarModalAgregar');
  const modal = document.getElementById('modalAgregarProducto');
  const form = document.getElementById('formAgregarProducto');
  const mensaje = document.getElementById('mensajeAgregar');
  const productosListado = document.querySelector('.productos-listado');

  abrirModalBtn?.addEventListener('click', () => {
    modal.style.display = 'flex';
  });

  cerrarModalBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    form.reset();
    mensaje.textContent = '';
    modoEdicion = false;
    productoEditandoId = null;
    form.querySelector('button[type="submit"]').textContent = 'Agregar';
    form.querySelector('label[for="imagen"]').textContent = 'Imagen:';
    form.imagen.required = true;
  });

  window.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
      form.reset();
      mensaje.textContent = '';
      modoEdicion = false;
      productoEditandoId = null;
      form.querySelector('button[type="submit"]').textContent = 'Agregar';
      form.querySelector('label[for="imagen"]').textContent = 'Imagen:';
      form.imagen.required = true;
    }
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    let url = '/tienda-tecnologia/php/guardar_producto.php';

    if (modoEdicion) {
      formData.append('id', productoEditandoId);
      url = '/tienda-tecnologia/php/modificar_producto.php';
    }

    try {
      const response = await fetch(url, {
        method: 'POST',
        body: formData,
      });

      const data = await response.json();

      if (data.exito) {
        mensaje.textContent = modoEdicion ? 'Producto modificado correctamente.' : 'Producto agregado correctamente.';
        mensaje.style.color = '#018658';

        const precioOriginal = parseFloat(data.producto.precio);
        const descuento = parseFloat(data.producto.descuento || 0);
        const precioFinal = precioOriginal - (precioOriginal * (descuento / 100));

        const nuevoProducto = document.createElement('section');
        nuevoProducto.classList.add('producto-detalle');
        nuevoProducto.innerHTML = `
          <div class="producto-imagen">
            <img src="../assets/productos/${data.producto.categoria}/${data.producto.imagen}" alt="Producto">
          </div>
          <div class="producto-info">
            <h2 class="titulo-con-logo">${data.producto.nombre}</h2>
            <div class="descripcion-scroll">
              <p>${data.producto.descripcion}</p>
            </div>
            <div class="precio-boton">
              <div class="precio">
                ${
                  descuento > 0
                    ? `<span class="descuento">$${precioFinal.toFixed(2)}</span>
                       <span class="estandar tachado">$${precioOriginal.toFixed(2)}</span>`
                    : `<span class="estandar">$${precioOriginal.toFixed(2)}</span>`
                }
              </div>
              <button class="btn-agregar" data-nombre="${data.producto.nombre}" data-precio="${precioFinal.toFixed(2)}">Agregar al carrito</button>
            </div>
            <div class="acciones-producto">
              <button class="btn-modificar" data-id="${data.producto.id}">
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
              <button class="btn-eliminar" data-id="${data.producto.id}">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          </div>
        `;

        if (modoEdicion) {
          const antiguo = document.querySelector(`.btn-modificar[data-id="${data.producto.id}"]`)?.closest('.producto-detalle');
          if (antiguo) antiguo.replaceWith(nuevoProducto);
        } else {
          productosListado.prepend(nuevoProducto);
        }

        form.reset();
        setTimeout(() => {
          modal.style.display = 'none';
          mensaje.textContent = '';
        }, 2000);

        modoEdicion = false;
        productoEditandoId = null;
        form.querySelector('button[type="submit"]').textContent = 'Agregar';
        form.querySelector('label[for="imagen"]').textContent = 'Imagen:';
        form.imagen.required = true;

      } else {
        mensaje.textContent = data.mensaje || 'Error al guardar el producto.';
        mensaje.style.color = 'red';
      }
    } catch (error) {
      console.error('Error:', error);
      mensaje.textContent = 'Error al conectar con el servidor.';
      mensaje.style.color = 'red';
    }
  });
});
