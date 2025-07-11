<?php
/**
 * Modelo Usuario para autenticación
 */

class Usuario
{
    private $db;
    private $conexion;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conexion = $this->db->getConexion();
    }

    /**
     * Verificar credenciales de login
     */
    /**
     * Verificar credenciales de login
     */
    /**
     * Verificar credenciales de login (CON DEBUG DETALLADO)
     */
    public function login($username, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE (username = :username OR email = :username) AND estado = 'activo'";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([':username' => $username]);
            $usuario = $stmt->fetch();
            
            // DEBUG DETALLADO
            echo "<div style='background: lightblue; padding: 10px; margin: 10px; border: 2px solid blue;'>";
            echo "<strong>🔍 DEBUG en model/Usuario.php:</strong><br>";
            echo "SQL ejecutado: " . htmlspecialchars($sql) . "<br>";
            echo "Buscando usuario: '{$username}'<br>";
            echo "Usuario encontrado en BD: " . ($usuario ? 'SÍ' : 'NO') . "<br>";
            
            if ($usuario) {
                echo "Username en BD: '{$usuario['username']}'<br>";
                echo "Password en BD: '{$usuario['password_hash']}'<br>";
                echo "Estado en BD: '{$usuario['estado']}'<br>";
                echo "Rol en BD: '{$usuario['rol']}'<br>";
                echo "Password ingresado: '{$password}'<br>";
                echo "¿Passwords coinciden?: " . ($password === $usuario['password_hash'] ? 'SÍ' : 'NO') . "<br>";
            } else {
                echo "<strong style='color: red;'>❌ Usuario NO encontrado</strong><br>";
                
                // Verificar si existe el usuario SIN filtro de estado
                $sql_check = "SELECT username, estado FROM usuarios WHERE username = :username";
                $stmt_check = $this->conexion->prepare($sql_check);
                $stmt_check->execute([':username' => $username]);
                $usuario_check = $stmt_check->fetch();
                
                if ($usuario_check) {
                    echo "Usuario existe pero estado es: '{$usuario_check['estado']}'<br>";
                } else {
                    echo "Usuario '{$username}' NO existe en la tabla<br>";
                }
            }
            echo "</div>";
            
            // COMPARACIÓN SIMPLE
            if ($usuario && $password === $usuario['password_hash']) {
                // Actualizar último acceso
                $this->actualizarUltimoAcceso($usuario['id']);
                
                // Si es estudiante, obtener datos adicionales
                if ($usuario['rol'] === 'estudiante' && !empty($usuario['estudiante_id'])) {
                    $sql_estudiante = "SELECT nombre, apellido FROM estudiantes WHERE id = :estudiante_id";
                    $stmt_est = $this->conexion->prepare($sql_estudiante);
                    $stmt_est->execute([':estudiante_id' => $usuario['estudiante_id']]);
                    $datos_estudiante = $stmt_est->fetch();
                    
                    if ($datos_estudiante) {
                        $usuario['nombre'] = $datos_estudiante['nombre'];
                        $usuario['apellido'] = $datos_estudiante['apellido'];
                    }
                }
                
                // Remover password del resultado
                unset($usuario['password_hash']);
                return $usuario;
            }
            
            return false;
            
        } catch (PDOException $e) {
            echo "<div style='background: red; color: white; padding: 10px;'>";
            echo "❌ ERROR DE BD: " . htmlspecialchars($e->getMessage());
            echo "</div>";
            return false;
        }
    }
    
    /**
     * Obtener usuario por ID con datos del estudiante si aplica
     */
   public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([':id' => $id]);
            $usuario = $stmt->fetch();
            
            // Si es estudiante, obtener datos adicionales
            if ($usuario && $usuario['rol'] === 'estudiante' && !empty($usuario['estudiante_id'])) {
                $sql_estudiante = "SELECT nombre, apellido FROM estudiantes WHERE id = :estudiante_id";
                $stmt_est = $this->conexion->prepare($sql_estudiante);
                $stmt_est->execute([':estudiante_id' => $usuario['estudiante_id']]);
                $datos_estudiante = $stmt_est->fetch();
                
                if ($datos_estudiante) {
                    $usuario['nombre'] = $datos_estudiante['nombre'];
                    $usuario['apellido'] = $datos_estudiante['apellido'];
                }
            }
            
            return $usuario;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Actualizar último acceso
     */
    private function actualizarUltimoAcceso($id)
    {
        $sql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            // Log error si es necesario
        }
    }

    /**
     * Obtener todos los usuarios (para administración)
     */
    public function obtenerTodos()
    {
        $sql = "SELECT id, username, email, rol, estado, ultimo_acceso, fecha_creacion 
                FROM usuarios ORDER BY fecha_creacion DESC";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
}