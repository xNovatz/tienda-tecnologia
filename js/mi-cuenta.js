// Este JS es utilizado en: mi-cuenta.php
// Modal de actualización de datos
const modal = document.getElementById('modalActualizar');
const btnAbrir = document.getElementById('btnActualizar');
const form = document.getElementById('formActualizar');

btnAbrir.addEventListener('click', () => modal.classList.add('show'));
function cerrarModal() { modal.classList.remove('show'); }

form.addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(form);

  fetch('../php/actualizar_datos.php', {
    method: 'POST',
    body: formData
  })
    .then(res => {
      if (!res.ok) throw new Error("Error al actualizar");
      return res.text();
    })
    .then(() => {
      document.getElementById('info-nombre').textContent = formData.get('nombre');
      document.getElementById('info-telefono').textContent = formData.get('telefono');
      document.getElementById('info-departamento').textContent = formData.get('departamento');
      document.getElementById('info-municipio').textContent = formData.get('municipio');
      document.getElementById('info-direccion').textContent = formData.get('direccion');
      document.getElementById('info-referencia').textContent = formData.get('punto_referencia');
      document.getElementById('mensajeExito').style.display = 'block';
      setTimeout(() => {
        document.getElementById('mensajeExito').style.display = 'none';
        cerrarModal();
      }, 2000);
    })
    .catch(err => alert("Ocurrió un error al guardar."));
});

// Modal de cambio de contraseña
const modalClave = document.getElementById('modalPassword');
const btnClave = document.getElementById('btnCambiar');
const formClave = document.getElementById('formPassword');

btnClave.addEventListener('click', () => modalClave.classList.add('show'));
function cerrarModalClave() { modalClave.classList.remove('show'); }

formClave.addEventListener('submit', function (e) {
  e.preventDefault();

  const actual = formClave.clave_actual.value;
  const nueva = formClave.nueva_clave.value;
  const confirmar = formClave.confirmar_clave.value;

  const mensaje = document.getElementById('mensajeClave');

  if (nueva !== confirmar) {
    mensaje.style.display = 'block';
    mensaje.style.color = 'red';
    mensaje.textContent = 'Las contraseñas no coinciden';
    return;
  }

  fetch('../php/cambiar_clave.php', {
    method: 'POST',
    body: new FormData(formClave)
  })
    .then(res => res.text())
    .then(data => {
      mensaje.style.color = data.includes('correctamente') ? 'green' : 'red';
      mensaje.style.display = 'block';
      mensaje.textContent = data;
      if (data.includes('correctamente')) {
        formClave.reset();
        setTimeout(cerrarModalClave, 2000);
      }
    })
    .catch(() => {
      mensaje.style.color = 'red';
      mensaje.style.display = 'block';
      mensaje.textContent = 'Error al cambiar la contraseña';
    });
});
