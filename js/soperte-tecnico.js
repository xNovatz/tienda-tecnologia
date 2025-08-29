// Este JS es utilizado en: index.php, gabinetes.php, graphic.php, laptops.php, mi-cuenta.php, monitores.php, mouse.php, procesadores.php, rams.php
// -------------- Soporte tecnico ------------
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('chat-toggle');
  const chatBox = document.getElementById('chat-box');

  if (toggle && chatBox) {
    toggle.addEventListener('click', () => {
      chatBox.classList.toggle('hidden');
    });
  }
});