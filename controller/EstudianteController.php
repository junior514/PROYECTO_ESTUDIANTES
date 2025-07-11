<?php
/**
 * Controlador de Estudiantes mejorado
 * Incluye validaciones, logs y mejor manejo de errores
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class EstudianteController
{
    private $modelo;
    private $logger;

    public function __construct()
    {
        $this->modelo = new Estudiante();
        
        // Configurar logger
        $this->logger = new Logger('estudiantes');
        $this->logger->pushHandler(new StreamHandler(LOGS_PATH . '/estudiantes.log', Logger::DEBUG));
    }

    /**
     * Mostrar lista de estudiantes con paginación
     */
    public function listar()
    {
        try {
            $pagina = $_GET['pagina'] ?? 1;
            $limite = env('ITEMS_PER_PAGE', 10);
            $offset = ($pagina - 1) * $limite;
            
            // Obtener estudiantes y total
            $estudiantes = $this->modelo->obtenerTodos($limite, $offset);
            $total = $this->modelo->contarTotal();
            $totalPaginas = ceil($total / $limite);
            
            // Datos para la vista
            $datos = [
                'estudiantes' => $estudiantes,
                'total' => $total,
                'pagina_actual' => $pagina,
                'total_paginas' => $totalPaginas,
                'limite' => $limite
            ];
            
            $this->logger->info("Lista de estudiantes cargada", [
                'total' => $total,
                'pagina' => $pagina
            ]);
            
            // Cargar vista
            $this->cargarVista('lista', $datos);
            
        } catch (Exception $e) {
            $this->logger->error("Error al listar estudiantes: " . $e->getMessage());
            $this->mostrarError("Error al cargar la lista de estudiantes");
        }
    }

    /**
     * Mostrar formulario para agregar estudiante
     */
    public function mostrarFormulario()
    {
        $datos = [
            'titulo' => 'Agregar Nuevo Estudiante',
            'accion' => 'agregar',
            'estudiante' => null,
            'errores' => mostrarMensaje('error'),
            'exito' => mostrarMensaje('success')
        ];
        
        $this->cargarVista('formulario', $datos);
    }

    /**
     * Procesar formulario y agregar estudiante
     */
    public function agregar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?accion=mostrar_formulario');
            return;
        }

        try {
            // Obtener y limpiar datos
            $datos = $this->obtenerDatosFormulario();
            
            // Validar datos
            $errores = $this->validarDatos($datos);
            
            if (!empty($errores)) {
                setMensaje(implode('<br>', $errores), 'error');
                $this->mostrarFormulario();
                return;
            }

            // Verificar email único
            if ($this->modelo->emailExiste($datos['email'])) {
                setMensaje('El email ya está registrado por otro estudiante', 'error');
                $this->mostrarFormulario();
                return;
            }

            // Agregar estudiante
            $id = $this->modelo->agregar($datos);
            
            if ($id) {
                $this->logger->info("Estudiante agregado exitosamente", [
                    'id' => $id,
                    'nombre' => $datos['nombre'],
                    'email' => $datos['email']
                ]);
                
                setMensaje('¡Estudiante agregado exitosamente!', 'success');
                redirect('index.php?accion=listar');
            } else {
                throw new Exception('Error al guardar en la base de datos');
            }
            
        } catch (Exception $e) {
            $this->logger->error("Error al agregar estudiante: " . $e->getMessage());
            setMensaje('Error al agregar el estudiante. Por favor, intenta nuevamente.', 'error');
            $this->mostrarFormulario();
        }
    }

    /**
     * Mostrar formulario para editar estudiante
     */
    public function editar($id = null)
    {
        if (!$id) {
            setMensaje('ID de estudiante no válido', 'error');
            redirect('index.php?accion=listar');
            return;
        }

        try {
            $estudiante = $this->modelo->obtenerPorId($id);
            
            if (!$estudiante) {
                setMensaje('Estudiante no encontrado', 'error');
                redirect('index.php?accion=listar');
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->procesarEdicion($id, $estudiante);
                return;
            }

            $datos = [
                'titulo' => 'Editar Estudiante',
                'accion' => 'editar',
                'estudiante' => $estudiante,
                'errores' => mostrarMensaje('error'),
                'exito' => mostrarMensaje('success')
            ];
            
            $this->cargarVista('formulario', $datos);
            
        } catch (Exception $e) {
            $this->logger->error("Error al editar estudiante: " . $e->getMessage());
            setMensaje('Error al cargar el estudiante para edición', 'error');
            redirect('index.php?accion=listar');
        }
    }

    /**
     * Procesar edición de estudiante
     */
    private function procesarEdicion($id, $estudianteActual)
    {
        try {
            $datos = $this->obtenerDatosFormulario();
            $errores = $this->validarDatos($datos);
            
            if (!empty($errores)) {
                setMensaje(implode('<br>', $errores), 'error');
                redirect("index.php?accion=editar&id={$id}");
                return;
            }

            // Verificar email único (excluyendo el estudiante actual)
            if ($this->modelo->emailExiste($datos['email'], $id)) {
                setMensaje('El email ya está registrado por otro estudiante', 'error');
                redirect("index.php?accion=editar&id={$id}");
                return;
            }

            // Actualizar estudiante
            if ($this->modelo->actualizar($id, $datos)) {
                $this->logger->info("Estudiante actualizado", [
                    'id' => $id,
                    'nombre' => $datos['nombre']
                ]);
                
                setMensaje('¡Estudiante actualizado exitosamente!', 'success');
                redirect('index.php?accion=listar');
            } else {
                throw new Exception('Error al actualizar en la base de datos');
            }
            
        } catch (Exception $e) {
            $this->logger->error("Error al actualizar estudiante: " . $e->getMessage());
            setMensaje('Error al actualizar el estudiante', 'error');
            redirect("index.php?accion=editar&id={$id}");
        }
    }

    /**
     * Eliminar estudiante
     */
    public function eliminar($id = null)
    {
        if (!$id) {
            setMensaje('ID de estudiante no válido', 'error');
            redirect('index.php?accion=listar');
            return;
        }

        try {
            $estudiante = $this->modelo->obtenerPorId($id);
            
            if (!$estudiante) {
                setMensaje('Estudiante no encontrado', 'error');
                redirect('index.php?accion=listar');
                return;
            }

            if ($this->modelo->eliminar($id)) {
                $this->logger->info("Estudiante eliminado", [
                    'id' => $id,
                    'nombre' => $estudiante['nombre'] . ' ' . $estudiante['apellido']
                ]);
                
                setMensaje('Estudiante eliminado exitosamente', 'success');
            } else {
                setMensaje('Error al eliminar el estudiante', 'error');
            }
            
        } catch (Exception $e) {
            $this->logger->error("Error al eliminar estudiante: " . $e->getMessage());
            setMensaje('Error al eliminar el estudiante', 'error');
        }
        
        redirect('index.php?accion=listar');
    }

    /**
     * Buscar estudiantes
     */
    public function buscar()
    {
        $termino = trim($_GET['q'] ?? '');
        
        if (empty($termino)) {
            redirect('index.php?accion=listar');
            return;
        }

        try {
            $estudiantes = $this->modelo->buscar($termino);
            $total = count($estudiantes);
            
            $datos = [
                'estudiantes' => $estudiantes,
                'total' => $total,
                'termino_busqueda' => $termino,
                'es_busqueda' => true
            ];
            
            $this->cargarVista('lista', $datos);
            
        } catch (Exception $e) {
            $this->logger->error("Error en búsqueda: " . $e->getMessage());
            setMensaje('Error al realizar la búsqueda', 'error');
            redirect('index.php?accion=listar');
        }
    }

    /**
     * Obtener datos del formulario
     */
    private function obtenerDatosFormulario()
    {
        return [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'edad' => intval($_POST['edad'] ?? 0)
        ];
    }

    /**
     * Validar datos del formulario
     */
    private function validarDatos($datos)
    {
        $errores = [];

        if (empty($datos['nombre'])) {
            $errores[] = "El nombre es obligatorio";
        } elseif (strlen($datos['nombre']) < 2) {
            $errores[] = "El nombre debe tener al menos 2 caracteres";
        }

        if (empty($datos['apellido'])) {
            $errores[] = "El apellido es obligatorio";
        } elseif (strlen($datos['apellido']) < 2) {
            $errores[] = "El apellido debe tener al menos 2 caracteres";
        }

        if (empty($datos['email'])) {
            $errores[] = "El email es obligatorio";
        } elseif (!validarEmail($datos['email'])) {
            $errores[] = "El email no tiene un formato válido";
        }

        if ($datos['edad'] < 1 || $datos['edad'] > 120) {
            $errores[] = "La edad debe estar entre 1 y 120 años";
        }

        return $errores;
    }

    /**
     * Cargar vista con layout
     */
    private function cargarVista($vista, $datos = [])
    {
        // Hacer disponibles las variables para la vista
        extract($datos);
        
        // Cargar layout
        include 'view/layouts/header.php';
        
        // Cargar vista específica
        switch ($vista) {
            case 'lista':
                include 'view/estudiantes/lista.php';
                break;
            case 'formulario':
                include 'view/estudiantes/formulario.php';
                break;
            case 'editar':
                include 'view/estudiantes/editar.php';
                break;
            case 'perfil':
                include 'view/estudiantes/perfil.php';
                break;
            default:
                include 'view/estudiantes/lista.php';
                break;
        }
        
        include 'view/layouts/footer.php';
    }

    /**
     * Mostrar página de error
     */
    private function mostrarError($mensaje)
    {
        $datos = ['mensaje_error' => $mensaje];
        $this->cargarVista('error', $datos);
    }
}