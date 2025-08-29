document.addEventListener('DOMContentLoaded', () => {
  const tabla = document.querySelector('#tablaUsuarios tbody');
  const modal = document.getElementById('modalEditar');
  const form = document.getElementById('formEditar');
  const filtro = document.getElementById('filtroUsuarios');
  let todosLosUsuarios = [];

  // Cargar todos los usuarios al inicio
  function cargarUsuarios() {
    fetch('obtener_usuarios.php')
      .then(res => res.json())
      .then(data => {
        todosLosUsuarios = data;
        renderizarTabla(data);
      });
  }

  // Pintar la tabla con una lista dada
  function renderizarTabla(usuarios) {
    tabla.innerHTML = '';
    usuarios.forEach(user => {
      const fila = document.createElement('tr');
      fila.innerHTML = `
        <td>${user.id}</td>
        <td>${user.nombre}</td>
        <td>${user.correo}</td>
        <td>${user.rol}</td>
        <td>${user.telefono ?? ''}</td>
        <td>
          <button class="btn-editar" data-user='${JSON.stringify(user)}'>✏️</button>
          <button class="btn-eliminar" data-id="${user.id}">🗑️</button>
        </td>
      `;
      tabla.appendChild(fila);
    });

    // Botones eliminar
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        if (confirm('¿Eliminar este usuario?')) {
          fetch('eliminar_usuario.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
          }).then(() => cargarUsuarios());
        }
      });
    });

    // Botones editar
    document.querySelectorAll('.btn-editar').forEach(btn => {
      btn.addEventListener('click', () => {
        const user = JSON.parse(btn.dataset.user);
        document.getElementById('edit-id').value = user.id;
        document.getElementById('edit-nombre').value = user.nombre;
        document.getElementById('edit-correo').value = user.correo;
        document.getElementById('edit-rol').value = user.rol;
        document.getElementById('edit-telefono').value = user.telefono ?? '';
        modal.classList.add('show');
      });
    });
  }

  // Guardar cambios
  form.addEventListener('submit', e => {
    e.preventDefault();
    const formData = {
      id: form['id'].value,
      nombre: form['nombre'].value,
      correo: form['correo'].value,
      rol: form['rol'].value,
      telefono: form['telefono'].value
    };

    fetch('editar_usuario.php', {
      method: 'POST',
      body: JSON.stringify(formData),
      headers: { 'Content-Type': 'application/json' }
    }).then(res => res.json()).then(res => {
      if (res.ok) {
        modal.classList.remove('show');
        cargarUsuarios();
      } else {
        alert('Error al actualizar');
      }
    });
  });

  // Buscar en tiempo real
  filtro.addEventListener('input', () => {
    const texto = filtro.value.toLowerCase();
    const filtrados = todosLosUsuarios.filter(user =>
      user.nombre.toLowerCase().includes(texto) ||
      user.correo.toLowerCase().includes(texto)
    );
    renderizarTabla(filtrados);
  });

  // Iniciar carga
  cargarUsuarios();
});
