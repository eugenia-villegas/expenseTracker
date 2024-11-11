<?php 
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "test";

    $conexion = new mysqli($server, $user, $pass, $db);

    if($conexion -> connect_errno) {
        die("Conexion fallida". $conexion -> connect_errno);
    }

    return $conexion;