<?php
require './config/conexion.php';

$nombre     = $_POST['nombre'];
$edad       = $_POST['edad'];
$correo     = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$telefono   = $_POST['telefono'];
$rol        = $_POST['rol'];
$genero     = $_POST['genero'];

if (strlen($contrasena) < 8) {
    echo "<script>
            alert('Error: La contraseña debe tener como mínimo 8 caracteres.');
            window.history.back();
          </script>";
    exit();
}

if (strlen($telefono) !== 10 || !is_numeric($telefono)) {
    echo "<script>
            alert('Error: El número telefónico debe contener exactamente 10 dígitos numéricos.');
            window.history.back();
          </script>";
    exit();
}

$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nombre, edad, correo, contrasena, telefono, rol, genero) 
        VALUES ('$nombre', '$edad', '$correo', '$contrasena_hash', '$telefono', '$rol', '$genero')";

if ($conexion->query($sql) === TRUE) {
    echo "<script>
            alert('¡Usuario registrado con éxito!');
            window.location.href = 'login.php';
          </script>";
} else {
    echo "Error al registrar: " . $conexion->error;
}

$conexion->close();
?>