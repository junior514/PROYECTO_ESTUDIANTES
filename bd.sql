-- =====================================================
-- SISTEMA DE ESTUDIANTES - BASE DE DATOS MEJORADA
-- =====================================================

DROP DATABASE IF EXISTS escuela_db;
CREATE DATABASE escuela_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE escuela_db;

-- =====================================================
-- TABLA: carreras
-- =====================================================
CREATE TABLE carreras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    descripcion TEXT,
    duracion_semestres INT NOT NULL DEFAULT 8,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLA: estudiantes (mejorada)
-- =====================================================
CREATE TABLE estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_estudiante VARCHAR(15) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(15),
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('M', 'F', 'Otro') NOT NULL,
    direccion TEXT,
    carrera_id INT,
    semestre_actual INT DEFAULT 1,
    estado ENUM('activo', 'inactivo', 'graduado', 'retirado') DEFAULT 'activo',
    promedio_general DECIMAL(3,2) DEFAULT 0.00,
    fecha_ingreso DATE NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices y relaciones
    FOREIGN KEY (carrera_id) REFERENCES carreras(id) ON DELETE SET NULL,
    INDEX idx_codigo_estudiante (codigo_estudiante),
    INDEX idx_email (email),
    INDEX idx_estado (estado),
    INDEX idx_carrera (carrera_id)
);

-- =====================================================
-- TABLA: materias
-- =====================================================
CREATE TABLE materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    creditos INT NOT NULL DEFAULT 3,
    semestre_recomendado INT NOT NULL,
    carrera_id INT,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (carrera_id) REFERENCES carreras(id) ON DELETE CASCADE,
    INDEX idx_codigo_materia (codigo),
    INDEX idx_carrera_semestre (carrera_id, semestre_recomendado)
);

-- =====================================================
-- TABLA: inscripciones
-- =====================================================
CREATE TABLE inscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    materia_id INT NOT NULL,
    semestre VARCHAR(10) NOT NULL, -- Ej: "2025-1", "2025-2"
    nota_final DECIMAL(3,2) DEFAULT NULL,
    estado ENUM('inscrito', 'aprobado', 'reprobado', 'retirado') DEFAULT 'inscrito',
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE,
    UNIQUE KEY unique_inscripcion (estudiante_id, materia_id, semestre),
    INDEX idx_estudiante_semestre (estudiante_id, semestre),
    INDEX idx_materia_semestre (materia_id, semestre)
);

-- =====================================================
-- TABLA: usuarios (para sistema de login futuro)
-- =====================================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'profesor', 'estudiante') NOT NULL,
    estudiante_id INT NULL, -- Solo si es estudiante
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    ultimo_acceso TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_rol (rol)
);

-- =====================================================
-- DATOS DE EJEMPLO
-- =====================================================

-- Insertar carreras
INSERT INTO carreras (nombre, codigo, descripcion, duracion_semestres) VALUES
('Ingeniería de Sistemas', 'ING-SIS', 'Carrera enfocada en desarrollo de software y sistemas', 10),
('Administración de Empresas', 'ADM-EMP', 'Carrera enfocada en gestión empresarial y negocios', 8),
('Contaduría Pública', 'CON-PUB', 'Carrera enfocada en contabilidad y finanzas', 8),
('Diseño Gráfico', 'DIS-GRA', 'Carrera enfocada en diseño visual y multimedia', 6);

-- Insertar materias
INSERT INTO materias (codigo, nombre, descripcion, creditos, semestre_recomendado, carrera_id) VALUES
-- Materias de Ingeniería de Sistemas
('MAT-101', 'Matemáticas I', 'Álgebra y trigonometría', 4, 1, 1),
('PRO-101', 'Programación I', 'Fundamentos de programación', 4, 1, 1),
('MAT-102', 'Matemáticas II', 'Cálculo diferencial', 4, 2, 1),
('PRO-102', 'Programación II', 'Programación orientada a objetos', 4, 2, 1),
('BDD-101', 'Base de Datos I', 'Fundamentos de bases de datos', 3, 3, 1),

