<?php
session_start();

require_once __DIR__ . '/config/conexion.php'; 

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conexion, trim($_POST['email']));
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {

        $consulta = "SELECT * FROM usuarios WHERE correo = '$email'";
        $resultado = $conexion->query($consulta);

        if ($resultado && $resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();

            if (password_verify($password, $usuario['contrasena'])) {
                
                $_SESSION['usuario_id'] = $usuario['ID']; 
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol']; // Asigna 'admin', 'cliente', etc.

                header("Location: dashboard.php"); 
                exit;
                
            } else {
                $error_message = "El correo electrónico o la contraseña son incorrectos.";
            }
        } else {
            $error_message = "El correo electrónico o la contraseña son incorrectos.";
        }
    } else {
        $error_message = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - LOCKBYCES</title>
    
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/propiedades_personalizadas.css">
    <link rel="icon" type="image/png" href="img/favicon.jpeg" />

    <!-- Fuentes Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<body>

    <div class="login-container">

        <img src="img/logo_lockbyces.jpeg" alt="LOCKBYCES Logo" class="login-logo">
        
        <h2>Bienvenido</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error-box">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-submit">Ingresar</button>
        </form>
    </div>

</body>
</html>