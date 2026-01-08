<?php
session_start();
require_once '../includes/conexion.php';

// 1. Seguridad
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

// 2. Validar datos
if (isset($_FILES['imagen']) && isset($_POST['id'])) {
    
    $id = (int)$_POST['id'];
    $archivo = $_FILES['imagen'];
    $nombreOriginal = $archivo['name'];
    $tmpName = $archivo['tmp_name'];
    
    // Validar tipo
    $permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($archivo['type'], $permitidos)) {
        echo json_encode(['status' => 'error', 'message' => 'Formato inválido']);
        exit;
    }

    // 3. Subir archivo
    $directorio = '../../public/images/uploads/noticias/';
    if (!file_exists($directorio)) mkdir($directorio, 0777, true);

    $nombreFinal = 'noticia_' . $id . '_' . time() . '.jpg'; // Nombre único
    $rutaDestino = $directorio . $nombreFinal;
    $rutaBD = 'images/uploads/noticias/' . $nombreFinal;

    if (move_uploaded_file($tmpName, $rutaDestino)) {
        // 4. Actualizar tabla NOTICIAS
        $query = "UPDATE noticias SET imagen = '$rutaBD' WHERE id = $id";
        
        if (mysqli_query($conn, $query)) {
            echo json_encode(['status' => 'success', 'url' => $rutaBD]);
        } else {
            echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al mover archivo']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
?>