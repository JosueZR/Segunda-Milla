<?php
session_start();
require_once '../includes/conexion.php';

// 1. Seguridad
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

// 2. Verificar datos mínimos
if (isset($_POST['id']) && isset($_POST['titulo']) && isset($_POST['contenido'])) {
    
    $id = (int)$_POST['id'];
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $contenido = mysqli_real_escape_string($conn, $_POST['contenido']);
    
    // Query base para actualizar texto
    $query = "UPDATE noticias SET titulo = '$titulo', contenido = '$contenido'";

    // 3. Manejo de la Imagen (Opcional)
    // Solo si el usuario subió una nueva, la actualizamos
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $archivo = $_FILES['imagen'];
        $tipo = $archivo['type'];
        $permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (in_array($tipo, $permitidos)) {
            $directorio = '../../public/images/uploads/noticias/';
            if (!file_exists($directorio)) mkdir($directorio, 0777, true);

            $nombreFinal = 'news_' . $id . '_' . time() . '.jpg';
            $rutaDestino = $directorio . $nombreFinal;
            
            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                $rutaBD = 'images/uploads/noticias/' . $nombreFinal;
                // Agregamos la actualización de imagen al query
                $query .= ", imagen = '$rutaBD'";
            }
        }
    }

    // Cerrar el query
    $query .= " WHERE id = $id";

    // 4. Ejecutar
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
?>