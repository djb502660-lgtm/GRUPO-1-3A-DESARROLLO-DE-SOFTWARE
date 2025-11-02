<?php
if (headers_sent($file, $line)) {
    die("❌ Error Fatal: Salida antes de headers en archivo $file en línea $line. Elimine espacios/caracteres.");
}
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 86400');

// Las cabeceras Content-Type deben ir después de las CORS
header('Content-Type: application/json');

// --- CORRECCIÓN CLAVE: Línea 11 ---
// Se usa el operador ?? para evitar 'Undefined array key' si se ejecuta por CLI.
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'CLI';

// Manejar peticiones OPTIONS (preflight)
if ($request_method === 'OPTIONS') { // Usamos la variable segura
    http_response_code(200);
    exit();
}

// Establecer un manejador de errores para capturar problemas fatales y convertirlos en una respuesta JSON.
set_error_handler(function($severity, $message, $file, $line) {
    // Solo manejamos errores que interrumpirían la ejecución
    if (!(error_reporting() & $severity)) {
        return;
    }
    if (!headers_sent()) {
        http_response_code(500); // Internal Server Error
    }

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

        // Usamos la variable ya verificada globalmente
        global $request_method;
        $method = $request_method;

        // Si el método es 'CLI', probablemente no queremos ejecutar el controlador
        if ($method === 'CLI') {
            echo json_encode(['success' => false, 'message' => 'Este script debe ejecutarse a través de un servidor web (HTTP).']);
            return;
        }

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
                        // 🟢 CORRECCIÓN: Se envían 4 argumentos, incluyendo 'observacion'
                        echo consultas::crearActividad(
                            $_POST['actividad'],
                            $_POST['descripcion'],
                            $_POST['estado'],
                            $_POST['observacion'] ?? null // Aseguramos que se envía aunque esté vacío
                        );
                    } elseif ($action == 'editar_actividad') {
                        // 🟢 CORRECCIÓN: Se envían 5 argumentos, incluyendo 'observacion'
                        echo consultas::editarActividad(
                            $_POST['id'],
                            $_POST['actividad'],
                            $_POST['descripcion'],
                            $_POST['estado'],
                            $_POST['observacion'] ?? null // Aseguramos que se envía aunque esté vacío
                        );
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