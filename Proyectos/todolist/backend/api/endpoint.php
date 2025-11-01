<?php 
// Establecer un manejador de errores para capturar problemas fatales y convertirlos en una respuesta JSON.
header('Content-Type: application/json');
set_error_handler(function($severity, $message, $file, $line) {
    // Solo manejamos errores que interrumpirían la ejecución
    if (!(error_reporting() & $severity)) {
        return;
    }
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'message' => "Error interno del servidor.",
        'error_details' => "Error: [$severity] $message in $file on line $line"
    ]);
    exit;
});

include '../query/consultas.php';

class endpoint{
    public static function mostrarActividades(){
        return consultas::mostrarActividad();
    }

    public static function EndpointController(){
        $method = $_SERVER['REQUEST_METHOD'];
        $action = null;

        // Unificar la obtención del parámetro 'action'
        if ($method == 'GET' && isset($_GET['action'])) {
            $action = $_GET['action'];
        } else if ($method == 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
        } else if ($method == 'DELETE') {
            parse_str(file_get_contents("php://input"), $data);
            if (isset($data['action'])) {
                $action = $data['action'];
            }
        }

        if ($action === null) {
            echo json_encode(['success' => false, 'message' => 'Acción no especificada o método incorrecto.']);
            return;
        }

        try {
            switch ($method) {
                case 'GET':
                    if ($action == 'mostrar_actividades') {
                        echo self::mostrarActividades();
                    } elseif ($action == 'obtener_actividad' && isset($_GET['id'])) {
                        echo consultas::obtenerActividadPorId($_GET['id']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Acción GET no válida o parámetros faltantes.']);
                    }
                    break;
                case 'POST':
                    if ($action == 'crear_actividad') {
                        echo consultas::crearActividad($_POST['actividad'], $_POST['descripcion'], $_POST['estado'], $_POST['observacion']);
                    } elseif ($action == 'editar_actividad') {
                        echo consultas::editarActividad($_POST['id'], $_POST['actividad'], $_POST['descripcion'], $_POST['estado'], $_POST['observacion']);
                    } elseif ($action == 'agregar_observacion') {
                        echo consultas::agregarObservacion($_POST['id'], $_POST['observacion']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Acción POST no válida.']);
                    }
                    break;
                case 'DELETE':
                    if ($action == 'eliminar_actividad' && isset($data['id'])) {
                        echo consultas::eliminarActividad($data['id']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Acción DELETE no válida o parámetros faltantes.']);
                    }
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                    break;
            }
        } catch (Exception $e) {
            // Captura excepciones generales
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Ocurrió una excepción en el servidor.',
                'error_details' => $e->getMessage()
            ]);
        }
    }
    
}

endpoint::EndpointController();

?>