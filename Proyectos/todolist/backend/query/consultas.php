<?php 
// Ruta al archivo de conexión. Asegúrate de que sea la correcta.
include '../dbconexion/dbconexion.php'; 

class consultas {

    // FUNCION MOSTRAR ACTIVIDADES
    public static function mostrarActividad() {
        try {
            $conn = dbconexion::conectar();
            if (!$conn) {
                return json_encode(['success' => false, 'message' => 'No se pudo conectar a la base de datos.']);
            }
            $query = "SELECT * FROM actividades ORDER BY fecha_de_creacion DESC";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode(['success' => true, 'data' => $actividades]);
        } catch (PDOException $e) {
            error_log("Error en mostrarActividad: " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Error al consultar las actividades.']);
        }
    }

    // FUNCION CREAR ACTIVIDAD
    public static function crearActividad($actividad, $descripcion, $estado, $observacion) {
        try {
            $conn = dbconexion::conectar();
            if (!$conn) {
                return json_encode(['success' => false, 'message' => 'No se pudo conectar a la base de datos.']);
            }
            $query = "INSERT INTO actividades (actividad, descripcion, estado, observacion) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $actividad);
            $stmt->bindParam(2, $descripcion);
            $stmt->bindParam(3, $estado);
            $stmt->bindParam(4, $observacion);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return json_encode(['success' => true, 'message' => 'Actividad creada correctamente']);
            } else {
                return json_encode(['success' => false, 'message' => 'Error al crear la actividad']);
            }
        } catch (PDOException $e) {
            error_log("Error en crearActividad: " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Error en el servidor al crear la actividad.']);
        }
    }

    // FUNCION ELIMINAR ACTIVIDAD
    public static function eliminarActividad($id) {
        try {
            $conn = dbconexion::conectar();
            if (!$conn) {
                return json_encode(['success' => false, 'message' => 'No se pudo conectar a la base de datos.']);
            }
            $query = "DELETE FROM actividades WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return json_encode(['success' => true, 'message' => 'Actividad eliminada correctamente']);
            } else {
                return json_encode(['success' => false, 'message' => 'Error al eliminar la actividad o no se encontró el ID.']);
            }
        } catch (PDOException $e) {
            error_log("Error en eliminarActividad: " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Error en el servidor al eliminar la actividad.']);
        }
    }

    // FUNCION EDITAR ACTIVIDAD
    public static function editarActividad($id, $actividad, $descripcion, $estado, $observacion) {
        try {
            $conn = dbconexion::conectar();
            if (!$conn) {
                return json_encode(['success' => false, 'message' => 'No se pudo conectar a la base de datos.']);
            }
            $query = "UPDATE actividades SET actividad = ?, descripcion = ?, estado = ?, observacion = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $actividad);
            $stmt->bindParam(2, $descripcion);
            $stmt->bindParam(3, $estado);
            $stmt->bindParam(4, $observacion);
            $stmt->bindParam(5, $id);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return json_encode(['success' => true, 'message' => 'Actividad actualizada correctamente']);
            } else {
                return json_encode(['success' => false, 'message' => 'No se realizaron cambios o no se encontró el ID.']);
            }
        } catch (PDOException $e) {
            error_log("Error en editarActividad: " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Error en el servidor al editar la actividad.']);
        }
    }

    // FUNCION OBTENER ACTIVIDAD POR ID
    public static function obtenerActividadPorId($id) {
        try {
            $conn = dbconexion::conectar();
            if (!$conn) {
                return json_encode(['success' => false, 'message' => 'No se pudo conectar a la base de datos.']);
            }
            $query = "SELECT * FROM actividades WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $actividad = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($actividad) {
                return json_encode(['success' => true, 'data' => $actividad]);
            } else {
                return json_encode(['success' => false, 'message' => 'Actividad no encontrada']);
            }
        } catch (PDOException $e) {
            error_log("Error en obtenerActividadPorId: " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Error en el servidor al obtener la actividad.']);
        }
    }

    // FUNCION AGREGAR OBSERVACION
    public static function agregarObservacion($id, $observacion) {
        try {
            $conn = dbconexion::conectar();
            if (!$conn) {
                return json_encode(['success' => false, 'message' => 'No se pudo conectar a la base de datos.']);
            }
            $query = "UPDATE actividades SET observacion = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $observacion);
            $stmt->bindParam(2, $id);
            $stmt->execute();
            
            return json_encode(['success' => true, 'message' => 'Observación actualizada correctamente.']);

        } catch (PDOException $e) {
            error_log("Error en agregarObservacion: " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Error en el servidor al agregar la observación.']);
        }
    }
}
?>