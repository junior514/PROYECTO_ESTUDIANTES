<?php
// Generar usuarios con hashes reales - ELIMINAR después de usar

echo "<h2>🔐 Generador de Usuarios Final</h2>";

$usuarios = [
    ['username' => 'admin', 'email' => 'admin@sistema.com', 'password' => 'admin123', 'rol' => 'admin', 'estudiante_id' => 'NULL'],
    ['username' => 'profesor', 'email' => 'profesor@sistema.com', 'password' => 'profesor123', 'rol' => 'profesor', 'estudiante_id' => 'NULL'],  
    ['username' => 'estudiante', 'email' => 'estudiante@sistema.com', 'password' => 'estudiante123', 'rol' => 'estudiante', 'estudiante_id' => '1']
];

echo "<h3>✅ Usuarios generados:</h3>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";

foreach ($usuarios as $user) {
    $hash = password_hash($user['password'], PASSWORD_DEFAULT);
    echo "<p><strong>{$user['username']}</strong> / {$user['password']} → Hash generado ✓</p>";
}

echo "</div>";

echo "<h3>📋 SQL final para ejecutar:</h3>";
echo "<textarea style='width: 100%; height: 300px; font-family: monospace; font-size: 12px;'>";

// SQL para limpiar y crear usuarios
echo "-- Limpiar usuarios existentes\n";
echo "DELETE FROM usuarios;\n\n";

echo "-- Insertar usuarios con hashes correctos\n";
foreach ($usuarios as $user) {
    $hash = password_hash($user['password'], PASSWORD_DEFAULT);
    $estudiante_id = ($user['estudiante_id'] === 'NULL') ? 'NULL' : $user['estudiante_id'];
    
    echo "INSERT INTO usuarios (username, email, password_hash, rol, estudiante_id, estado) VALUES \n";
    echo "('{$user['username']}', '{$user['email']}', '{$hash}', '{$user['rol']}', {$estudiante_id}, 'activo');\n\n";
}

echo "-- Verificar usuarios creados\n";
echo "SELECT username, email, rol, 'Usuario creado correctamente' as status FROM usuarios;\n";

echo "</textarea>";

echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h4>📋 Pasos:</h4>";
echo "<ol>";
echo "<li><strong>Copia todo el SQL</strong> del cuadro de arriba</li>";
echo "<li><strong>Ve a phpMyAdmin</strong></li>";
echo "<li><strong>Pega y ejecuta el SQL</strong></li>";
echo "<li><strong>Elimina este archivo</strong></li>";
echo "<li><strong>Prueba login con:</strong></li>";
echo "<ul>";
foreach ($usuarios as $user) {
    echo "<li><strong>{$user['username']}</strong> / {$user['password']}</li>";
}
echo "</ul>";
echo "</ol>";
echo "</div>";

echo "<strong style='color: red;'>⚠️ ELIMINA este archivo después de usarlo</strong><br>";
echo "<a href='index.php' style='background: blue; color: white; padding: 10px; text-decoration: none; margin-top: 10px; display: inline-block;'>← Ir al Sistema</a>";
?>