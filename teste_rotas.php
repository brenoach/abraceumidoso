<?php
// Arquivo: teste_rota.php
$url_digitada = $_GET['url'] ?? 'Nada (Você está na página inicial)';

echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
echo "<h1 style='color: green;'>✅ O .htaccess está funcionando perfeitamente!</h1>";
echo "<h2>O radar capturou a seguinte rota:</h2>";
echo "<p style='font-size: 24px; color: #5b3a26; background: #eee; padding: 10px; display: inline-block; border-radius: 5px;'>";
echo "<strong>" . htmlspecialchars($url_digitada) . "</strong>";
echo "</p>";
echo "</div>";  