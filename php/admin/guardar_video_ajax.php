<?php
session_start();
require_once '../includes/conexion.php';

// Solo admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['url']) && isset($data['tipo'])) {
    
    $url = mysqli_real_escape_string($conn, $data['url']);
    $descripcion = mysqli_real_escape_string($conn, $data['descripcion'] ?? 'Nuevo Video');
    $tipo = mysqli_real_escape_string($conn, $data['tipo']);

    // --- TRUCO: Convertir enlaces normales de YouTube a Embed ---
    // Si el usuario pega: https://www.youtube.com/watch?v=ABC12345
    // Lo convertimos a: https://www.youtube.com/embed/ABC12345
    if (strpos($url, 'watch?v=') !== false) {
        $url = str_replace('watch?v=', 'embed/', $url);
        // Quitar parámetros extra como &t=...
        if (strpos($url, '&') !== false) {
            $url = strtok($url, '&');
        }
    } elseif (strpos($url, 'youtu.be/') !== false) {
        // Soporte para enlaces cortos youtu.be/ABC12345
        $url = str_replace('youtu.be/', 'www.youtube.com/embed/', $url);
    }

    $query = "INSERT INTO multimedia (tipo, url, descripcion) VALUES ('$tipo', '$url', '$descripcion')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
?>