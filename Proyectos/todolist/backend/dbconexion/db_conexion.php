<?php
class dbconexion{
    public static function conectar(){
        try{
            $host="localhost";
            $user="root";
            $password="";
            $port=3307;
            $database="dbactividades.sql";

        
            $conn= new PDO("mysql:host=$host;dbname=$database;port=$port;charset=utf8", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }catch(PDOException $e){
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }

}
?>