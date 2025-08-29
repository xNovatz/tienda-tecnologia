document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("inputBuscador");
  const resultados = document.createElement("div");
  resultados.classList.add("buscador-resultados");
  input.parentNode.appendChild(resultados);

  input.addEventListener("input", () => {
    const query = input.value.trim();

    if (query.length < 2) {
      resultados.innerHTML = "";
      resultados.style.display = "none";
      return;
    }

    fetch(`/tienda-tecnologia/php/buscador/buscar.php?q=${encodeURIComponent(query)}`)
      .then(res => res.json())
      .then(data => {
        if (data.length > 0) {
          resultados.innerHTML = data.map(p => `
            <div class="buscador-item" data-id="${p.id}">
              <img src="/tienda-tecnologia/assets/productos/${p.categoria}/${p.imagen}" alt="${p.nombre}">
              <span>${p.nombre}</span>
              <small>$${parseFloat(p.precio).toFixed(2)}</small>
            </div>
          `).join('');
        } else {
          resultados.innerHTML = `<p class="sin-resultados">No se encontraron productos</p>`;
        }
        resultados.style.display = "block";

        // Redirección al hacer clic
        document.querySelectorAll(".buscador-item").forEach(item => {
          item.addEventListener("click", () => {
            const id = item.dataset.id;
            window.location.href = `/tienda-tecnologia/paginas/producto.php?id=${id}`;
          });
        });
      })
      .catch(err => {
        resultados.innerHTML = `<p class="sin-resultados">Error en el buscador</p>`;
        resultados.style.display = "block";
      });
  });

  // Ocultar resultados si haces clic fuera
  document.addEventListener("click", e => {
    if (!input.parentNode.contains(e.target)) {
      resultados.style.display = "none";
    }
  });
});
