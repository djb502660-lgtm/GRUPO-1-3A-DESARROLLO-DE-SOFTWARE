<?php
// Script de prueba movido a /Proyectos/todolist/scripts/
// Verifica la conexión usando la clase dbactividades
require_once __DIR__ . '/../backend/dbconexion/dbconexion.php';

try {
    $pdo = dbactividades::conectar(true); // lanzar excepción si falla

    if ($pdo instanceof PDO) {
        echo "OK: Conexión establecida\n";
    } else {
        echo "FAIL: No se obtuvo un objeto PDO\n";
    }
} catch (PDOException $e) {
    // Mostrar un mensaje legible; detalles adicionales están en el log para no exponerlos innecesariamente
    echo "ERROR: No se pudo conectar a la base de datos. Detalle: " . $e->getMessage() . "\n";
}

