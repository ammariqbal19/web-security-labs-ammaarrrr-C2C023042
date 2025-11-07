<?php
$base = __DIR__ . '/modules';
$folders = is_dir($base) ? scandir($base) : [];
echo "<h2>Diagnose modules</h2>";
echo "<p>Path: $base</p>";
echo "<ul>";
foreach ($folders as $f) {
    if ($f === '.' || $f === '..') continue;
    $path = $base . '/' . $f;
    if (is_dir($path)) {
        $hasIndex = file_exists($path . '/index.php') ? 'YES' : 'NO';
        $url = "modules/{$f}/index.php";
        echo "<li><strong>$f</strong> — index.php: $hasIndex — <a href='$url' target='_blank'>Buka langsung</a></li>";
    }
}
echo "</ul>";
?>
