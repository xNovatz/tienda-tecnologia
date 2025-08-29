  document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-reseña");
    if (form) {
      form.addEventListener("submit", async e => {
        e.preventDefault();
        let datos = new FormData(form);
        let res = await fetch("/tienda-tecnologia/php/reseñas/agregar.php", {
          method: "POST",
          body: datos
        });
        let json = await res.json();
        if (json.success) {
          mostrarToast("✅ ¡Reseña guardada con éxito!");
          form.reset();
          let productoId = datos.get("producto_id");
          cargarReseñas(productoId);
          actualizarPromedio(productoId);
        } else {
          mostrarToast("⚠️ " + (json.error || "Error al guardar"));
        }
      });
    }

    // Cargar reseñas al inicio
    let productoId = form?.querySelector("input[name='producto_id']")?.value;
    if (productoId) {
      cargarReseñas(productoId);
    }
  });

  async function cargarReseñas(productoId) {
    let res = await fetch(`/tienda-tecnologia/php/reseñas/obtener.php?producto_id=${productoId}`);
    let reseñas = await res.json();
    let cont = document.getElementById("lista-reseñas");
    cont.innerHTML = reseñas.length > 0
      ? reseñas.map(r => `
        <div class="reseña">
          <b>${r.nombre}</b> (${r.calificacion}⭐)
          <p>${r.comentario}</p>
        </div>
      `).join("")
      : "<p>No hay reseñas aún</p>";
  }

  async function actualizarPromedio(productoId) {
    let res = await fetch(`/tienda-tecnologia/php/reseñas/promedio.php?producto_id=${productoId}`);
    let data = await res.json();
    let promedio = data.promedio;

    let contPromedio = document.querySelector(".promedio-estrellas");
    if (promedio) {
      contPromedio.innerHTML = `${"⭐".repeat(Math.round(promedio))} <span>(${parseFloat(promedio).toFixed(1)}/5)</span>`;
    } else {
      contPromedio.innerHTML = "<span>Sin reseñas todavía</span>";
    }
  }

  // ✅ Toast bonito en lugar de alert()
  function mostrarToast(mensaje) {
    let toast = document.createElement("div");
    toast.className = "toast";
    toast.innerText = mensaje;
    document.body.appendChild(toast);

    setTimeout(() => { toast.classList.add("mostrar"); }, 100); // animación
    setTimeout(() => { toast.classList.remove("mostrar"); }, 3000);
    setTimeout(() => { toast.remove(); }, 3500);
  }
