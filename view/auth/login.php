<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Estudiantes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
        }

        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .demo-credentials {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 12px;
        }

        .demo-credentials h4 {
            color: #495057;
            margin-bottom: 8px;
        }

        .demo-credentials p {
            color: #6c757d;
            margin: 5px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🎓 Sistema de Estudiantes</h1>
            <p>Inicia sesión para continuar</p>
        </div>

        <!-- Mostrar mensajes -->
        <?php if ($mensaje_error = mostrarMensaje('error')): ?>
            <div class="alert alert-error"><?php echo $mensaje_error; ?></div>
        <?php endif; ?>
        
        <?php if ($mensaje_success = mostrarMensaje('success')): ?>
            <div class="alert alert-success"><?php echo $mensaje_success; ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?accion=login">
            <div class="form-group">
                <label for="username">Usuario o Email:</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo escape($_POST['username'] ?? ''); ?>"
                       required placeholder="Ingresa tu usuario o email">
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" 
                       required placeholder="Ingresa tu contraseña">
            </div>

            <button type="submit" class="btn-login">🔐 Iniciar Sesión</button>
        </form>

       <div class="demo-credentials">
    <h4>🧪 Credenciales de Prueba:</h4>
    <p><strong>Administrador:</strong> admin / admin123</p>
    <p><strong>Profesor:</strong> profesor / profesor123</p>
    <p><strong>Estudiante:</strong> estudiante / estudiante123</p>
</div>

        <div class="footer">
            <p>&copy; 2025 Sistema de Estudiantes</p>
        </div>
    </div>
</body>
</html>