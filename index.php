<?php
/**
 * Sistema de Estudiantes - Punto de entrada principal
 * Actualizado con Composer y arquitectura mejorada
 */

// Cargar bootstrap del sistema
require_once 'includes/bootstrap.php';

// Cargar clases
require_once 'model/Database.php';
require_once 'model/Estudiante.php';
require_once 'model/Usuario.php';
require_once 'controller/EstudianteController.php';
require_once 'controller/AuthController.php';

try {
    // Obtener la acción solicitada
    $accion = $_GET['accion'] ?? 'mostrar_login';
    
    // Acciones de autenticación (no requieren login)
    $acciones_auth = ['mostrar_login', 'login', 'logout'];
    
    // Verificar si necesita autenticación
    if (!in_array($accion, $acciones_auth)) {
        // Verificar si está logueado
        if (!isset($_SESSION['usuario_logueado']) || $_SESSION['usuario_logueado'] !== true) {
            redirect('index.php?accion=mostrar_login');
            return;
        }
    }
    
    // Validar acción
    $acciones_permitidas = [
        'mostrar_login', 'login', 'logout', 'dashboard',
        'listar', 'mostrar_formulario', 'agregar', 'editar', 'eliminar', 'buscar'
    ];
    
    if (!in_array($accion, $acciones_permitidas)) {
        $accion = 'mostrar_login';
    }
    
    // Crear instancia del controlador apropiado
    if (in_array($accion, $acciones_auth) || $accion === 'dashboard') {
        $controller = new AuthController();
    } else {
        $controller = new EstudianteController();
    }
    
    // Ejecutar la acción correspondiente
    switch ($accion) {
        // Acciones de autenticación
        case 'mostrar_login':
            $controller->mostrarLogin();
            break;
            
        case 'login':
            $controller->login();
            break;
            
        case 'logout':
            $controller->logout();
            break;
            
        case 'dashboard':
            $controller->dashboard();
            break;
            
        // Acciones de estudiantes
        case 'listar':
            $controller->listar();
            break;
            
        case 'mostrar_formulario':
            $controller->mostrarFormulario();
            break;
            
        case 'agregar':
            $controller->agregar();
            break;
            
        case 'editar':
            $id = $_GET['id'] ?? null;
            $controller->editar($id);
            break;
            
        case 'eliminar':
            $id = $_GET['id'] ?? null;
            $controller->eliminar($id);
            break;
            
        case 'buscar':
            $controller->buscar();
            break;
            
        default:
            redirect('index.php?accion=mostrar_login');
            break;
    }
    
} catch (Exception $e) {
    // Mostrar error amigable
    include 'view/header.php';
    ?>
    <div class="container">
        <div class="card">
            <h2>❌ Error en el Sistema</h2>
            <?php if (env('APP_DEBUG', false)): ?>
                <div class="error">
                    <strong>Error:</strong> <?php echo escape($e->getMessage()); ?>
                </div>
            <?php else: ?>
                <div class="error">
                    Ha ocurrido un error inesperado. Por favor, intenta nuevamente.
                </div>
            <?php endif; ?>
            <a href="index.php" class="nav">🏠 Volver al Inicio</a>
        </div>
    </div>
    <?php
    include 'view/footer.php';
}
?>