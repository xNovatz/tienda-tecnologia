// Este JS es utilizado en: index.php, gabinetes.php, graphic.php, laptops.php, mi-cuenta.php, monitores.php, mouse.php, procesadores.php, rams.php
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
      window.location.href = "/tienda-tecnologia/php/cerrar_sesion.php";
    });

    cancelar.addEventListener("click", function () {
      modal.classList.remove("show");
    });
  }
});
