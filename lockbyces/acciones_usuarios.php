<?php
// Limpiar cualquier búfer de salida previo para asegurar JSON limpio
ob_start();
session_start();

// Ocultar avisos/errores en pantalla para no dañar el formato JSON devuelto
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

// Cargar la conexión a la base de datos
if (file_exists(__DIR__ . '/config/conexion.php')) {
    require_once __DIR__ . '/config/conexion.php';
} elseif (file_exists(__DIR__ . '/conexion.php')) {
    require_once __DIR__ . '/conexion.php';
} else {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'No se encontró el archivo de conexión (conexion.php)']);
    exit;
}

// Verificar que la conexión sea válida
if (!isset($conexion) || $conexion->connect_error) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la BD: ' . ($conexion->connect_error ?? 'desconocido')]);
    exit;
}

// Validar que el usuario en sesión sea Administrador
$rolActual = $_SESSION['usuario_rol'] ?? '';
if (!isset($_SESSION['usuario_id']) || $rolActual !== 'admin') {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Acceso denegado. Se requieren permisos de administrador.']);
    exit;
}

$accion = $_POST['accion'] ?? '';

// ==========================================
// 1. LISTAR USUARIOS
// ==========================================
if ($accion === 'listar') {
    $sql = "SELECT ID as id, nombre, genero, edad, rol, telefono, correo FROM usuarios ORDER BY ID ASC";
    $resultado = $conexion->query($sql);
    $usuarios = [];

    if ($resultado) {
        while ($row = $resultado->fetch_assoc()) {
            $usuarios[] = $row;
        }
        ob_end_clean();
        echo json_encode(['success' => true, 'data' => $usuarios]);
    } else {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $conexion->error]);
    }
    exit;
}

// ==========================================
// 2. CREAR USUARIO (CON TODOS LOS CAMPOS)
// ==========================================
if ($accion === 'crear') {
    $nombre     = trim($_POST['nombre'] ?? '');
    $genero     = trim($_POST['genero'] ?? '');
    $edad       = intval($_POST['edad'] ?? 0);
    $rol        = trim($_POST['rol'] ?? 'cliente');
    $telefono   = trim($_POST['telefono'] ?? '');
    $correo     = trim($_POST['correo'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Por favor completa los campos obligatorios (Nombre, Correo y Contraseña).']);
        exit;
    }

    // Hash seguro para la contraseña
    $passHash = password_hash($contrasena, PASSWORD_BCRYPT);

    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, genero, edad, rol, telefono, correo, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissss", $nombre, $genero, $edad, $rol, $telefono, $correo, $passHash);

    if ($stmt->execute()) {
        ob_end_clean();
        echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente.']);
    } else {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Error al crear usuario: ' . $stmt->error]);
    }
    exit;
}

// ==========================================
// 3. EDITAR USUARIO
// ==========================================
if ($accion === 'editar') {
    $id         = intval($_POST['id'] ?? 0);
    $nombre     = trim($_POST['nombre'] ?? '');
    $genero     = trim($_POST['genero'] ?? '');
    $edad       = intval($_POST['edad'] ?? 0);
    $rol        = trim($_POST['rol'] ?? 'cliente');
    $telefono   = trim($_POST['telefono'] ?? '');
    $correo     = trim($_POST['correo'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if ($id <= 0 || empty($nombre) || empty($correo)) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Datos insuficientes para actualizar.']);
        exit;
    }

    // Si se escribió una nueva contraseña, la actualizamos
    if (!empty($contrasena)) {
        $passHash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, genero = ?, edad = ?, rol = ?, telefono = ?, correo = ?, contrasena = ? WHERE ID = ?");
        $stmt->bind_param("ssissssi", $nombre, $genero, $edad, $rol, $telefono, $correo, $passHash, $id);
    } else {
        // Si la contraseña se dejó en blanco, mantenemos la existente
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, genero = ?, edad = ?, rol = ?, telefono = ?, correo = ? WHERE ID = ?");
        $stmt->bind_param("ssisssi", $nombre, $genero, $edad, $rol, $telefono, $correo, $id);
    }

    if ($stmt->execute()) {
        ob_end_clean();
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
    } else {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $stmt->error]);
    }
    exit;
}

// ==========================================
// 4. ELIMINAR USUARIO
// ==========================================
if ($accion === 'eliminar') {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'ID de usuario no válido.']);
        exit;
    }

    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        ob_end_clean();
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado de la base de datos.']);
    } else {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Error al eliminar usuario: ' . $stmt->error]);
    }
    exit;
}

// Acción por defecto si no coincide
ob_end_clean();
echo json_encode(['success' => false, 'message' => 'Acción solicitada no válida.']);
?>