<div class="card">
    <h2>🏠 Dashboard - Bienvenido <?php echo escape($_SESSION['usuario_nombre']); ?></h2>

    <!-- Información del usuario -->
    <div class="stats">
        <div class="stat-box">
            <div class="stat-number">👤</div>
            <div><strong><?php echo escape($_SESSION['usuario_nombre']); ?></strong></div>
            <div>Rol: <?php echo ucfirst($_SESSION['usuario_rol']); ?></div>
        </div>
        <div class="stat-box">
            <div class="stat-number">🕒</div>
            <div>Último acceso</div>
            <div><?php echo date('d/m/Y H:i'); ?></div>
        </div>
    </div>

    <!-- Menú según el rol -->
    <div style="margin-top: 30px;">
        <h3>📋 Acciones Disponibles</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            
            <?php if ($_SESSION['usuario_rol'] === 'admin' || $_SESSION['usuario_rol'] === 'profesor'): ?>
            <!-- Opciones para Admin y Profesor -->
            <div class="card" style="padding: 20px; text-align: center;">
                <h4>👥 Gestión de Estudiantes</h4>
                <p>Ver, agregar, editar y eliminar estudiantes</p>
                <a href="index.php?accion=listar" class="btn btn-edit">Ver Estudiantes</a>
            </div>

            <div class="card" style="padding: 20px; text-align: center;">
                <h4>📊 Reportes</h4>
                <p>Generar reportes y estadísticas</p>
                <a href="index.php?accion=reportes" class="btn btn-edit">Ver Reportes</a>
            </div>
            <?php endif; ?>

            <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
            <!-- Opciones solo para Admin -->
            <div class="card" style="padding: 20px; text-align: center;">
                <h4>🎓 Gestión de Carreras</h4>
                <p>Administrar carreras y materias</p>
                <a href="index.php?accion=carreras" class="btn btn-edit">Gestionar Carreras</a>
            </div>

            <div class="card" style="padding: 20px; text-align: center;">
                <h4>👤 Gestión de Usuarios</h4>
                <p>Administrar usuarios del sistema</p>
                <a href="index.php?accion=usuarios" class="btn btn-edit">Gestionar Usuarios</a>
            </div>
            <?php endif; ?>

            <?php if ($_SESSION['usuario_rol'] === 'estudiante'): ?>
            <!-- Opciones para Estudiante -->
            <div class="card" style="padding: 20px; text-align: center;">
                <h4>📋 Mi Perfil</h4>
                <p>Ver y editar mi información personal</p>
                <a href="index.php?accion=mi_perfil" class="btn btn-edit">Ver Mi Perfil</a>
            </div>

            <div class="card" style="padding: 20px; text-align: center;">
                <h4>📚 Mis Materias</h4>
                <p>Ver materias inscritas y calificaciones</p>
                <a href="index.php?accion=mis_materias" class="btn btn-edit">Ver Mis Materias</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Botón de logout -->
    <div style="text-align: center; margin-top: 30px;">
        <a href="index.php?accion=logout" class="btn btn-delete" 
           onclick="return confirm('¿Estás seguro de cerrar sesión?')">
           🚪 Cerrar Sesión
        </a>
    </div>
</div>