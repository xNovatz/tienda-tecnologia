document.addEventListener('DOMContentLoaded', async () => {
  const ventas = document.getElementById('total-ventas');
  const usuarios = document.getElementById('total-usuarios');
  const ingresos = document.getElementById('ingresos-totales');
  const tablaBody = document.querySelector('#tabla-notificaciones tbody');

  try {
    const res = await fetch('obtener_estadisticas.php');
    const data = await res.json();
    ventas.textContent = data.total_ventas;
    usuarios.textContent = data.total_usuarios;
    ingresos.textContent = `$${parseFloat(data.total_ingresos).toFixed(2)}`;
  } catch (err) {
    ventas.textContent = usuarios.textContent = ingresos.textContent = 'Error';
  }

  try {
    const res = await fetch('obtener_notificaciones.php');
    const notificaciones = await res.json();
    tablaBody.innerHTML = notificaciones.map(n => `
      <tr>
        <td>${n.id}</td>
        <td>${n.usuario}</td>
        <td>$${parseFloat(n.total).toFixed(2)}</td>
        <td>${n.fecha}</td>
      </tr>
    `).join('');
  } catch (err) {
    tablaBody.innerHTML = '<tr><td colspan="4">Error al cargar ventas recientes</td></tr>';
  }
});
