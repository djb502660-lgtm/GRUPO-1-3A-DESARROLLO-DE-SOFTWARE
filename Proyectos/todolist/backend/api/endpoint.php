<?php
include '../query/consultas.php';

// Asegurar que la respuesta sea JSON
header('Content-Type: application/json');

class Endpoint {
    public static function mostrarActividades() {
        return consultas::mostrarActividad();
    }

    public static function EndpointController() {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if (isset($_GET['mostrar_actividades_getmethod'])) {
                    echo json_encode(self::mostrarActividades());
                } elseif (isset($_GET['obtener_actividad_por_id']) && isset($_GET['id'])) {
                    echo json_encode(consultas::obtenerActividadPorId($_GET['id']));
                } else {
                    echo json_encode(['error' => 'Parámetro no válido']);
                }
                break;

            case 'POST':
                if (isset($_POST['crear_actividad_postmethod'])) {
                    echo json_encode(consultas::crearActividad(
                        $_POST['actividad'] ?? null,
                        $_POST['descripcion'] ?? null,
                        $_POST['estado'] ?? null
                    ));
                } elseif (isset($_POST['editar_actividad_postmethod'])) {
                    echo json_encode(consultas::editarActividad(
                        $_POST['id'] ?? null,
                        $_POST['actividad'] ?? null,
                        $_POST['descripcion'] ?? null,
                        $_POST['estado'] ?? null
                    ));
                } else {
                    echo json_encode(['error' => 'Parámetro no válido']);
                }
                break;

            case 'DELETE':
                $data = json_decode(file_get_contents("php://input"), true);
                if (isset($data['eliminar_actividad_deletemethod']) && isset($data['id'])) {
                    echo json_encode(consultas::eliminarActividad($data['id']));
                } else {
                    echo json_encode(['error' => 'Parámetro no válido']);
                }
                break;

            default:
                echo json_encode(['error' => 'Método no permitido']);
        }
    }
}

Endpoint::EndpointController();
?>