<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>

    <link rel="stylesheet" href="../../css/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #50e3c2;
            --light-gray: #f7f9fc;
            --dark-gray: #4a4a4a;
            --text-muted: #9b9b9b;
            --border-color: #e1e8ed;
        }

        body {
            background-color: var(--light-gray);
            color: var(--dark-gray);
            font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .container-wrapper {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
            margin-top: 50px;
        }

        h1 {
            color: var(--dark-gray);
            font-weight: 700;
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #357abd;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 10px;
            margin-top: 20px;
        }

        .table thead th {
            background-color: transparent;
            border: none;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            transition: all 0.2s ease-in-out;
        }

        .table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .table tbody td {
            border: none;
            padding: 20px;
            vertical-align: middle;
        }

        .table tbody td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
        .table tbody td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }

        .badge {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .action-icon {
            font-size: 20px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s ease;
        }

        .action-icon:hover {
            transform: scale(1.2);
        }
    </style>
</head>

<body>
    <div class="container container-wrapper">
        <h1 class="text-center">📋 LISTA DE ACTIVIDADES</h1>

        <div class="text-end mb-3">
            <a href="../../home/Index.php" class="btn btn-secondary me-2">🏠 Regresar a Home</a>
            <a href="crear_actividad.php" class="btn btn-primary">➕ Agregar Actividad</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
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
                                <td><span class="badge rounded-pill ${estadoInfo.clase}">${estadoInfo.texto}</span></td>
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
                case '0': return { texto: 'Pendiente', clase: 'bg-warning text-dark' };
                case '2': return { texto: 'En progreso', clase: 'bg-primary' };
                case '1': return { texto: 'Completado', clase: 'bg-success' };
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