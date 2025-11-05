<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/bootstrap-5.3.8-dist/css/bootstrap.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="
https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css
">

    <!-- Librería para generar PDF -->
    <script src="
https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js
"></script>
</head>
<body>
    <div class="container cont_todolist">
        <h1 class="text-center">MOSTRAR ACTIVIDAD</h1>

        <a href="index.php" class="btn btn-primary">Regresar al index</a>
        <button class="btn btn-info" id="descargar_actividad">Descargar Actividad (PDF)</button>

        <div class="container documento_de_actividad" id="DescargarActividad">
            <h1 class="text-center"> <strong>Actividad:</strong> <span id="actividad">Deberes de calculo</span></h1>
            <p> <strong>Descripción:</strong> <span id="descripcion">Deberes de calculo</span></p>
            <p> <strong>Estado:</strong> <span id="estado">Completado</span></p>
            <p> <strong>Tipo:</strong> <span id="tipo">Cargando...</span></p>
            <p> <strong>Fecha de Creación:</strong> <span id="fecha_creacion">2025-10-22</span></p>
            <p> <strong>Fecha de Actualización:</strong> <span id="fecha_actualizacion">2025-10-22</span></p>
            <p> <strong>Observación:</strong> <span id="observacion">Sin observaciones</span></p>
        </div>
    </div>

    <!-- jQuery -->
    <script src="js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="css/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11
"></script>

    <script type="module">
        import { API_URL } from "./js/config.js";

        document.addEventListener('DOMContentLoaded', function () {
            VerActividad();
            ImprimirActividad();
        });

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
                    document.getElementById("estado").innerText = getEstadoTexto(actividad.estado);
                    document.getElementById("tipo").innerText = actividad.tipo_actividad || "No especificado";
                    document.getElementById("fecha_creacion").innerText = actividad.fecha_de_creacion;
                    document.getElementById("fecha_actualizacion").innerText = actividad.fecha_de_actualizacion;
                    document.getElementById("observacion").innerText = actividad.observacion || "Sin observaciones";
                })
                .catch(() => {
                    
Swal.fire
("Error", "No se pudo conectar con el servidor.", "error");
                });
        }

        function ImprimirActividad() {
            document.getElementById("descargar_actividad").addEventListener("click", function () {
                const elemento = document.getElementById("DescargarActividad");

                const opciones = {
                    filename: `Actividad.pdf`,
                    image: { type: 'jpeg', quality: 1 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                html2pdf().set(opciones).from(elemento).save();
            });
        }
    </script>

</body>
</html> 
