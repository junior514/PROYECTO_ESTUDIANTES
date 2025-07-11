<?php
/**
 * Clase Database mejorada con configuración desde .env
 */

class Database
{
    private static $instance = null;
    private $conexion;
    private $config;

    private function __construct()
    {
        $this->config = require CONFIG_PATH . '/database.php';
        $this->conectar();
    }

    /**
     * Singleton pattern para una sola instancia de conexión
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Conectar a la base de datos con PDO
     */
    private function conectar()
    {
        try {
            $dsn = "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']};charset={$this->config['charset']}";
            
            $this->conexion = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $this->config['options']
            );

            // Log de conexión exitosa
            if (class_exists('Monolog\Logger')) {
                $logger = new Monolog\Logger('database');
                $logger->pushHandler(new Monolog\Handler\StreamHandler(LOGS_PATH . '/database.log'));
                $logger->info('Conexión a base de datos establecida');
            }

        } catch (PDOException $e) {
            // Log del error
            if (class_exists('Monolog\Logger')) {
                $logger = new Monolog\Logger('database');
                $logger->pushHandler(new Monolog\Handler\StreamHandler(LOGS_PATH . '/database.log'));
                $logger->error('Error de conexión a BD: ' . $e->getMessage());
            }
            
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Obtener la conexión PDO
     */
    public function getConexion()
    {
        return $this->conexion;
    }

    /**
     * Ejecutar una consulta preparada
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            if (class_exists('Monolog\Logger')) {
                $logger = new Monolog\Logger('database');
                $logger->pushHandler(new Monolog\Handler\StreamHandler(LOGS_PATH . '/database.log'));
                $logger->error('Error en consulta: ' . $e->getMessage(), ['sql' => $sql, 'params' => $params]);
            }
            throw $e;
        }
    }

    /**
     * Obtener el último ID insertado
     */
    public function lastInsertId()
    {
        return $this->conexion->lastInsertId();
    }

    /**
     * Iniciar transacción
     */
    public function beginTransaction()
    {
        return $this->conexion->beginTransaction();
    }

    /**
     * Confirmar transacción
     */
    public function commit()
    {
        return $this->conexion->commit();
    }

    /**
     * Deshacer transacción
     */
    public function rollback()
    {
        return $this->conexion->rollback();
    }

    /**
     * Cerrar conexión
     */
    public function cerrarConexion()
    {
        $this->conexion = null;
    }

    /**
     * Prevenir clonación
     */
    private function __clone() {}

    /**
     * Prevenir deserialización
     */
    public function __wakeup() {}
}