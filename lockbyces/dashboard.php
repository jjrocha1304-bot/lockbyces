<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtenemos el rol guardado en la sesión
$rolUsuario = isset($_SESSION['usuario_rol']) ? $_SESSION['usuario_rol'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LOCKBYCES</title>
    
    <!-- Estilos CSS (v=1.2 para obligar la recarga sin caché) -->
    <link rel="stylesheet" href="css/dashboard.css?v=1.2">
    <link rel="stylesheet" href="css/admin.css?v=1.2">
    <link rel="icon" type="image/png" href="img/favicon.jpeg" />

    <!-- Fuentes Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <!-- Script principal -->
    <script src="js/script.js" defer></script>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <h2>LOCKBYCES</h2>
            <ul class="nav-links">                
                <li><a href="#" class="nav-btn active" onclick="mostrarSeccion('inicio', this)">Inicio</a></li>
                
                <!-- OCULTO SI ES ADMINISTRADOR ($rolUsuario !== 'admin') -->
                <?php if ($rolUsuario !== 'admin'): ?>
                    <li><a href="#" class="nav-btn" onclick="mostrarSeccion('objetivos', this)">Objetivos</a></li>
                <?php endif; ?>

                <!-- SOLO VISIBLE SI ES ROL 'admin' -->
                <?php if ($rolUsuario === 'admin'): ?>
                    <li><a href="#" class="nav-btn" onclick="mostrarSeccion('admin-usuarios', this)">Administrar Usuarios</a></li>
                <?php endif; ?>
            </ul>
        </div>
        
        <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <div class="header">
            <h1>Bienvenido a la dashboard de Lockbyces, <span><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span></h1>
        </div>

        <!-- SECCIÓN 1: INICIO -->
        <div id="seccion-inicio" class="seccion-contenido">
            <div class="card">
                <h3>Estado del Sistema</h3>
                <p style="margin-top: 10px; color: #aaa;">Tu inicio de sesión se ha procesado de manera correcta y segura mediante la conexión de LOCKBYCES.</p>
            </div>
        </div>

        <!-- SECCIÓN 2: OBJETIVOS (Solo para no-admin) -->
        <?php if ($rolUsuario !== 'admin'): ?>
            <div id="seccion-objetivos" class="seccion-contenido" style="display: none;">
                <h2 style="margin-bottom: 20px; color: #f9b816;">Objetivos</h2>
                
                <div class="contenedor-objetivos">
                    <div class="card">
                        <span class="numero-objetivo">01</span>
                        <p>Analizar la fenomenología del hurto de bicicletas en Cali, evaluando variables estadísticas y encuestas de victimización para determinar patrones temporales y espaciales del delito.</p>
                    </div>

                    <div class="card">
                        <span class="numero-objetivo">02</span>
                        <p>Identificar y mapear zonas críticas de alta incidencia delictiva y las modalidades predominantes (como el "halado" y el uso de armas blancas) para optimizar la respuesta del sistema.</p>
                    </div>

                    <div class="card">
                        <span class="numero-objetivo">03</span>
                        <p>Diseñar la arquitectura de hardware del dispositivo, integrando un microcontrolador (ej. ESP32), sensores de movimiento/vibración (PIR) y módulos de comunicación inalámbrica para el procesamiento de alertas.</p>
                    </div>

                    <div class="card">
                        <span class="numero-objetivo">04</span>
                        <p>Desarrollar un protocolo de notificación en tiempo real que active una alarma sonora local y envíe alertas instantáneas a dispositivos móviles vinculados al detectar movimientos no autorizados.</p>
                    </div>

                    <div class="card">
                        <span class="numero-objetivo">05</span>
                        <p>Implementar un sistema de geolocalización activa mediante módulos GPS para el registro de rutas y la recuperación efectiva del vehículo en caso de siniestro.</p>
                    </div>

                    <div class="card">
                        <span class="numero-objetivo">06</span>
                        <p>Validar la eficiencia del prototipo mediante pruebas de campo rigurosas, comparando su capacidad de disuasión y precisión frente a sistemas de seguridad convencionales.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECCIÓN 3: ADMINISTRACIÓN DE USUARIOS -->
        <?php if ($rolUsuario === 'admin'): ?>
            <div id="seccion-admin-usuarios" class="seccion-contenido" style="display: none; width: 100%;">
                <h2 style="margin-bottom: 20px; color: #f9b816;">Administración de Usuarios</h2>
                
                <div class="card" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                    <div class="header-admin">
                        <h3>Panel de Usuarios Registrados</h3>
                        <button class="btn-crear" onclick="crearUsuarioAdmin()">
                            + Nuevo Usuario
                        </button>
                    </div>

                    <div style="width: 100%; overflow-x: auto;">
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
                                    <th style="text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-admin-usuarios">
                                <!-- Datos renderizados por script.js -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>