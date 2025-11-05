<?php
require_once __DIR__ . "/../dbconexion/db_conexion.php";

class consultas{

    public static function mostrarActividad(){
        $conn=dbconexion::conectar();
        $query="SELECT * FROM actividades";
        $stmt=$conn->prepare($query);
        $stmt->execute();
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function crearActividad($actividad, $descripcion, $estado, $observacion,$tipo_actividad){
        $conn=dbconexion::conectar();
        $query="INSERT INTO actividades (actividad, descripcion, estado, observacion, tipo_actividad) VALUES (?, ?, ?, ?, ?)";
        $stmt=$conn->prepare($query);
        $stmt->bindParam(1, $actividad);
        $stmt->bindParam(2, $descripcion);
        $stmt->bindParam(3, $estado, PDO::PARAM_INT);
        $stmt->bindParam(4, $observacion);
        $stmt->bindParam(5, $tipo_actividad);
        $stmt->execute();
        return json_encode(['success' => $stmt->rowCount() > 0]);
    }

    public static function eliminarActividad($id){
        $conn=dbconexion::conectar();
        $query="DELETE FROM actividades WHERE id=?";
        $stmt=$conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return json_encode(['success' => $stmt->rowCount() > 0]);
    }

    public static function editarActividad($id, $actividad, $descripcion, $estado, $observacion, $tipo_actividad){
        $conn=dbconexion::conectar();
        $query="UPDATE actividades SET actividad=?, descripcion=?, estado=?, observacion=?, tipo_actividad=? WHERE id=?";
        $stmt=$conn->prepare($query);
        $stmt->bindParam(1, $actividad);
        $stmt->bindParam(2, $descripcion);
        $stmt->bindParam(3, $estado, PDO::PARAM_INT);
        $stmt->bindParam(4, $observacion);
        $stmt->bindParam(5, $tipo_actividad);
        $stmt->bindParam(6, $id, PDO::PARAM_INT);
        $stmt->execute();
        return json_encode(['success' => $stmt->rowCount() > 0]);
    }

    public static function obtenerActividadPorId($id){
        $conn=dbconexion::conectar();
        $query="SELECT * FROM actividades WHERE id=?";
        $stmt=$conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $actividad = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode(['success' => !!$actividad, 'data' => $actividad]);
    }
}