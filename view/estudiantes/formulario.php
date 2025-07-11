<div class="card">
    <h2><?php echo $titulo ?? '➕ Agregar Nuevo Estudiante'; ?></h2>

    <?php 
    $es_edicion = isset($estudiante) && $estudiante;
    $accion_form = $es_edicion ? 'editar' : 'agregar';
    $id_estudiante = $es_edicion ? $estudiante['id'] : '';
    ?>

    <form method="POST" action="index.php?accion=<?php echo $accion_form; ?><?php echo $es_edicion ? '&id=' . $id_estudiante : ''; ?>">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo escape($estudiante['nombre'] ?? $_POST['nombre'] ?? ''); ?>"
                required placeholder="Ingrese el nombre">
        </div>

        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido"
                value="<?php echo escape($estudiante['apellido'] ?? $_POST['apellido'] ?? ''); ?>"
                required placeholder="Ingrese el apellido">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email"
                value="<?php echo escape($estudiante['email'] ?? $_POST['email'] ?? ''); ?>"
                required placeholder="ejemplo@correo.com">
        </div>

        <div class="form-group">
            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" min="1" max="120"
                value="<?php echo escape($estudiante['edad'] ?? $_POST['edad'] ?? ''); ?>"
                required placeholder="Ingrese la edad">
        </div>

        <button type="submit">
            <?php echo $es_edicion ? '✏️ Actualizar Estudiante' : '💾 Guardar Estudiante'; ?>
        </button>
        
        <a href="index.php?accion=listar" class="btn btn-edit" style="margin-left: 10px;">
            📋 Volver a la Lista
        </a>
    </form>
</div>