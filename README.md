# 📁 Estructura de Carpetas Mejorada

```
PROYECTO_ESTUDIANTES/
│
├── 📁 config/                          # Configuraciones del sistema
│   ├── database.php                    # Configuración de BD
│   ├── constants.php                   # Constantes del sistema
│   └── app_config.php                  # Configuración general
│
├── 📁 controller/                      # Controladores MVC
│   ├── EstudianteController.php        ✅ (ya tienes)
│   ├── CarreraController.php           # Nuevo
│   ├── MateriaController.php           # Nuevo
│   ├── InscripcionController.php       # Nuevo
│   └── BaseController.php              # Controlador base
│
├── 📁 model/                          # Modelos MVC
│   ├── Database.php                    ✅ (ya tienes)
│   ├── Estudiante.php                 ✅ (ya tienes)
│   ├── Carrera.php                    # Nuevo
│   ├── Materia.php                    # Nuevo
│   ├── Inscripcion.php                # Nuevo
│   └── BaseModel.php                  # Modelo base
│
├── 📁 view/                           # Vistas MVC
│   ├── 📁 layouts/                    # Plantillas base
│   │   ├── header.php                 ✅ (mover aquí)
│   │   ├── footer.php                 ✅ (mover aquí)
│   │   ├── navbar.php                 # Nuevo
│   │   └── sidebar.php                # Nuevo
│   │
│   ├── 📁 estudiantes/                # Vistas de estudiantes
│   │   ├── lista.php                  ✅ (renombrar lista_estudiantes.php)
│   │   ├── formulario.php             ✅ (renombrar form_estudiante.php)
│   │   ├── perfil.php                 # Nuevo - ver perfil
│   │   └── editar.php                 # Nuevo - editar estudiante
│   │
│   ├── 📁 carreras/                   # Vistas de carreras
│   │   ├── lista.php                  # Nuevo
│   │   ├── formulario.php             # Nuevo
│   │   └── materias.php               # Nuevo
│   │
│   ├── 📁 materias/                   # Vistas de materias
│   │   ├── lista.php                  # Nuevo
│   │   ├── formulario.php             # Nuevo
│   │   └── por_carrera.php            # Nuevo
│   │
│   ├── 📁 inscripciones/              # Vistas de inscripciones
│   │   ├── lista.php                  # Nuevo
│   │   ├── formulario.php             # Nuevo
│   │   └── por_estudiante.php         # Nuevo
│   │
│   ├── 📁 reportes/                   # Vistas de reportes
│   │   ├── dashboard.php              # Nuevo - página principal
│   │   ├── estudiantes_carrera.php    # Nuevo
│   │   └── estadisticas.php           # Nuevo
│   │
│   └── 📁 auth/                       # Autenticación (futuro)
│       ├── login.php                  # Nuevo
│       ├── register.php               # Nuevo
│       └── forgot_password.php        # Nuevo
│
├── 📁 assets/                         # Recursos estáticos
│   ├── 📁 css/                       # Hojas de estilo
│   │   ├── main.css                  # Estilos principales
│   │   ├── forms.css                 # Estilos de formularios
│   │   ├── tables.css                # Estilos de tablas
│   │   └── responsive.css            # Diseño responsive
│   │
│   ├── 📁 js/                        # JavaScript
│   │   ├── main.js                   # Scripts principales
│   │   ├── validation.js             # Validaciones
│   │   ├── ajax.js                   # Peticiones AJAX
│   │   └── charts.js                 # Gráficos (Chart.js)
│   │
│   ├── 📁 images/                    # Imágenes
│   │   ├── logo.png                  # Logo del sistema
│   │   ├── icons/                    # Iconos
│   │   └── backgrounds/              # Fondos
│   │
│   └── 📁 fonts/                     # Fuentes personalizadas
│       └── custom-fonts.css
│
├── 📁 utils/                         # Utilidades y helpers
│   ├── Validator.php                 # Validaciones
│   ├── Session.php                   # Manejo de sesiones
│   ├── Helper.php                    # Funciones auxiliares
│   ├── Pagination.php                # Paginación
│   └── FileUpload.php                # Subida de archivos
│
├── 📁 includes/                      # Archivos de inclusión
│   ├── autoload.php                  # Autoload de clases
│   ├── functions.php                 # Funciones globales
│   └── bootstrap.php                 # Inicialización
│
├── 📁 uploads/                       # Archivos subidos
│   ├── 📁 estudiantes/              # Fotos de estudiantes
│   │   └── .htaccess                # Seguridad
│   └── 📁 documentos/               # Documentos varios
│       └── .htaccess                # Seguridad
│
├── 📁 sql/                          # Scripts de base de datos
│   ├── schema.sql                   # Estructura de BD
│   ├── data.sql                     # Datos de prueba
│   ├── updates/                     # Actualizaciones
│   └── backup/                      # Respaldos
│
├── 📁 docs/                         # Documentación
│   ├── README.md                    # Documentación principal
│   ├── database.md                  # Documentación de BD
│   ├── api.md                       # Documentación de API
│   └── changelog.md                 # Registro de cambios
│
├── 📁 tests/                        # Pruebas (futuro)
│   ├── unit/                        # Pruebas unitarias
│   ├── integration/                 # Pruebas de integración
│   └── fixtures/                    # Datos de prueba
│
├── 📁 logs/                         # Logs del sistema
│   ├── app.log                      # Log principal
│   ├── error.log                    # Log de errores
│   └── .htaccess                    # Seguridad
│
├── 📄 index.php                     ✅ (ya tienes - archivo principal)
├── 📄 .htaccess                     # Configuración Apache
├── 📄 .gitignore                    # Archivos ignorados por Git
├── 📄 composer.json                 # Dependencias PHP (futuro)
├── 📄 README.md                     # Documentación del proyecto
└── 📄 bd.sql                        ✅ (mover a sql/schema.sql)

```

## 🎯 **Ventajas de esta Estructura:**

### ✅ **Organización Modular**
- Cada funcionalidad tiene su espacio dedicado
- Fácil mantenimiento y expansión
- Separación clara de responsabilidades

### ✅ **Escalabilidad**
- Preparado para múltiples módulos
- Estructura que crece con el proyecto
- Fácil agregar nuevas funcionalidades

### ✅ **Mantenibilidad**
- Archivos organizados por tipo y función
- Fácil localizar y modificar código
- Estructura estándar de la industria

### ✅ **Seguridad**
- Carpetas protegidas con .htaccess
- Separación de archivos públicos y privados
- Configuraciones centralizadas

## 📝 **Archivos que Necesitas Crear:**

### 🔧 **Configuración**
- `config/database.php` - Mover configuración de BD
- `config/constants.php` - Constantes del sistema
- `includes/autoload.php` - Autoload de clases

### 🎨 **Estilos**
- Mover CSS del HTML a archivos separados
- Crear estilos responsivos
- Organizar por componentes

### 🔒 **Seguridad**
- `.htaccess` principal
- Protección de carpetas sensibles
- Validaciones centralizadas

¿Te parece bien esta estructura? ¿Quieres que empecemos creando algunos de estos archivos o prefieres que mejoremos primero alguna funcionalidad específica?