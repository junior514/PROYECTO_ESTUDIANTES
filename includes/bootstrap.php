<?php
/**
 * Bootstrap del Sistema de Estudiantes
 * Inicialización principal con Composer
 */

// Verificar versión de PHP
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    die('Este sistema requiere PHP 7.4 o superior. Versión actual: ' . PHP_VERSION);
}

// Definir constantes del sistema
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('LOGS_PATH', ROOT_PATH . '/logs');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// Autoloader de Composer
require_once ROOT_PATH . '/vendor/autoload.php';

// Cargar variables de entorno
if (file_exists(ROOT_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
    $dotenv->load();
}

// Configuración de errores según entorno
if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configuración de zona horaria
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'America/Lima');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Crear carpetas si no existen
$carpetas = [LOGS_PATH, UPLOADS_PATH, UPLOADS_PATH . '/estudiantes', UPLOADS_PATH . '/documentos'];
foreach ($carpetas as $carpeta) {
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0755, true);
    }
}

// Funciones auxiliares
require_once ROOT_PATH . '/includes/functions.php';

// Sistema cargado
define('SYSTEM_LOADED', true);