<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtenemos el rol y datos del usuario desde la sesión
$rolUsuario      = $_SESSION['usuario_rol']      ?? '';
$nombreUsuario   = $_SESSION['usuario_nombre']   ?? '';
$telefonoUsuario = $_SESSION['usuario_telefono'] ?? 'Sin teléfono registrado';
$correoUsuario   = $_SESSION['usuario_correo']   ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LOCKBYCES</title>
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="css/dashboard.css?v=4.0">
    <link rel="stylesheet" href="css/propiedades_personalizadas.css">
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
    <script src="js/script.js?v=4.0" defer></script>
</head>
<body>

    <!-- BARRA SUPERIOR PARA MÓVILES -->
    <div class="mobile-header">
        <div class="mobile-logo">LOCKBYCES</div>
        <button class="btn-menu-mobile" id="btn-menu-toggle" aria-label="Abrir Menú">☰</button>
    </div>

    <!-- OVERLAY OSCURO PARA CERRAR EL MENÚ MÓVIL AL HACER CLIC FUERA -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="sidebar" id="sidebar">
        <div class="logo-section">
            <h2>LOCKBYCES</h2>
            <p id="mensaje-infinito" class="carrusel-texto"></p>
            <ul class="nav-links">                
                <li><a href="#" class="nav-btn active" onclick="seleccionarNavegacion('inicio', this)">Inicio</a></li>
                
                <!-- SECCIONES VISIBLES SOLO PARA CLIENTES / NO ADMIN -->
                <?php if ($rolUsuario !== 'admin'): ?>
                    <li><a href="#" class="nav-btn" onclick="seleccionarNavegacion('objetivos', this)">Objetivos</a></li>
                    <li><a href="#" class="nav-btn" onclick="seleccionarNavegacion('monitoreo', this)">Rastreo & IoT</a></li>
                    <li><a href="#" class="nav-btn" onclick="seleccionarNavegacion('estadisticas', this)">Estadísticas</a></li>
                    <li><a href="#" class="nav-btn" onclick="seleccionarNavegacion('contactar', this)">Soporte</a></li>
                <?php endif; ?>

                <!-- SECCIÓN VISIBLE SOLO PARA ADMINISTRADORES -->
                <?php if ($rolUsuario === 'admin'): ?>
                    <li><a href="#" class="nav-btn" onclick="seleccionarNavegacion('admin-usuarios', this)">Administrar Usuarios</a></li>
                <?php endif; ?>
            </ul>
        </div>
        
        <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <div class="header">
            <h1>Bienvenido a la dashboard de Lockbyces, <span><?php echo htmlspecialchars($nombreUsuario); ?></span></h1>
        </div>

        <!-- SECCIÓN 1: INICIO (Todos) -->
        <div id="seccion-inicio" class="seccion-contenido">
            <div class="card">
                <h3>Estado del Sistema</h3>
                <p class="texto-secundario mt-10">Tu inicio de sesión se ha procesado de manera correcta y segura mediante la conexión de LOCKBYCES.</p>
            </div>

            <!-- Tabla de Integrantes del Equipo -->
            <div class="card mt-20">
                <h3>Equipo de Desarrollo LockByces</h3>
                <div class="tabla-contenedor-scroll">
                    <table class="tabla-lockbyces mt-15">
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
        </div>

        <!-- SECCIONES PARA CLIENTES / NO ADMIN -->
        <?php if ($rolUsuario !== 'admin'): ?>
            
            <!-- SECCIÓN: OBJETIVOS -->
            <div id="seccion-objetivos" class="seccion-contenido seccion-oculta">
                <h2 class="titulo-seccion mb-20">Objetivos del Proyecto</h2>
                
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

            <!-- SECCIÓN: RASTREO Y MONITOREO IOT -->
            <div id="seccion-monitoreo" class="seccion-contenido seccion-oculta">
                <h2 class="titulo-seccion mb-20">Centro de Monitoreo & Rastreo IoT</h2>

                <div class="grid-layout">
                    <!-- Simulador de Sensor de Alarma -->
                    <div class="card">
                        <h3>Simulador de Sensor de Sonido / Alarma</h3>
                        <p class="texto-secundario texto-sm mt-5">Alertas activadas: <b id="alarm-count" class="texto-resaltado">0</b></p>
                        
                        <div class="grupo-botones mt-15">
                            <button class="btn-crear" onclick="simulateSoundTrigger()">Probar Alarma</button>
                            <button class="btn-cancelar" onclick="resetAlarmCount()">Reiniciar</button>
                        </div>

                        <h4 class="subtitulo-blanco mt-20">Historial de Eventos:</h4>
                        <ul id="alarm-log" class="log-lista">
                            <li class="empty-log">Sin registros de activación.</li>
                        </ul>
                    </div>

                    <!-- Ficha Técnica de la Bicicleta -->
                    <div class="card">
                        <h3>Ficha Técnica del Vehículo</h3>
                        <form onsubmit="saveBikeInfo(event)" class="form-ficha-tecnica mt-15">
                            <input type="text" id="bike-brand" class="crud-input" placeholder="Marca (Ej: Gw, Specialized)" value="GW">
                            <input type="text" id="bike-model" class="crud-input" placeholder="Modelo / Año" value="Scorpion 2024">
                            <input type="text" id="bike-serial" class="crud-input" placeholder="Número de Serie del Marco" value="GW9920182X">
                            
                            <div class="grupo-botones mt-10">
                                <button type="submit" class="btn-guardar">Guardar Ficha</button>
                                <button type="button" class="btn-eliminar" onclick="reportStolen()">🚨 Reportar Robo</button>
                            </div>
                        </form>
                    </div>

                    <!-- MAPA INTERACTIVO REAL DE CALI, COLOMBIA -->
                    <div class="card grid-ancho-total">
                        <h3>Geolocalización GPS Activa (Cali, Colombia)</h3>
                        <p class="texto-secundario texto-sm">Coordenadas actualizadas: <span id="coords-display" class="texto-resaltado">03.4516° N, -76.5320° W</span> | Última señal: <span id="time-display" class="texto-blanco">Justo ahora</span></p>
                        
                        <!-- Contenedor del Mapa Leaflet -->
                        <div id="mapa-cali" class="mapa-simulado"></div>

                        <button class="btn-crear mt-15 btn-full-mobile" onclick="updateLocation()">Simular Movimiento GPS en Cali</button>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: ESTADÍSTICAS DEL HURTO -->
            <div id="seccion-estadisticas" class="seccion-contenido seccion-oculta ancho-completo">
                <h2 class="titulo-seccion mb-10">Estadísticas de Hurto de Bicicletas</h2>
                <p class="descripcion-seccion mb-25">Análisis consolidado basado en reportes de seguridad ciudadana.</p>

                <div class="grid-graficas">
                    <div class="contenedor-grafica">
                        <h4 class="titulo-grafica mb-15">Modalidades de Hurto</h4>
                        <div class="caja-canvas">
                            <canvas id="chartModalidades"></canvas>
                        </div>
                    </div>

                    <div class="contenedor-grafica">
                        <h4 class="titulo-grafica mb-15">Ciudades con Mayor Incidencia (%)</h4>
                        <div class="caja-canvas">
                            <canvas id="chartCiudades"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: CONTACTO Y MENSAJERÍA -->
            <div id="seccion-contactar" class="seccion-contenido seccion-oculta">

                <h2 class="titulo-seccion mb-10">Contáctanos</h2>
                <p class="descripcion-seccion mb-20">¿Tienes preguntas, inconvenientes con tu dispositivo o deseas soporte técnico? Envíanos un mensaje.</p>

                <div class="card card-contacto">
                    <form class="form-contacto" action="https://formsubmit.co/jjrocha1304@gmail.com" method="POST">

                        <input type="hidden" name="_captcha" value="false">
                        <input type="hidden" name="_next" value="http://localhost/lockbyces/dashboard.php">

                        <div class="campo-grupo">
                            <label class="crud-label label-contacto mb-5">Asunto</label>
                            <input  name="asunto" type="text" id="contacto-asunto" class="crud-input" placeholder="Ej: Soporte de GPS, Pregunta General" required>
                        </div>

                        <div class="campo-grupo">
                            <label class="crud-label label-contacto mb-5">Mensaje</label>
                            <textarea name="mensaje" id="contacto-mensaje" class="crud-input textarea-contacto" rows="5" placeholder="Escribe aquí los detalles de tu consulta..." required></textarea>
                        </div>
                        
                        <div class="flex-end">
                            <button type="submit" class="btn-guardar btn-enviar-contacto">📩 Enviar Mensaje</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECCIÓN: ADMINISTRACIÓN DE USUARIOS (Solo Admin) -->
        <?php if ($rolUsuario === 'admin'): ?>
            <div id="seccion-admin-usuarios" class="seccion-contenido seccion-oculta ancho-completo">
                <h2 class="titulo-seccion mb-20">Administración de Usuarios</h2>
                
                <div class="card ancho-completo box-border">
                    <div class="header-admin">
                        <h3>Panel de Usuarios Registrados</h3>
                        <button class="btn-crear" onclick="crearUsuarioAdmin()">+ Nuevo Usuario</button>
                    </div>

                    <div class="tabla-contenedor-scroll">
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
                                    <th class="text-center">Acciones</th>
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