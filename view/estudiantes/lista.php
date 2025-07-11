<div class="card">
    <h2>📊 Lista de Estudiantes</h2>

    <!-- Estadísticas -->
    <div class="stats">
        <div class="stat-box">
            <div class="stat-number"><?php echo $total ?? 0; ?></div>
            <div>Total de Estudiantes</div>
        </div>
        <?php if (isset($es_busqueda) && $es_busqueda): ?>
        <div class="stat-box">
            <div class="stat-number"><?php echo count($estudiantes); ?></div>
            <div>Resultados para: "<?php echo escape($termino_busqueda); ?>"</div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Barra de búsqueda -->
    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="index.php" style="display: flex; gap: 10px; align-items: center;">
            <input type="hidden" name="accion" value="buscar">
            <input type="text" name="q" placeholder="Buscar estudiante..." 
                   value="<?php echo escape($_GET['q'] ?? ''); ?>" 
                   style="flex: 1;">
            <button type="submit">🔍 Buscar</button>
            <?php if (isset($es_busqueda) && $es_busqueda): ?>
                <a href="index.php?accion=listar" class="btn btn-edit">📋 Ver Todos</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($estudiantes)): ?>
        <p style="text-align: center; color: #666; font-style: italic; padding: 40px;">
            <?php if (isset($es_busqueda) && $es_busqueda): ?>
                No se encontraron estudiantes con el término "<?php echo escape($termino_busqueda); ?>".
                <br><a href="index.php?accion=listar">Ver todos los estudiantes</a>
            <?php else: ?>
                No hay estudiantes registrados.
                <a href="index.php?accion=mostrar_formulario">¡Agrega el primero!</a>
            <?php endif; ?>
        </p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Edad</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $estudiante): ?>
                    <tr>
                        <td><?php echo escape($estudiante['id']); ?></td>
                        <td><?php echo escape(($estudiante['nombre'] ?? '') . ' ' . ($estudiante['apellido'] ?? '')); ?></td>
                        <td><?php echo escape($estudiante['email'] ?? ''); ?></td>
                        <td><?php echo escape(($estudiante['edad'] ?? 'N/A')); ?> <?php echo !empty($estudiante['edad']) ? 'años' : ''; ?></td>
                        <td><?php echo formatearFecha($estudiante['fecha_registro'] ?? null); ?></td>
                        <td>
                            <a href="index.php?accion=editar&id=<?php echo $estudiante['id']; ?>" 
                               class="btn btn-edit">✏️ Editar</a>
                            <a href="index.php?accion=eliminar&id=<?php echo $estudiante['id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('¿Estás seguro de eliminar este estudiante?')">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <?php if (isset($total_paginas) && $total_paginas > 1): ?>
        <div style="text-align: center; margin-top: 20px;">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <?php if ($i == ($pagina_actual ?? 1)): ?>
                    <strong style="padding: 8px 12px; background: #007cba; color: white; margin: 2px;"><?php echo $i; ?></strong>
                <?php else: ?>
                    <a href="index.php?accion=listar&pagina=<?php echo $i; ?>" 
                       style="padding: 8px 12px; background: #f0f0f0; color: #333; text-decoration: none; margin: 2px;"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>