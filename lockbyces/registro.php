<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro a Lockbyces</title>

    <link rel="stylesheet" href="./css/registro.css">
    <link rel="icon" type="image/png" href="img/favicon.jpeg" />

    <!-- Fuentes google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<body>
    
    <div class="contenedor">

        <h1>Lockbyces</h1>

        <p>Completa la información y presiona el botón para registrarte.</p>
      
        <form action="guardar.php" method="POST">
       
            <label>Escribe tu nombre</label>
            <input type="text" name="nombre" required placeholder="Ejemplo: Juan Rodríguez">

            <label>Escribe tu edad</label>
            <input type="number" name="edad" required>

            <label>Escribe tu correo electrónico</label>
            <input type="email" name="correo" required placeholder="Ejemplo: juanrodriguez@gmail.com">

            <label>Escribe tu contraseña (mínimo 8 caracteres)</label>
            <div class="campo-contrasena">
                <!-- Restricción de mínimo 8 caracteres -->
                <input type="password" name="contrasena" id="input-contrasena" required minlength="8" placeholder="Mínimo 8 caracteres">
                <button type="button" id="btn-mostrar-ocultar" class="ojo-icono" onclick="cambiarVista()" aria-label="Mostrar contraseña">👁️</button>
            </div>

            <label>Escribe tu número telefónico (10 dígitos)</label>
            <!-- Restricción de exactamente 10 dígitos numéricos -->
            <input 
                type="tel" 
                name="telefono" 
                required 
                minlength="10" 
                maxlength="10" 
                pattern="[0-9]{10}" 
                placeholder="Ejemplo: 3001234567"
                title="El número telefónico debe contener exactamente 10 dígitos."
            >

            <label>Elige tu rol</label>
            <select name="rol" required>
                <option value="">Selecciona una opción</option>
                <option value="cliente">cliente</option>
                <option value="empleado">empleado</option>
            </select>

            <label>género</label>
            <select name="genero" required>
                <option value="">Selecciona una opción</option>
                <option value="masculino">masculino</option>
                <option value="femenino">femenino</option>
                <option value="otro">otro</option>
            </select>

            <button type="submit">
                Registrarme
            </button>

        </form>
        
        <div id="resultado"></div>

    </div>

    <!-- Enlazamos tu script JavaScript -->
    <script src="js/script.js"></script>
</body>
</html>