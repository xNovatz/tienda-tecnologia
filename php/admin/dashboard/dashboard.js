document.addEventListener('DOMContentLoaded', async () => {
  const ventas = document.getElementById('total-ventas');
  const usuarios = document.getElementById('total-usuarios');
  const ingresos = document.getElementById('ingresos-totales');
  const tablaBody = document.querySelector('#tabla-notificaciones tbody');

  // Función para animar contadores
  function animarContador(elemento, valorFinal, duracion = 1500) {
    let inicio = 0;
    let incremento = valorFinal / (duracion / 30);
    let intervalo = setInterval(() => {
      inicio += incremento;
      if (inicio >= valorFinal) {
        inicio = valorFinal;
        clearInterval(intervalo);
      }
      elemento.textContent = Math.floor(inicio);
    }, 30);
  }

  try {
    const res = await fetch('obtener_estadisticas.php');
    const data = await res.json();

    animarContador(ventas, data.total_ventas);
    animarContador(usuarios, data.total_usuarios);
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

async function cargarGraficas() {
  try {
    let res = await fetch("obtener_graficas.php");
    let data = await res.json();

    // Gráfica de ventas por mes (línea)
    new Chart(document.getElementById("grafica-ventas").getContext("2d"), {
      type: "line",
      data: {
        labels: data.meses,
        datasets: [{
          label: "Ventas",
          data: data.ventas,
          borderColor: "#006341",
          backgroundColor: "rgba(0,99,65,0.2)",
          tension: 0.3,
          fill: true,
          pointRadius: 5,
          pointBackgroundColor: "#006341"
        }]
      },
      options: {
        plugins: {
          tooltip: {
            callbacks: {
              label: ctx => `${ctx.parsed.y} ventas`
            }
          }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    // Gráfica de usuarios por mes (barras)
    new Chart(document.getElementById("grafica-usuarios").getContext("2d"), {
      type: "bar",
      data: {
        labels: data.meses,
        datasets: [{
          label: "Usuarios registrados",
          data: data.usuarios,
          backgroundColor: "#4da6ff"
        }]
      },
      options: {
        plugins: {
          tooltip: {
            callbacks: {
              label: ctx => `${ctx.parsed.y} usuarios`
            }
          }
        },
        scales: {
          y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
      }
    });

  } catch (err) {
    console.error("Error cargando gráficas", err);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  cargarGraficas();
});
