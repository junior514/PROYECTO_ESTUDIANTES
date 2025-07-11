<?php
/**
 * Funciones auxiliares del sistema
 */

/**
 * Escapar HTML para prevenir XSS
 */
function escape($string) {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Redireccionar a una URL
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Obtener variable de entorno con valor por defecto
 */
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

/**
 * Formatear fecha para mostrar
 */
function formatearFecha($fecha, $formato = 'd/m/Y H:i') {
    if (empty($fecha) || $fecha === null) {
        return 'N/A';
    }
    return date($formato, strtotime($fecha));
}

/**
 * Calcular edad desde fecha de nacimiento
 */
function calcularEdad($fechaNacimiento) {
    $hoy = new DateTime();
    $nacimiento = new DateTime($fechaNacimiento);
    return $hoy->diff($nacimiento)->y;
}

/**
 * Validar email
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generar código único para estudiante
 */
function generarCodigoEstudiante() {
    $año = date('Y');
    $timestamp = time();
    return "EST-{$año}-" . substr($timestamp, -6);
}

/**
 * Mostrar mensaje flash
 */
function mostrarMensaje($tipo = 'info') {
    if (isset($_SESSION["mensaje_{$tipo}"])) {
        $mensaje = $_SESSION["mensaje_{$tipo}"];
        unset($_SESSION["mensaje_{$tipo}"]);
        return $mensaje;
    }
    return null;
}

/**
 * Establecer mensaje flash
 */
function setMensaje($mensaje, $tipo = 'info') {
    $_SESSION["mensaje_{$tipo}"] = $mensaje;
}

/**
 * Validar que el usuario esté logueado (para futuro)
 */
function verificarSesion() {
    // Por ahora retorna true, implementar cuando agregues autenticación
    return true;
}

/**
 * Formatear tamaño de archivo
 */
function formatearTamaño($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}

/**
 * Verificar si el usuario está logueado
 */
function estaLogueado() {
    return isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado'] === true;
}

/**
 * Verificar permisos por rol
 */
function verificarPermiso($rolRequerido) {
    if (!estaLogueado()) {
        return false;
    }

    $rolUsuario = $_SESSION['usuario_rol'];

    // Admin tiene acceso a todo
    if ($rolUsuario === 'admin') {
        return true;
    }

    // Verificar rol específico
    return $rolUsuario === $rolRequerido;
}

/**
 * Requiere login - redirige si no está logueado
 */
function requiereLogin() {
    if (!estaLogueado()) {
        redirect('index.php?accion=mostrar_login');
        exit;
    }
}

/**
 * Requiere rol específico
 */
function requiereRol($rolRequerido) {
    requiereLogin();
    
    if (!verificarPermiso($rolRequerido)) {
        setMensaje('No tienes permisos para acceder a esta sección', 'error');
        redirect('index.php?accion=dashboard');
        exit;
    }
}