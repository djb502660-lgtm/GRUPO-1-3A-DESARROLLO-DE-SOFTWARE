<?php

// ==== CONFIGURACIÓN CORS ====
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=utf-8");

// Si es preflight OPTIONS, responder y terminar
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// ==== CONEXIÓN A LA BD ====
require_once "../dbconexion/db_conexion.php";
$conn = dbconexion::conectar();

// Obtener acción por GET o POST
$accion = $_POST["action"] ?? $_GET["action"] ?? '';


// =====================================================================
// ✅ CREAR ACTIVIDAD - ACTUALIZADO
// =====================================================================
if ($accion === "crear_actividad") {

    $actividad      = $_POST["actividad"] ?? '';
    $descripcion    = $_POST["descripcion"] ?? '';
    $observacion    = $_POST["observacion"] ?? '';
    $estado         = $_POST["estado"] ?? '';
    $tipo_actividad = $_POST["tipo_actividad"] ?? 'General'; // 🆕 Agregado

    if (empty($actividad) || empty($descripcion) || $estado === '') {
        echo json_encode(["success" => false, "message" => "Completa los campos requeridos."]);
        exit();
    }

    try {
        $sql = "INSERT INTO actividades (actividad, descripcion, observacion, tipo_actividad, estado, fecha_de_creacion)
                VALUES (:actividad, :descripcion, :observacion, :tipo_actividad, :estado, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":actividad", $actividad);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":observacion", $observacion);
        $stmt->bindParam(":tipo_actividad", $tipo_actividad); // 🆕 Agregado
        $stmt->bindParam(":estado", $estado);
        
        $stmt->execute();

        echo json_encode(["success" => true, "message" => "✅ Actividad creada correctamente."]);
        exit();

    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
    "message" => "❌ Error al guardar la actividad.",
    "error" => $e->getMessage(),
    "trace" => $e->getTraceAsString()]);
        exit();
    }
}


// =====================================================================
// ✅ MOSTRAR TODAS LAS ACTIVIDADES
// =====================================================================
if ($accion === "mostrar_actividades") {

    try {
        $sql = "SELECT * FROM actividades ORDER BY id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["success" => true, "data" => $datos]);
        exit();

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error al obtener actividades.", "error" => $e->getMessage()]);
        exit();
    }
}


// =====================================================================
// ✅ OBTENER DATOS DE UNA ACTIVIDAD (PARA VER DETALLE)
// =====================================================================
if ($accion === "obtener_actividad") {

    $id = $_GET["id"] ?? '';

    if (!$id) {
        echo json_encode(["success" => false, "message" => "Falta el ID."]);
        exit();
    }

    try {
        $sql = "SELECT * FROM actividades WHERE id = :id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $actividad = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($actividad) {
            echo json_encode(["success" => true, "data" => $actividad]);
        } else {
            echo json_encode(["success" => false, "message" => "Actividad no encontrada."]);
        }
        exit();

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error al obtener actividad.", "error" => $e->getMessage()]);
        exit();
    }
}


// =====================================================================
// ✅ ELIMINAR ACTIVIDAD
// =====================================================================
if ($accion === "eliminar_actividad") {

    $id = $_POST["id"] ?? '';

    if (!$id) {
        echo json_encode(["success" => false, "message" => "No se recibió el ID de la actividad."]);
        exit();
    }

    try {
        $sql = "DELETE FROM actividades WHERE id = :id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "🗑️ Actividad eliminada correctamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "No se encontró la actividad."]);
        }
        exit();

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error al eliminar.", "error" => $e->getMessage()]);
        exit();
    }
}

// =====================================================================
// ✅ EDITAR ACTIVIDAD - ACTUALIZADO
// =====================================================================
if ($accion === "editar_actividad") {

    $id             = $_POST["id"] ?? '';
    $actividad      = $_POST["actividad"] ?? '';
    $descripcion    = $_POST["descripcion"] ?? '';
    $observacion    = $_POST["observacion"] ?? '';
    $estado         = $_POST["estado"] ?? '';
    $tipo_actividad = $_POST["tipo_actividad"] ?? 'General'; // 🆕 Agregado

    if (empty($id) || empty($actividad) || empty($descripcion)) {
        echo json_encode([
            "success" => false,
            "message" => "Completa los campos requeridos."
        ]);
        exit();
    }

    try {
        $sql = "UPDATE actividades
                SET actividad = :actividad,
                    descripcion = :descripcion,
                    observacion = :observacion,
                    tipo_actividad = :tipo_actividad,
                    estado = :estado,
                    fecha_de_actualizacion = NOW()
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":actividad", $actividad);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":observacion", $observacion);
        $stmt->bindParam(":tipo_actividad", $tipo_actividad); // 🆕 Agregado
        $stmt->bindParam(":estado", $estado);

        $stmt->execute();

        echo json_encode([
            "success" => true,
            "message" => "✅ Actividad actualizada correctamente."
        ]);
        exit();

    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "❌ Error al actualizar la actividad.",
            "error" => $e->getMessage()
        ]);
        exit();
    }
}

// =====================================================================
// ❌ ACCIÓN NO RECONOCIDA
// =====================================================================
echo json_encode(["success" => false, "message" => "Acción no válida."]);
exit();