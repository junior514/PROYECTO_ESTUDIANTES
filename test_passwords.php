<?php
// Test rápido de contraseñas
// ARCHIVO TEMPORAL - Eliminar después de usar

echo "<h2>🔍 Test de Contraseñas</h2>";

// PASO 1: Ve a phpMyAdmin y copia los hashes aquí
$hashes_from_db = [
    'admin' => '$2y$10$MA0Au6kmJYQS7.H3SWnmwe3SuG2xKmvRoUJV/E3x/0KGkaLPVcWa2', // ← REEMPLAZA con el hash real de admin
    'profesor' => '$2y$10$aypOMaDwiZKMxqBOXErzh.GI0STEgNJ2u6MVtdosmYfy14coAHJdu', // ← REEMPLAZA con el hash real de profesor  
    'estudiante' => '$2y$10$5OpxelNhBo...'    // ← REEMPLAZA con el hash real de estudiante
];

$passwords_to_test = ['secret', 'admin123', 'profesor123', 'estudiante123', 'admin', 'password', '123456'];

echo "<div style='background: white; padding: 20px; font-family: monospace;'>";

foreach ($hashes_from_db as $user => $hash) {
    echo "<h3>👤 Usuario: $user</h3>";
    foreach ($passwords_to_test as $pass) {
        $works = password_verify($pass, $hash);
        echo "<p style='margin: 5px 0;'>$pass: " . ($works ? '<span style="color: green;">✅ FUNCIONA</span>' : '<span style="color: red;">❌ No</span>') . "</p>";
    }
    echo "<hr>";
}

echo "</div>";

echo "<br><strong style='color: red;'>⚠️ ELIMINA este archivo después de usarlo</strong>";
echo "<br><br><a href='index.php' style='background: blue; color: white; padding: 10px; text-decoration: none;'>← Volver al Sistema</a>";
?>