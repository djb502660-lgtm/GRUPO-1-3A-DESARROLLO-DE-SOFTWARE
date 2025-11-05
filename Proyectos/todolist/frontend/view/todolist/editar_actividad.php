<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Todo List</title>
        <link rel="stylesheet" href="../../css/style.css">
        <link rel="stylesheet" href="../../css/bootstrap-5.3.8-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    </head>
    <body>
        <div class="container cont_todolist">
            <h1 class="text-center">EDITAR ACTIVIDAD</h1>
         <a href="index.php" class="btn btn-primary">Regresar al index</a>

         <div class="container fomulario_actividad" >
            <form action="" method="post" id="formulario_editar_actividad">
                <input type="hidden" id="id" name="id">
                <div class="form-group">
                    <label for="actividad">Actividad</label>
                    <input type="text" class="form-control" id="actividad" name="actividad" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                </div>
                <div class="form-group">
                    <label for="observacion">Observación (Opcional)</label>
                    <textarea class="form-control" id="observacion" name="observacion" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="tipo_actividad">Tipo de Actividad</label>
                    <input type="text" class="form-control" id="tipo_actividad" name="tipo_actividad" required>
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control">
                        <option value="0">Pendiente</option>
                        <option value="1">Completado</option>
                        <option value="2">En progreso</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Actividad</button>
            </form>
         </div>
        </div>
        <script src="js/jquery-3.7.1.min.js"></script>
        <script src="css/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script type="module">
import { API_URL } from "./js/config.js";

// Obtener ID desde la URL
const params = new URLSearchParams(window.location.search);
const id = params.get("id");

// Si no hay ID, regresar al inicio
if (!id) window.location.href = "index.php";

// ✅ Cargar datos de la actividad a editar
fetch(`${API_URL}?action=obtener_actividad&id=${id}`)
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            Swal.fire("Error", "No se pudo cargar la actividad", "error");
            return;
        }

        const actividad = data.data;

        document.getElementById("id").value = actividad.id;
        document.getElementById("actividad").value = actividad.actividad;
        document.getElementById("descripcion").value = actividad.descripcion;
        document.getElementById("observacion").value = actividad.observacion || "";
        document.getElementById("tipo_actividad").value = actividad.tipo_actividad || "General";
        document.getElementById("estado").value = actividad.estado;
    });

// ✅ Enviar actualización
document.getElementById("formulario_editar_actividad").addEventListener("submit", function(e){
    e.preventDefault();

    const formData = new FormData(this);
    formData.append("action", "editar_actividad");

    fetch(API_URL, {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire("Éxito", data.message, "success");
            setTimeout(() => window.location.href = "index.php", 1500);
        } else {
            Swal.fire("Error", data.message, "error");
        }
    })
    .catch(() => {
        Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
    });
});
</script>

    </body>
</html>