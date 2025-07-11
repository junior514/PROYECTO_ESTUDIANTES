<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Sistema de Estudiantes'; ?> - MVC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #333;
            color: white;
            padding: 1rem 0;
            margin-bottom: 20px;
        }

        header h1 {
            text-align: center;
        }

        .nav {
            text-align: center;
            margin: 20px 0;
        }

        .nav a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #007cba;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .nav a:hover {
            background-color: #005a87;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            padding: 12px 20px;
            background-color: #007cba;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #005a87;
        }

        .success {
            color: #28a745;
            font-weight: bold;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .error {
            color: #dc3545;
            font-weight: bold;
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .stat-box {
            text-align: center;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 8px;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #1976d2;
        }

        .btn {
            padding: 8px 15px;
            margin: 2px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }

        .btn-edit {
            background-color: #28a745;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1>🎓 Sistema de Estudiantes - Arquitectura MVC</h1>
        </div>
    </header>

    <div class="container">
        <nav class="nav">
            <?php if (isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado']): ?>
                <!-- Menú para usuarios logueados -->
                <a href="index.php?accion=dashboard">🏠 Dashboard</a>
                
                <?php if ($_SESSION['usuario_rol'] === 'admin' || $_SESSION['usuario_rol'] === 'profesor'): ?>
                <a href="index.php?accion=listar">📋 Ver Estudiantes</a>
                <a href="index.php?accion=mostrar_formulario">➕ Agregar Estudiante</a>
                <?php endif; ?>
                
                <span style="color: white; margin: 0 20px;">
                    👤 <?php echo escape($_SESSION['usuario_nombre']); ?> 
                    (<?php echo ucfirst($_SESSION['usuario_rol']); ?>)
                </span>
                
                <a href="index.php?accion=logout" 
                   onclick="return confirm('¿Cerrar sesión?')" 
                   style="background-color: #dc3545;">🚪 Salir</a>
            <?php else: ?>
                <!-- Menú para usuarios no logueados -->
                <a href="index.php?accion=mostrar_login">🔐 Iniciar Sesión</a>
            <?php endif; ?>
        </nav>

        <!-- Mostrar mensajes flash -->
        <?php if ($mensaje_success = mostrarMensaje('success')): ?>
            <div class="success"><?php echo $mensaje_success; ?></div>
        <?php endif; ?>
        
        <?php if ($mensaje_error = mostrarMensaje('error')): ?>
            <div class="error"><?php echo $mensaje_error; ?></div>
        <?php endif; ?>