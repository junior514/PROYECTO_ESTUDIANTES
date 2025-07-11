<?php
/**
 * Modelo Estudiante mejorado con PDO
 */

class Estudiante
{
    private $db;
    private $conexion;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conexion = $this->db->getConexion();
    }

    /**
     * Obtener todos los estudiantes con paginación
     */
    public function obtenerTodos($limite = null, $offset = 0)
    {
        $sql = "SELECT * FROM estudiantes ORDER BY id DESC";
        
        if ($limite !== null) {
            $sql .= " LIMIT :limite OFFSET :offset";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        } else {
            $stmt = $this->conexion->prepare($sql);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Agregar nuevo estudiante
     */
    public function agregar($datos)
    {
        $sql = "INSERT INTO estudiantes (nombre, apellido, email, edad) VALUES (:nombre, :apellido, :email, :edad)";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $resultado = $stmt->execute([
                ':nombre' => $datos['nombre'],
                ':apellido' => $datos['apellido'],
                ':email' => $datos['email'],
                ':edad' => $datos['edad']
            ]);
            
            return $resultado ? $this->conexion->lastInsertId() : false;
            
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Obtener estudiante por ID
     */
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM estudiantes WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Validar email único
     */
    public function emailExiste($email, $excluirId = null)
    {
        $sql = "SELECT id FROM estudiantes WHERE email = :email";
        
        if ($excluirId !== null) {
            $sql .= " AND id != :id";
        }
        
        $stmt = $this->conexion->prepare($sql);
        $params = [':email' => $email];
        
        if ($excluirId !== null) {
            $params[':id'] = $excluirId;
        }
        
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }

    /**
     * Contar total de estudiantes
     */
    public function contarTotal()
    {
        $sql = "SELECT COUNT(*) as total FROM estudiantes";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch();
        return $resultado['total'];
    }

    /**
     * Actualizar estudiante
     */
    public function actualizar($id, $datos)
    {
        $sql = "UPDATE estudiantes SET nombre = :nombre, apellido = :apellido, email = :email, edad = :edad WHERE id = :id";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':nombre' => $datos['nombre'],
                ':apellido' => $datos['apellido'],
                ':email' => $datos['email'],
                ':edad' => $datos['edad']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Eliminar estudiante
     */
    public function eliminar($id)
    {
        $sql = "DELETE FROM estudiantes WHERE id = :id";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Buscar estudiantes
     */
    public function buscar($termino)
    {
        $sql = "SELECT * FROM estudiantes 
                WHERE nombre LIKE :termino 
                OR apellido LIKE :termino 
                OR email LIKE :termino 
                ORDER BY id DESC";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':termino' => "%{$termino}%"]);
        return $stmt->fetchAll();
    }
}