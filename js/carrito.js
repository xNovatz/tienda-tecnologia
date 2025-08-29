// Este JS es utilizado en: index.php, gabinetes.php, graphic.php, laptops.php, mi-cuenta.php, monitores.php, mouse.php, procesadores.php, rams.php
document.addEventListener('DOMContentLoaded', function () {
  const contador = document.getElementById('contador-carrito');
  const carritoContenedor = document.getElementById('carrito-contenedor');
  const lista = document.getElementById('carrito-lista');
  const total = document.getElementById('carrito-total');
  const btnAbrir = document.getElementById('abrir-carrito');
  const btnVaciar = document.getElementById('vaciar-carrito');
  const fondoOscuro = document.getElementById('fondo-oscuro');
  const btnPagar = document.getElementById('btnPagar');

  let carrito = [];

  async function obtenerCarrito() {
    try {
      const res = await fetch('/tienda-tecnologia/php/carrito/obtener_carrito.php');
      const data = await res.json();
      carrito = data;
      renderCarrito();
    } catch (e) {
      console.error('Error al obtener el carrito:', e);
    }
  }

  async function agregarProducto(idProducto, tipo = 'productos') {
    try {
      await fetch('/tienda-tecnologia/php/carrito/agregar_al_carrito.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_producto=${idProducto}&cantidad=1&tipo=${tipo}`
      });
      await obtenerCarrito();
    } catch (e) {
      console.error('Error al agregar producto:', e);
    }
  }

  async function vaciarCarritoBD() {
    try {
      await fetch('/tienda-tecnologia/php/carrito/vaciar_carrito.php');
      await obtenerCarrito();
    } catch (e) {
      console.error('Error al vaciar el carrito:', e);
    }
  }

  function renderCarrito() {
    lista.innerHTML = '';
    let totalCompra = 0;

    carrito.forEach((prod, index) => {
      const subtotal = prod.precio * prod.cantidad;
      totalCompra += subtotal;

      const li = document.createElement('li');
      li.classList.add('item-carrito');

      li.innerHTML = `
        <div class="info-producto horizontal">
          <img src="${prod.imagen}" alt="${prod.nombre}">
          <div class="detalles-producto">
            <span class="nombre">${prod.nombre}</span>
            <div class="precios">
              ${parseFloat(prod.precioOriginal) > parseFloat(prod.precio)
                ? `<span class="tachado">$${parseFloat(prod.precioOriginal).toFixed(2)}</span>`
                : ''}
              <span class="actual">$${prod.precio.toFixed(2)}</span>
            </div>
            <div class="controles-cantidad">
              <button class="menos" data-index="${index}">−</button>
              <span>${prod.cantidad}</span>
              <button class="mas" data-index="${index}">+</button>
            </div>
            <span class="subtotal">Subtotal: $${subtotal.toFixed(2)}</span>
          </div>
          <button class="boton-eliminar" data-index="${index}" title="Eliminar">
            <i class="far fa-trash-alt"></i>
          </button>
        </div>
      `;

      lista.appendChild(li);
    });

    lista.querySelectorAll('.mas').forEach(btn => {
      btn.addEventListener('click', () => {
        const index = btn.dataset.index;
        const idProducto = carrito[index].id;
        const tipo = carrito[index].tipo_producto || 'productos';
        agregarProducto(idProducto, tipo);
      });
    });

    lista.querySelectorAll('.menos').forEach(btn => {
      btn.addEventListener('click', async () => {
        const index = btn.dataset.index;
        const producto = carrito[index];
        if (producto.cantidad > 1) {
          await fetch('/tienda-tecnologia/php/carrito/agregar_al_carrito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_producto=${producto.id}&cantidad=-1&tipo=${producto.tipo_producto}`
          });
        } else {
          await fetch('/tienda-tecnologia/php/carrito/eliminar_producto.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_producto=${producto.id}&tipo=${producto.tipo_producto}`
          });
        }
        await obtenerCarrito();
      });
    });

    lista.querySelectorAll('.boton-eliminar').forEach(btn => {
      btn.addEventListener('click', async () => {
        const index = btn.dataset.index;
        const producto = carrito[index];
        await fetch('/tienda-tecnologia/php/carrito/eliminar_producto.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id_producto=${producto.id}&tipo=${producto.tipo_producto}`
        });
        await obtenerCarrito();
      });
    });

    total.textContent = totalCompra.toFixed(2);
    contador.textContent = carrito.reduce((s, p) => s + p.cantidad, 0);
  }

  document.body.addEventListener('click', function (e) {
  const boton = e.target.closest('.btn-agregar');
  if (boton) {
    const id = boton.dataset.id;
    const tipo = boton.dataset.tipo || 'productos';
    agregarProducto(id, tipo);
  }
  });

  btnAbrir.addEventListener('click', () => {
    carritoContenedor.classList.toggle('activo');
    fondoOscuro.classList.toggle('activo');
  });

  fondoOscuro.addEventListener('click', () => {
    carritoContenedor.classList.remove('activo');
    fondoOscuro.classList.remove('activo');
  });

  btnVaciar.addEventListener('click', () => {
    vaciarCarritoBD();
  });

  btnPagar.addEventListener('click', () => {
    if (carrito.length === 0) {
      alert("Tu carrito está vacío.");
    } else {
      const volver = encodeURIComponent(window.location.pathname);
      window.location.href = `/tienda-tecnologia/php/pago/pago.php?volver=${volver}`;
    }
  });

  obtenerCarrito();
});
