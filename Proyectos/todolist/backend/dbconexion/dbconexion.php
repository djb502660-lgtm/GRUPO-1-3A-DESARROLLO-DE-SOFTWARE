<?php
class dbconexion {
    public static function conectar() {
        try {
            $host = "localhost";
            $user = "root";
            $password = "";
            $database = "dbactividades"; // Revisa que este nombre coincida con tu BD

            $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;

        } catch (PDOException $e) {
            // Muestra el error de conexión en caso de que ocurra
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }
}

// --- CÓDIGO DE PRUEBA DE CONEXIÓN ---
// Visita este archivo en tu navegador para verificar si la conexión funciona.
$conn = dbconexion::conectar();
if ($conn) {
    echo "✅ Conexión exitosa a la base de datos.<br>";
}
// --- FIN DEL CÓDIGO DE PRUEBA ---
?>