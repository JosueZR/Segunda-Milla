<?php
session_start();
require_once '../includes/conexion.php';

// 1. Seguridad: Solo administradores
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

// 2. Verificar datos
if (isset($_FILES['imagen']) && isset($_POST['seccion']) && isset($_POST['clave'])) {
    
    $seccion = mysqli_real_escape_string($conn, $_POST['seccion']);
    $clave = mysqli_real_escape_string($conn, $_POST['clave']);
    
    $archivo = $_FILES['imagen'];
    $nombreOriginal = $archivo['name'];
    $tipo = $archivo['type'];
    $tmpName = $archivo['tmp_name'];
    
    // Validar que sea imagen
    $permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($tipo, $permitidos)) {
        echo json_encode(['status' => 'error', 'message' => 'Formato no válido (solo JPG, PNG, WEBP)']);
        exit;
    }

    // 3. Preparar ruta de destino
    // Guardaremos en public/images/uploads/
    $directorio = '../../public/images/uploads/';
    
    // Crear carpeta si no existe
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }

    // Nombre único para evitar duplicados/caché
    $nombreFinal = time() . '_' . rand(100,999) . '_' . basename($nombreOriginal);
    $rutaDestino = $directorio . $nombreFinal;
    
    // Ruta relativa para guardar en BD (lo que usará el <img src="">)
    // Como los archivos PHP están en "public/", la ruta debe ser relativa a esa carpeta
    $rutaBD = 'images/uploads/' . $nombreFinal;

    // 4. Mover el archivo
    if (move_uploaded_file($tmpName, $rutaDestino)) {
        
        // 5. Actualizar Base de Datos
        $check = mysqli_query($conn, "SELECT id FROM textos WHERE seccion='$seccion' AND clave='$clave'");
        
        if (mysqli_num_rows($check) > 0) {
            $query = "UPDATE textos SET contenido='$rutaBD' WHERE seccion='$seccion' AND clave='$clave'";
        } else {
            $query = "INSERT INTO textos (seccion, clave, contenido) VALUES ('$seccion', '$clave', '$rutaBD')";
        }

        if (mysqli_query($conn, $query)) {
            echo json_encode(['status' => 'success', 'url' => $rutaBD]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error en BD: ' . mysqli_error($conn)]);
        }

    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo mover el archivo']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
?>