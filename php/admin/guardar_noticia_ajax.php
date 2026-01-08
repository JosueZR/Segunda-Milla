<?php
session_start();
require_once '../includes/conexion.php';

// 1. Seguridad
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

// 2. Verificar datos mínimos
if (isset($_POST['titulo']) && isset($_POST['contenido'])) {
    
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $contenido = mysqli_real_escape_string($conn, $_POST['contenido']);
    $fecha = date('Y-m-d'); // Fecha de hoy
    $rutaBD = '';

    // 3. Manejo de la Imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $archivo = $_FILES['imagen'];
        $tipo = $archivo['type'];
        $permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (in_array($tipo, $permitidos)) {
            // Carpeta de destino
            $directorio = '../../public/images/uploads/noticias/';
            if (!file_exists($directorio)) mkdir($directorio, 0777, true);

            // Nombre único
            $nombreFinal = 'news_' . time() . '_' . rand(100,999) . '.jpg';
            $rutaDestino = $directorio . $nombreFinal;
            
            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                // Ruta para la BD (relativa a public/)
                $rutaBD = 'images/uploads/noticias/' . $nombreFinal;
            }
        }
    }

    // 4. Insertar en BD
    $query = "INSERT INTO noticias (titulo, contenido, fecha, imagen) VALUES ('$titulo', '$contenido', '$fecha', '$rutaBD')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
?>