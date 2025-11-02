<?php
class dbconexion{
    public static function conectar(){
        try{
            $host="localhost";
            $user="root";
            $password="1234";
            $port=3306;
            $database="dbactividades";

        
            $conn= new PDO("mysql:host=$host;dbname=$database;port=$port;charset=utf8", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }catch(PDOException $e){

            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}
