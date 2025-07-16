// Este JS es utilizado en: index.php, mi-cuenta.php
function toggleCategorias() {
  const lista = document.getElementById("listaCategorias");
  lista.style.display = lista.style.display === "block" ? "none" : "block";
}


// Cerrar si se hace clic fuera
document.addEventListener('click', function (e) {
  const menu = document.querySelector('.menu-categorias');
  const lista = document.getElementById('listaCategorias');
  if (!menu.contains(e.target)) {
    lista.classList.remove('show');
  }
});


