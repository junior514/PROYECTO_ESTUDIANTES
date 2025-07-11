<?php
/**
 * Controlador de Autenticación
 */

class AuthController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Mostrar formulario de login
     */
    public function mostrarLogin()
    {
        // Si ya está logueado, redirigir al dashboard
        if ($this->estaLogueado()) {
            redirect('index.php?accion=dashboard');
            return;
        }

        // Cargar vista de login sin layout
        include 'view/auth/login.php';
    }

    /**
     * Procesar login
     */
 public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?accion=mostrar_login');
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // DEBUG TEMPORAL - Eliminar después
        echo "<div style='background: yellow; padding: 10px; margin: 10px;'>";
        echo "<strong>DEBUG:</strong><br>";
        echo "Usuario ingresado: '{$username}'<br>";
        echo "Password ingresado: '{$password}'<br>";
        echo "</div>";

        // Validaciones básicas
        if (empty($username) || empty($password)) {
            setMensaje('Por favor completa todos los campos', 'error');
            redirect('index.php?accion=mostrar_login');
            return;
        }

        // Intentar login
        $usuario = $this->usuarioModel->login($username, $password);

        // DEBUG: Mostrar resultado
        echo "<div style='background: " . ($usuario ? 'green' : 'red') . "; color: white; padding: 10px; margin: 10px;'>";
        echo "Resultado login: " . ($usuario ? 'ÉXITO' : 'FALLÓ') . "<br>";
        if ($usuario) {
            echo "Usuario encontrado: " . $usuario['username'] . "<br>";
            echo "Rol: " . $usuario['rol'] . "<br>";
        }
        echo "</div>";
        echo "<a href='javascript:history.back()'>← Volver</a>";
        exit; // TEMPORAL - Para ver el debug

        if ($usuario) {
            // Determinar el nombre para mostrar
            $nombre_mostrar = '';
            if (!empty($usuario['nombre']) && !empty($usuario['apellido'])) {
                $nombre_mostrar = $usuario['nombre'] . ' ' . $usuario['apellido'];
            } else {
                $nombre_mostrar = $usuario['username'];
            }

            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_username'] = $usuario['username'];
            $_SESSION['usuario_nombre'] = $nombre_mostrar;
            $_SESSION['usuario_rol'] = $usuario['rol'];
            $_SESSION['usuario_logueado'] = true;
            $_SESSION['estudiante_id'] = $usuario['estudiante_id'];

            setMensaje('¡Bienvenido ' . $nombre_mostrar . '!', 'success');
            redirect('index.php?accion=dashboard');
        } else {
            setMensaje('Usuario o contraseña incorrectos', 'error');
            redirect('index.php?accion=mostrar_login');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Limpiar sesión
        $_SESSION = [];
        session_destroy();
        
        setMensaje('Has cerrado sesión correctamente', 'success');
        redirect('index.php?accion=mostrar_login');
    }

    /**
     * Mostrar dashboard después del login
     */
    public function dashboard()
    {
        if (!$this->estaLogueado()) {
            redirect('index.php?accion=mostrar_login');
            return;
        }

        // Cargar vista con layout
        include 'view/layouts/header.php';
        include 'view/reportes/dashboard.php';
        include 'view/layouts/footer.php';
    }

    /**
     * Verificar si el usuario está logueado
     */
    public function estaLogueado()
    {
        return isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado'] === true;
    }
}