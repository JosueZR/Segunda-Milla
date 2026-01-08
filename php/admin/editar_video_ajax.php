<?php
session_start();
require_once '../includes/conexion.php';

// 1. Seguridad
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

// 2. Recibir JSON
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && isset($data['url']) && isset($data['descripcion'])) {
    
    $id = (int)$data['id'];
    $url = mysqli_real_escape_string($conn, $data['url']);
    $descripcion = mysqli_real_escape_string($conn, $data['descripcion']);
    
    // Si viene el tipo, lo actualizamos, si no, mantenemos el anterior (aunque el modal lo enviará)
    $tipoStr = isset($data['tipo']) ? ", tipo = '" . mysqli_real_escape_string($conn, $data['tipo']) . "'" : "";

    // --- LOGICA YOUTUBE (Convertir enlace normal a Embed) ---
    if (strpos($url, 'watch?v=') !== false) {
        $url = str_replace('watch?v=', 'embed/', $url);
        if (strpos($url, '&') !== false) $url = strtok($url, '&');
    } elseif (strpos($url, 'youtu.be/') !== false) {
        $url = str_replace('youtu.be/', 'www.youtube.com/embed/', $url);
    }

    // 3. Actualizar
    $query = "UPDATE multimedia SET url = '$url', descripcion = '$descripcion' $tipoStr WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
?>