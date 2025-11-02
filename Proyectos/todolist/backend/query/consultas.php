<?php
require_once __DIR__ . '/dbconexion.php';

class consultas{

    public static function mostrarActividad(){
        $conn=dbconexion::conectar();
        $query="SELECT * FROM actividades";
        $stmt=$conn->prepare($query);
        $stmt->execute();
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // 🟢 CORRECCIÓN: Recibe 4 argumentos y usa 4 placeholders en INSERT
    public static function crearActividad($actividad, $descripcion, $estado, $observacion){
        $conn=dbconexion::conectar();
        $query="INSERT INTO actividades (actividad, descripcion, estado, observacion) VALUES (?, ?, ?, ?)";
        $stmt=$conn->prepare($query);
        $stmt->bindParam(1, $actividad);
        $stmt->bindParam(2, $descripcion);
        $stmt->bindParam(3, $estado);
        $stmt->bindParam(4, $observacion); // Nuevo bindParam
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

    // 🟢 CORRECCIÓN: Recibe 5 argumentos y usa 4 SETs + 1 WHERE en UPDATE
    public static function editarActividad($id, $actividad, $descripcion, $estado, $observacion){
        $conn=dbconexion::conectar();
        $query="UPDATE actividades SET actividad=?, descripcion=?, estado=?, observacion=? WHERE id=?";
        $stmt=$conn->prepare($query);
        $stmt->bindParam(1, $actividad);
        $stmt->bindParam(2, $descripcion);
        $stmt->bindParam(3, $estado);
        $stmt->bindParam(4, $observacion); // Nuevo bindParam
        $stmt->bindParam(5, $id);
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