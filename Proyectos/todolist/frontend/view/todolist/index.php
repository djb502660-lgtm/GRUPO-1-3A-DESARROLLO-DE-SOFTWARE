<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>

    <link rel="stylesheet" href="../../css/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body {
            background-color: #f5f5f5;
            color: #4a4a4a;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            padding: 40px 0;
        }

        .container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .badge-pendiente {
            background-color: #f39c12;
        }

        .badge-en-progreso {
            background-color: #3498db;
        }

        .badge-completado {
            background-color: #2ecc71;
        }

        .action-icon {
            font-size: 18px;
            cursor: pointer;
        }

        .action-icon:hover {
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">📋 LISTA DE ACTIVIDADES</h1>

        <div class="text-end mb-3">
            <a href="crear_actividad.php" class="btn btn-primary">➕ Agregar Actividad</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Actividad</th>
                        <th>Descripción</th>
                        <th>Observación</th>
                        <th>Tipo</th> <!-- 🆕 Solo agregué esta columna -->
                        <th>Estado</th>
                        <th>Creación</th>
                        <th>Actualización</th>
                        <th colspan="4">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script src="../../js/jquery-3.7.1.min.js"></script>
    <script src="../../css/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="module">
        import { API_URL } from '../../js/config.js';

        document.addEventListener('DOMContentLoaded', () => {
            MostrarActividad();
        });

        function MostrarActividad() {
            fetch(`${API_URL}?action=mostrar_actividades`)
                .then(res => res.json())
                .then(response => {
                    const tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';

                    if (!response.success || response.data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="11" class="text-center text-muted">No hay actividades registradas.</td></tr>`;
                        return;
                    }

                    response.data.forEach(actividad => {
                        const estadoInfo = getEstadoInfo(actividad.estado);
                        const observacion = actividad.observacion || '—';

                        const fechaCreacion = actividad.fecha_creacion
                            ? new Date(actividad.fecha_creacion).toLocaleDateString('es-ES')
                            : '—';

                        const fechaActualizacion = actividad.fecha_actualizacion
                            ? new Date(actividad.fecha_actualizacion).toLocaleDateString('es-ES')
                            : '—';

                        tbody.innerHTML += `
                            <tr>
                                <td>${actividad.id}</td>
                                <td>${actividad.actividad}</td>
                                <td>${actividad.descripcion}</td>
                                <td>${observacion}</td>
                                <td>${actividad.tipo_actividad || 'General'}</td> <!-- 🆕 Solo agregué esta celda -->
                                <td><span class="badge ${estadoInfo.clase}">${estadoInfo.texto}</span></td>
                                <td>${fechaCreacion}</td>
                                <td>${fechaActualizacion}</td>

                                <td><a href="ver_actividad.php?id=${actividad.id}" class="text-info action-icon" title="Ver Detalles">👁️</a></td>

                                <td><a href="editar_actividad.php?id=${actividad.id}" class="text-warning action-icon" title="Editar">✏️</a></td>

                                <td><span onclick="eliminarActividad(${actividad.id})" class="text-danger action-icon" title="Eliminar">🗑️</span></td>
                                <td><a href="descargar_actividad.php?id=${actividad.id}" class="text-success action-icon" title="Descargar">⬇️</a></td>
                            </tr>
                        `;
                    });
                })
                .catch(() => {
                    document.querySelector('tbody').innerHTML = `<tr><td colspan="11" class="text-center text-danger">Error de conexión.</td></tr>`;
                });
        }

        function getEstadoInfo(estado) {
            switch (String(estado)) {
                case '0': return { texto: 'Pendiente', clase: 'badge-pendiente' };
                case '2': return { texto: 'En progreso', clase: 'badge-en-progreso' };
                case '1': return { texto: 'Completado', clase: 'badge-completado' };
                default: return { texto: 'Desconocido', clase: '' };
            }
        }

        window.eliminarActividad = function (id) {
            Swal.fire({
                title: "¿Eliminar actividad?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(API_URL, {
                        method: "POST",
                        body: new URLSearchParams({ action: "eliminar_actividad", id })
                    })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire(data.success ? "Eliminado" : "Error", data.message, data.success ? "success" : "error");
                            MostrarActividad();
                        });
                }
            });
        }
    </script>
</body>

</html>