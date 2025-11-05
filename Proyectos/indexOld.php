<!DOCTYPE html>
<html lang="es">

<?php include 'todolist/'; ?>
<?php include '../componet/nav.php'; ?>

    <body>
        <div class="container cont_todolist">
            <h1 class="text-center">LISTAS DE ACTIVIDADES  <?php echo $_SESSION['nombre']; ?></h1>
         <a href="crear_actividad" class="btn btn-primary">Agregar Actividad</a>
        <table class="table table-dark table-striped" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Actividad</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Observación</th>
                    <th>Fecha de Creación</th>
                    <th>Fecha de Actualización</th>
                    <th>Ver</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                    <th>Descargar</th>
                </tr>
                
            </thead>
            <tbody>
              
            </tbody>
        </table>
        
        </div>
      
    <?php include '../componet/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            MostrarActividad();
        });
    </script>
    </body>
</html>