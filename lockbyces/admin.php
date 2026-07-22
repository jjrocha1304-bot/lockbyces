<?php
session_start();

// Validar que el usuario tenga sesión activa y sea administrador
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - LockByces</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

    <div class="container">
        <div class="header-admin">
            <h2>Administración de Usuarios</h2>
            <button class="btn-crear" onclick="crearUsuarioAdmin()">+ Nuevo Usuario</button>
        </div>

        <h3>Panel de Usuarios Registrados</h3>

        <div style="overflow-x: auto;">
            <table class="tabla-lockbyces">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Género</th>
                        <th>Edad</th>
                        <th>Rol</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-admin-usuarios">
                    <!-- JavaScript cargará los datos de los usuarios aquí -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>