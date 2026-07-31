<?php
// 1. Conexión a la base de datos para traer el total de usuarios en tiempo real
require './config/conexion.php';

$sql_total = "SELECT COUNT(*) AS total FROM usuarios";
$resultado = $conexion->query($sql_total);

$total_usuarios = 0;
if ($resultado) {
    $fila = $resultado->fetch_assoc();
    $total_usuarios = $fila['total'];
}

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> LOCKBYCES </title>

    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/propiedades_personalizadas.css">

    <script src="./js/script.js" defer></script>

    <!-- Iconos -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <link rel="icon" type="image/png" href="img/favicon.jpeg" />

    <!-- Fuentes google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <!-- Fuentes google fonts slogan-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Playpen+Sans+Deva:wght@100..800&display=swap" rel="stylesheet">

</head>
<body>
    <header>
        <img src="img/logo_lockbyces-transparente.png" alt="Logo" width="180" height="180">
        <h2>LOCKBYCES</h2>
    </header>

    <div class="menu">
        <input type="checkbox" id="menu-toggle">
        
        <label for="menu-toggle" class="hamburguesa">
            ☰
        </label>

        <nav>
            <a href="#sobre-nosotros">Sobre nosotros</a>
            <a href="#metas"> Metas</a>
            <a href="#porque-lockbyces">Por que elegir a LOCKBYCES</a>
            <a href="#registros">Usuarios registrados</a>
            <a href="#contactenos">Contáctenos</a>
            <a href="registro.php">Registrarme</a>
            <a href="login.php">Iniciar sesión</a>
        </nav>
    </div>    
    
    <main>
        <section class="quienes-somos">
            <h2>Hola, somos LockByces</h2>
            <p> 
                 Nuestro objetivo es implementar un sistema inteligente antirrobo para bicicletas, basado 
                 en tecnologías de Internet de las Cosas (IoT), orientado a la prevención del hurto, la notificación 
                 automatizada de sustracciones y la facilitación de la geolocalización en tiempo real en 
                 centros urbanos con altos índices de criminalidad, como Santiago de Cali.
            </p>
            <h2>Queremos para ti:</h2>
            <div class="contenedor">
                
                <div class="tarjeta">
                    <h3>Tranquilidad</h3>
                    <i class="fa-solid fa-face-smile icono"></i>                    
                </div>
                <div class="tarjeta">
                    <h3>Seguridad</h3>
                    <i class="fa-solid fa-lock icono"></i>                
                </div>
                <div class="tarjeta">                    
                    <h3>Satisfacción</h3>
                    <i class="fa-solid fa-handshake icono"></i>                    
                </div>
            </div>
            
            <div class="slogan">
                <h2>"Seguridad inteligente, para tu tranquilidad"</h2>
            </div>
        </section>  

        <aside class="integrantes">
            <h2> Datos Rápidos</h2>
            <p><strong>Grado: </strong>9° y 10°.</p>
            <p><strong>Tema: </strong>Sistema antirrobo.</p>
            <p><strong>Producto: </strong>Seguros tecnológicos antirrobos, con alerta en tiempo real.</p>
            
            <table class="tabla-integrantes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Integrantes</th>
                    </tr>
                </thead>
                <tbody id="cuerpo-tabla"></tbody>
            </table>
        </aside>
    </main>

    <div class="sobre-nosotros" id="sobre-nosotros">     
        <div class="descripcion-sobre-nosotros">
            <h2>Sobre Nosotros</h2>        
            <p>            
                En LOCKBYCES nos dedicamos a proteger tu bicicleta y devolverte la tranquilidad, 
                especialmente en entornos urbanos con alta incidencia de robos. Somos los creadores de un 
                sistema de seguridad inteligente equipado con sensor de movimiento, alarma sonora disuasoria
                y rastreador en tiempo real.

                Ante cualquier intento de forzamiento, nuestro dispositivo activa una sirena para ahuyentar al
                agresor y te envía una alerta instantánea a tu celular. Combinamos tecnología y prevención 
                para que te desplaces sin preocupaciones y mantengas tu vehículo siempre protegido.
            </p>                 
        </div>
        <div class="imagen-sobre-nosotros">
            <img src="img/dispositivo.png" alt="dispositivo">
        </div>           
    </div>

    <div class="nuestras-metas">
        <div class="imagen-metas">
            <img src="img/imagen_metas.jpg" alt="dispositivo" width= "606px" height= "400px">
        </div>
        <div class="metas" id="metas">
            <h2>Nuestra Meta</h2>
            <p>
                Nuestra meta principal es garantizar a cada ciclista la máxima satisfacción, seguridad y 
                absoluta tranquilidad en su vida cotidiana a través de un avanzado e integral sistema 
                antirrobo de vanguardia para sus bicicletas. Nos dedicamos a diseñar tecnología inteligente 
                capaz de monitorear y detectar con alta precisión cualquier intento de robo, manipulación no 
                autorizada o forcejeo, enviando notificaciones e incidencias de alerta al propietario en 
                tiempo real para brindarle una respuesta inmediata y proteger su patrimonio en todo momento.         
            </p>
        </div>
    </div>

    <div class="porque-lockbyces" id="porque-lockbyces">
        <div class="texto-porque-lockbyces">
                <h2>¿Por qué elegir a LOCKBYCES?</h2>
                <p>            
                    Nuestro sistema no solo cuida tu bicicleta sino que garantiza tu seguridad y 
                    tranquilidad, con nuestros sensores de movimiento y la alarma. Además esta pensado 
                    especialmente para ciudades donde el robo de bicicletas es frecuente por lo que busca 
                    brindar una solución practica para los ciclistas. LOCKBYCES ofrece una respuesta rápida 
                    ante un intento de robo y un sistema fácil de usar e instalar. por estas razones, 
                    LOCKBYCES no solo es un sistema de seguridad , sino una herramienta que brinda, confianza,
                    seguridad Y  tranquilidad a los que usan bicicletas como medio de trasporte.
                </p>
        </div>            
        <div class="tarjetas-porque-lockbyces">
            <div class="tarjeta-porque">
                <h3>Seguridad anti-robo</h3>
                <i class="fa-solid fa-mask icono-porque"></i>    
                <p>Detecta movimientos sospechosos y activa una alarma inmediatamente, ayudando a 
                    prevenir robos, proteger la bicicleta y brindar mayor seguridad y tranquilidad 
                    al usuario en cualquier momento.</p>                
            </div>
            <div class="tarjeta-porque">                    
                <h3>Alerta</h3>
                <i class="fa-solid fa-bullhorn icono-porque"></i> 
                <p>Al detectar movimiento, emite una alarma sonora de alta intensidad que alerta a las 
                    personas cercanas, disuade al ladrón y aumenta la protección de la bicicleta.</p>                 
            </div>
            <div class="tarjeta-porque">
                <h3>Ubicación</h3>
                <i class="fa-solid fa-map-location-dot icono-porque"></i>  
                <p>Permite visualizar la ubicación de la bicicleta en un mapa en tiempo real, facilitando 
                    su localización inmediata y aumentando las posibilidades de recuperarla ante un robo 
                    o extravío.</p>         
            </div>
        </div>
    </div>

    <div class="registros" id="registros">
            <h1>Número de usuarios registrados</h1>
            <span id="valor-contador"><?php echo $total_usuarios; ?></span>
    </div>

    <div class="contactenos" id="contactenos">
        <div class="contenedor-formulario">
            <form class="formulario"
                action="https://formsubmit.co/jjrocha1304@gmail.com" method="POST">

                <input type="hidden" name="_captcha" value="false">
                <input type="hidden" name="_next" value="http://localhost/lockbyces/index.php">

                <h2>Contactenos</h2>

                <div class="grupo">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required>
                </div>

                <div class="grupo">
                    <label>Correo electrónico</label>
                    <input type="email" name="correo" required>
                </div>

                <div class="grupo">
                    <label>Asunto</label>
                    <input type="text" name="asunto" required>
                </div>

                <div class="grupo">
                    <label>Mensaje</label>
                    <textarea name="mensaje" rows="6" required></textarea>
                </div>

                <button type="submit">Enviar mensaje</button>
            </form>
        </div>
    </div>
  
    <footer class="footer">
        <p>
            Comfandi El Prado Bachiller
        </p>
         <p>
            LockByces - Derechos Reservados 2026
        </p>
        <p>
            Cali - Colombia
        </p>
    </footer>    
</body>
</html>