-- Materias de Administración
('ADM-101', 'Fundamentos de Administración', 'Principios básicos de administración', 3, 1, 2),
('ECO-101', 'Economía General', 'Principios económicos fundamentales', 3, 1, 2),
('CON-101', 'Contabilidad General', 'Fundamentos de contabilidad', 3, 2, 2);

-- Insertar estudiantes (con código automático)
INSERT INTO estudiantes (codigo_estudiante, nombre, apellido, email, telefono, fecha_nacimiento, genero, direccion, carrera_id, semestre_actual, fecha_ingreso) VALUES
('EST-2025-001', 'Juan Carlos', 'Pérez García', 'juan.perez@email.com', '555-1234', '2003-05-15', 'M', 'Calle 123 #45-67', 1, 3, '2023-02-01'),
('EST-2025-002', 'María Elena', 'García López', 'maria.garcia@email.com', '555-5678', '2004-08-22', 'F', 'Avenida 456 #89-12', 1, 2, '2023-08-01'),
('EST-2025-003', 'Carlos Andrés', 'López Martín', 'carlos.lopez@email.com', '555-9876', '2003-12-10', 'M', 'Carrera 789 #34-56', 2, 4, '2022-02-01'),
('EST-2025-004', 'Ana Sofía', 'Martínez Cruz', 'ana.martinez@email.com', '555-4321', '2005-03-18', 'F', 'Diagonal 321 #78-90', 2, 1, '2025-01-15'),
('EST-2025-005', 'Luis Fernando', 'Rodríguez Silva', 'luis.rodriguez@email.com', '555-6543', '2004-07-25', 'M', 'Transversal 654 #12-34', 3, 2, '2024-02-01');

-- Insertar algunas inscripciones
INSERT INTO inscripciones (estudiante_id, materia_id, semestre, nota_final, estado) VALUES
-- Juan Carlos (estudiante 1) - semestres anteriores
(1, 1, '2023-1', 4.2, 'aprobado'),
(1, 2, '2023-1', 3.8, 'aprobado'),
(1, 3, '2023-2', 4.0, 'aprobado'),
(1, 4, '2023-2', 3.5, 'aprobado'),
(1, 5, '2024-1', 4.5, 'aprobado'),

-- María Elena (estudiante 2) - semestre actual
(2, 1, '2023-2', 3.9, 'aprobado'),
(2, 2, '2023-2', 4.1, 'aprobado'),
(2, 3, '2024-1', NULL, 'inscrito'),
(2, 4, '2024-1', NULL, 'inscrito'),

-- Carlos Andrés (estudiante 3) - Administración
(3, 6, '2022-1', 4.0, 'aprobado'),
(3, 7, '2022-1', 3.7, 'aprobado'),
(3, 8, '2022-2', 3.9, 'aprobado');

-- Actualizar promedios (esto normalmente se haría con triggers o procedimientos)
UPDATE estudiantes SET promedio_general = 4.0 WHERE id = 1;
UPDATE estudiantes SET promedio_general = 4.0 WHERE id = 2;
UPDATE estudiantes SET promedio_general = 3.87 WHERE id = 3;

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista para obtener información completa de estudiantes
CREATE VIEW vista_estudiantes_completa AS
SELECT 
    e.id,
    e.codigo_estudiante,
    CONCAT(e.nombre, ' ', e.apellido) AS nombre_completo,
    e.email,
    e.telefono,
    e.fecha_nacimiento,
    FLOOR(DATEDIFF(CURDATE(), e.fecha_nacimiento) / 365.25) AS edad,
    e.genero,
    c.nombre AS carrera,
    c.codigo AS codigo_carrera,
    e.semestre_actual,
    e.estado,
    e.promedio_general,
    e.fecha_ingreso,
    e.fecha_registro
FROM estudiantes e
LEFT JOIN carreras c ON e.carrera_id = c.id;

-- Vista para estadísticas por carrera
CREATE VIEW vista_estadisticas_carrera AS
SELECT 
    c.id,
    c.nombre AS carrera,
    c.codigo,
    COUNT(e.id) AS total_estudiantes,
    COUNT(CASE WHEN e.estado = 'activo' THEN 1 END) AS estudiantes_activos,
    COUNT(CASE WHEN e.estado = 'graduado' THEN 1 END) AS graduados,
    ROUND(AVG(e.promedio_general), 2) AS promedio_carrera
