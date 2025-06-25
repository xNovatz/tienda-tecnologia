document.addEventListener("DOMContentLoaded", function () {
  const btnCerrarSesion = document.getElementById("btnCerrarSesion");
  const modal = document.getElementById("modalCerrarSesion");
  const confirmar = document.getElementById("confirmarCerrarSesion");
  const cancelar = document.getElementById("cancelarCerrarSesion");

  if (btnCerrarSesion && modal && confirmar && cancelar) {
    btnCerrarSesion.addEventListener("click", function (e) {
      e.preventDefault();
      modal.classList.add("show");
    });

    confirmar.addEventListener("click", function () {
      // Detecta si estás en una subcarpeta como /paginas/
      const basePath = window.location.pathname.includes("/paginas/") ? "../" : "./";
      window.location.href = basePath + "php/cerrar_sesion.php";
    });

    cancelar.addEventListener("click", function () {
      modal.classList.remove("show");
    });
  }
});
