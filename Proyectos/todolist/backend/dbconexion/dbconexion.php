<?php
class dbconexion {
    // Propiedades estáticas para mantener las credenciales en un solo lugar.
    private static $host = "localhost";
    private static $user = "root";
    private static $password = "";
    private static $database = "dbactividades";

    public static function conectar() {
        try {
            // Usamos las propiedades estáticas y agregamos charset=utf8 para el manejo de caracteres especiales.
            $conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$database . ";charset=utf8", self::$user, self::$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;

        } catch (PDOException $e) {
            // En un entorno de producción, es mejor registrar el error que mostrarlo en pantalla.
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Método público para obtener el nombre de la base de datos.
     * @return string
     */
    public static function getDatabaseName() {
        return self::$database;
    }
}

// --- CÓDIGO DE PRUEBA DE CONEXIÓN ---
// Este bloque solo se ejecuta si visitas este archivo directamente en el navegador.
// Por ejemplo: http://localhost/xampp/GRUPO-1-3A-DESARROLLO-DE-SOFTWARE/Proyectos/todolist/backend/dbconexion/dbconexion.php
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1>Prueba de Conexión a la Base de Datos</h1>";
    $conn = dbconexion::conectar();
    if ($conn) {
        echo "<p style='color: green; font-weight: bold;'>✅ ¡Conexión exitosa a la base de datos `" . dbconexion::getDatabaseName() . "`!</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Error: No se pudo conectar a la base de datos.</p>";
        echo "<p>Revisa las credenciales en `dbconexion.php` y asegúrate de que el servidor de base de datos (MariaDB/MySQL) esté en ejecución.</p>";
    }
}
?>