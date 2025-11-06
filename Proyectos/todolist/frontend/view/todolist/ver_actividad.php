<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ver Actividad</title>
        <link rel="stylesheet" href="../../css/style.css">
        <link rel="stylesheet" href="../../css/bootstrap-5.3.8-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="
https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css
">
    </head>
    <body>
        <div class="container cont_todolist">
            <h1 class="text-center">👁️ Ver Actividad</h1>
         <a href="index.php" class="btn btn-primary me-2">Regresar a Actividades</a>
         <a href="../../home/Index.php" class="btn btn-secondary">🏠 Regresar a Home</a>
         <div class="container documento_de_actividad" >
            <h1 class="text-center"> <strong>Actividad:</strong> <span id="actividad">Deberes de calculo</span></h1>
            <p> <strong>Descripción:</strong> <span id="descripcion">Deberes de calculo</span></p>
            <p> <strong>Estado:</strong> <span id="estado">Completado</span></p>
            <p> <strong>Tipo:</strong> <span id="tipo">Cargando...</span></p>
            <p> <strong>Fecha de Creación:</strong> <span id="fecha_creacion">2025-10-22</span></p>
            <p> <strong>Fecha de Actualización:</strong> <span id="fecha_actualizacion">2025-10-22</span></p>
            <p> <strong>Observación:</strong> <span id="observacion">Sin observaciones</span></p>
         </div>
        </div>
        <script src="../../js/jquery-3.7.1.min.js"></script>
        <script src="../../css/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script type="module">
import { API_URL } from "../../js/config.js";

document.addEventListener("DOMContentLoaded", function () {
    VerActividad();
});

function VerActividad() {
    const params = new URLSearchParams(
window.location.search
);
    const id = params.get("id");

    if (!id) {
        
Swal.fire
("Error", "No se recibió el ID.", "error");
        return;
    }

    fetch(`${API_URL}?action=obtener_actividad&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                
Swal.fire
("Error", data.message, "error");
                return;
            }

            const actividad = 
data.data
;

            document.getElementById("actividad").innerText = actividad.actividad;
            document.getElementById("descripcion").innerText = actividad.descripcion;
            document.getElementById("observacion").innerText = actividad.observacion || "Sin observaciones";

            const estadoTxt = getEstadoTexto(actividad.estado);
            document.getElementById("estado").innerText = estadoTxt;
            document.getElementById("tipo").innerText = actividad.tipo_actividad || "No especificado";

            document.getElementById("fecha_creacion").innerText = actividad.fecha_de_creacion;
            document.getElementById("fecha_actualizacion").innerText = actividad.fecha_de_actualizacion || "Aún no se ha actualizado";
        })
        .catch(() => {
            
Swal.fire
("Error", "No se pudo conectar con el servidor.", "error");
        });
}

function getEstadoTexto(estado) {
    switch (String(estado)) {
        case "0": return "Pendiente";
        break;
        case "1": return "Completado";
        break;
        case "2": return "En progreso";
        break;
        default: return "Desconocido";
    }
}
</script>

    </body>
</html> 