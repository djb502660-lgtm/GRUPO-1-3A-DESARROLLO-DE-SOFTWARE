<?php
class dbconexion{
    public static function conectar(){
        try{
            $host="localhost";
            $user="root";
            $password="";
            $port=3306;
            $database="db_actividades";
            $conn= new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
            

        }catch(PDOException $e){
            echo "❌ Error de conexión: " . $e->getMessage();
        }
    }
}
?>

