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
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="css/dashboard.css?v=2.2">
    <link rel="icon" type="image/png" href="img/favicon.jpeg" />

    <!-- Fuentes Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <!-- Leaflet JS (Mapa interactivo de Cali) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Librería Chart.js para las gráficas estadísticas -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script principal -->
    <script src="js/script.js?v=2.2" defer></script>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <h2>LOCKBYCES</h2>
            <p id="mensaje-infinito" class="carrusel-texto"></p>
            <ul class="nav-links">                
                <li><a href="#" class="nav-btn active" onclick="mostrarSeccion('inicio', this)">Inicio</a></li>
                
                <!-- SECCIONES VISIBLES SOLO PARA CLIENTES / NO ADMIN -->
                <?php if ($rolUsuario !== 'admin'): ?>
                    <li><a href="#" class="nav-btn" onclick="mostrarSeccion('objetivos', this)">Objetivos</a></li>
                    <li><a href="#" class="nav-btn" onclick="mostrarSeccion('catalogo', this)">Comprar / Servicios</a></li>
                    <li><a href="#" class="nav-btn" onclick="mostrarSeccion('monitoreo', this)">Rastreo & IoT</a></li>
                    <li><a href="#" class="nav-btn" onclick="mostrarSeccion('estadisticas', this)">Estadísticas</a></li>
                <?php endif; ?>

                <!-- SECCIÓN VISIBLE SOLO PARA ADMINISTRADORES -->
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

        <!-- SECCIÓN 1: INICIO (Todos) -->
        <div id="seccion-inicio" class="seccion-contenido">
            <div class="card">
                <h3>Estado del Sistema</h3>
                <p style="margin-top: 10px; color: #aaa;">Tu inicio de sesión se ha procesado de manera correcta y segura mediante la conexión de LOCKBYCES.</p>
            </div>

            <!-- Tabla de Integrantes del Equipo -->
            <div class="card" style="margin-top: 20px;">
                <h3>Equipo de Desarrollo LockByces</h3>
                <table class="tabla-lockbyces" style="margin-top: 15px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre Integrante</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-tabla">
                        <!-- Cargado dinámicamente desde script.js -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIONES PARA CLIENTES / NO ADMIN -->
        <?php if ($rolUsuario !== 'admin'): ?>
            
            <!-- SECCIÓN: OBJETIVOS -->
            <div id="seccion-objetivos" class="seccion-contenido" style="display: none;">
                <h2 style="margin-bottom: 20px; color: #f9b816;">Objetivos del Proyecto</h2>
                
                <div class="contenedor-objetivos">
                    <div class="card">
                        <span class="numero-objetivo">01</span>
                        <p>Analizar la fenomenología del hurto de bicicletas en Cali, evaluando variables estadísticas y encuestas de victimización para determinar patrones temporales y espaciales del delito.</p>
                    </div>

                    <div class="card">
                        <span class="numero-objetivo">02</span>
                        <p>Identificar y mapear zonas críticas de alta incidencia delictiva y las modalidades predominantes para optimizar la respuesta del sistema.</p>
                    </div>

                    <div class="card">
                        <span class="numero-objetivo">03</span>
                        <p>Diseñar la arquitectura de hardware del dispositivo, integrando un microcontrolador (ESP32), sensores de movimiento/vibración y módulos de comunicación inalámbrica.</p>
                    </div>

                    <div class="card">
                        <span class="numero-objetivo">04</span>
                        <p>Desarrollar un protocolo de notificación en tiempo real que active una alarma sonora local y envíe alertas instantáneas a dispositivos móviles.</p>
                    </div>

                    <div class="card">
                        <span class="numero-objetivo">05</span>
                        <p>Implementar un sistema de geolocalización activa mediante módulos GPS para el registro de rutas y la recuperación efectiva del vehículo.</p>
                    </div>

                    <div class="card">
                        <span class="numero-objetivo">06</span>
                        <p>Validar la eficiencia del prototipo mediante pruebas de campo rigurosas, comparando su capacidad de disuasión y precisión.</p>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: CATÁLOGO Y PROCESO DE COMPRA -->
            <div id="seccion-catalogo" class="seccion-contenido" style="display: none; width: 100%;">
                <h2 style="margin-bottom: 10px; color: #f9b816;">Información de Compra & Servicios LockByces</h2>
                <p style="color: #ccc; margin-bottom: 30px; line-height: 1.6;">
                    Conoce las especificaciones de nuestro dispositivo antirrobo, los métodos de adquisición y las garantías que ofrecemos para proteger tu vehículo.
                </p>

                <div style="display: flex; flex-direction: column; gap: 25px; border-left: 2px solid #f9b816; padding-left: 20px;">
                    <div>
                        <h3 style="color: #f9b816; margin-bottom: 5px;">01. Dispositivo Inteligente LockByces</h3>
                        <p style="color: #aaa; margin: 0; line-height: 1.5;">Protege tu bicicleta con nuestro módulo antirrobo con sensor de sonido/vibración y GPS activo.</p>
                    </div>

                    <div>
                        <h3 style="color: #f9b816; margin-bottom: 5px;">02. Alertas Inmediatas</h3>
                        <p style="color: #aaa; margin: 0; line-height: 1.5;">Notificaciones instantáneas en tu móvil ante cualquier movimiento no autorizado.</p>
                    </div>

                    <div>
                        <h3 style="color: #f9b816; margin-bottom: 5px;">03. Pasos para Adquirirlo</h3>
                        <p style="color: #aaa; margin: 0; line-height: 1.5;">
                            1. Selecciona tu plan.<br>
                            2. Coordina el punto de entrega.<br>
                            3. Vincula el dispositivo en esta plataforma.
                        </p>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: RASTREO Y MONITOREO IOT -->
            <div id="seccion-monitoreo" class="seccion-contenido" style="display: none;">
                <h2 style="margin-bottom: 20px; color: #f9b816;">Centro de Monitoreo & Rastreo IoT</h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <!-- Simulador de Sensor de Alarma -->
                    <div class="card">
                        <h3>Simulador de Sensor de Sonido / Alarma</h3>
                        <p style="color: #aaa; font-size: 14px; margin-top: 5px;">Alertas activadas: <b id="alarm-count" style="color:#f9b816;">0</b></p>
                        
                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <button class="btn-crear" onclick="simulateSoundTrigger()">Probar Alarma</button>
                            <button class="btn-cancelar" onclick="resetAlarmCount()">Reiniciar</button>
                        </div>

                        <h4 style="margin-top: 20px; color: #fff;">Historial de Eventos:</h4>
                        <ul id="alarm-log" class="log-lista">
                            <li class="empty-log">Sin registros de activación.</li>
                        </ul>
                    </div>

                    <!-- Ficha Técnica de la Bicicleta -->
                    <div class="card">
                        <h3>Ficha Técnica del Vehículo</h3>
                        <form onsubmit="saveBikeInfo(event)" style="margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                            <input type="text" id="bike-brand" class="crud-input" placeholder="Marca (Ej: Gw, Specialized)" value="GW">
                            <input type="text" id="bike-model" class="crud-input" placeholder="Modelo / Año" value="Scorpion 2024">
                            <input type="text" id="bike-serial" class="crud-input" placeholder="Número de Serie del Marco" value="GW9920182X">
                            
                            <div style="display: flex; gap: 10px; margin-top: 10px;">
                                <button type="submit" class="btn-guardar">Guardar Ficha</button>
                                <button type="button" class="btn-eliminar" onclick="reportStolen()">🚨 Reportar Robo</button>
                            </div>
                        </form>
                    </div>

                    <!-- MAPA INTERACTIVO REAL DE CALI, COLOMBIA -->
                    <div class="card" style="grid-column: 1 / -1;">
                        <h3>Geolocalización GPS Activa (Cali, Colombia)</h3>
                        <p style="color: #aaa; font-size: 14px;">Coordenadas actualizadas: <span id="coords-display" style="color:#f9b816;">03.4516° N, -76.5320° W</span> | Última señal: <span id="time-display" style="color:#fff;">Justo ahora</span></p>
                        
                        <!-- Contenedor del Mapa Leaflet -->
                        <div id="mapa-cali" class="mapa-simulado"></div>

                        <button class="btn-crear" onclick="updateLocation()" style="margin-top: 15px;">Simular Movimiento GPS en Cali</button>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: ESTADÍSTICAS DEL HURTO -->
            <div id="seccion-estadisticas" class="seccion-contenido" style="display: none; width: 100%;">
                <h2 style="margin-bottom: 10px; color: #f9b816;">Estadísticas de Hurto de Bicicletas</h2>
                <p style="color: #ccc; margin-bottom: 25px; line-height: 1.6;">Análisis consolidado basado en reportes de seguridad ciudadana.</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; align-items: center;">
                    <div style="background: rgba(255, 255, 255, 0.03); padding: 20px; border-radius: 8px;">
                        <h4 style="color: #f9b816; text-align: center; margin-bottom: 15px;">Modalidades de Hurto</h4>
                        <div style="position: relative; height: 260px;">
                            <canvas id="chartModalidades"></canvas>
                        </div>
                    </div>

                    <div style="background: rgba(255, 255, 255, 0.03); padding: 20px; border-radius: 8px;">
                        <h4 style="color: #f9b816; text-align: center; margin-bottom: 15px;">Ciudades con Mayor Incidencia (%)</h4>
                        <div style="position: relative; height: 260px;">
                            <canvas id="chartCiudades"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECCIÓN: ADMINISTRACIÓN DE USUARIOS (Solo Admin) -->
        <?php if ($rolUsuario === 'admin'): ?>
            <div id="seccion-admin-usuarios" class="seccion-contenido" style="display: none; width: 100%;">
                <h2 style="margin-bottom: 20px; color: #f9b816;">Administración de Usuarios</h2>
                
                <div class="card" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                    <div class="header-admin">
                        <h3>Panel de Usuarios Registrados</h3>
                        <button class="btn-crear" onclick="crearUsuarioAdmin()">+ Nuevo Usuario</button>
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