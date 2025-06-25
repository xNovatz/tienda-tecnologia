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