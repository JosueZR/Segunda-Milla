<?php
session_start();
require_once '../includes/conexion.php'; 

// 1. Seguridad: Solo permitir si es Admin
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403); // Prohibido
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

// 2. Leer los datos JSON que envía TinyMCE
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['seccion']) && isset($data['clave']) && isset($data['contenido'])) {
    
    $seccion = mysqli_real_escape_string($conn, $data['seccion']);
    $clave = mysqli_real_escape_string($conn, $data['clave']);
    // TinyMCE envía HTML, asegúrate de escapar caracteres especiales
    $contenido = mysqli_real_escape_string($conn, $data['contenido']);

    // 3. Verificar si existe el registro para Actualizar o Insertar
    $check = mysqli_query($conn, "SELECT id FROM textos WHERE seccion='$seccion' AND clave='$clave'");

    if (mysqli_num_rows($check) > 0) {
        $query = "UPDATE textos SET contenido='$contenido' WHERE seccion='$seccion' AND clave='$clave'";
    } else {
        $query = "INSERT INTO textos (seccion, clave, contenido) VALUES ('$seccion', '$clave', '$contenido')";
    }

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
}
?>