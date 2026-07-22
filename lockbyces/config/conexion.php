<?php
$servidor = 'localhost';
$usuario = 'root';
$contraseña = '';
$basedatos = 'lockbyces';

$conexion = new mysqli($servidor, $usuario, $contraseña, $basedatos);

if ($conexion->connect_error) {
    die("error de conexion:".$conexion->connect_error);
}

$conexion -> set_charset("utf8");

?>