FROM carreras c
LEFT JOIN estudiantes e ON c.id = e.carrera_id
GROUP BY c.id, c.nombre, c.codigo;

-- =====================================================
-- PROCEDIMIENTOS ALMACENADOS ÚTILES
-- =====================================================

DELIMITER //

-- Procedimiento para generar código de estudiante automático
CREATE PROCEDURE GenerarCodigoEstudiante(OUT nuevo_codigo VARCHAR(15))
BEGIN
    DECLARE ultimo_numero INT DEFAULT 0;
    DECLARE año_actual YEAR DEFAULT YEAR(CURDATE());
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(codigo_estudiante, -3) AS UNSIGNED)), 0) 
    INTO ultimo_numero
    FROM estudiantes 
    WHERE codigo_estudiante LIKE CONCAT('EST-', año_actual, '-%');
    
    SET nuevo_codigo = CONCAT('EST-', año_actual, '-', LPAD(ultimo_numero + 1, 3, '0'));
END //

-- Procedimiento para calcular promedio de estudiante
CREATE PROCEDURE CalcularPromedioEstudiante(IN estudiante_id INT)
BEGIN
    DECLARE nuevo_promedio DECIMAL(3,2);
    
    SELECT ROUND(AVG(nota_final), 2)
    INTO nuevo_promedio
    FROM inscripciones 
    WHERE estudiante_id = estudiante_id 
    AND estado = 'aprobado' 
    AND nota_final IS NOT NULL;
    
    UPDATE estudiantes 
    SET promedio_general = COALESCE(nuevo_promedio, 0.00)
    WHERE id = estudiante_id;
END //

DELIMITER ;

-- =====================================================
-- TRIGGERS
-- =====================================================

DELIMITER //

-- Trigger para actualizar promedio cuando se actualiza una nota
CREATE TRIGGER actualizar_promedio_after_inscripcion
AFTER UPDATE ON inscripciones
FOR EACH ROW
BEGIN
    IF NEW.nota_final != OLD.nota_final OR NEW.estado != OLD.estado THEN
        CALL CalcularPromedioEstudiante(NEW.estudiante_id);
    END IF;
END //

DELIMITER ;

-- =====================================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =====================================================

-- Índice compuesto para búsquedas frecuentes
CREATE INDEX idx_estudiante_carrera_estado ON estudiantes(carrera_id, estado);
CREATE INDEX idx_inscripcion_semestre_estado ON inscripciones(semestre, estado);

-- Índice de texto completo para búsquedas
ALTER TABLE estudiantes ADD FULLTEXT(nombre, apellido);

-- =====================================================
-- CONSULTAS DE PRUEBA
-- =====================================================

-- Probar la vista de estudiantes completa
SELECT * FROM vista_estudiantes_completa LIMIT 5;

-- Probar estadísticas por carrera
SELECT * FROM vista_estadisticas_carrera;

-- Probar generación de código
CALL GenerarCodigoEstudiante(@nuevo_codigo);
SELECT @nuevo_codigo AS codigo_generado;


-- Limpiar usuarios existentes
DELETE FROM usuarios;

-- Insertar usuarios con hashes correctos
INSERT INTO usuarios (username, email, password_hash, rol, estudiante_id, estado) VALUES 
('admin', 'admin@sistema.com', '$2y$10$jvp9t1gqRrwEpmpWfN3L.eGbbE93C/B.sjABQ1QksNP82XIPmonqS', 'admin', NULL, 'activo');

INSERT INTO usuarios (username, email, password_hash, rol, estudiante_id, estado) VALUES 
('profesor', 'profesor@sistema.com', '$2y$10$yMzKzud9IZH/pedPvGq0n.JUHv2lIMzOzta4jjCrVfpp18avwES8a', 'profesor', NULL, 'activo');

INSERT INTO usuarios (username, email, password_hash, rol, estudiante_id, estado) VALUES 
('estudiante', 'estudiante@sistema.com', '$2y$10$0IJBUhhhJZ.WlD0FtYwVXuyvb2vXjaZa04c27u8kDqUCUV2H6gn82', 'estudiante', 1, 'activo');